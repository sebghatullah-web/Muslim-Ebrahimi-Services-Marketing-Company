<!-- Portfolio Page -->
<?php if ($portfolios): ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">نمونه‌کارها</h2>
                <p class="text-gray-600">مروری بر پروژه‌ها و نمونه‌کارهای انجام‌شده توسط تیم ما.</p>
            </div>
            
            <?php if ($categories): ?>
            <div class="flex gap-2 overflow-x-auto pb-2">
                <a href="<?= url('/portfolio') ?>" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:border-indigo-200 transition whitespace-nowrap">
                    همه
                </a>
                <?php foreach ($categories as $category): ?>
                <a href="<?= url('/portfolio?category=' . $category['id']) ?>" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:border-indigo-200 transition whitespace-nowrap <?= ($activeCategory == $category['id']) ? 'bg-indigo-50 border-indigo-200 text-indigo-600' : '' ?>">
                    <?= e($category['title'] ?? '') ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($portfolios as $portfolio): ?>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition">
                <?php if ($portfolio['image']): ?>
                <img src="<?= e($portfolio['image']) ?>" alt="<?= e($portfolio['title']) ?>" class="w-full h-48 object-cover">
                <?php endif; ?>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= e($portfolio['title'] ?? '') ?></h3>
                    <p class="text-gray-600 mb-4 line-clamp-3"><?= e($portfolio['summary'] ?? '') ?></p>
                    <a href="<?= url('/portfolio/' . ($portfolio['slug'] ?? '')) ?>" class="text-indigo-600 text-sm font-medium hover:underline">مشاهده پروژه →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php else: ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">نمونه‌کارها</h2>
        <p class="text-gray-600">هنوز نمونه‌کاری ثبت نشده است.</p>
    </div>
</section>
<?php endif; ?>