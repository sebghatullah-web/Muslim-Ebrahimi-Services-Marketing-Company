<?php

namespace App\Core;

/**
 * بارگذاری ساده فایل .env به آرایه متغیرهای محیطی.
 * بدون وابستگی خارجی. مقادیر را در $_ENV و getenv قرار می‌دهد.
 */
class Env
{
    private static array $vars = [];

    public static function load(string $path): void
    {
        if (!is_file($path)) {
            return;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (!str_contains($line, '=')) {
                continue;
            }
            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            // حذف کوتیشن‌های احاطه‌کننده
            if (strlen($value) >= 2) {
                $first = $value[0];
                $last = $value[strlen($value) - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }
            self::$vars[$name] = $value;
            $_ENV[$name] = $value;
            if (function_exists('putenv')) {
                putenv("$name=$value");
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, self::$vars)) {
            return self::$vars[$key];
        }
        $val = getenv($key);
        return $val === false ? $default : $val;
    }
}
