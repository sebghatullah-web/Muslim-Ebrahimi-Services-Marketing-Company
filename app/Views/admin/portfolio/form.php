<?php
// $item (null=create, array=edit), $categories, $nextOrder, $errors
$isEdit = !empty($item);
$action = $isEdit ? url('admin/portfolio/' . $item['id']) : url('admin/portfolio');
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900"><?= $isEdit ? 'ویرایش نمونه‌کار' : 'افزودن نمونه‌کار جدید' ?></h2>
        <p class="text-sm text-gray-500 mt-1"><?= $isEdit ? 'تغییرات نمونه‌کار را ذخیره کنید.' : 'فرم زیر را پر کنید تا نمونه‌کار جدید ایجاد شود.' ?></p>
    </div>

    <form action="<?= e($action) ?>" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-5">
        <?= csrfField() ?>
        <?php if ($isEdit): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">عنوان نمونه‌کار <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="<?= e(old('title', $item['title'] ?? '')) ?>" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm <?php if (error_for('title')): ?>border-red-500<?php endif; ?>">
                <?php if ($e = error_for('title')): ?><p class="mt-1 text-xs text-red-600"><?= e($e) ?></p><?php endif; ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نامک (slug)</label>
                <input type="text" name="slug" value="<?= e(old('slug', $item['slug'] ?? '')) ?>" placeholder="مثل: project-name" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نام مشتری</label>
                <input type="text" name="client_name" value="<?= e(old('client_name', $item['client_name'] ?? '')) ?>" placeholder="نام مشتری" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">دسته‌بندی</label>
                <select name="category_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm bg-white">
                    <option value="">بدون دسته‌بندی</option>
                    <?php foreach ($categories ?? [] as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (old('category_id', $item['category_id'] ?? null)) == $cat['id'] ? 'selected' : '' ?>><?= e($cat['title']) ?></option>
                    <?php endforeach; ?>
                </select>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">چکیده (summary)</label>
            <textarea name="summary" rows="2" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm"><?= e(old('summary', $item['summary'] ?? '')) ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">توضیحات کامل</label>
            <textarea name="description" rows="4" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm"><?= e(old('description', $item['description'] ?? '')) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">لینک پروژه</label>
                <input type="url" name="project_url" value="<?= e(old('project_url', $item['project_url'] ?? '')) ?>" placeholder="https://example.com" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">عنوان سئو (meta title)</label>
                <input type="text" name="seo_title" value="<?= e(old('seo_title', $item['seo_title'] ?? '')) ?>" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">توضیح سئو (meta description)</label>
                <input type="text" name="seo_description" value="<?= e(old('seo_description', $item['seo_description'] ?? '')) ?>" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ترتیب نمایش</label>
                <input type="number" name="sort_order" value="<?= e(old('sort_order', $item['sort_order'] ?? $nextOrder)) ?>" min="0" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            </div>
            <div class="mt-8 md:mt-0 flex items-center gap-2">
                <input type="checkbox" name="is_published" value="1" <?= (old('is_published', $item['is_published'] ?? 1) ? 'checked' : '') ?> class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                <label class="text-sm font-medium text-gray-700">منتشر شود (نمایش در سایت)</label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">تصویر نمونه‌کار</label>
            <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
            <?php if ($isEdit && !empty($item['image'])): ?>
                <div class="mt-3 flex items-center gap-3">
                    <img src="<?= uploadUrl($item['image']) ?>" class="w-16 h-16 rounded object-cover border border-gray-200" alt="تصویر فعلی">
                    <span class="text-xs text-gray-500">تصویر فعلی</span>
                </div>
            <?php endif; ?>
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="<?= url('admin/portfolio') ?>" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg text-sm font-medium">لغو</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium shadow-sm transition"><?= $isEdit ? 'ذخیره تغییرات' : 'ایجاد نمونه‌کار' ?></button>
        </div>
    </form>
</div>
            </div>
        </div>