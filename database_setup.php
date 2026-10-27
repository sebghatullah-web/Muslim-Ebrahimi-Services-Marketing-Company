<?php

/**
 * راه‌اندازی پایگاه داده: اجرای schema.sql و seed.sql روی دیتابیس .env.
 *
 * نحوه اجرا:
 *   C:\xampp\php\php.exe database_setup.php
 *
 * این اسکریپت دیتابیس را (در صورت نبود) می‌سازد و جداول و داده اولیه را import می‌کند.
 * اجرای مکرر آن ایمن است (جداول با IF NOT EXISTS ساخته می‌شوند و seed با ON DUPLICATE KEY).
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

$host = Config::get('db.host');
$port = Config::get('db.port');
$db   = Config::get('db.database');
$user = Config::get('db.username');
$pass = Config::get('db.password');

// ۱) اتصال اولیه بدون انتخاب دیتابیس برای ساخت آن
try {
    $pdo = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "دیتابیس «{$db}» آماده است.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'خطا در اتصال/ساخت دیتابیس: ' . $e->getMessage() . "\n");
    exit(1);
}

// ۲) اجرای فایل‌های SQL
function runSqlFile(PDO $pdo, string $file): void
{
    if (!is_file($file)) {
        fwrite(STDERR, "فایل یافت نشد: {$file}\n");
        exit(1);
    }
    $sql = file_get_contents($file);
    try {
        $pdo->exec($sql);
    } catch (Throwable $e) {
        fwrite(STDERR, "خطا در اجرای {$file}: " . $e->getMessage() . "\n");
        exit(1);
    }
}

// اتصال به دیتابیس انتخاب‌شده
$pdo = new PDO("mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4", $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$schemaFile = __DIR__ . '/database/schema.sql';
$seedFile   = __DIR__ . '/database/seed.sql';

runSqlFile($pdo, $schemaFile);
echo "ساختار جداول با موفقیت import شد.\n";

runSqlFile($pdo, $seedFile);
echo "داده اولیه با موفقیت import شد.\n";

echo "\nگام بعدی: ایجاد حساب مدیر با دستور زیر:\n";
echo "  C:\\xampp\\php\\php.exe create_admin.php\n";
