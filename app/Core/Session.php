<?php

namespace App\Core;

/**
 * مدیریت نشست (session) با تنظیمات امن.
 */
class Session
{
    private static array $previousFlash = [];

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        $lifetime = (int) Config::get('session.lifetime', 7200);
        session_set_cookie_params([
            'lifetime' => $lifetime,
            'path'     => parse_url(Config::get('app.url'), PHP_URL_PATH) ?: '/',
            'httponly' => true,
            'samesite' => 'Lax',
            // در صورت فعال بودن HTTPS این را true کنید
            'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        ]);
        session_name('me_marketing_session');
        session_start();
        // پیام‌های فلش تنظیم‌شده در درخواست قبلی را جدا می‌کنیم تا در همین درخواست نمایش و سپس پاک شوند
        self::$previousFlash = $_SESSION['_flash'] ?? [];
        $_SESSION['_flash'] = [];
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', $params['secure'], $params['httponly']);
            }
            session_destroy();
        }
    }

    /**
     * تنظیم پیام فلش برای نمایش در درخواست بعدی (پس از ریدایرکت).
     */
    public static function flash(string $type, string $message): void
    {
        $_SESSION['_flash'][$type] = $message;
    }

    /**
     * خواندن پیام فلش تنظیم‌شده در درخواست قبلی.
     */
    public static function getFlash(string $type): ?string
    {
        return self::$previousFlash[$type] ?? null;
    }

    public static function allFlash(): array
    {
        return self::$previousFlash;
    }
}
