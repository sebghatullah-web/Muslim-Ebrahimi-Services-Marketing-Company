<?php

namespace App\Core;

/**
 * پاسخ HTTP — شامل ریدایرکت و JSON.
 */
class Response
{
    public static function redirect(string $url, int $code = 302): never
    {
        header('Location: ' . $url, true, $code);
        exit;
    }

    public static function json(mixed $data, int $code = 200): never
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function back(): never
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '/';
        self::redirect($ref);
    }
}
