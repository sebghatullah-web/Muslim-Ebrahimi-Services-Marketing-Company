<?php

namespace App\Core;

/**
 * احراز هویت مدیر. اعتبارسنجی نشست در سمت سرور انجام می‌شود.
 */
class Auth
{
    /**
     * بررسی اینکه کاربر وارد شده است.
     */
    public static function check(): bool
    {
        if (empty($_SESSION['_admin_id'])) {
            return false;
        }
        // بررسی وجود واقعی کاربر در پایگاه داده (نگهبان سرور)
        $user = Database::fetch('SELECT id, name, email FROM admin_users WHERE id = ? LIMIT 1', [(int) $_SESSION['_admin_id']]);
        if (!$user) {
            self::logout();
            return false;
        }
        return true;
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        return Database::fetch('SELECT id, name, email FROM admin_users WHERE id = ? LIMIT 1', [(int) $_SESSION['_admin_id']]);
    }

    /**
     * تلاش برای ورود. در صورت موفقیت نشست ساخته می‌شود.
     */
    public static function attempt(string $email, string $password): bool
    {
        $user = Database::fetch('SELECT id, name, email, password_hash FROM admin_users WHERE email = ? LIMIT 1', [$email]);
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }
        // ورود موفق — بازتولید شناسه نشست برای جلوگیری از session fixation
        session_regenerate_id(true);
        $_SESSION['_admin_id'] = (int) $user['id'];
        $_SESSION['_admin_email'] = $user['email'];
        Csrf::regenerate();
        return true;
    }

    public static function login(int $userId): void
    {
        session_regenerate_id(true);
        $_SESSION['_admin_id'] = $userId;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        Session::destroy();
    }

    /**
     * محافظت از مسیر مدیریتی؛ اگر وارد نشده، به صفحه ورود هدایت می‌شود.
     */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            Session::set('_redirect_after_login', $_SERVER['REQUEST_URI'] ?? '/admin');
            Session::flash('error', 'برای دسترسی به پنل مدیریت باید وارد شوید.');
            Response::redirect(url('admin/login'));
        }
    }
}
