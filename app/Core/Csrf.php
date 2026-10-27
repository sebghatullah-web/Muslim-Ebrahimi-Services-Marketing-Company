<?php

namespace App\Core;

/**
 * محافظت در برابر CSRF با توکن هم‌بسته با نشست.
 */
class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * بررسی توکن ارسال‌شده با توکن نشست. در صورت عدم تطابق، متوقف می‌شود.
     */
    public static function check(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if ($method === 'GET' || $method === 'HEAD') {
            return;
        }
        $sent = $_POST['_csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        $sessionToken = $_SESSION['_csrf_token'] ?? '';
        if (!is_string($sent) || !is_string($sessionToken) || !hash_equals($sessionToken, $sent)) {
            http_response_code(419);
            exit('درخواست نامعتبر است (نشست منقضی شده یا توکن امنیتی نامعتبر). لطفاً صفحه را دوباره بارگذاری کنید.');
        }
    }

    /**
     * تولید توکن جدید پس از عملیات حساس.
     */
    public static function regenerate(): void
    {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
}
