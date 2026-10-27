<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">درباره ما</h1>
            <?php if ($about): ?>
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="prose prose-lg text-gray-700 leading-relaxed">
                    <?= e($about['body'] ?? '') ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($advantages): ?>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">مزایای ما</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($advantages as $advantage): ?>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-3"><?= e($advantage['title'] ?? '') ?></h3>
                    <p class="text-gray-600 text-sm leading-relaxed"><?= e($advantage['description'] ?? '') ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>