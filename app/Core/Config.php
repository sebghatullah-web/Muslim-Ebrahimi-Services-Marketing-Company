<?php

namespace App\Core;

/**
 * تنظیمات مرکزی برنامه که از متغیرهای محیطی خوانده می‌شود.
 */
class Config
{
    private static array $config = [];

    public static function load(): void
    {
        self::$config = [
            'app' => [
                'name'     => Env::get('APP_NAME', 'خدمات بازاریابی مسلم ابراهیمی'),
                'env'      => Env::get('APP_ENV', 'production'),
                'debug'    => filter_var(Env::get('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN),
                'url'      => rtrim(Env::get('APP_URL', ''), '/'),
                'timezone' => Env::get('APP_TIMEZONE', 'Asia/Kabul'),
                'locale'   => Env::get('APP_LOCALE', 'fa'),
                'key'      => Env::get('APP_KEY', ''),
            ],
            'db' => [
                'host'     => Env::get('DB_HOST', '127.0.0.1'),
                'port'     => Env::get('DB_PORT', '3306'),
                'database' => Env::get('DB_DATABASE', 'muslim_ebrahimi_marketing'),
                'username' => Env::get('DB_USERNAME', 'root'),
                'password' => Env::get('DB_PASSWORD', ''),
                'charset'  => 'utf8mb4',
            ],
            'session' => [
                'lifetime' => (int) Env::get('SESSION_LIFETIME', 7200),
            ],
            'upload' => [
                'max_mb'         => (int) Env::get('UPLOAD_MAX_MB', 3),
                'allowed_ext'    => array_filter(array_map('trim', explode(',', Env::get('UPLOAD_ALLOWED_EXT', 'jpg,jpeg,png,webp,gif,svg')))),
                'max_bytes'      => (int) Env::get('UPLOAD_MAX_MB', 3) * 1024 * 1024,
            ],
        ];

        date_default_timezone_set(self::$config['app']['timezone']);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = self::$config;
        foreach ($segments as $seg) {
            if (!is_array($value) || !array_key_exists($seg, $value)) {
                return $default;
            }
            $value = $value[$seg];
        }
        return $value;
    }
}
