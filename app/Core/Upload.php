<?php

namespace App\Core;

/**
 * بارگذاری امن تصویر با محدودیت نوع و اندازه.
 */
class Upload
{
    /**
     * بارگذاری یک فایل از $_FILES.
     *
     * @param array $file ورودی $_FILES['name']
     * @param string $subdir زیرپوشه مقصد نسبت به storage/uploads
     * @return array ['success' => bool, 'path' => ?string, 'error' => ?string]
     */
    public static function image(array $file, string $subdir = ''): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return $this->fail('فایلی بارگذاری نشد.');
        }
        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return $this->fail('هیچ فایلی انتخاب نشده است.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return $this->fail('حجم فایل بیش از حد مجاز است.');
            default:
                return $this->fail('خطا در بارگذاری فایل.');
        }

        // بررسی حجم
        $maxBytes = Config::get('upload.max_bytes');
        if ($file['size'] > $maxBytes) {
            $maxMb = Config::get('upload.max_mb');
            return $this->fail("حجم فایل بیش از حد مجاز است. حداکثر {$maxMb} مگابایت مجاز است.");
        }

        // بررسی نوع واقعی فایل
        $allowed = Config::get('upload.allowed_ext');
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            'image/svg+xml' => 'svg',
        ];
        if (!isset($allowedMimes[$mime]) || !in_array($allowedMimes[$mime], $allowed, true)) {
            return $this->fail('نوع فایل مجاز نیست. فقط تصاویر JPG، PNG، WEBP، GIF و SVG پذیرفته می‌شوند.');
        }

        // ساخت نام یکتا
        $ext = $allowedMimes[$mime];
        $filename = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;

        $uploadDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'uploads';
        if ($subdir !== '') {
            $uploadDir .= DIRECTORY_SEPARATOR . trim($subdir, '/\\');
        }
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }
        // جلوگیری از اجرای اسکریپت در پوشه بارگذاری
        $htaccess = $uploadDir . DIRECTORY_SEPARATOR . '.htaccess';
        if (!is_file($htaccess)) {
            @file_put_contents($htaccess, "php_flag engine off\nOptions -ExecCGI\nAddType text/plain .php .phtml .php3 .php4 .php5 .pl .py .cgi .asp .aspx\n");
        }

        $destination = $uploadDir . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return $this->fail('انتقال فایل به پوشه مقصد ناموفق بود.');
        }

        $relativePath = ($subdir !== '' ? trim($subdir, '/\\') . '/' : '') . $filename;
        return ['success' => true, 'path' => $relativePath, 'error' => null];
    }

    /**
     * حذف یک فایل بارگذاری‌شده.
     */
    public static function delete(?string $relativePath): bool
    {
        if (empty($relativePath)) {
            return false;
        }
        $full = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . ltrim($relativePath, '/\\');
        if (is_file($full)) {
            return unlink($full);
        }
        return false;
    }

    private function fail(string $message): array
    {
        return ['success' => false, 'path' => null, 'error' => $message];
    }
}
