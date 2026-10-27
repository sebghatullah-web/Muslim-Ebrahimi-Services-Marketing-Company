<!-- Portfolio Detail Page -->
<?php if ($item): ?>
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <?php if ($item['image']): ?>
            <img src="<?= e($item['image']) ?>" alt="<?= e($item['title']) ?>" class="w-full h-64 object-cover rounded-xl mb-6">
            <?php endif; ?>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= e($item['title'] ?? '') ?></h1>
            <p class="text-gray-600 mb-6 leading-relaxed"><?= e($item['description'] ?? $item['summary'] ?? '') ?></p>
            
            <div class="bg-gray-50 p-6 rounded-xl mb-8">
                <h3 class="font-bold text-gray-900 mb-3">جزئیات پروژه</h3>
                <p class="text-sm text-gray-600"><span class="font-medium">دسته‌بندی:</span> <?= e($item['category_title'] ?? 'کلیه') ?></p>
                <p class="text-sm text-gray-600"><span class="font-medium">تاریخ:</span> <?= e($item['created_at'] ?? '') ?></p>
            </div>
            
            <a href="<?= url('/portfolio') ?>" class="text-indigo-600 hover:underline">بازگعه به لیست نمونه‌کارها →</a>
        </div>
    </div>
</section>

<?php if ($others): ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">پروژه‌های دیگر</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($others as $other): ?>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition">
                <?php if ($other['image']): ?>
                <img src="<?= e($other['image']) ?>" alt="<?= e($other['title']) ?>" class="w-full h-48 object-cover">
                <?php endif; ?>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= e($other['title'] ?? '') ?></h3>
                    <a href="<?= url('/portfolio/' . ($other['slug'] ?? '')) ?>" class="text-indigo-600 text-sm font-medium hover:underline">مشاهده پروژه →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php endif; ?>