<?php
// $testimonials, $search available
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">لیست نظرات مشتریان</h2>
            <p class="text-sm text-gray-500">مدیریت، تایید و حذف نظرات مشتریان منتشر‌شده در سایت</p>
        </div>
        <a href="<?= url('admin/testimonials/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            افزودن نظر جدید
        </a>
    </div>

    <div class="px-6 py-4">
        <form method="GET" action="<?= url('admin/testimonials') ?>" class="flex gap-2 mb-4">
            <input type="text" name="q" value="<?= e($search) ?>" placeholder="جستجوی نام مشتری یا متن نظر..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition">جستجو</button>
            <?php if ($search !== ''): ?>
                <a href="<?= url('admin/testimonials') ?>" class="px-3 py-2 text-gray-500 hover:text-gray-700">پاک کردن</a>
            <?php endif; ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 font-medium">آواتار</th>
                        <th class="px-4 py-3 font-medium">نام مشتری</th>
                        <th class="px-4 py-3 font-medium">نوشته</th>
                        <th class="px-4 py-3 font-medium">امتیاز</th>
                        <th class="px-4 py-3 font-medium">وضعیت</th>
                        <th class="px-4 py-3 font-medium">ترتیب</th>
                        <th class="px-4 py-3 font-medium">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($testimonials)): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">نظرات مشتری یافت نشد.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($testimonials as $t): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <?php if (!empty($t['avatar'])): ?>
                                    <img src="<?= uploadUrl($t['avatar']) ?>" class="w-10 h-10 rounded-full object-cover border border-gray-200" alt="آواتار">
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs font-bold">چ</div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900"><?= e($t['client_name']) ?></td>
                            <td class="px-4 py-3">
                                <div class="text-gray-700 italic max-w-xs truncate" title="<?= e($t['quote']) ?>"><?= e($t['quote']) ?></div>
                                <?php if (!empty($t['client_role'])): ?>
                                    <div class="text-xs text-gray-500 mt-1"><?= e($t['client_role']) . (!empty($t['client_company']) ? ' | ' . e($t['client_company']) : '') ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="text-yellow-400 text-xs"><?= $i <= (int) $t['rating'] ? '★' : '☆' ?></span>
                                    <?php endfor; ?>
                                    <span class="ml-1 text-xs text-gray-500"><?= (int) $t['rating'] ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <form action="<?= url('admin/testimonials/' . $t['id'] . '/toggle') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                    <?= csrfField() ?>
                                    <button type="submit" class="px-3 py-1 rounded text-xs font-medium <?= (int) $t['is_published'] === 1 ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                                        <?= (int) $t['is_published'] === 1 ? 'منتشر شده' : 'پ‌نشر' ?>
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-gray-600 font-medium"><?= e($t['sort_order']) ?></td>
                            <td class="px-4 py-3">
                                <a href="<?= url('admin/testimonials/' . $t['id'] . '/edit') ?>" class="text-indigo-600 hover:text-indigo-800 hover:underline text-sm">ویرایش</a>
                                <form action="<?= url('admin/testimonials/' . $t['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن به حذف این نظر هستید؟');">
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
        </form>