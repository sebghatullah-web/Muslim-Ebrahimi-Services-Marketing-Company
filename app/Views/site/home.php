<!-- Home Page -->
<!-- Hero Section -->
<?php if ($hero): ?>
<section class="bg-indigo-600 text-white py-16 rounded-xl overflow-hidden relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl font-bold mb-4 leading-tight"><?= e($hero['title'] ?? 'خدمات بازاریابی مسلم ابراهیمی') ?></h1>
            <p class="text-xl sm:text-2xl text-indigo-100 mb-8 font-medium"><?= e($hero['subtitle'] ?? '') ?></p>
            <p class="text-indigo-200 mb-8 max-w-lg mx-auto text-lg"><?= e($hero['body'] ?? '') ?></p>
            <?php if ($cta): ?>
            <a href="<?= e($cta['cta_url'] ?? '/contact') ?>" class="bg-white text-indigo-600 px-8 py-3 rounded-full font-bold hover:bg-indigo-50 transition">
                <?= e($cta['cta_text'] ?? 'تماس با ما') ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Advantages Section -->
<?php if ($advantages): ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">مزایای همکاری با ما</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">چرا مشتریان ما را انتخاب می‌کنند</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($advantages as $adv): ?>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mb-4">
                    <?= e($adv['icon'] ?? '★') ?>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2"><?= e($adv['title'] ?? '') ?></h3>
                <p class="text-gray-600 text-sm leading-relaxed"><?= e($adv['description'] ?? '') ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Services Section -->
<?php if ($services): ?>
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">خدمات ما</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">مینی-Services ارائه شده توسط شرکت</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($services as $service): ?>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition">
                <?php if ($service['image']): ?>
                <img src="<?= e($service['image']) ?>" alt="<?= e($service['title']) ?>" class="w-full h-48 object-cover">
                <?php endif; ?>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= e($service['title'] ?? '') ?></h3>
                    <p class="text-gray-600 mb-4 line-clamp-3"><?= e($service['summary'] ?? '') ?></p>
                    <a href="<?= url('/services/' . ($service['slug'] ?? '')) ?>" class="text-indigo-600 text-sm font-medium hover:underline">بیشتر بدانید →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimonials Section -->
<?php if ($testimonials): ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">ما را چه می‌گویند</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">نظرات مشتریان محترمان درباره خدمات ما</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($testimonials as $t): ?>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <?php if ($t['avatar']): ?>
                    <img src="<?= e($t['avatar']) ?>" alt="<?= e($t['client_name']) ?>" class="w-10 h-10 rounded-full">
                    <?php else: ?>
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                        <?= strtoupper(substr(e($t['client_name'] ?? ''), 0, 1)) ?>
                    </div>
                    <?php endif; ?>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm"><?= e($t['client_name'] ?? '') ?></h4>
                        <p class="text-xs text-gray-500"><?= e($t['client_role'] ?? '') . ($t['client_company'] ? ' - ' . e($t['client_company']) : '') ?></p>
                    </div>
                </div>
                <div class="flex text-yellow-400 mb-4">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <svg class="w-4 h-4 <?= ($i <= ($t['rating'] ?? 5)) ? 'fill-current text-yellow-400' : 'text-gray-200'; ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 001.503 1.07l1.618.709a1 1 0 00.487-.803l.625-1.922a1 1 0 00-.487-1.503l-1.306-1.173a1 1 0 00-1.503 0l-1.306 1.173a1 1 0 00-.487 1.503l.625 1.922a1 1 0 001.503.803l1.618-.709a1 1 0 001.503-1.07l1.07-3.292z"></path></svg>
                    <?php endfor; ?>
                </div>
                <p class="text-gray-600 italic text-sm leading-relaxed">"<?= e($t['quote'] ?? '') ?>"</p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-16 bg-indigo-600 rounded-xl overflow-hidden mb-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">آماده برای شروع هستید؟</h2>
        <p class="text-indigo-100 mb-8 max-w-lg mx-auto text-lg">با ما تماس بگیرید و پروژه خود را شروع کنید.</p>
        <?php if ($cta): ?>
        <a href="<?= e($cta['cta_url'] ?? '/contact') ?>" class="bg-white text-indigo-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition">
            <?= e($cta['cta_text'] ?? 'تماس با ما') ?>
        </a>
        <?php else: ?>
        <a href="/contact" class="bg-white text-indigo-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition">
            تماس با ما
        </a>
        <?php endif; ?>
    </div>
</section>