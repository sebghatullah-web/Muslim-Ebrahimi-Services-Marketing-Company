<section class="py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">پرسش‌های پرتکرار</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">پاسخ پرسش‌های رایج درباره خدمات و فرآیند همکاری.</p>
        </div>
        <div class="max-w-2xl mx-auto space-y-4">
            <?php if ($faqs): ?>
                <?php foreach ($faqs as $faq): ?>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-2"><?= e($faq['question'] ?? '') ?></h3>
                    <p class="text-gray-600 text-sm leading-relaxed"><?= e($faq['answer'] ?? '') ?></p>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-12 text-gray-500">
                    پرسش و پاسخ یافت نشد.
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>