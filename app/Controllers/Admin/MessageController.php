<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Message;

class MessageController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $model = new Message();
        $page = max(1, (int) $this->request->query('page', 1));
        $search = trim((string) $this->request->query('q', ''));
        $readFilter = $this->request->query('read', '');
        if (!in_array($readFilter, ['read', 'unread'], true)) {
            $readFilter = null;
        }

        $this->adminView('admin/messages/index', [
            'title' => 'پیام‌های دریافتی | پنل مدیریت',
            'result' => $model->paginate($page, 10, $search, $readFilter),
            'search' => $search,
            'readFilter' => $readFilter,
            'unreadCount' => $model->unreadCount(),
        ]);
    }

    public function show(string $id): void
    {
        Auth::requireLogin();
        $model = new Message();
        $message = $model->find((int) $id);
        if (!$message) {
            Session::flash('error', 'پیام مورد نظر یافت نشد.');
            \App\Core\Response::redirect(url('admin/messages'));
        }
        // علامت‌گذاری خودکار به‌عنوان خوانده‌شده هنگام مشاهده
        if ((int) $message['is_read'] === 0) {
            $model->markRead((int) $id);
            $message['is_read'] = 1;
        }
        $this->adminView('admin/messages/show', [
            'title' => 'مشاهده پیام | پنل مدیریت',
            'message' => $message,
        ]);
    }

    public function destroy(string $id): void
    {
        Auth::requireLogin();
        $model = new Message();
        if ($model->delete((int) $id)) {
            Session::flash('success', 'پیام با موفقیت حذف شد.');
        } else {
            Session::flash('error', 'پیام مورد نظر یافت نشد.');
        }
        \App\Core\Response::redirect(url('admin/messages'));
    }

    public function toggleRead(string $id): void
    {
        Auth::requireLogin();
        $model = new Message();
        $message = $model->find((int) $id);
        if ($message) {
            if ((int) $message['is_read'] === 1) {
                $model->markUnread((int) $id);
                Session::flash('success', 'پیام به‌عنوان خوانده‌نشده علامت‌گذاری شد.');
            } else {
                $model->markRead((int) $id);
                Session::flash('success', 'پیام به‌عنوان خوانده‌شده علامت‌گذاری شد.');
            }
        }
        \App\Core\Response::redirect(url('admin/messages'));
    }

    public function bulk(): void
    {
        Auth::requireLogin();
        $action = (string) $this->request->post('action', '');
        $ids = $this->request->post('ids', []);
        if (!is_array($ids) || empty($ids)) {
            Session::flash('error', 'هیچ موردی انتخاب نشده است.');
            \App\Core\Response::redirect(url('admin/messages'));
        }
        $model = new Message();
        $ids = array_map('intval', $ids);
        $ids = array_filter($ids, fn($id) => $id > 0);
        if (empty($ids)) {
            Session::flash('error', 'مورد نامعتبر انتخاب شده است.');
            \App\Core\Response::redirect(url('admin/messages'));
        }

        if ($action === 'read') {
            foreach ($ids as $id) {
                $model->markRead($id);
            }
            Session::flash('success', 'پیام‌های انتخاب‌شده خوانده‌شده علامت‌گذاری شدند.');
        } elseif ($action === 'unread') {
            foreach ($ids as $id) {
                $model->markUnread($id);
            }
            Session::flash('success', 'پیام‌های انتخاب‌شده خوانده‌نشده علامت‌گذاری شدند.');
        } elseif ($action === 'delete') {
            foreach ($ids as $id) {
                $model->delete($id);
            }
            Session::flash('success', 'پیام‌های انتخاب‌شده حذف شدند.');
        }
        \App\Core\Response::redirect(url('admin/messages'));
    }
}
