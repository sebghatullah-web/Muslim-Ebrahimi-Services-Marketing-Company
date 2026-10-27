<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Service;
use App\Models\Portfolio;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\Advantage;
use App\Models\HomeSection;

class HomeController extends Controller
{
    public function index(): void
    {
        $serviceModel = new Service();
        $portfolioModel = new Portfolio();
        $testimonialModel = new Testimonial();
        $faqModel = new Faq();
        $advantageModel = new Advantage();
        $homeModel = new HomeSection();

        $this->view('site/home', [
            'title' => setting('seo_title', setting('site_name')),
            'metaDescription' => setting('seo_description'),
            'hero' => $homeModel->findByKey('hero'),
            'about' => $homeModel->findByKey('about'),
            'cta' => $homeModel->findByKey('cta'),
            'services' => $serviceModel->published(),
            'portfolios' => $portfolioModel->published(),
            'testimonials' => $testimonialModel->published(),
            'faqs' => $faqModel->published(),
            'advantages' => $advantageModel->published(),
        ]);
    }

    public function about(): void
    {
        $homeModel = new HomeSection();
        $this->view('site/about', [
            'title' => 'درباره ما | ' . setting('site_name'),
            'metaDescription' => 'درباره شرکت خدماتی و بازاریابی مسلم ابراهیمی بیشتر بدانید.',
            'about' => $homeModel->findByKey('about'),
            'advantages' => (new Advantage())->published(),
        ]);
    }

    public function contact(): void
    {
        $this->view('site/contact', [
            'title' => 'تماس با ما | ' . setting('site_name'),
            'metaDescription' => 'برای درخواست مشاوره یا سفارش با ما در تماس باشید.',
        ]);
    }

    public function faq(): void
    {
        $this->view('site/faq', [
            'title' => 'پرسش‌های پرتکرار | ' . setting('site_name'),
            'metaDescription' => 'پاسخ پرسش‌های رایج درباره خدمات و فرآیند همکاری.',
            'faqs' => (new Faq())->published(),
        ]);
    }
}
