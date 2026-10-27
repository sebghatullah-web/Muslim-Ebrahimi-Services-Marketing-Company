<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'پنل مدیریت') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Vazirmatn', Tahoma, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <meta name="csrf-token" content="<?= \App\Core\Csrf::token() ?>">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="bg-white border-l border-gray-200 z-20 flex flex-col w-64 shrink-0">
            <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                <div class="bg-indigo-600 rounded-lg p-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v14m-6 0h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="font-bold text-lg text-indigo-700">مusalimEbrahimi</span>
                <span class="text-xs text-gray-500 block">پنل مدیریت</span>
            <nav class="flex-1 overflow-y-auto p-2 space-y-1">
                <?php
                $route = $_SERVER['REQUEST_URI'] ?? '/';
                $nav = [
                    ['url' => url('admin'), 'label' => 'داشبورد', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['url' => url('admin/services'), 'label' => 'خدمات', 'icon' => 'M12 6V4m0 2a2 2 0 100 4h2a2 2 0 100-4H12m0 2a2 2 0 100 4h2a2 2 0 100-4H12m0 5a2 2 0 100 4h2a2 2 0 100-4H12m-5 4a2 2 0 100 4h2a2 2 0 100-4H7m0 5a2 2 0 100 4h2a2 2 0 100-4H7m-2-4h2m0 4h2m0-4h2m5-11h2a2 2 0 100-4H2m0 0h2m0 4h2m0-4h2m5-11h2a2 2 0 100-4H7m0 0h2m0 4h2m0-4h2m-5-5V8m0 0h2M7 8h2m0 0v8m0 0H5a2 2 0 01-2-2V6a2 2 0 012-2h2a2 2 0 012 2v2m0 0h2M7 8V6m0 0l-2 2m2-2l2 2m0 0v8m0 0H5a2 2 0 01-2-2V6a2 2 0 012-2h2a2 2 0 012 2v2m0 0h2'],
                    ['url' => url('admin/portfolio'), 'label' => 'نمونه‌کارها', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['url' => url('admin/portfolio-categories'), 'label' => 'دسته‌بندی‌ها', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['url' => url('admin/testimonials'), 'label' => 'نظرات مشتریان', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['url' => url('admin/faqs'), 'label' => 'پرسش‌های پرتکرار', 'icon' => 'M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['url' => url('admin/advantages'), 'label' => 'مزایا و مراحل', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['url' => url('admin/messages'), 'label' => 'پیام‌ها', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['url' => url('admin/home'), 'label' => 'محتوای صفحه خانه', 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
                    ['url' => url('admin/settings'), 'label' => 'تنظیمات سایت', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c-1.756.426-1.756 2.924 0 3.35a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c-1.756.426-1.756 2.924 0 3.35a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c-1.756.426-1.756 2.924 0 3.35a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37L19.835 21h-1.47c-1.756 0-2.37-1.756-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37zM12 22c-4.971 0-9-4.029-9-9s4.029-9 9-9 9 4.029 9 9-4.029 9-9 9z'],
                    ['url' => url('admin/settings/social'), 'label' => 'شبکه‌های اجتماعی', 'icon' => 'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m8-12v-2a4 4 0 014-4h8a4 4 0 014 4v2M5 9h8V5H5v4zm0 2h8v4H5V9zM5 14h8v4H5v-4zM5 5a2 2 0 012-2h8a2 2 0 012 2v2H5V5z'],
                    ['url' => url('admin/profile'), 'label' => 'پروفایل مدیریت', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ];
                $isActive = fn($u) => $route === $u || str_starts_with($route, rtrim($u, '/') . '/');
                ?>
                <?php foreach ($nav as $item): 
                    $active = $isActive($item['url']);
                ?>
                    <a href="<?= e($item['url']) ?>" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition <?= $active ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' ?>">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= e($item['icon']) ?>"></path></svg>
                        <?= e($item['label']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="p-4 border-t border-gray-100">
                <a href="<?= url('admin/logout') ?>" onclick="return confirm('از خروج از سیستم مطمئن هستید؟');" class="block px-4 py-3 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition flex items-center justify-between">
                    <span>خروج از سیستم</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </a>
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900"><?= e($title ?? 'پنل مدیریت') ?></h1>
                        <?php if (!empty($subtitle ?? '')): ?>
                            <p class="text-gray-500 text-sm mt-1"><?= e($subtitle) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                            <?= e($authUser['name'] ?? 'مدیر') ?>
                        </span>
                    </div>
                </div>

                <!-- Flash messages -->
                <?php if ($flash): ?>
                    <div class="mb-6 space-y-3">
                        <?php foreach ($flash as $type => $messages): ?>
                            <?php foreach ((array) $messages as $msg): ?>
                                <div class="p-4 rounded-lg text-sm border <?php
                                    if ($type === 'success') echo 'bg-green-50 border-green-200 text-green-800';
                                    elseif ($type === 'error') echo 'bg-red-50 border-red-200 text-red-800';
                                    elseif ($type === 'warning') echo 'bg-orange-50 border-orange-200 text-orange-800';
                                    elseif ($type === 'info') echo 'bg-blue-50 border-blue-200 text-blue-800';
                                    else echo 'bg-gray-50 border-gray-200 text-gray-800';
                                ?>"><?= e($msg) ?></div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Content -->
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>

    <script>
        function confirmAction(message, url) {
            if (confirm(message)) { location.href = url; }
        }
    </script>
</body>
</html>
            </div>