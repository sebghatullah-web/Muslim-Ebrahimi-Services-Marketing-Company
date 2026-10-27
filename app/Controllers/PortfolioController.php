<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;

class PortfolioController extends Controller
{
    public function index(): void
    {
        $portfolioModel = new Portfolio();
        $categoryModel = new PortfolioCategory();

        $categoryId = isset($_GET['category']) ? (int) $_GET['category'] : null;
        if ($categoryId !== null) {
            $exists = \App\Core\Database::fetch(
                'SELECT id FROM portfolio_categories WHERE id = ? AND is_published = 1 LIMIT 1',
                [$categoryId]
            );
            if (!$exists) {
                $categoryId = null;
            }
        }

        $this->view('site/portfolio', [
            'title' => 'نمونه‌کارها | ' . setting('site_name'),
            'metaDescription' => 'مروری بر پروژه‌ها و نمونه‌کارهای انجام‌شده توسط تیم ما.',
            'portfolios' => $portfolioModel->published($categoryId),
            'categories' => $categoryModel->published(),
            'activeCategory' => $categoryId,
        ]);
    }

    public function show(string $slug): void
    {
        $model = new Portfolio();
        $item = $model->findBySlug($slug);

        if (!$item || (int) $item['is_published'] !== 1) {
            http_response_code(404);
            $this->view('site/404', ['title' => 'پروژه پیدا نشد']);
            return;
        }

        $others = array_values(array_filter(
            $model->published(),
            fn($p) => (int) $p['id'] !== (int) $item['id']
        ));
        $others = array_slice($others, 0, 3);

        $this->view('site/portfolio-show', [
            'title' => $item['seo_title'] ?: $item['title'] . ' | ' . setting('site_name'),
            'metaDescription' => $item['seo_description'] ?: $item['summary'],
            'item' => $item,
            'others' => $others,
        ]);
    }
}
