# شرکت خدماتی و بازاریابی مسلم ابراهیمی

پنل مدیریت و وب‌سایت شرکتی با چارچوب PHP MVC سفارشی، طراحی RTL (فارسی/دری)، استایل‌بندی با Tailwind CSS و فونت Vazirmatn.

## ساختار پیش‌نیازها

- **XAMPP** (Apache + MySQL/MariaDB + PHP 7.4+ ، پیشنهاد 8.0+)
- پورت ۳۳۰۶ MySQL در دسترس باشد (در XAMPP خدمت MySQL را Run کنید)
- افزونه PHP `pdo_mysql` فعال باشد (در XAMPP به‌طور پیش‌فرض فعال است)

## مراحل اجرا

### ۱) کلoning / کپ کردن پروژه
پوشه پروژه را درíasی که Apache آن را обслужuje قرار دهید، مثلاً:

```
C:\xampp\htdocs\MuslimEbrahimiServicesMarketingCompany
```

### ۲) تنظیم متغیر محیطی (`.env`)
فایل `.env.example` را کپی کرده و نام آن را `.env` بگذارید (این فایل در مخازن ساخته شده است):

```
cp .env.example .env
```

یا در ویندوز:
```
copy .env.example .env
```

 kemudian مقادیر را بسته به محیط خود Édit کنید. مقادیر پیش‌فرض برای XAMPP:

```
APP_ENV=local
APP_DEBUG=true
APP_URL="http://localhost/MuslimEbrahimiServicesMarketingCompany"
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=muslim_ebrahimi_marketing
DB_USERNAME=root
DB_PASSWORD=
APP_KEY=
```

> **نکته:** در محیط `production` از `APP_DEBUG=false` استفاده کنید و `APP_KEY` را یک رشته تصادفی طولانی کنید.

### ۳) ایجاد پایگاه‌د و import اسکیمای
۱. XAMPP Control Panel را باز کنید و **Apache** و **MySQL** را Start کنید.
۲. به phpMyAdmin بروید: `http://localhost/phpmyadmin`
۳. یک پایگاه‌د جدید بسازید با نام `muslim_ebrahimi_marketing` و کدگذاری `utf8mb4_unicode_ci`.
۴. فایل `database/schema.sql` را import کنید (تب Import → انتخاب فایل → Go).

اسکیمای شامل ۱۱ جدول و داده‌های اولیه است:
`admin_users`, `services`, `portfolio_categories`, `portfolio_items`, `testimonials`, `faqs`, `advantages`, `messages`, `settings`, `social_links`, `home_sections`.

### ۴) دسترسی به سایت و پنل مدیریت

**وب‌سایت عمومی:**
```
http://localhost/MuslimEbrahimiServicesMarketingCompany
```

**پنل مدیریت:**
```
http://localhost/MuslimEbrahimiServicesMarketingCompany/admin/login
```

کاربر پیش‌فرضadmin (ساخته شده در seed):
- ایمیل: `admin@muslimebrahimi.ir`
- رمز: `admin123`

### ۵)ذية (در صورت بروز خطای اتصال به پایگاه‌د)
اگر پیام «خطای اتصال به پایگاه‌د. لطفاً تنظیمات .env و در دسترس بودن MySQL را بررسی کنید.» ظاهر می‌شود:
1. مطمئن شوید خدمت **MySQL** در XAMPP فعال (Run) است.
2. مطمئن شوید پایگاه‌د `muslim_ebrahimi_marketing` ایجاد شده و `schema.sql` import شده است.
3. مطمئن شوید فایل `.env` وجود دارد و `DB_DATABASE` با نام پایگاه‌د مطابقت دارد.
4. برای دیدن خطای دقیق (Debug) در `.env` مقدار `APP_DEBUG=true` قرار دهید.

## ویژگی‌های اصلی
- پنل مدیریت کامل: داشبورد، خدمات، نمونه‌کارها (پورتفولیو)، دسته‌بندی نمونه‌کار، نظرات مشتریان، پرسش‌های پرتکرار (FAQ)، مزایا، پیام‌های دریافتی، محتوای صفحه خانه، تنظیمات سایت و شبکه‌های اجتماعی.
- لاگین/خروج مدیریت،-upload تصویر، تغییر رمز عبور، flash messages، pagination، جستجو و فیلتر، bulk actions.
- RTL، فونت Vazirmatn، Tailwind CDN، برچسب‌های فارسی.

## فایل‌های کلیدی
- `index.php` — فرانت‌کنترل (تمام درخواست‌ها)
- `app/Core/App.php` — تعریف مسیرها و اجرا
- `app/Core/Database.php` — اتصال PDO به MySQL
- `app/Core/Config.php` — خواندن تنظیمات از `.env`
- `app/Core/Env.php` — لود فایل `.env`
- `config/helpers.php` — توابع کمکی (`url`, `asset`, `uploadUrl`, `e`, `old`, ...)
- `app/Controllers/Admin/` — کنترلرهای پنل مدیریت
- `app/Views/admin/` — نمای‌های پنل مدیریت
- `app/Views/layouts/admin.php` — لایه اشتراکی پنل مدیریت
- `database/schema.sql` — اسکیمای پایگاه‌د و داده‌های اولیه

## مجوز
پروژه داخلی شرکت — برای استفاده شخصی/شرکت.