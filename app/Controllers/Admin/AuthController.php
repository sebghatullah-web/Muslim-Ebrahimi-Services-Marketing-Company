<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        // اگر قبلاً وارد شده، به داشبورد هدایت شود
        if (Auth::check()) {
            \App\Core\Response::redirect(url('admin'));
        }

        // پنل ورود چیدمان مستقل دارد
        \App\Core\View::renderOut('admin/auth/login', [
            'title' => 'ورود مدیر',
            'flash' => Session::allFlash(),
        ], null);
    }

    public function login(): void
    {
        $email = strtolower(trim((string) $this->request->post('email', '')));
        $password = (string) $this->request->post('password', '');

        // اعتبارسنجی پایه
        $validator = \App\Core\Validator::make([
            'email' => $email,
            'password' => $password,
        ], [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            Session::flash('error', 'ایمیل یا گذرواژه را به‌درستی وارد کنید.');
            \App\Core\Response::redirect(url('admin/login'));
        }

        // محدودیت نرخ تلاش ورود (۵ تلاش در ۵ دقیقه)
        $attempts = (int) Session::get('_login_attempts', 0);
        $firstAttempt = (int) Session::get('_login_first_attempt', 0);
        $now = time();
        if ($attempts >= 5 && ($now - $firstAttempt) < 300) {
            Session::flash('error', 'تلاش‌های ناموفق بیش از حد. لطفاً چند دقیقه بعد دوباره تلاش کنید.');
            \App\Core\Response::redirect(url('admin/login'));
        }
        if ($attempts === 0 || ($now - $firstAttempt) > 300) {
            Session::set('_login_first_attempt', $now);
            Session::set('_login_attempts', 0);
        }

        if (Auth::attempt($email, $password)) {
            Session::set('_login_attempts', 0);
            Session::remove('_login_first_attempt');
            Session::flash('success', 'خوش آمدید!');
            $redirect = Session::get('_redirect_after_login');
            Session::remove('_redirect_after_login');
            \App\Core\Response::redirect($redirect ?: url('admin'));
        }

        Session::set('_login_attempts', $attempts + 1);
        // پیام عمومی برای جلوگیری از افشای وجود یا عدم وجود حساب
        Session::flash('error', 'ایمیل یا گذرواژه نادرست است.');
        \App\Core\Response::redirect(url('admin/login'));
    }

    public function logout(): void
    {
        Auth::logout();
        Session::flash('success', 'با موفقیت خارج شدید.');
        \App\Core\Response::redirect(url('admin/login'));
    }
}
