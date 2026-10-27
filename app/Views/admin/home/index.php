<?php
// $hero, $about, $cta arrays available
$h = fn($k, $d = '') => $hero[$k] ?? $d;
$a = fn($k, $d = '') => $about[$k] ?? $d;
$ct = fn($k, $d = '') => $cta[$k] ?? $d;
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">محتوای صفحه خانه</h2>
        <p class="text-sm text-gray-500 mt-1">مدیریت بخش‌هایician، درباره ما و دعوت به اقدام (CTA) صفحه اصلی</p>
    </div>

    <form action="<?= url('admin/home') ?>" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-8">
        <?= csrfField() ?>
        <input type="hidden" name="_method" value="PUT">

        <!-- Hero -->
        <div class="border rounded-xl p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4h2a2 2 0 100-4H12m0 2a2 2 0 100 4h2a2 2 0 100-4H12m0 5a2 2 0 100 4h2a2 2 0 100-4H12m-5 4a2 2 0 100 4h2a2 2 0 100-4H7m0 5a2 2 0 100 4h2a2 2 0 100-4H7m-2-4h2m0 4h2m0-4h2m5-11h2a2 2 0 100-4H2m0 0h2m0 4h2m0-4h2m5-11h2a2 2 0 100-4H7m0 0h2m0 4h2m0-4h2m-5-5V8m0 0h2M7 8h2m0 0v8m0 0H5a2 2 0 01-2-2V6a2 2 0 012-2h2a2 2 0 012 2v2m0 0h2M7 8V6m0 0l-2 2m2-2l2 2m0 0v8m0 0H5a2 2 0 01-2-2V6a2 2 0 012-2h2a2 2 0 012 2v2m0 0h2"></path></svg>
                بخش هیرو (صفحه اصلی)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">عنوان اصلی</label>
                    <input type="text" name="hero_title" value="<?= e($h('title')) ?>" placeholder="عنوان صفحه اصلی" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">زیرنویس</label>
                    <input type="text" name="hero_subtitle" value="<?= e($h('subtitle')) ?>" placeholder="توضیح کوتاه..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">متن دکمه</label>
                    <input type="text" name="hero_button_text" value="<?= e($h('button_text')) ?>" placeholder="مثل: تماس با ما" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">لینک دکمه</label>
                    <input type="text" name="hero_button_link" value="<?= e($h('button_link')) ?>" placeholder="/services" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">تصویر هیرو</label>
                    <input type="file" name="hero_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                    <?php if (!empty($h('image'))): ?>
                        <div class="mt-3 flex items-center gap-3">
                            <img src="<?= uploadUrl($h('image')) ?>" class="w-20 h-20 rounded object-cover border border-gray-200" alt="تصویر هیرو">
                            <span class="text-xs text-gray-500">تصویر فعلی</span>
                        </div>
                    <?php endif; ?>
                </div>
        <!-- About -->
        <div class="border rounded-xl p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                بخش درباره ما
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">عنوان درباره ما</label>
                    <input type="text" name="about_title" value="<?= e($a('title')) ?>" placeholder="عنوان بخش درباره ما" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">توضیح درباره ما</label>
                    <textarea name="about_description" rows="3" placeholder="متن درباره ما..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm"><?= e($a('description')) ?></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">تصویر درباره ما</label>
                    <input type="file" name="about_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                    <?php if (!empty($a('image'))): ?>
                        <div class="mt-3 flex items-center gap-3">
                            <img src="<?= uploadUrl($a('image')) ?>" class="w-20 h-20 rounded object-cover border border-gray-200" alt="تصویر درباره ما">
                            <span class="text-xs text-gray-500">تصویر فعلی</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="border rounded-xl p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                بخش دعوت به اقدام (CTA)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">عنوان CTA</label>
                    <input type="text" name="cta_title" value="<?= e($ct('title')) ?>" placeholder="عنوان دعوت به اقدام" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">زیرنویس CTA</label>
                    <input type="text" name="cta_subtitle" value="<?= e($ct('subtitle')) ?>" placeholder="توضیح کوتاه..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">متن دکمه CTA</label>
                    <input type="text" name="cta_button_text" value="<?= e($ct('button_text')) ?>" placeholder="مثل: ثبت نام" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">لینک دکمه CTA</label>
                    <input type="text" name="cta_button_link" value="<?= e($ct('button_link')) ?>" placeholder="/contact" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">تصویر CTA</label>
                    <input type="file" name="cta_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                    <?php if (!empty($ct('image'))): ?>
                        <div class="mt-3 flex items-center gap-3">
                            <img src="<?= uploadUrl($ct('image')) ?>" class="w-20 h-20 rounded object-cover border border-gray-200" alt="تصویر CTA">
                            <span class="text-xs text-gray-500">تصویر فعلی</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-gray-200">
            <a href="<?= url('admin') ?>" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg text-sm font-medium">لغو</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium shadow-sm transition">ذخیره محتوای خانه</button>
        </div>
    </form>
</div>
            </div>
        </div>