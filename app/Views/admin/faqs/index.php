<?php
// $faqs, $search available
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">لیست پرسش‌های پرتکرار</h2>
            <p class="text-sm text-gray-500">مدیریت پرسش‌های متداول مشتریان و پاسخ‌ها</p>
        </div>
        <a href="<?= url('admin/faqs/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            افزودن پرسش جدید
        </a>
    </div>

    <div class="px-6 py-4">
        <form method="GET" action="<?= url('admin/faqs') ?>" class="flex gap-2 mb-4">
            <input type="text" name="q" value="<?= e($search) ?>" placeholder="جستجوی سوال یا پاسخ..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition">جستجو</button>
            <?php if ($search !== ''): ?>
                <a href="<?= url('admin/faqs') ?>" class="px-3 py-2 text-gray-500 hover:text-gray-700">پاک کردن</a>
            <?php endif; ?>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 font-medium">سوال</th>
                        <th class="px-4 py-3 font-medium">پاسخ</th>
                        <th class="px-4 py-3 font-medium">وضعیت</th>
                        <th class="px-4 py-3 font-medium">ترتیب</th>
                        <th class="px-4 py-3 font-medium">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($faqs)): ?>
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">پرسشی یافت نشد.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($faqs as $f): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900 max-w-xs truncate" title="<?= e($f['question']) ?>"><?= e($f['question']) ?></td>
                            <td class="px-4 py-3 text-gray-700 max-w-sm truncate italic" title="<?= e($f['answer']) ?>"><?= e($f['answer']) ?></td>
                            <td class="px-4 py-3">
                                <form action="<?= url('admin/faqs/' . $f['id'] . '/toggle') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                    <?= csrfField() ?>
                                    <button type="submit" class="px-3 py-1 rounded text-xs font-medium <?= (int) $f['is_published'] === 1 ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                                        <?= (int) $f['is_published'] === 1 ? 'منتشر شده' : 'پ‌نشر' ?>
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-gray-600 font-medium"><?= e($f['sort_order']) ?></td>
                            <td class="px-4 py-3">
                                <a href="<?= url('admin/faqs/' . $f['id'] . '/edit') ?>" class="text-indigo-600 hover:text-indigo-800 hover:underline text-sm">ویرایش</a>
                                <form action="<?= url('admin/faqs/' . $f['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن به حذف این پرسش هستید؟');">
                                    <?= csrfField() ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800 hover:underline text-sm ml-2">حذف</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>