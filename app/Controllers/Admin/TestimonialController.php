<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Upload;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $model = new Testimonial();
        $search = trim((string) $this->request->query('q', ''));
        $all = $model->all('sort_order', 'ASC');
        if ($search !== '') {
            $all = array_values(array_filter($all, function ($t) use ($search) {
                return mb_stripos($t['client_name'], $search) !== false
                    || mb_stripos((string) $t['quote'], $search) !== false;
            }));
        }
        $this->adminView('admin/testimonials/index', [
            'title' => 'مدیریت نظرات مشتریان | پنل مدیریت',
            'testimonials' => $all,
            'search' => $search,
        ]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $this->adminView('admin/testimonials/form', [
            'title' => 'افزودن نظر مشتری | پنل مدیریت',
            'item' => null,
            'errors' => \App\Core\errors(),
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, [
            'client_name' => 'required|min:2|max:160',
            'client_role' => 'max:160',
            'client_company' => 'max:160',
            'quote' => 'required|min:5|max:2000',
            'rating' => 'int',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::set('_old', $data);
            Session::flash('error', 'لطفاً خطاهای فرم را برطرف کنید.');
            \App\Core\Response::redirect(url('admin/testimonials/create'));
        }

        $model = new Testimonial();
        $model->create([
            'client_name' => $data['client_name'],
            'client_role' => $data['client_role'] ?? '',
            'client_company' => $data['client_company'] ?? '',
            'avatar' => $this->handleAvatar(),
            'quote' => $data['quote'],
            'rating' => max(1, min(5, (int) ($data['rating'] ?? 5))),
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        Csrf::regenerate();
        Session::flash('success', 'نظر مشتری با موفقیت ایجاد شد.');
        \App\Core\Response::redirect(url('admin/testimonials'));
    }

    public function edit(string $id): void
    {
        Auth::requireLogin();
        $model = new Testimonial();
        $item = $model->find((int) $id);
        if (!$item) {
            Session::flash('error', 'نظر مورد نظر یافت نشد.');
            \App\Core\Response::redirect(url('admin/testimonials'));
        }
        $this->adminView('admin/testimonials/form', [
            'title' => 'ویرایش نظر مشتری | پنل مدیریت',
            'item' => $item,
            'errors' => \App\Core\errors(),
        ]);
    }

    public function update(string $id): void
    {
        Auth::requireLogin();
        $model = new Testimonial();
        $item = $model->find((int) $id);
        if (!$item) {
            Session::flash('error', 'نظر مورد نظر یافت نشد.');
            \App\Core\Response::redirect(url('admin/testimonials'));
        }
        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, [
            'client_name' => 'required|min:2|max:160',
            'client_role' => 'max:160',
            'client_company' => 'max:160',
            'quote' => 'required|min:5|max:2000',
            'rating' => 'int',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::set('_old', $data);
            Session::flash('error', 'لطفاً خطاهای فرم را برطرف کنید.');
            \App\Core\Response::redirect(url("admin/testimonials/{$id}/edit"));
        }

        $update = [
            'client_name' => $data['client_name'],
            'client_role' => $data['client_role'] ?? '',
            'client_company' => $data['client_company'] ?? '',
            'quote' => $data['quote'],
            'rating' => max(1, min(5, (int) ($data['rating'] ?? 5))),
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $file = $this->request->file('avatar');
        if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = Upload::image($file, 'testimonials');
            if (!$result['success']) {
                Session::set('_old', $data);
                Session::flash('error', $result['error']);
                \App\Core\Response::redirect(url("admin/testimonials/{$id}/edit"));
            }
            $update['avatar'] = $result['path'];
            if (!empty($item['avatar'])) {
                Upload::delete($item['avatar']);
            }
        }
        $model->update((int) $id, $update);
        Csrf::regenerate();
        Session::flash('success', 'تغییرات نظر با موفقیت ذخیره شد.');
        \App\Core\Response::redirect(url('admin/testimonials'));
    }

    public function destroy(string $id): void
    {
        Auth::requireLogin();
        $model = new Testimonial();
        $item = $model->find((int) $id);
        if ($item) {
            if (!empty($item['avatar'])) {
                Upload::delete($item['avatar']);
            }
            $model->delete((int) $id);
            Session::flash('success', 'نظر با موفقیت حذف شد.');
        } else {
            Session::flash('error', 'نظر مورد نظر یافت نشد.');
        }
        \App\Core\Response::redirect(url('admin/testimonials'));
    }

    public function toggle(string $id): void
    {
        Auth::requireLogin();
        $model = new Testimonial();
        $item = $model->find((int) $id);
        if ($item) {
            $newState = (int) $item['is_published'] === 1 ? 0 : 1;
            $model->update((int) $id, ['is_published' => $newState, 'updated_at' => date('Y-m-d H:i:s')]);
            Session::flash('success', $newState === 1 ? 'نظر منتشر شد.' : 'نظر از حالت انتشار خارج شد.');
        }
        \App\Core\Response::redirect(url('admin/testimonials'));
    }

    private function handleAvatar(): string
    {
        $file = $this->request->file('avatar');
        if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = Upload::image($file, 'testimonials');
            if (!$result['success']) {
                Session::set('_old', $this->request->all());
                Session::flash('error', $result['error']);
                \App\Core\Response::redirect(url('admin/testimonials/create'));
            }
            return $result['path'];
        }
        return '';
    }
}
