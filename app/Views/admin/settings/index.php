<?php
// $general, $contact, $seo arrays (key => value), $maxMb, $errors available
$g = fn($k) => $general[$k] ?? '';
$c = fn($k) => $contact[$k] ?? '';
$s = fn($k) => $seo[$k] ?? '';
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">تنظیمات سایت</h2>
        <p class="text-sm text-gray-500 mt-1">تنظیمات کلی، تماس و سئو سایت را می‌توانید در این صفحه مدیریت کنید.</p>
    </div>

    <form action="<?= url('admin/settings') ?>" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-8">
        <?= csrfField() ?>

        <div class="border rounded-xl p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4h2a2 2 0 100-4H12m0 2a2 2 0 100 4h2a2 2 0 100-4H12m0 5a2 2 0 100 4h2a2 2 0 100-4H12m-5 4a2 2 0 100 4h2a2 2 0 100-4H7m0 5a2 2 0 100 4h2a2 2 0 100-4H7m-2-4h2m0 4h2m0-4h2m5-11h2a2 2 0 100-4H2m0 0h2m0 4h2m0-4h2m5-11h2a2 2 0 100-4H7m0 0h2m0 4h2m0-4h2m-5-5V8m0 0h2M7 8h2m0 0v8m0 0H5a2 2 0 01-2-2V6a2 2 0 012-2h2a2 2 0 012 2v2m0 0h2M7 8V6m0 0l-2 2m2-2l2 2m0 0v8m0 0H5a2 2 0 01-2-2V6a2 2 0 012-2h2a2 2 0 012 2v2m0 0h2"></path></svg>
                عمومی
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach (['site_name' => 'نام سایت', 'site_tagline' => 'شعار سایت', 'logo_text' => 'متن لوگو', 'primary_color' => 'رنگ اولیه', 'secondary_color' => 'رنگ ثانویه', 'footer_text' => 'متن فوتر'] as $key => $label): ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1"><?= e($label) ?></label>
                        <input type="text" name="<?= e($key) ?>" value="<?= e($g($key)) ?>" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                    </div>
                <?php endforeach; ?>
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">لوگو سایت (تصویر)</label>
                    <input type="file" name="logo_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                    <?php if (!empty($g('logo_image'))): ?>
                        <div class="mt-3 flex items-center gap-3">
                            <img src="<?= uploadUrl($g('logo_image')) ?>" class="w-16 h-16 rounded object-cover border border-gray-200" alt="لوگو">
                            <span class="text-xs text-gray-500">لوگو فعلی</span>
                        </div>
                    <?php endif; ?>
                    <p class="text-xs text-gray-500 mt-1">حداکثر <?= e($maxMb) ?> مگابایت</p>
                </div>
        <div class="border rounded-xl p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                تماس
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach (['phone_primary' => 'تلفن اصلی', 'phone_secondary' => 'تلفن ثانویه', 'whatsapp_number' => 'شماره واتساپ', 'email' => 'ایمیل', 'address' => 'آدرس', 'working_hours' => 'ساعات کار'] as $key => $label): ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1"><?= e($label) ?></label>
                        <input type="text" name="<?= e($key) ?>" value="<?= e($c($key)) ?>" placeholder="<?= e($label) ?>" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="border rounded-xl p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v14m-6 0h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                سئو
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">عنوان سئو (meta title)</label>
                    <input type="text" name="seo_title" value="<?= e($s('seo_title')) ?>" placeholder="عنوان سئو سایت" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">توضیح سئو (meta description)</label>
                    <textarea name="seo_description" rows="2" placeholder="توضیح کوتاه سئو..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm"><?= e($s('seo_description')) ?></textarea>
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-gray-200">
            <a href="<?= url('admin') ?>" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg text-sm font-medium">لغو</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium shadow-sm transition">ذخیره تنظیمات</button>
        </div>
    </form>
</div>
            </div>
        </div>