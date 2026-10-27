<?php

namespace App\Core;

/**
 * موتور نمایش ساده. داده‌ها را استخراج و فایل view را include می‌کند.
 * پشتیبانی از چیدمان (layout) و بخش‌های جزئی (partial).
 */
class View
{
    private string $viewPath;
    private array $data;
    private ?string $layout;

    public function __construct(string $viewPath = '', array $data = [], ?string $layout = null)
    {
        $this->viewPath = $viewPath;
        $this->data = $data;
        $this->layout = $layout;
    }

    /**
     * رندر یک view و بازگرداندن محتوا به‌صورت رشته.
     */
    public static function render(string $view, array $data = [], ?string $layout = null): string
    {
        $viewFile = self::resolve($view);
        if (!is_file($viewFile)) {
            throw new \RuntimeException("View [{$view}] not found.");
        }

        $content = self::renderFile($viewFile, $data);

        if ($layout !== null) {
            $layoutFile = self::resolve("layouts/{$layout}");
            if (!is_file($layoutFile)) {
                throw new \RuntimeException("Layout [{$layout}] not found.");
            }
            $data['content'] = $content;
            $content = self::renderFile($layoutFile, $data);
        }

        return $content;
    }

    public static function renderOut(string $view, array $data = [], ?string $layout = null): void
    {
        echo self::render($view, $data, $layout);
    }

    /**
     * رندر یک بخش جزئی و بازگرداندن رشته.
     */
    public static function partial(string $partial, array $data = []): string
    {
        $file = self::resolve("partials/{$partial}");
        if (!is_file($file)) {
            throw new \RuntimeException("Partial [{$partial}] not found.");
        }
        return self::renderFile($file, $data);
    }

    private static function resolve(string $view): string
    {
        $base = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR;
        $file = $base . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $view) . '.php';
        return $file;
    }

    private static function renderFile(string $file, array $data): string
    {
        // توابع کمکی برای استفاده در view‌ها
        $e = fn($v) => htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
        $data['e'] = $e;
        $data['url'] = fn($path = '') => url($path);
        $data['asset'] = fn($path) => asset($path);
        $data['partial'] = fn($name, $d = []) => self::partial($name, $d);
        $data['csrfField'] = fn() => Csrf::field();
        $data['old'] = fn($key, $default = '') => \App\Core\old($key, $default);
        $data['setting'] = fn($key, $default = '') => setting($key, $default);

        extract($data, EXTR_SKIP);
        ob_start();
        include $file;
        return ob_get_clean();
    }
}
