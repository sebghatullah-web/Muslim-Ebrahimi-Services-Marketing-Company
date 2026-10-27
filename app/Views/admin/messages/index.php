<?php
// $result (data,total,page,perPage,lastPage), $search, $readFilter, $unreadCount
$res = $result ?? [];
$data = $res['data'] ?? [];
$page = max(1, (int) ($res['page'] ?? 1));
$perPage = (int) ($res['perPage'] ?? 10);
$total = (int) ($res['total'] ?? 0);
$lastPage = max(1, (int) ($res['lastPage'] ?? 1));
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">پیام‌های دریافتی</h2>
            <p class="text-sm text-gray-500">مدیریت پیام‌های ارسال‌شده از طریق فرم تماس سایت</p>
        </div>
        <div class="inline-flex gap-2">
            <a href="<?= url('admin/messages') ?>" class="px-3 py-2 text-sm font-medium rounded <?= $readFilter === null ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">همه</a>
            <a href="<?= url('admin/messages?read=unread' . ($search !== '' ? '&q=' . urlencode($search) : '')) ?>" class="px-3 py-2 text-sm font-medium rounded <?= $readFilter === 'unread' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">خوانده‌نشده <span class="badge ml-1"><?= $unreadCount ?></span></a>
            <a href="<?= url('admin/messages?read=read' . ($search !== '' ? '&q=' . urlencode($search) : '')) ?>" class="px-3 py-2 text-sm font-medium rounded <?= $readFilter === 'read' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">خوانده‌شده</a>
        </div>
    </div>

    <div class="px-6 py-4">
        <!-- Search + bulk -->
        <form method="GET" action="<?= url('admin/messages') ?>" class="flex gap-2 mb-4 flex-wrap">
            <input type="text" name="q" value="<?= e($search) ?>" placeholder="جستجوی نام، ایمیل، موضوع..." class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm">
            <label class="ml-2 flex items-center gap-1 cursor-pointer">
                <input type="checkbox" name="read" value="unread" <?= $readFilter === 'unread' ? 'checked' : '' ?> class="w-3 h-3 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                فقط خوانده‌نشده
            </label>
            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium text-sm transition">جستجو</button>
            <?php if ($search !== '' || $readFilter): ?>
                <a href="<?= url('admin/messages') ?>" class="px-3 py-2 text-gray-500 hover:text-gray-700">بازنشانی</a>
            <?php endif; ?>
        <!-- Bulk actions -->
        <?php if (!empty($data)): ?>
            <form action="<?= url('admin/messages/bulk') ?>" method="POST" class="mb-4 flex items-center gap-3">
                <?= csrfField() ?>
                <select name="action" class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="read">علائم خوانده‌شده بر موارد انتخاب‌شده</option>
                    <option value="unread">علائم خوانده‌نشده بر موارد انتخاب‌شده</option>
                    <option value="delete">حذف موارد انتخاب‌شده</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-sm font-medium">اجرا روی انتخاب‌شده‌ها</button>
                <span class="text-xs text-gray-500 ml-3">تیک موارد را انتخاب کنید</span>
            </form>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 font-medium w-5"><input type="checkbox" id="selectAll" class="w-3 h-3 text-indigo-600 rounded border-gray-300"></th>
                        <th class="px-4 py-3 font-medium">فرستنده</th>
                        <th class="px-4 py-3 font-medium">موضوع</th>
                        <th class="px-4 py-3 font-medium">پیام</th>
                        <th class="px-4 py-3 font-medium">تاریخ</th>
                        <th class="px-4 py-3 font-medium">وضعیت</th>
                        <th class="px-4 py-3 font-medium">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($data)): ?>
                        <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">پیام یافت نشد.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($data as $m): ?>
                        <tr class="hover:bg-gray-50 transition <?= (int) $m['is_read'] === 0 ? 'bg-orange-50/30' : '' ?>">
                            <td class="px-4 py-3"><input type="checkbox" name="ids[]" value="<?= $m['id'] ?>" class="w-3 h-3 text-indigo-600 rounded border-gray-300 bulk-checkbox"></td>
                            <td class="px-4 py-3 font-medium text-gray-900"><?= e($m['name']) ?></td>
                            <td class="px-4 py-3 text-gray-700"><?= e($m['email']) ?> <span class="text-xs text-gray-400 ml-1">·</span> <?= e($m['phone'] ?? '-') ?></td>
                            <td class="px-4 py-3 text-gray-700 max-w-xs truncate" title="<?= e($m['subject']) ?>"><?= e($m['subject']) ?></td>
                            <td class="px-4 py-3 text-gray-600"><?= e($m['created_at']) ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold <?= (int) $m['is_read'] === 0 ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-green-100 text-green-700 border border-green-200' ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?= (int) $m['is_read'] === 0 ? 'bg-red-700' : 'bg-green-700' ?>"></span>
                                    <?= (int) $m['is_read'] === 0 ? 'خوانده‌نشده' : 'خوانده‌شده' ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="<?= url('admin/messages/' . $m['id']) ?>" class="text-indigo-600 hover:text-indigo-800 hover:underline text-sm">مشاهده</a>
                                <form action="<?= url('admin/messages/' . $m['id'] . '/toggle-read') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                    <?= csrfField() ?>
                                    <button type="submit" class="px-2 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700 ml-2"><?= (int) $m['is_read'] === 0 ? 'نشانه‌گذاری خوانده‌شده' : 'نشانه‌گذاری خوانده‌نشده' ?></button>
                                </form>
                                <form action="<?= url('admin/messages/' . $m['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن به حذف این پیام هستید؟');">
                                    <?= csrfField() ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800 hover:underline text-sm ml-2">حذف</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <!-- Pagination -->
        <?php if ($lastPage > 1): ?>
            <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200">
                <div class="text-sm text-gray-600">نمایش <b><?= (($page - 1) * $perPage) + 1 ?></b> تا <b><?= min($page * $perPage, $total) ?></b> از <b><?= $total ?></b> مورد</div>
                <div class="flex gap-1">
                    <?php for ($p = 1; $p <= $lastPage; $p++): ?>
                        <a href="<?= url('admin/messages?page=' . $p . ($search !== '' ? '&q=' . urlencode($search) : '') . ($readFilter ? '&read=' . $readFilter : '')) ?>" class="px-3 py-1.5 rounded text-sm <?= $p === $page ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-100' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.getElementById('selectAll')?.addEventListener('change', function () {
        document.querySelectorAll('.bulk-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
        </div>
        </form>