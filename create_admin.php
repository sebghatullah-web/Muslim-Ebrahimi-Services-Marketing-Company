<?php

/**
 * ابزار خط فرمان برای ایجاد حساب مدیر.
 *
 * نحوه اجرا (داخل پوشه پروژه):
 *   C:\xampp\php\php.exe create_admin.php
 *
 * این ابزار نام، ایمیل و گذرواژه را می‌پرسد و آن را به‌صورت هش امن ذخیره می‌کند.
 * هیچ گذرواژه‌ای داخل کد قرار ندارد.
 */

declare(strict_types=1);

require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/app/autoload.php';

use App\Core\Config;
use App\Core\Env;

Env::load(__DIR__ . '/.env');
Config::load();

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('این اسکریپت فقط از خط فرمان قابل اجرا است.');
}

/**
 * خواندن یک خط از ورودی استاندارد.
 */
function prompt(string $label): string
{
    echo $label;
    $line = (string) fgets(STDIN);
    echo PHP_EOL;
    return trim($line);
}

echo "============================================\n";
echo "  ایجاد حساب مدیر — مسلم ابراهیمی\n";
echo "============================================\n";

$name = prompt('نام و تخلص مدیر: ');
if ($name === '') {
    $name = 'مدیر';
}

$email = strtolower(prompt('ایمیل مدیر: '));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fwrite(STDERR, "ایمیل وارد‌شده معتبر نیست.\n");
    exit(1);
}

$password = prompt('گذرواژه (حداقل ۸ نویسه): ');
if (strlen($password) < 8) {
    fwrite(STDERR, "گذرواژه باید حداقل ۸ نویسه باشد.\n");
    exit(1);
}

$confirm = prompt('تکرار گذرواژه: ');
if ($password !== $confirm) {
    fwrite(STDERR, "گذرواژه‌ها یکسان نیستند.\n");
    exit(1);
}

try {
    $pdo = \App\Core\Database::connect();
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS admin_users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(120) NOT NULL,
            email VARCHAR(160) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    $exists = \App\Core\Database::fetch('SELECT id FROM admin_users WHERE email = ? LIMIT 1', [$email]);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $now = date('Y-m-d H:i:s');

    if ($exists) {
        \App\Core\Database::update(
            'admin_users',
            ['name' => $name, 'password_hash' => $hash, 'updated_at' => $now],
            'email = ?',
            ['email' => $email]
        );
        echo "حساب مدیر با ایمیل «{$email}» به‌روزرسانی شد.\n";
    } else {
        \App\Core\Database::insert('admin_users', [
            'name' => $name,
            'email' => $email,
            'password_hash' => $hash,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        echo "حساب مدیر با ایمیل «{$email}» با موفقیت ایجاد شد.\n";
    }

    $base = rtrim((string) Config::get('app.url'), '/');
    echo "اکنون می‌توانید از این نشانی وارد شوید: {$base}/admin/login\n";
} catch (\Throwable $e) {
    fwrite(STDERR, 'خطا: ' . $e->getMessage() . "\n");
    fwrite(STDERR, "لطفاً از اجرای بودن MySQL و صحت تنظیمات فایل .env مطمئن شوید.\n");
    exit(1);
}
