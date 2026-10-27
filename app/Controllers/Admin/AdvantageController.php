<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\Advantage;

class AdvantageController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $this->adminView('admin/advantages/index', [
            'title' => 'مدیریت مزایا و مراحل همکاری | پنل مدیریت',
            'advantages' => (new Advantage())->all('sort_order', 'ASC'),
            'errors' => \App\Core\errors(),
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, [
            'title' => 'required|min:2|max:180',
            'description' => 'max:320',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::flash('error', 'عنوان مزايا الزامی است.');
            \App\Core\Response::redirect(url('admin/advantages'));
        }
        $model = new Advantage();
        $model->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'icon' => $data['icon'] ?? '',
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'sort_order' => (int) ($data['sort_order'] ?? $model->nextSortOrder()),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        Csrf::regenerate();
        Session::flash('success', 'مزیت جدید ایجاد شد.');
        \App\Core\Response::redirect(url('admin/advantages'));
    }

    public function update(string $id): void
    {
        Auth::requireLogin();
        $model = new Advantage();
        $item = $model->find((int) $id);
        if (!$item) {
            Session::flash('error', 'مورد یافت نشد.');
            \App\Core\Response::redirect(url('admin/advantages'));
        }
        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, [
            'title' => 'required|min:2|max:180',
            'description' => 'max:320',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::flash('error', 'عنوان مزايا الزامی است.');
            \App\Core\Response::redirect(url('admin/advantages'));
        }
        $model->update((int) $id, [
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'icon' => $data['icon'] ?? '',
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'sort_order' => (int) ($data['sort_order'] ?? $item['sort_order']),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        Csrf::regenerate();
        Session::flash('success', 'مزیت به‌روزرسانی شد.');
        \App\Core\Response::redirect(url('admin/advantages'));
    }

    public function destroy(string $id): void
    {
        Auth::requireLogin();
        $model = new Advantage();
        if ($model->delete((int) $id)) {
            Session::flash('success', 'مورد با موفقیت حذف شد.');
        } else {
            Session::flash('error', 'مورد یافت نشد.');
        }
        \App\Core\Response::redirect(url('admin/advantages'));
    }

    public function toggle(string $id): void
    {
        Auth::requireLogin();
        $model = new Advantage();
        $item = $model->find((int) $id);
        if ($item) {
            $newState = (int) $item['is_published'] === 1 ? 0 : 1;
            $model->update((int) $id, ['is_published' => $newState, 'updated_at' => date('Y-m-d H:i:s')]);
            Session::flash('success', $newState === 1 ? 'منتشر شد.' : 'از حالت انتشار خارج شد.');
        }
        \App\Core\Response::redirect(url('admin/advantages'));
    }
}
