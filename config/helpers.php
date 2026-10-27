<?php

/**
 * توابع کمکی سراسری پروژه.
 * این فایل در ابتدای اجرا (index.php) include می‌شود.
 */

use App\Core\Config;
use App\Core\Session;

if (!function_exists('url')) {
    /**
     * ساخت نشانی کامل سایت بر اساس APP_URL.
     */
    function url(string $path = ''): string
    {
        $base = rtrim(Config::get('app.url', ''), '/');
        $path = '/' . ltrim($path, '/');
        return $base . $path;
    }
}

if (!function_exists('asset')) {
    /**
     * نشانی فایل‌های استاتیک در پوشه public.
     */
    function asset(string $path): string
    {
        return url('public/assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('uploadUrl')) {
    /**
     * نشانی فایل‌های بارگذاری‌شده در storage/uploads.
     */
    function uploadUrl(?string $path): string
    {
        if (empty($path)) {
            return '';
        }
        return url('storage/uploads/' . ltrim($path, '/'));
    }
}

if (!function_exists('setting')) {
    /**
     * خواندن یک تنظیم از جدول settings با کش درون‌درخواستی.
     */
    function setting(string $key, string $default = ''): string
    {
        static $cache = null;
        if ($cache === null) {
            $cache = [];
            try {
                $rows = \App\Core\Database::fetchAll('SELECT setting_key, setting_value FROM settings');
                foreach ($rows as $row) {
                    $cache[$row['setting_key']] = $row['setting_value'];
                }
            } catch (\Throwable $e) {
                $cache = [];
            }
        }
        return $cache[$key] ?? $default;
    }
}

if (!function_exists('old')) {
    /**
     * بازیابی مقدار قبلی فرم در صورت خطای اعتبارسنجی.
     */
    function old(string $key, string $default = ''): string
    {
        $old = Session::get('_old', []);
        return htmlspecialchars((string) ($old[$key] ?? $default), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('errors')) {
    /**
     * خطاهای اعتبارسنجی درخواست فعلی.
     */
    function errors(): array
    {
        return Session::get('_errors', []);
    }
}

if (!function_exists('error_for')) {
    function error_for(string $field): ?string
    {
        $errors = Session::get('_errors', []);
        return $errors[$field] ?? null;
    }
}

if (!function_exists('e')) {
    /**
     * کوتاه‌نویسی برای خروجی امن HTML.
     */
    function e(mixed $value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('slugify')) {
    /**
     * ساخت نامک لاتین از رشته (پشتیبانی از تبدیل کاراکترهای فارسی).
     */
    function slugify(string $text): string
    {
        // نگاشت ساده کاراکترهای فارسی/عربی به لاتین
        $map = [
            'ا' => 'a', 'أ' => 'a', 'آ' => 'a', 'ب' => 'b', 'پ' => 'p', 'ت' => 't', 'ث' => 's',
            'ج' => 'j', 'چ' => 'ch', 'ح' => 'h', 'خ' => 'kh', 'د' => 'd', 'ذ' => 'z', 'ر' => 'r',
            'ز' => 'z', 'ژ' => 'zh', 'س' => 's', 'ش' => 'sh', 'ص' => 's', 'ض' => 'z', 'ط' => 't',
            'ظ' => 'z', 'ع' => 'a', 'غ' => 'gh', 'ف' => 'f', 'ق' => 'gh', 'ک' => 'k', 'ك' => 'k',
            'گ' => 'g', 'ل' => 'l', 'م' => 'm', 'ن' => 'n', 'و' => 'v', 'ؤ' => 'o', 'ه' => 'h',
            'ة' => 'h', 'ی' => 'y', 'ي' => 'y', 'ئ' => 'y', 'ء' => '', '۰' => '0', '۱' => '1',
            '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
        ];
        $text = strtr($text, $map);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        return $text !== '' ? $text : 'item';
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): never
    {
        \App\Core\Response::redirect(url($path));
    }
}

if (!function_exists('format_number_fa')) {
    /**
     * تبدیل ارقام لاتین به فارسی برای نمایش.
     */
    function format_number_fa(string|int|null $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        $fa = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($en, $fa, (string) $value);
    }
}

if (!function_exists('num_to_en')) {
    /**
     * تبدیل ارقام فارسی/عربی به لاتین (برای پردازش ورودی).
     */
    function num_to_en(string $value): string
    {
        $fa = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $ar = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace(array_merge($fa, $ar), $en, $value);
    }
}
