<?php
// $item (null=create, array=edit), $errors available
$isEdit = !empty($item);
$action = $isEdit ? url('admin/faqs/' . $item['id']) : url('admin/faqs');
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900"><?= $isEdit ? 'ویرایش پرسش' : 'افزودن پرسش جدید' ?></h2>
        <p class="text-sm text-gray-500 mt-1"><?= $isEdit ? 'تغییرات پرسش و پاسخ را ذخیره کنید.' : 'فرم زیر را پر کنید تا پرسش جدید ایجاد شود.' ?></p>
    </div>

    <form action="<?= e($action) ?>" method="POST" class="px-6 py-6 space-y-5">
        <?= csrfField() ?>
        <?php if ($isEdit): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">متن سوال <span class="text-red-500">*</span></label>
            <input type="text" name="question" value="<?= e(old('question', $item['question'] ?? '')) ?>" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm <?php if (error_for('question')): ?>border-red-500<?php endif; ?>">
            <?php if ($e = error_for('question')): ?><p class="mt-1 text-xs text-red-600"><?= e($e) ?></p><?php endif; ?>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">متن پاسخ <span class="text-red-500">*</span></label>
            <textarea name="answer" rows="5" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm <?php if (error_for('answer')): ?>border-red-500<?php endif; ?>"><?= e(old('answer', $item['answer'] ?? '')) ?></textarea>
            <?php if ($e = error_for('answer')): ?><p class="mt-1 text-xs text-red-600"><?= e($e) ?></p><?php endif; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="mt-8 md:mt-0 flex items-center gap-2">
                <input type="checkbox" name="is_published" value="1" <?= (old('is_published', $item['is_published'] ?? 1) ? 'checked' : '') ?> class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                <label class="text-sm font-medium text-gray-700">منتشر شود (نمایش در سایت)</label>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ترتیب نمایش</label>
                <input type="number" name="sort_order" value="<?= e(old('sort_order', $item['sort_order'] ?? 0)) ?>" min="0" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="<?= url('admin/faqs') ?>" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg text-sm font-medium">لغو</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium shadow-sm transition"><?= $isEdit ? 'ذخیره تغییرات' : 'ایجاد پرسش' ?></button>
        </div>
    </form>
</div>