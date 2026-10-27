<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Service;
use App\Models\Portfolio;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\Message;
use App\Models\Advantage;

class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $this->adminView('admin/dashboard', [
            'title' => 'داشبورد | پنل مدیریت',
            'stats' => [
                'services'    => (new Service())->count(),
                'portfolios'  => (new Portfolio())->count(),
                'testimonials'=> (new Testimonial())->count(),
                'faqs'        => (new Faq())->count(),
                'advantages'  => (new Advantage())->count(),
                'unread'      => (new Message())->unreadCount(),
            ],
            'recentMessages' => (new Message())->latest(5),
        ]);
    }
}
