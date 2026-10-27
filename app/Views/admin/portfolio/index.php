<?php
// $result (data,total,page,perPage,lastPage), $categories, $search, $categoryId
$res = $result ?? [];
$data = $res['data'] ?? [];
$page = max(1, (int) ($res['page'] ?? 1));
$perPage = (int) ($res['perPage'] ?? 10);
$total = (int) ($res['total'] ?? 0);
$lastPage = max(1, (int) ($res['lastPage'] ?? 1));
$catLabel = fn($id) => (($categories ?? [])[$id] ?? null)['title'] ?? '-';
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">لیست نمونه‌کارها</h2>
            <p class="text-sm text-gray-500">مدیریت نمونه‌کارها وポートفولیو نمایش داده‌شده در سایت</p>
        </div>
        <a href="<?= url('admin/portfolio/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            افزودن نمونه‌کار جدید
        </a>
    </div>

    <div class="px-6 py-4">
        <form method="GET" action="<?= url('admin/portfolio') ?>" class="flex gap-2 flex-wrap mb-4">
            <input type="text" name="q" value="<?= e($search) ?>" placeholder="جستجوی عنوان یا مشتری..." class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm bg-white">
                <option value="">همه دسته‌بندی‌ها</option>
                <?php foreach ($categories ?? [] as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $categoryId == $cat['id'] ? 'selected' : '' ?>><?= e($cat['title']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition">جستجو</button>
            <?php if ($search !== '' || $categoryId): ?>
                <a href="<?= url('admin/portfolio') ?>" class="px-3 py-2 text-gray-500 hover:text-gray-700">بازنشانی</a>
            <?php endif; ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 font-medium">تصویر</th>
                        <th class="px-4 py-3 font-medium">عنوان</th>
                        <th class="px-4 py-3 font-medium">مشتری</th>
                        <th class="px-4 py-3 font-medium">دسته‌بندی</th>
                        <th class="px-4 py-3 font-medium">نامک (slug)</th>
                        <th class="px-4 py-3 font-medium">ترتیب</th>
                        <th class="px-4 py-3 font-medium">وضعیت</th>
                        <th class="px-4 py-3 font-medium">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($data)): ?>
                        <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">نمونه‌کاری یافت نشد.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($data as $p): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <?php if (!empty($p['image'])): ?>
                                    <img src="<?= uploadUrl($p['image']) ?>" class="w-10 h-10 rounded object-cover border border-gray-200" alt="تصویر">
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center text-gray-400 text-xs">تصویر</div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 max-w-xs truncate" title="<?= e($p['title']) ?>"><?= e($p['title']) ?></td>
                            <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="<?= e($p['client_name']) ?>"><?= e($p['client_name'] ?? '-') ?></td>
                            <td class="px-4 py-3 text-gray-600 text-xs max-w-xs truncate"><?= e($catLabel($p['category_id'] ?? null)) ?></td>
                            <td class="px-4 py-3 text-gray-500 text-xs font-mono break-all"><?= e($p['slug']) ?></td>
                            <td class="px-4 py-3 text-gray-600 font-medium"><?= e($p['sort_order']) ?></td>
                            <td class="px-4 py-3">
                                <form action="<?= url('admin/portfolio/' . $p['id'] . '/toggle') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                    <?= csrfField() ?>
                                    <button type="submit" class="px-3 py-1 rounded text-xs font-medium <?= (int) $p['is_published'] === 1 ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                                        <?= (int) $p['is_published'] === 1 ? 'منتشر شده' : 'پ‌نشر' ?>
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3">
                                <a href="<?= url('admin/portfolio/' . $p['id'] . '/edit') ?>" class="text-indigo-600 hover:text-indigo-800 hover:underline text-sm">ویرایش</a>
                                <form action="<?= url('admin/portfolio/' . $p['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن به حذف این نمونه‌کار هستید؟');">
                                    <?= csrfField() ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800 hover:underline text-sm ml-2">حذف</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($lastPage > 1): ?>
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-600">نمایش <b><?= (($page - 1) * $perPage) + 1 ?></b> تا <b><?= min($page * $perPage, $total) ?></b> از <b><?= $total ?></b> مورد</div>
                <div class="flex gap-1">
                    <?php for ($pg = 1; $pg <= $lastPage; $pg++): ?>
                        <a href="<?= url('admin/portfolio?page=' . $pg . ($search !== '' ? '&q=' . urlencode($search) : '') . ($categoryId ? '&category=' . $categoryId : '')) ?>" class="px-3 py-1.5 rounded text-sm <?= $pg === $page ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-100' ?>"><?= $pg ?></a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
        </form>