<?php

/**
 * فرانت‌کنترلول اصلی برنامه.
 * تمام درخواست‌ها از طریق این فایل هدایت می‌شوند.
 */

declare(strict_types=1);

use App\Core\App;
use App\Core\Env;

// بارگذاری توابع کمکی پیش از هر چیز
require_once __DIR__ . '/config/helpers.php';

// بارگذاری خودکار کلاس‌ها
require_once __DIR__ . '/app/autoload.php';

// بارگذاری متغیرهای محیطی
Env::load(__DIR__ . '/.env');

// اجرای برنامه (تنظیمات، نشست، امنیت و مسیریابی)
App::run();
