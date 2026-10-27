<?php

namespace App\Core;

/**
 * بسته‌بندی درخواست HTTP ورودی.
 */
class Request
{
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    /**
     * مسیر درخواست نسبت به ریشه نصب پروژه، بدون query string.
     */
    public function path(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        // حذف prefiks نصب (زیرپوشه) از ابتدای مسیر
        $base = parse_url(Config::get('app.url'), PHP_URL_PATH);
        if ($base && $base !== '/' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        } else {
            // fallback: استفاده از SCRIPT_NAME اگر rewrite کار نکرد
            $script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
            if ($script !== '/' && $script !== '' && str_starts_with($uri, $script)) {
                $uri = substr($uri, strlen($script));
            }
        }
        $uri = '/' . trim($uri, '/');
        return $uri === '/' ? '/' : rtrim($uri, '/');
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function all(): array
    {
        return $_POST;
    }

    public function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($_POST[$key]) || isset($_GET[$key]);
    }
}
