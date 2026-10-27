<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Session;
use App\Core\Upload;
use App\Models\SocialLink;

class SocialLinkController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $this->adminView('admin/settings/social', [
            'title' => 'شبکه‌های اجتماعی | پنل مدیریت',
            'links' => (new SocialLink())->all('sort_order', 'ASC'),
            'errors' => \App\Core\errors(),
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, [
            'platform' => 'required|min:2|max:80',
            'url' => 'required|url|max:255',
            'label' => 'max:120',
            'icon' => 'max:120',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::flash('error', 'نام شبکه و نشانی (URL) معتبر الزامی است.');
            \App\Core\Response::redirect(url('admin/settings/social'));
        }
        (new SocialLink())->create([
            'platform' => $data['platform'],
            'label' => $data['label'] ?? '',
            'url' => $data['url'],
            'icon' => $data['icon'] ?? '',
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        Csrf::regenerate();
        Session::flash('success', 'شبکه اجتماعی جدید اضافه شد.');
        \App\Core\Response::redirect(url('admin/settings/social'));
    }

    public function update(string $id): void
    {
        Auth::requireLogin();
        $model = new SocialLink();
        $item = $model->find((int) $id);
        if (!$item) {
            Session::flash('error', 'مورد یافت نشد.');
            \App\Core\Response::redirect(url('admin/settings/social'));
        }
        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, [
            'platform' => 'required|min:2|max:80',
            'url' => 'required|url|max:255',
            'label' => 'max:120',
            'icon' => 'max:120',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::flash('error', 'نام شبکه و نشانی (URL) معتبر الزامی است.');
            \App\Core\Response::redirect(url('admin/settings/social'));
        }
        $model->update((int) $id, [
            'platform' => $data['platform'],
            'label' => $data['label'] ?? '',
            'url' => $data['url'],
            'icon' => $data['icon'] ?? '',
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        Csrf::regenerate();
        Session::flash('success', 'شبکه اجتماعی به‌روزرسانی شد.');
        \App\Core\Response::redirect(url('admin/settings/social'));
    }

    public function destroy(string $id): void
    {
        Auth::requireLogin();
        $model = new SocialLink();
        if ($model->delete((int) $id)) {
            Session::flash('success', 'شبکه اجتماعی حذف شد.');
        } else {
            Session::flash('error', 'مورد یافت نشد.');
        }
        \App\Core\Response::redirect(url('admin/settings/social'));
    }
}
