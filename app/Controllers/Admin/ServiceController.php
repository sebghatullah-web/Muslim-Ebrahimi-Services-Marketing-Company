<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Session;
use App\Core\Upload;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $model = new Service();
        $search = trim((string) $this->request->query('q', ''));
        $status = (string) $this->request->query('status', '');

        $this->adminView('admin/services/index', [
            'title' => 'مدیریت خدمات | پنل مدیریت',
            'services' => $this->filtered($model, $search, $status),
            'search' => $search,
            'status' => $status,
        ]);
    }

    private function filtered(Service $model, string $search, string $status): array
    {
        $all = $model->all('sort_order', 'ASC');
        if ($search !== '') {
            $all = array_values(array_filter($all, function ($s) use ($search) {
                return mb_stripos($s['title'], $search) !== false
                    || mb_stripos((string) $s['summary'], $search) !== false;
            }));
        }
        if ($status === 'published') {
            $all = array_values(array_filter($all, fn($s) => (int) $s['is_published'] === 1));
        } elseif ($status === 'draft') {
            $all = array_values(array_filter($all, fn($s) => (int) $s['is_published'] === 0));
        }
        return $all;
    }

    public function create(): void
    {
        Auth::requireLogin();
        $model = new Service();
        $this->adminView('admin/services/form', [
            'title' => 'افزودن خدمت جدید | پنل مدیریت',
            'service' => null,
            'nextOrder' => $model->nextSortOrder(),
            'errors' => \App\Core\errors(),
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $data = $this->clean($this->request->all());

        $validator = \App\Core\Validator::make($data, [
            'title' => 'required|min:2|max:160',
            'summary' => 'max:255',
            'seo_title' => 'max:255',
            'seo_description' => 'max:320',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::set('_old', $data);
            Session::flash('error', 'لطفاً خطاهای فرم را برطرف کنید.');
            \App\Core\Response::redirect(url('admin/services/create'));
        }

        $model = new Service();
        $slug = $this->makeUniqueSlug(!empty($data['slug']) ? $data['slug'] : slugify($data['title']));

        $imagePath = '';
        $file = $this->request->file('image');
        if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = Upload::image($file, 'services');
            if (!$result['success']) {
                Session::set('_old', $data);
                Session::flash('error', $result['error']);
                \App\Core\Response::redirect(url('admin/services/create'));
            }
            $imagePath = $result['path'];
        }

        $model->create([
            'title' => $data['title'],
            'slug' => $slug,
            'icon' => $data['icon'] ?? '',
            'image' => $imagePath,
            'summary' => $data['summary'] ?? '',
            'description' => $data['description'] ?? '',
            'seo_title' => $data['seo_title'] ?? '',
            'seo_description' => $data['seo_description'] ?? '',
            'sort_order' => (int) ($data['sort_order'] ?? $model->nextSortOrder()),
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Csrf::regenerate();
        Session::flash('success', 'خدمت جدید با موفقیت ایجاد شد.');
        \App\Core\Response::redirect(url('admin/services'));
    }

    public function edit(string $id): void
    {
        Auth::requireLogin();
        $model = new Service();
        $service = $model->find((int) $id);
        if (!$service) {
            Session::flash('error', 'خدمت مورد نظر یافت نشد.');
            \App\Core\Response::redirect(url('admin/services'));
        }
        $this->adminView('admin/services/form', [
            'title' => 'ویرایش خدمت | پنل مدیریت',
            'service' => $service,
            'errors' => \App\Core\errors(),
        ]);
    }

    public function update(string $id): void
    {
        Auth::requireLogin();
        $model = new Service();
        $service = $model->find((int) $id);
        if (!$service) {
            Session::flash('error', 'خدمت مورد نظر یافت نشد.');
            \App\Core\Response::redirect(url('admin/services'));
        }

        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, [
            'title' => 'required|min:2|max:160',
            'summary' => 'max:255',
            'seo_title' => 'max:255',
            'seo_description' => 'max:320',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::set('_old', $data);
            Session::flash('error', 'لطفاً خطاهای فرم را برطرف کنید.');
            \App\Core\Response::redirect(url("admin/services/{$id}/edit"));
        }

        $slug = $this->makeUniqueSlug(!empty($data['slug']) ? $data['slug'] : slugify($data['title']), (int) $id);

        $update = [
            'title' => $data['title'],
            'slug' => $slug,
            'icon' => $data['icon'] ?? '',
            'summary' => $data['summary'] ?? '',
            'description' => $data['description'] ?? '',
            'seo_title' => $data['seo_title'] ?? '',
            'seo_description' => $data['seo_description'] ?? '',
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $file = $this->request->file('image');
        if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = Upload::image($file, 'services');
            if (!$result['success']) {
                Session::set('_old', $data);
                Session::flash('error', $result['error']);
                \App\Core\Response::redirect(url("admin/services/{$id}/edit"));
            }
            $update['image'] = $result['path'];
            if (!empty($service['image'])) {
                Upload::delete($service['image']);
            }
        }

        $model->update((int) $id, $update);
        Csrf::regenerate();
        Session::flash('success', 'تغییرات خدمت با موفقیت ذخیره شد.');
        \App\Core\Response::redirect(url('admin/services'));
    }

    public function destroy(string $id): void
    {
        Auth::requireLogin();
        $model = new Service();
        $service = $model->find((int) $id);
        if ($service) {
            if (!empty($service['image'])) {
                Upload::delete($service['image']);
            }
            $model->delete((int) $id);
            Session::flash('success', 'خدمت با موفقیت حذف شد.');
        } else {
            Session::flash('error', 'خدمت مورد نظر یافت نشد.');
        }
        \App\Core\Response::redirect(url('admin/services'));
    }

    public function toggle(string $id): void
    {
        Auth::requireLogin();
        $model = new Service();
        $service = $model->find((int) $id);
        if ($service) {
            $newState = (int) $service['is_published'] === 1 ? 0 : 1;
            $model->update((int) $id, ['is_published' => $newState, 'updated_at' => date('Y-m-d H:i:s')]);
            Session::flash('success', $newState === 1 ? 'خدمت منتشر شد.' : 'خدمت از حالت انتشار خارج شد.');
        }
        \App\Core\Response::redirect(url('admin/services'));
    }

    public function reorder(): void
    {
        Auth::requireLogin();
        $order = $this->request->post('order', []);
        if (is_array($order)) {
            $model = new Service();
            $position = 1;
            foreach ($order as $id) {
                $model->update((int) $id, ['sort_order' => $position++, 'updated_at' => date('Y-m-d H:i:s')]);
            }
            Session::flash('success', 'ترتیب نمایش خدمات به‌روزرسانی شد.');
        }
        \App\Core\Response::redirect(url('admin/services'));
    }

    private function makeUniqueSlug(string $slug, ?int $exceptId = null): string
    {
        $slug = $slug !== '' ? $slug : 'service';
        $base = $slug;
        $i = 1;
        while (true) {
            $existing = Database::fetch('SELECT id FROM services WHERE slug = ? LIMIT 1', [$slug]);
            if (!$existing || ($exceptId !== null && (int) $existing['id'] === $exceptId)) {
                return $slug;
            }
            $slug = $base . '-' . $i++;
        }
    }
}

