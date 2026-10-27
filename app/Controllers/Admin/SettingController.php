<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Core\Upload;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * فیلدهای متنی مجاز هر گروه.
     */
    private array $textFields = [
        'general' => [
            'site_name', 'site_tagline', 'logo_text', 'primary_color', 'secondary_color', 'footer_text',
        ],
        'contact' => [
            'phone_primary', 'phone_secondary', 'whatsapp_number', 'email', 'address', 'working_hours',
        ],
        'seo' => [
            'seo_title', 'seo_description',
        ],
    ];

    public function edit(): void
    {
        Auth::requireLogin();
        $model = new Setting();
        $this->adminView('admin/settings/index', [
            'title' => 'تنظیمات سایت | پنل مدیریت',
            'general' => $model->getByGroup('general'),
            'contact' => $model->getByGroup('contact'),
            'seo' => $model->getByGroup('seo'),
            'maxMb' => \App\Core\Config::get('upload.max_mb'),
            'errors' => \App\Core\errors(),
        ]);
    }

    public function update(): void
    {
        Auth::requireLogin();
        $model = new Setting();
        $input = $this->request->all();

        // اعتبارسنجی ایمیل اگر پر باشد
        if (!empty($input['email']) && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'ایمیل وارد‌شده معتبر نیست.');
            \App\Core\Response::redirect(url('admin/settings'));
        }

        // ذخیره فیلدهای متنی مجاز
        foreach ($this->textFields as $group => $fields) {
            $data = [];
            foreach ($fields as $field) {
                if (array_key_exists($field, $input)) {
                    $value = is_string($input[$field]) ? trim($input[$field]) : '';
                    // تبدیل ارقام فارسی به لاتین در شماره تماس/واتساپ
                    if (in_array($field, ['phone_primary', 'phone_secondary', 'whatsapp_number'], true)) {
                        $value = num_to_en($value);
                    }
                    $data[$field] = $value;
                }
            }
            if (!empty($data)) {
                $model->setMany($data, $group);
            }
        }

        // بارگذاری لوگو (اختیاری)
        $file = $this->request->file('logo_image');
        if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = Upload::image($file, 'site');
            if (!$result['success']) {
                Session::flash('error', $result['error']);
                \App\Core\Response::redirect(url('admin/settings'));
            }
            // حذف لوگوی قبلی
            $oldLogo = $model->getByGroup('general')['logo_image'] ?? '';
            if (!empty($oldLogo)) {
                Upload::delete($oldLogo);
            }
            $model->set('logo_image', $result['path'], 'general');
        }

        Session::flash('success', 'تنظیمات سایت با موفقیت ذخیره شد.');
        \App\Core\Response::redirect(url('admin/settings'));
    }
}
