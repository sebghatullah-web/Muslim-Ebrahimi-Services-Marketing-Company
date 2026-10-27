<?php

namespace App\Core;

/**
 * کنترلر پایه. دسترسی راحت به Request و رندر view.
 */
abstract class Controller
{
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    protected function view(string $view, array $data = [], ?string $layout = 'site'): void
    {
        // افزودن داده‌های مشترک سایت
        $data['authUser'] = Auth::user();
        $data['flash'] = Session::allFlash();
        View::renderOut($view, $data, $layout);
    }

    protected function adminView(string $view, array $data = [], ?string $layout = 'admin'): void
    {
        $data['authUser'] = Auth::user();
        $data['flash'] = Session::allFlash();
        View::renderOut($view, $data, $layout);
    }

    /**
     * اعتبارسنجی و بازگرداندن خطاها یا داده پاک‌شده.
     */
    protected function validate(array $rules): array
    {
        $validator = Validator::make($this->request->all(), $rules);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::set('_old', $this->request->all());
            Session::flash('error', 'لطفاً خطاهای فرم را برطرف کنید.');
            Response::back();
        }
        return $this->clean($this->request->all());
    }

    /**
     * پاک‌سازی مقادیر متنی از فضاهای اضافی.
     */
    protected function clean(array $data): array
    {
        $clean = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $clean[$key] = trim($value);
            } else {
                $clean[$key] = $value;
            }
        }
        return $clean;
    }
}
