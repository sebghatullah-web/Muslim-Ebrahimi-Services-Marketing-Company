<?php
// $advantages, $errors available
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">مزایا و مراحل همکاری</h2>
        <p class="text-sm text-gray-500 mt-1">مدیریت لیست مزایا و مراحل همکاری نمایش داده‌شده در سایت</p>
    </div>

    <div class="px-6 py-6 space-y-6">
        <div class="bg-indigo-50 p-5 rounded-xl border border-indigo-100">
            <h3 class="text-sm font-semibold text-indigo-900 mb-4">افزودن مزیت جدید</h3>
            <form action="<?= url('admin/advantages') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <?= csrfField() ?>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">عنوان مزیت <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="<?= e(old('title')) ?>" required placeholder="مثل: پشتیبانی ۲۴ ساعته" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm <?php if (error_for('title')): ?>border-red-500<?php endif; ?>">
                    <?php if ($e = error_for('title')): ?><p class="mt-1 text-xs text-red-600"><?= e($e) ?></p><?php endif; ?>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">توضیح کوتاه</label>
                    <input type="text" name="description" value="<?= e(old('description')) ?>" placeholder="توضیح اختصار..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">افزودن</button>
                </div>
            </form>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 font-medium">عنوان</th>
                        <th class="px-4 py-3 font-medium">توضیح</th>
                        <th class="px-4 py-3 font-medium">آیکون</th>
                        <th class="px-4 py-3 font-medium">ترتیب</th>
                        <th class="px-4 py-3 font-medium">وضعیت</th>
                        <th class="px-4 py-3 font-medium">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($advantages)): ?>
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">موردی ثبت نشده است.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($advantages as $a): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                <form action="<?= url('admin/advantages/' . $a['id']) ?>" method="POST" class="flex items-center gap-2">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="text" name="title" value="<?= e($a['title']) ?>" class="px-2 py-1 border rounded text-sm bg-gray-50 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none w-32">
                                    <input type="text" name="description" value="<?= e($a['description'] ?? '') ?>" class="px-2 py-1 border rounded text-sm bg-gray-50 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none w-32">
                                    <input type="text" name="icon" value="<?= e($a['icon'] ?? '') ?>" class="px-2 py-1 border rounded text-sm bg-gray-50 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none w-20">
                                    <input type="number" name="sort_order" value="<?= e($a['sort_order'] ?? 0) ?>" min="0" class="px-2 py-1 border rounded text-sm bg-gray-50 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none w-16 text-center">
                                    <label class="flex items-center gap-1 text-xs cursor-pointer"><input type="checkbox" name="is_published" value="1" <?= (int) $a['is_published'] === 1 ? 'checked' : '' ?> class="w-3 h-3 text-indigo-600"><span><?= (int) $a['is_published'] === 1 ? 'منتشر' : 'پ‌نشر' ?></span></label>
                                    <button type="submit" class="px-2 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">ذخیره</button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="<?= e($a['description'] ?? '') ?>"><?= e($a['description'] ?? '-') ?></td>
                            <td class="px-4 py-3 text-gray-500 text-xs font-mono"><?= e($a['icon'] ?? '-') ?></td>
                            <td class="px-4 py-3 text-gray-600 font-medium"><?= e($a['sort_order']) ?></td>
                            <td class="px-4 py-3">
                                <form action="<?= url('admin/advantages/' . $a['id'] . '/toggle') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                    <?= csrfField() ?>
                                    <button type="submit" class="px-3 py-1 rounded text-xs font-medium <?= (int) $a['is_published'] === 1 ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                                        <?= (int) $a['is_published'] === 1 ? 'منتشر شده' : 'پ‌نشر' ?>
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3">
                                <form action="<?= url('admin/advantages/' . $a['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن به حذف این مورد هستید؟');">
                                    <?= csrfField() ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800 hover:underline text-sm">حذف</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
        </div>