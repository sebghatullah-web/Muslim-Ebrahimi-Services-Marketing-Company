<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\PortfolioCategory;
use App\Models\Portfolio;

class PortfolioCategoryController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $model = new PortfolioCategory();
        $categories = $model->all('sort_order', 'ASC');
        // شمارش نمونه‌کارهای هر دسته
        foreach ($categories as &$cat) {
            $cat['item_count'] = Database::count('portfolio_items', 'category_id = ?', ['category_id' => $cat['id']]);
        }
        unset($cat);

        $this->adminView('admin/portfolio/categories', [
            'title' => 'دسته‌بندی نمونه‌کارها | پنل مدیریت',
            'categories' => $categories,
            'errors' => \App\Core\errors(),
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, [
            'name' => 'required|min:2|max:120',
            'sort_order' => 'int',
        ]);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::flash('error', 'نام دسته‌بندی الزامی است.');
            \App\Core\Response::redirect(url('admin/portfolio-categories'));
        }

        $model = new PortfolioCategory();
        $model->create([
            'name' => $data['name'],
            'slug' => $this->makeUniqueSlug(!empty($data['slug']) ? $data['slug'] : slugify($data['name'])),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        Session::flash('success', 'دسته‌بندی جدید ایجاد شد.');
        \App\Core\Response::redirect(url('admin/portfolio-categories'));
    }

    public function update(string $id): void
    {
        Auth::requireLogin();
        $model = new PortfolioCategory();
        $cat = $model->find((int) $id);
        if (!$cat) {
            Session::flash('error', 'دسته‌بندی یافت نشد.');
            \App\Core\Response::redirect(url('admin/portfolio-categories'));
        }
        $data = $this->clean($this->request->all());
        $validator = \App\Core\Validator::make($data, ['name' => 'required|min:2|max:120']);
        if ($validator->fails()) {
            Session::set('_errors', $validator->errors);
            Session::flash('error', 'نام دسته‌بندی الزامی است.');
            \App\Core\Response::redirect(url('admin/portfolio-categories'));
        }
        $model->update((int) $id, [
            'name' => $data['name'],
            'slug' => $this->makeUniqueSlug(!empty($data['slug']) ? $data['slug'] : slugify($data['name']), (int) $id),
            'sort_order' => (int) ($data['sort_order'] ?? $cat['sort_order']),
            'is_published' => isset($data['is_published']) ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        Session::flash('success', 'دسته‌بندی به‌روزرسانی شد.');
        \App\Core\Response::redirect(url('admin/portfolio-categories'));
    }

    public function destroy(string $id): void
    {
        Auth::requireLogin();
        $model = new PortfolioCategory();
        $cat = $model->find((int) $id);
        if ($cat) {
            // جدا کردن نمونه‌کارها از این دسته (به NULL می‌روند، حذف نمی‌شوند)
            $model->delete((int) $id);
            Session::flash('success', 'دسته‌بندی حذف شد. نمونه‌کارهای مرتبط بدون دسته باقی ماندند.');
        } else {
            Session::flash('error', 'دسته‌بندی یافت نشد.');
        }
        \App\Core\Response::redirect(url('admin/portfolio-categories'));
    }

    private function makeUniqueSlug(string $slug, ?int $exceptId = null): string
    {
        $slug = $slug !== '' ? $slug : 'category';
        $base = $slug;
        $i = 1;
        while (true) {
            $existing = Database::fetch('SELECT id FROM portfolio_categories WHERE slug = ? LIMIT 1', [$slug]);
            if (!$existing || ($exceptId !== null && (int) $existing['id'] === $exceptId)) {
                return $slug;
            }
            $slug = $base . '-' . $i++;
        }
    }
}
