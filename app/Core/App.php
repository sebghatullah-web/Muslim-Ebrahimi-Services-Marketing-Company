<?php

namespace App\Core;

/**
 * هسته برنامه: راه‌اندازی تنظیمات، نشست، امنیت و تعریف مسیرها.
 */
class App
{
    public static function run(): void
    {
        Config::load();
        self::configureErrorHandling();
        Session::start();
        Csrf::check();
        self::clearStaleInput();
        $router = self::registerRoutes();
        $request = new Request();
        $router->dispatch($request->method(), $request->path());
    }

    private static function configureErrorHandling(): void
    {
        error_reporting(E_ALL);
        if (Config::get('app.debug')) {
            ini_set('display_errors', '1');
        } else {
            ini_set('display_errors', '0');
            ini_set('log_errors', '1');
            $logDir = dirname(__DIR__, 2) . '/storage/logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0775, true);
            }
            ini_set('error_log', $logDir . '/php-error.log');
        }
    }

    private static function clearStaleInput(): void
    {
        Session::remove('_old');
        Session::remove('_errors');
    }

    private static function registerRoutes(): Router
    {
        $router = Router::getInstance();

        // ---------------- مسیرهای عمومی ----------------
        $router->get('/', 'HomeController@index');
        $router->get('/services', 'ServiceController@index');
        $router->get('/services/{slug}', 'ServiceController@show');
        $router->get('/portfolio', 'PortfolioController@index');
        $router->get('/portfolio/{slug}', 'PortfolioController@show');
        $router->get('/about', 'HomeController@about');
        $router->get('/contact', 'HomeController@contact');
        $router->post('/contact', 'ContactController@store');
        $router->get('/faq', 'HomeController@faq');

        // ---------------- احراز هویت مدیریت ----------------
        $router->get('/admin/login', 'Admin\AuthController@showLogin');
        $router->post('/admin/login', 'Admin\AuthController@login');
        $router->post('/admin/logout', 'Admin\AuthController@logout');

        // ---------------- پنل مدیریت ----------------
        $router->get('/admin', 'Admin\DashboardController@index');
        $router->get('/admin/profile', 'Admin\ProfileController@edit');
        $router->post('/admin/profile', 'Admin\ProfileController@update');
        $router->post('/admin/profile/password', 'Admin\ProfileController@updatePassword');

        // خدمات
        $router->get('/admin/services', 'Admin\ServiceController@index');
        $router->get('/admin/services/create', 'Admin\ServiceController@create');
        $router->post('/admin/services', 'Admin\ServiceController@store');
        $router->get('/admin/services/{id}/edit', 'Admin\ServiceController@edit');
        $router->post('/admin/services/{id}', 'Admin\ServiceController@update');
        $router->post('/admin/services/{id}/delete', 'Admin\ServiceController@destroy');
        $router->post('/admin/services/{id}/toggle', 'Admin\ServiceController@toggle');
        $router->post('/admin/services/reorder', 'Admin\ServiceController@reorder');

        // نمونه‌کارها
        $router->get('/admin/portfolio', 'Admin\PortfolioController@index');
        $router->get('/admin/portfolio/create', 'Admin\PortfolioController@create');
        $router->post('/admin/portfolio', 'Admin\PortfolioController@store');
        $router->get('/admin/portfolio/{id}/edit', 'Admin\PortfolioController@edit');
        $router->post('/admin/portfolio/{id}', 'Admin\PortfolioController@update');
        $router->post('/admin/portfolio/{id}/delete', 'Admin\PortfolioController@destroy');
        $router->post('/admin/portfolio/{id}/toggle', 'Admin\PortfolioController@toggle');

        // دسته‌بندی نمونه‌کارها
        $router->get('/admin/portfolio-categories', 'Admin\PortfolioCategoryController@index');
        $router->post('/admin/portfolio-categories', 'Admin\PortfolioCategoryController@store');
        $router->post('/admin/portfolio-categories/{id}', 'Admin\PortfolioCategoryController@update');
        $router->post('/admin/portfolio-categories/{id}/delete', 'Admin\PortfolioCategoryController@destroy');

        // نظرات مشتریان
        $router->get('/admin/testimonials', 'Admin\TestimonialController@index');
        $router->get('/admin/testimonials/create', 'Admin\TestimonialController@create');
        $router->post('/admin/testimonials', 'Admin\TestimonialController@store');
        $router->get('/admin/testimonials/{id}/edit', 'Admin\TestimonialController@edit');
        $router->post('/admin/testimonials/{id}', 'Admin\TestimonialController@update');
        $router->post('/admin/testimonials/{id}/delete', 'Admin\TestimonialController@destroy');
        $router->post('/admin/testimonials/{id}/toggle', 'Admin\TestimonialController@toggle');

        // پرسش‌های پرتکرار
        $router->get('/admin/faqs', 'Admin\FaqController@index');
        $router->get('/admin/faqs/create', 'Admin\FaqController@create');
        $router->post('/admin/faqs', 'Admin\FaqController@store');
        $router->get('/admin/faqs/{id}/edit', 'Admin\FaqController@edit');
        $router->post('/admin/faqs/{id}', 'Admin\FaqController@update');
        $router->post('/admin/faqs/{id}/delete', 'Admin\FaqController@destroy');
        $router->post('/admin/faqs/{id}/toggle', 'Admin\FaqController@toggle');

        // مزایا / مراحل همکاری
        $router->get('/admin/advantages', 'Admin\AdvantageController@index');
        $router->post('/admin/advantages', 'Admin\AdvantageController@store');
        $router->post('/admin/advantages/{id}', 'Admin\AdvantageController@update');
        $router->post('/admin/advantages/{id}/delete', 'Admin\AdvantageController@destroy');
        $router->post('/admin/advantages/{id}/toggle', 'Admin\AdvantageController@toggle');

        // پیام‌های دریافتی
        $router->get('/admin/messages', 'Admin\MessageController@index');
        $router->get('/admin/messages/{id}', 'Admin\MessageController@show');
        $router->post('/admin/messages/{id}/delete', 'Admin\MessageController@destroy');
        $router->post('/admin/messages/{id}/toggle-read', 'Admin\MessageController@toggleRead');
        $router->post('/admin/messages/bulk', 'Admin\MessageController@bulk');

        // محتوای صفحه خانه
        $router->get('/admin/home', 'Admin\HomeContentController@edit');
        $router->post('/admin/home', 'Admin\HomeContentController@update');

        // تنظیمات سایت (تماس، شبکه‌های اجتماعی، سئو، لوگو)
        $router->get('/admin/settings', 'Admin\SettingController@edit');
        $router->post('/admin/settings', 'Admin\SettingController@update');
        $router->get('/admin/settings/social', 'Admin\SocialLinkController@index');
        $router->post('/admin/settings/social', 'Admin\SocialLinkController@store');
        $router->post('/admin/settings/social/{id}', 'Admin\SocialLinkController@update');
        $router->post('/admin/settings/social/{id}/delete', 'Admin\SocialLinkController@destroy');

        return $router;
    }
}

