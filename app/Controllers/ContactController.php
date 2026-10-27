<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\Message;

class ContactController extends Controller
{
    /**
     * دریافت پیام از فرم تماس/سفارش با اعتبارسنجی سمت سرور.
     */
    public function store(): void
    {
        // محدودیت نرخ ساده برای جلوگیری از اسپم (بر اساس نشست)
        $now = time();
        $lastSubmit = (int) Session::get('_last_contact_submit', 0);
        if ($lastSubmit && ($now - $lastSubmit) < 30) {
            Session::flash('error', 'شما به‌تازگی پیامی ارسال کرده‌اید. لطفاً کمی صبر کنید.');
            \App\Core\Response::redirect(url('contact'));
        }

        $data = $this->clean($this->request->all());
        // تبدیل ارقام فارسی به لاتین در شماره تماس
        if (isset($data['phone'])) {
            $data['phone'] = num_to_en($data['phone']);
        }

        // اعتبارسنجی سمت سرور
        $validator = \App\Core\Validator::make($data, [
            'name'    => 'required|min:3|max:160',
            'email'   => 'max:160|email',
            'phone'   => 'max:50',
            'subject' => 'max:200',
            'message' => 'required|min:10|max:5000',
        ]);

        // حداقل یکی از ایمیل یا تلفن باید پر باشد
        if (empty($data['email']) && empty($data['phone'])) {
            Session::set('_errors', ['contact' => 'لطفاً حداقل یکی از ایمیل یا شماره تماس را وارد کنید.']);
            Session::set('_old', $data);
            Session::flash('error', 'برای پاسخ به شما، حداقل یکی از ایمیل یا شماره تماس لازم است.');
            \App\Core\Response::redirect(url('contact'));
        }

        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::set('_old', $data);
            Session::flash('error', 'لطفاً خطاهای فرم را برطرف کنید.');
            \App\Core\Response::redirect(url('contact'));
        }

        // ذخیره پیام
        $messageModel = new Message();
        $messageModel->create([
            'name' => $data['name'],
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'subject' => $data['subject'] ?? '',
            'message' => $data['message'],
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Session::set('_last_contact_submit', $now);
        Csrf::regenerate();
        Session::flash('success', 'پیام شما با موفقیت ارسال شد. به‌زودی با شما تماس می‌گیریم.');
        \App\Core\Response::redirect(url('contact'));
    }
}
