<?php

namespace App\Core;

/**
 * مسیریاب ساده مبتنی بر regex. از متدهای GET/POST پشتیبانی می‌کند.
 * نام گروه‌ها را هم می‌تواند به نام route تعریف شود.
 */
class Router
{
    private array $routes = [];
    public array $params = [];
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add(string $method, string $path, string $handler): void
    {
        $method = strtoupper($method);
        // تبدیل مسیر با {param} به الگوی regex
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '([^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        $this->routes[$method][] = [
            'path'    => $path,
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function get(string $path, string $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, string $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    /**
     * اجرای مسیریابی. $uri باید بدون query string و بدون prefiks نصب باشد.
     */
    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $uri = '/' . trim($uri, '/');

        if (!isset($this->routes[$method])) {
            $this->notFound();
            return;
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // حذف تطبیق کامل
                $this->params = $matches;
                [$controllerName, $action] = explode('@', $route['handler']);
                $this->callAction($controllerName, $action);
                return;
            }
        }

        // اگر مسیر با POST پیدا نشد، بررسی GET با همان آدرس برای صفحه ۴۰۵ ساده
        $this->notFound();
    }

    private function callAction(string $controllerName, string $action): void
    {
        $controllerClass = 'App\\Controllers\\' . str_replace('/', '\\', $controllerName);
        if (!class_exists($controllerClass)) {
            $this->notFound();
            return;
        }
        $controller = new $controllerClass();
        if (!method_exists($controller, $action)) {
            $this->notFound();
            return;
        }
        $controller->{$action}(...$this->params);
    }

    private function notFound(): void
    {
        http_response_code(404);
        $view = new View();
        $view->render('site/404', ['title' => 'صفحه پیدا نشد'], 'site');
    }
}
