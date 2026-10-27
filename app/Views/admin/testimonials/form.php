<?php
// $item (null=create, array=edit), $errors available
$isEdit = !empty($item);
$action = $isEdit ? url('admin/testimonials/' . $item['id']) : url('admin/testimonials');
$method = $isEdit ? 'POST' : 'POST';
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900"><?= $isEdit ? 'ویرایش نظر مشتری' : 'افزودن نظر مشتری جدید' ?></h2>
        <p class="text-sm text-gray-500 mt-1"><?= $isEdit ? 'تغییرات نظر مشتری را ذخیره کنید.' : 'فرم زیر را پر کنید تا نظر جدید ایجاد شود.' ?></p>
    </div>

    <form action="<?= e($action) ?>" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-5">
        <?= csrfField() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <!-- نام مشتری -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نام مشتری <span class="text-red-500">*</span></label>
                <input type="text" name="client_name" value="<?= e(old('client_name', $item['client_name'] ?? '')) ?>" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm <?php if (error_for('client_name')): ?>border-red-500<?php endif; ?>">
                <?php if ($e = error_for('client_name')): ?><p class="mt-1 text-xs text-red-600"><?= e($e) ?></p><?php endif; ?>
            </div>

            <!-- نقش/شغلی -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نقش یا شغلی</label>
                <input type="text" name="client_role" value="<?= e(old('client_role', $item['client_role'] ?? '')) ?>" placeholder="مثل: مدیر کارگاه" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            </div>

            <!-- شرکت -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">شرکت / организаissement</label>
                <input type="text" name="client_company" value="<?= e(old('client_company', $item['client_company'] ?? '')) ?>" placeholder="نام شرکت" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            </div>
        </div>

        <!-- متن نظر -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">متن نظر <span class="text-red-500">*</span></label>
            <textarea name="quote" rows="4" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm <?php if (error_for('quote')): ?>border-red-500<?php endif; ?>"><?= e(old('quote', $item['quote'] ?? '')) ?></textarea>
            <?php if ($e = error_for('quote')): ?><p class="mt-1 text-xs text-red-600"><?= e($e) ?></p><?php endif; ?>
        </div>

        <!-- آواتار -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">آواتار مشتری (تصویر)</label>
            <input type="file" name="avatar" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" <?php if ($isEdit): ?>data-has-existing<?php endif; ?>>
            <?php if ($isEdit && !empty($item['avatar'])): ?>
                <div class="mt-3 flex items-center gap-3">
                    <img src="<?= uploadUrl($item['avatar']) ?>" class="w-16 h-16 rounded-full object-cover border border-gray-200" alt="آواتار فعلی">
                    <span class="text-xs text-gray-500">آواتار فعلی</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- امتیاز -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">امتیاز (1 تا 5)</label>
                <select name="rating" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                    <?php for ($r = 1; $r <= 5; $r++): ?>
                        <option value="<?= $r ?>" <?= (old('rating', $item['rating'] ?? 5)) == $r ? 'selected' : '' ?>><?= $r ?> ستاره</option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- ترتیب -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ترتیب نمایش</label>
                <input type="number" name="sort_order" value="<?= e(old('sort_order', $item['sort_order'] ?? 0)) ?>" min="0" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            </div>

            <!-- منتشر شده -->
            <div class="mt-8 md:mt-0 flex items-center gap-2">
                <input type="checkbox" name="is_published" value="1" <?= (old('is_published', $item['is_published'] ?? 1) ? 'checked' : '') ?> class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                <label class="text-sm font-medium text-gray-700">منتشر شود (نمایش در سایت)</label>
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="<?= url('admin/testimonials') ?>" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg text-sm font-medium">لغو</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium shadow-sm transition"> <?= $isEdit ? 'ذخیره تغییرات' : 'ایجاد نظر' ?> </button>
        </div>
    </form>
</div>