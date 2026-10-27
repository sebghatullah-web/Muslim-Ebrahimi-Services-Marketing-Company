<?php

/**
 * بارگذاری خودکار کلاس‌های پروژه بر اساس استاندارد PSR-4 با پیشوند App\.
 */

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});
