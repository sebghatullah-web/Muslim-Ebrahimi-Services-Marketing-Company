<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit(): void
    {
        Auth::requireLogin();
        $user = Auth::user();
        $this->adminView('admin/profile/edit', [
            'title' => 'پروفایل و گذرواژه | پنل مدیریت',
            'user' => $user,
            'errors' => \App\Core\errors(),
        ]);
    }

    public function update(): void
    {
        Auth::requireLogin();
        $user = Auth::user();
        $data = $this->clean($this->request->all());

        $validator = \App\Core\Validator::make($data, [
            'name' => 'required|min:2|max:120',
            'email' => 'required|email|max:160',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::set('_old', $data);
            Session::flash('error', 'لطفاً خطاهای فرم را برطرف کنید.');
            \App\Core\Response::redirect(url('admin/profile'));
        }

        $model = new User();
        if ($model->emailExists($data['email'], (int) $user['id'])) {
            Session::set('_old', $data);
            Session::flash('error', 'این ایمیل قبلاً برای حساب دیگری ثبت شده است.');
            \App\Core\Response::redirect(url('admin/profile'));
        }

        $model->update((int) $user['id'], [
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $_SESSION['_admin_email'] = strtolower($data['email']);

        Session::flash('success', 'اطلاعات پروفایل با موفقیت ذخیره شد.');
        \App\Core\Response::redirect(url('admin/profile'));
    }

    public function updatePassword(): void
    {
        Auth::requireLogin();
        $user = Auth::user();
        $data = $this->request->all();

        $current = (string) ($data['current_password'] ?? '');
        $new = (string) ($data['new_password'] ?? '');
        $confirm = (string) ($data['new_password_confirm'] ?? '');

        $model = new User();
        if (!$model->verifyPassword((int) $user['id'], $current)) {
            Session::flash('error', 'گذرواژه فعلی نادرست است.');
            \App\Core\Response::redirect(url('admin/profile'));
        }
        if (strlen($new) < 8) {
            Session::flash('error', 'گذرواژه جدید باید حداقل ۸ نویسه باشد.');
            \App\Core\Response::redirect(url('admin/profile'));
        }
        if ($new !== $confirm) {
            Session::flash('error', 'گذرواژه جدید و تکرار آن یکسان نیستند.');
            \App\Core\Response::redirect(url('admin/profile'));
        }

        $model->changePassword((int) $user['id'], $new);
        Session::flash('success', 'گذرواژه با موفقیت تغییر کرد.');
        \App\Core\Response::redirect(url('admin/profile'));
    }
}
