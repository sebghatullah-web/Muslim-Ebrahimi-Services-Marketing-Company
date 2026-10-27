<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Session;
use App\Core\Upload;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;

class PortfolioController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $model = new Portfolio();
        $page = max(1, (int) $this->request->query('page', 1));
        $search = trim((string) $this->request->query('q', ''));
        $categoryId = isset($_GET['category']) ? (int) $_GET['category'] : null;

        $this->adminView('admin/portfolio/index', [
            'title' => 'مدیریت نمونه‌کارها | پنل مدیریت',
            'result' => $model->paginate($page, 10, $search, $categoryId),
            'categories' => (new PortfolioCategory())->all('sort_order', 'ASC'),
            'search' => $search,
            'categoryId' => $categoryId,
        ]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $this->adminView('admin/portfolio/form', [
            'title' => 'افزودن نمونه‌کار | پنل مدیریت',
            'item' => null,
            'categories' => (new PortfolioCategory())->all('sort_order', 'ASC'),
            'nextOrder' => (new Portfolio())->nextSortOrder(),
            'errors' => \App\Core\errors(),
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, [
            'title' => 'required|min:2|max:180',
            'client_name' => 'max:160',
            'summary' => 'max:255',
            'project_url' => 'url',
            'seo_title' => 'max:255',
            'seo_description' => 'max:320',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::set('_old', $data);
            Session::flash('error', 'لطفاً خطاهای فرم را برطرف کنید.');
            \App\Core\Response::redirect(url('admin/portfolio/create'));
        }

        $model = new Portfolio();
        $slug = $this->makeUniqueSlug(!empty($data['slug']) ? $data['slug'] : slugify($data['title']));
        $imagePath = $this->handleImage();

        $model->create([
            'category_id' => !empty($data['category_id']) ? (int) $data['category_id'] : null,
            'title' => $data['title'],
            'slug' => $slug,
            'client_name' => $data['client_name'] ?? '',
            'image' => $imagePath,
            'summary' => $data['summary'] ?? '',
            'description' => $data['description'] ?? '',
            'project_url' => $data['project_url'] ?? '',
            'seo_title' => $data['seo_title'] ?? '',
            'seo_description' => $data['seo_description'] ?? '',
            'sort_order' => (int) ($data['sort_order'] ?? $model->nextSortOrder()),
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Csrf::regenerate();
        Session::flash('success', 'نمونه‌کار جدید با موفقیت ایجاد شد.');
        \App\Core\Response::redirect(url('admin/portfolio'));
    }

    public function edit(string $id): void
    {
        Auth::requireLogin();
        $model = new Portfolio();
        $item = $model->find((int) $id);
        if (!$item) {
            Session::flash('error', 'نمونه‌کار مورد نظر یافت نشد.');
            \App\Core\Response::redirect(url('admin/portfolio'));
        }
        $this->adminView('admin/portfolio/form', [
            'title' => 'ویرایش نمونه‌کار | پنل مدیریت',
            'item' => $item,
            'categories' => (new PortfolioCategory())->all('sort_order', 'ASC'),
            'errors' => \App\Core\errors(),
        ]);
    }

    public function update(string $id): void
    {
        Auth::requireLogin();
        $model = new Portfolio();
        $item = $model->find((int) $id);
        if (!$item) {
            Session::flash('error', 'نمونه‌کار مورد نظر یافت نشد.');
            \App\Core\Response::redirect(url('admin/portfolio'));
        }

        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, [
            'title' => 'required|min:2|max:180',
            'client_name' => 'max:160',
            'summary' => 'max:255',
            'project_url' => 'url',
            'seo_title' => 'max:255',
            'seo_description' => 'max:320',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::set('_old', $data);
            Session::flash('error', 'لطفاً خطاهای فرم را برطرف کنید.');
            \App\Core\Response::redirect(url("admin/portfolio/{$id}/edit"));
        }

        $slug = $this->makeUniqueSlug(!empty($data['slug']) ? $data['slug'] : slugify($data['title']), (int) $id);
        $update = [
            'category_id' => !empty($data['category_id']) ? (int) $data['category_id'] : null,
            'title' => $data['title'],
            'slug' => $slug,
            'client_name' => $data['client_name'] ?? '',
            'summary' => $data['summary'] ?? '',
            'description' => $data['description'] ?? '',
            'project_url' => $data['project_url'] ?? '',
            'seo_title' => $data['seo_title'] ?? '',
            'seo_description' => $data['seo_description'] ?? '',
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $file = $this->request->file('image');
        if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = Upload::image($file, 'portfolio');
            if (!$result['success']) {
                Session::set('_old', $data);
                Session::flash('error', $result['error']);
                \App\Core\Response::redirect(url("admin/portfolio/{$id}/edit"));
            }
            $update['image'] = $result['path'];
            if (!empty($item['image'])) {
                Upload::delete($item['image']);
            }
        }

        $model->update((int) $id, $update);
        Csrf::regenerate();
        Session::flash('success', 'تغییرات نمونه‌کار با موفقیت ذخیره شد.');
        \App\Core\Response::redirect(url('admin/portfolio'));
    }

    public function destroy(string $id): void
    {
        Auth::requireLogin();
        $model = new Portfolio();
        $item = $model->find((int) $id);
        if ($item) {
            if (!empty($item['image'])) {
                Upload::delete($item['image']);
            }
            $model->delete((int) $id);
            Session::flash('success', 'نمونه‌کار با موفقیت حذف شد.');
        } else {
            Session::flash('error', 'نمونه‌کار مورد نظر یافت نشد.');
        }
        \App\Core\Response::redirect(url('admin/portfolio'));
    }

    public function toggle(string $id): void
    {
        Auth::requireLogin();
        $model = new Portfolio();
        $item = $model->find((int) $id);
        if ($item) {
            $newState = (int) $item['is_published'] === 1 ? 0 : 1;
            $model->update((int) $id, ['is_published' => $newState, 'updated_at' => date('Y-m-d H:i:s')]);
            Session::flash('success', $newState === 1 ? 'نمونه‌کار منتشر شد.' : 'نمونه‌کار از حالت انتشار خارج شد.');
        }
        \App\Core\Response::redirect(url('admin/portfolio'));
    }

    private function handleImage(): string
    {
        $file = $this->request->file('image');
        if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = Upload::image($file, 'portfolio');
            if (!$result['success']) {
                Session::set('_old', $this->request->all());
                Session::flash('error', $result['error']);
                \App\Core\Response::redirect(url('admin/portfolio/create'));
            }
            return $result['path'];
        }
        return '';
    }

    private function makeUniqueSlug(string $slug, ?int $exceptId = null): string
    {
        $slug = $slug !== '' ? $slug : 'project';
        $base = $slug;
        $i = 1;
        while (true) {
            $existing = Database::fetch('SELECT id FROM portfolio_items WHERE slug = ? LIMIT 1', [$slug]);
            if (!$existing || ($exceptId !== null && (int) $existing['id'] === $exceptId)) {
                return $slug;
            }
            $slug = $base . '-' . $i++;
        }
    }
}
