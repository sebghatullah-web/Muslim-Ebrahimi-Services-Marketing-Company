<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Core\Upload;
use App\Models\HomeSection;

class HomeContentController extends Controller
{
    /**
     * بخش‌های قابل مدیریت و کلید یکتای آن‌ها.
     */
    private array $sectionKeys = ['hero', 'about', 'cta'];

    public function edit(): void
    {
        Auth::requireLogin();
        $model = new HomeSection();
        $sections = [];
        foreach ($this->sectionKeys as $key) {
            $sections[$key] = $model->findByKey($key) ?: [
                'section_key' => $key, 'title' => '', 'subtitle' => '',
                'body' => '', 'cta_text' => '', 'cta_url' => '', 'is_published' => 1,
            ];
        }
        $this->adminView('admin/home/index', [
            'title' => 'مدیریت محتوای صفحه خانه | پنل مدیریت',
            'sections' => $sections,
            'maxMb' => \App\Core\Config::get('upload.max_mb'),
            'errors' => \App\Core\errors(),
        ]);
    }

    public function update(): void
    {
        Auth::requireLogin();
        $model = new HomeSection();
        $input = $this->request->all();
        $files = $_FILES;

        foreach ($this->sectionKeys as $key) {
            $data = [
                'title' => trim((string) ($input["{$key}_title"] ?? '')),
                'subtitle' => trim((string) ($input["{$key}_subtitle"] ?? '')),
                'body' => trim((string) ($input["{$key}_body"] ?? '')),
                'cta_text' => trim((string) ($input["{$key}_cta_text"] ?? '')),
                'cta_url' => trim((string) ($input["{$key}_cta_url"] ?? '')),
                'is_published' => isset($input["{$key}_published"]) ? 1 : 0,
            ];

            // بارگذاری تصویر بخش در صورت انتخاب
            $fileKey = "{$key}_image";
            if (isset($files[$fileKey]) && ($files[$fileKey]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $result = Upload::image($files[$fileKey], 'home');
                if (!$result['success']) {
                    Session::flash('error', "تصویر بخش «{$key}»: " . $result['error']);
                    \App\Core\Response::redirect(url('admin/home'));
                }
                $existing = $model->findByKey($key);
                if ($existing && !empty($existing['image'])) {
                    Upload::delete($existing['image']);
                }
                $data['image'] = $result['path'];
            }

            $model->upsertByKey($key, $data);
        }

        \App\Core\Csrf::regenerate();
        Session::flash('success', 'محتوای صفحه خانه با موفقیت ذخیره شد.');
        \App\Core\Response::redirect(url('admin/home'));
    }
}
