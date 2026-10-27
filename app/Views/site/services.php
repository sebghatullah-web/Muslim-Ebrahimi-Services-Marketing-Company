<!-- Services Page -->
<?php if ($services): ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">خدمات ما</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">فهرست کامل خدمات ارائه شده توسط شرکت خدمات بازاریابی مسلم ابراهیمی</p>
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

<?php if ($faqs): ?>
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">پرسش‌های رایج</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">پاسخ به پرسش‌های متداول درباره خدمات ما</p>
        </div>
        <div class="max-w-2xl mx-auto space-y-4">
            <?php foreach ($faqs as $faq): ?>
            <div class="border border-gray-200 rounded-lg p-6 hover:border-indigo-200 transition">
                <h4 class="font-bold text-gray-900 mb-2"><?= e($faq['question'] ?? '') ?></h4>
                <p class="text-gray-600 text-sm"><?= e($faq['answer'] ?? '') ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>