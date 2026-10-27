<!-- Service Detail Page -->
<?php if ($service): ?>
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <?php if ($service['image']): ?>
            <img src="<?= e($service['image']) ?>" alt="<?= e($service['title']) ?>" class="w-full h-64 object-cover rounded-xl mb-6">
            <?php endif; ?>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= e($service['title'] ?? '') ?></h1>
            <p class="text-gray-600 mb-6 leading-relaxed"><?= e($service['description'] ?? $service['summary'] ?? '') ?></p>
            
            <a href="<?= url('/services') ?>" class="text-indigo-600 hover:underline">بازگعه به لیست خدمات →</a>
        </div>
    </div>
</section>

<?php if ($others): ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">خدمات دیگر</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($others as $other): ?>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition">
                <?php if ($other['image']): ?>
                <img src="<?= e($other['image']) ?>" alt="<?= e($other['title']) ?>" class="w-full h-48 object-cover">
                <?php endif; ?>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= e($other['title'] ?? '') ?></h3>
                    <a href="<?= url('/services/' . ($other['slug'] ?? '')) ?>" class="text-indigo-600 text-sm font-medium hover:underline">بیشتر بدانید →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php endif; ?>