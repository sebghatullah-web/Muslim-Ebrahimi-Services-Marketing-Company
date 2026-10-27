<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $model = new Faq();
        $search = trim((string) $this->request->query('q', ''));
        $all = $model->all('sort_order', 'ASC');
        if ($search !== '') {
            $all = array_values(array_filter($all, fn($f) =>
                mb_stripos($f['question'], $search) !== false ||
                mb_stripos((string) $f['answer'], $search) !== false
            ));
        }
        $this->adminView('admin/faqs/index', [
            'title' => 'مدیریت پرسش‌های پرتکرار | پنل مدیریت',
            'faqs' => $all,
            'search' => $search,
        ]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $this->adminView('admin/faqs/form', [
            'title' => 'افزودن پرسش | پنل مدیریت',
            'item' => null,
            'errors' => \App\Core\errors(),
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, [
            'question' => 'required|min:3|max:255',
            'answer' => 'required|min:3|max:5000',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::set('_old', $data);
            Session::flash('error', 'لطفاً خطاهای فرم را برطرف کنید.');
            \App\Core\Response::redirect(url('admin/faqs/create'));
        }
        (new Faq())->create([
            'question' => $data['question'],
            'answer' => $data['answer'],
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        Csrf::regenerate();
        Session::flash('success', 'پرسش جدید با موفقیت ایجاد شد.');
        \App\Core\Response::redirect(url('admin/faqs'));
    }

    public function edit(string $id): void
    {
        Auth::requireLogin();
        $item = (new Faq())->find((int) $id);
        if (!$item) {
            Session::flash('error', 'پرسش مورد نظر یافت نشد.');
            \App\Core\Response::redirect(url('admin/faqs'));
        }
        $this->adminView('admin/faqs/form', [
            'title' => 'ویرایش پرسش | پنل مدیریت',
            'item' => $item,
            'errors' => \App\Core\errors(),
        ]);
    }

    public function update(string $id): void
    {
        Auth::requireLogin();
        $model = new Faq();
        $item = $model->find((int) $id);
        if (!$item) {
            Session::flash('error', 'پرسش مورد نظر یافت نشد.');
            \App\Core\Response::redirect(url('admin/faqs'));
        }
        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, [
            'question' => 'required|min:3|max:255',
            'answer' => 'required|min:3|max:5000',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::set('_old', $data);
            Session::flash('error', 'لطفاً خطاهای فرم را برطرف کنید.');
            \App\Core\Response::redirect(url("admin/faqs/{$id}/edit"));
        }
        $model->update((int) $id, [
            'question' => $data['question'],
            'answer' => $data['answer'],
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        Csrf::regenerate();
        Session::flash('success', 'تغییرات پرسش با موفقیت ذخیره شد.');
        \App\Core\Response::redirect(url('admin/faqs'));
    }

    public function destroy(string $id): void
    {
        Auth::requireLogin();
        $model = new Faq();
        if ($model->delete((int) $id)) {
            Session::flash('success', 'پرسش با موفقیت حذف شد.');
        } else {
            Session::flash('error', 'پرسش مورد نظر یافت نشد.');
        }
        \App\Core\Response::redirect(url('admin/faqs'));
    }

    public function toggle(string $id): void
    {
        Auth::requireLogin();
        $model = new Faq();
        $item = $model->find((int) $id);
        if ($item) {
            $newState = (int) $item['is_published'] === 1 ? 0 : 1;
            $model->update((int) $id, ['is_published' => $newState, 'updated_at' => date('Y-m-d H:i:s')]);
            Session::flash('success', $newState === 1 ? 'پرسش منتشر شد.' : 'پرسش از حالت انتشار خارج شد.');
        }
        \App\Core\Response::redirect(url('admin/faqs'));
    }
}
