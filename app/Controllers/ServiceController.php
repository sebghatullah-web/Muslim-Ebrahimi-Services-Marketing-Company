<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Service;
use App\Models\Faq;

class ServiceController extends Controller
{
    public function index(): void
    {
        $model = new Service();
        $this->view('site/services', [
            'title' => 'خدمات ما | ' . setting('site_name'),
            'metaDescription' => 'فهرست کامل خدمات برندسازی، طراحی، تولید محتوا و بازاریابی دیجیتال.',
            'services' => $model->published(),
            'faqs' => (new Faq())->published(),
        ]);
    }

    public function show(string $slug): void
    {
        $model = new Service();
        $service = $model->findBySlug($slug);

        if (!$service || (int) $service['is_published'] !== 1) {
            http_response_code(404);
            $this->view('site/404', ['title' => 'خدمت پیدا نشد']);
            return;
        }

        // سایر خدمات برای نمایش در پایان صفحه
        $others = array_values(array_filter(
            $model->published(),
            fn($s) => (int) $s['id'] !== (int) $service['id']
        ));

        $this->view('site/service-show', [
            'title' => $service['seo_title'] ?: $service['title'] . ' | ' . setting('site_name'),
            'metaDescription' => $service['seo_description'] ?: $service['summary'],
            'service' => $service,
            'others' => $others,
        ]);
    }
}
