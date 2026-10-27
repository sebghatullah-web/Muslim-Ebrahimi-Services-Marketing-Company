-- ------------------------------------------------------------------
# اسکیمای پایگاه‌د پروژه "خدمات بازاریابی مسلم ابراهیمی"
# برای import در phpMyAdmin یا MySQL CLI استفاده شود.
# ------------------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `muslim_ebrahimi_marketing`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `muslim_ebrahimi_marketing`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
-- Drop existing tables (idempotent import)
DROP TABLE IF EXISTS `home_sections`;
DROP TABLE IF EXISTS `social_links`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `advantages`;
DROP TABLE IF EXISTS `faqs`;
DROP TABLE IF EXISTS `testimonials`;
DROP TABLE IF EXISTS `portfolio_items`;
DROP TABLE IF EXISTS `portfolio_categories`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `admin_users`;


-- -----------------------------------------------------
-- Table `admin_users`
-- -----------------------------------------------------
CREATE TABLE `admin_users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL UNIQUE,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Table `services`
-- -----------------------------------------------------
CREATE TABLE `services` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL UNIQUE,
  `icon` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `summary` text,
  `description` longtext,
  `seo_title` varchar(150) DEFAULT NULL,
  `seo_description` text,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Table `portfolio_categories`
-- -----------------------------------------------------
CREATE TABLE `portfolio_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL UNIQUE,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Table `portfolio_items`
-- -----------------------------------------------------
CREATE TABLE `portfolio_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL UNIQUE,
  `client_name` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `summary` text,
  `description` longtext,
  `project_url` varchar(255) DEFAULT NULL,
  `seo_title` varchar(150) DEFAULT NULL,
  `seo_description` text,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_portfolio_category` (`category_id`),
  CONSTRAINT `fk_portfolio_category` FOREIGN KEY (`category_id`)
    REFERENCES `portfolio_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- -----------------------------------------------------
-- Table `testimonials`
-- -----------------------------------------------------
CREATE TABLE `testimonials` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(100) NOT NULL,
  `client_role` varchar(100) DEFAULT NULL,
  `client_company` varchar(150) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `quote` text NOT NULL,
  `rating` int NOT NULL DEFAULT 5,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Table `faqs`
-- -----------------------------------------------------
CREATE TABLE `faqs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` longtext NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Table `advantages`
-- -----------------------------------------------------
CREATE TABLE `advantages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text,
  `icon` varchar(100) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Table `messages`
-- -----------------------------------------------------
CREATE TABLE `messages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Table `settings`
-- -----------------------------------------------------
CREATE TABLE `settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL UNIQUE,
  `setting_value` text,
  `setting_group` varchar(50) NOT NULL DEFAULT 'general',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Table `social_links`
-- -----------------------------------------------------
CREATE TABLE `social_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `platform` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Table `home_sections`
-- -----------------------------------------------------
CREATE TABLE `home_sections` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `section_key` varchar(50) NOT NULL UNIQUE,
  `title` varchar(150) DEFAULT NULL,
  `subtitle` varchar(200) DEFAULT NULL,
  `body` text,
  `image` varchar(255) DEFAULT NULL,
  `cta_text` varchar(100) DEFAULT NULL,
  `cta_url` varchar(255) DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------------
# داده‌های اولیه (Seed)
-- ------------------------------------------------------------------

-- کاربر مدیریت (ایمیل: admin@muslimebrahimi.ir / رمز: admin123)
INSERT INTO `admin_users` (`name`, `email`, `password_hash`, `created_at`, `updated_at`) VALUES
('مدیر سامانه', 'admin@muslimebrahimi.ir', '$2y$10$30LvPZ6lZGmnXnapuaPdp.pQeDRro4LxR0mNMBdHHxl6Gd4eTjcO.', NOW(), NOW());

-- تنظیمات سایت
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`, `updated_at`) VALUES
('site_title', 'خدمات بازاریابی مسلم ابراهیمی', 'general', NOW()),
('site_description', 'ارائه خدمات بازاریابی و تبلیغات با کیفیت و حرفه‌ای', 'general', NOW()),
('contact_phone', '۰۹۱۲۳۴۵۶۷۸۹', 'contact', NOW()),
('contact_email', 'info@muslimebrahimi.ir', 'contact', NOW()),
('contact_address', 'کابل، افغانستان', 'contact', NOW()),
('site_logo', '', 'general', NOW()),
('seo_title', 'خدمات بازاریابی مسلم ابراهیمی', 'seo', NOW()),
('seo_description', 'ارائه خدمات بازاریابی و تبلیغات با کیفیت و حرفه‌ای', 'seo', NOW());

-- بخش‌های صفحه خانه
INSERT INTO `home_sections` (`section_key`, `title`, `subtitle`, `body`, `cta_text`, `cta_url`, `sort_order`, `updated_at`) VALUES
('hero', 'خدمات بازاریابی مسلم ابراهیمی', 'با ما رشد کنید', 'بفروشید بیشتر با استراتژی‌های بازاریابی مدرن.', 'تماس با ما', '/contact', 1, NOW()),
('about', 'درباره ما', 'تجربه و متخصصی', 'تیم متخصصی در تشخیص نیازها و ارائه راهکارهای بازاریابی.', 'بیشتر بدانید', '/about', 2, NOW()),
('cta', 'آماده برای شروع؟', 'امروز با ما تماس بگیرید', 'پردازش سریع، نتیجه بالاتر و پشتیبانی مستمر.', 'تماس با ما', '/contact', 3, NOW());

-- دسته‌بندی نمونه‌کار
INSERT INTO `portfolio_categories` (`name`, `slug`, `sort_order`, `is_published`, `created_at`, `updated_at`) VALUES
('بازاریابی دیجیتال', 'digital-marketing', 1, 1, NOW(), NOW()),
('طراحی وب', 'web-design', 2, 1, NOW(), NOW());

-- نمونه خدمات
INSERT INTO `services` (`title`, `slug`, `summary`, `sort_order`, `is_published`, `created_at`, `updated_at`) VALUES
('مدیریت شبکه‌های اجتماعی', 'social-media-management', 'مدیریت محتوای اینستاگرام، تلگرام و فیس‌بوک.', 1, 1, NOW(), NOW()),
('سئو و بهینه‌سازی سایت', 'seo-optimization', 'بهینه‌سازی سایت برای موتورهای جستجو.', 2, 1, NOW(), NOW());

-- testimonial نمونه
INSERT INTO `testimonials` (`client_name`, `client_role`, `client_company`, `quote`, `rating`, `is_published`, `sort_order`, `created_at`, `updated_at`) VALUES
('احمد excelente', 'مدیر%', 'شر%', 'خدمات عالی و ارائه در زمان مقرر.', 5, 1, 1, NOW(), NOW());

-- faq نمونه
INSERT INTO `faqs` (`question`, `answer`, `is_published`, `sort_order`, `created_at`, `updated_at`) VALUES
('هزینه خدمات چقدر است؟', 'هزینه بسته‌ها بر اساس نیاز مشتری تعیین می‌شود. برای قیمت‌بندی با ما تماس بگیرید.', 1, 1, NOW(), NOW());

-- advantage نمونه
INSERT INTO `advantages` (`title`, `description`, `icon`, `is_published`, `sort_order`, `created_at`, `updated_at`) VALUES
('پشتیبانی ۲۴ ساعته', 'پشتیبانی مستمر در تمامی مراحل همکاری.', NULL, 1, 1, NOW(), NOW());

-- social_link نمونه
INSERT INTO `social_links` (`platform`, `label`, `url`, `icon`, `is_published`, `sort_order`, `created_at`, `updated_at`) VALUES
('instagram', 'اینستاگرام', 'https://instagram.com/muslimebrahimi', NULL, 1, 1, NOW(), NOW());

-- ------------------------------------------------------------------
# پایان اسکیمای
-- ------------------------------------------------------------------
