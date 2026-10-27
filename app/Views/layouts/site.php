<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'خدمات بازاریابی مسلم ابراهیمی') ?></title>
    <meta name="description" content="<?= e($metaDescription ?? '') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Vazirmatn', Tahoma, sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">
    <meta name="csrf-token" content="<?= \App\Core\Csrf::token() ?>">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-indigo-600 rounded-lg p-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v14m-6 0h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-indigo-700">خدمات بازاریابی مسلم ابراهیمی</span>
                </div>
                <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                    <a href="<?= url('/') ?>" class="text-gray-700 hover:text-indigo-600 transition">صفحه اصلی</a>
                    <a href="<?= url('/services') ?>" class="text-gray-700 hover:text-indigo-600 transition">خدمات</a>
                    <a href="<?= url('/portfolio') ?>" class="text-gray-700 hover:text-indigo-600 transition">نمونه‌کارها</a>
                    <a href="<?= url('/about') ?>" class="text-gray-700 hover:text-indigo-600 transition">درباره ما</a>
                    <a href="<?= url('/contact') ?>" class="text-gray-700 hover:text-indigo-600 transition">تماس با ما</a>
                    <a href="<?= url('/faq') ?>" class="text-gray-700 hover:text-indigo-600 transition">پرسش‌های پرتکرار</a>
                </div>
                <div class="md:hidden">
                    <button class="text-gray-700 hover:text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash messages -->
    <?php if ($flash): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 space-y-3">
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

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-12 py-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm">
                © <?= date('Y') ?> خدمات بازاریابی مسلم ابراهیمی. تمامی حقوق محفوظ است.
            </p>
        </div>
    </footer>
</body>
</html>