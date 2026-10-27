<?php
// $socialLinks, $errors available
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">شبکه‌های اجتماعی</h2>
            <p class="text-sm text-gray-500">لینک شبکه‌های اجتماعی نمایش داده‌شده در فوتر سایت</p>
        </div>
        <div class="flex gap-2">
            <button type="button" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium" onclick="document.getElementById('addSocial').classList.toggle('hidden')">
                افزودن شبکه جدید
            </button>
        </div>
    </div>

    <div class="px-6 py-6 space-y-6">
        <!-- Inline add -->
        <div id="addSocial" class="hidden bg-indigo-50 p-5 rounded-xl border border-indigo-100 space-y-3">
            <form action="<?= url('admin/settings/social') ?>" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                <?= csrfField() ?>
                <div class="md:col-span-2">
                    <input type="text" name="platform" placeholder="مثل: اینستاگرام" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                </div>
                <div>
                    <input type="text" name="url" placeholder="لینک پروفایل" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">افزودن</button>
                </div>
            </form>
        </div>

        <!-- List -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 font-medium">شبکه</th>
                        <th class="px-4 py-3 font-medium">لینک</th>
                        <th class="px-4 py-3 font-medium">آیکون</th>
                        <th class="px-4 py-3 font-medium">ترتیب</th>
                        <th class="px-4 py-3 font-medium">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($socialLinks)): ?>
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">شبکهٔ اجتماعی ثبت نشده است.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($socialLinks as $s): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                <form action="<?= url('admin/settings/social/' . $s['id']) ?>" method="POST" class="flex items-center gap-2">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="text" name="platform" value="<?= e($s['platform']) ?>" class="w-24 px-2 py-1 border rounded text-sm bg-gray-50 focus:border-indigo-500 focus:ring-1 outline-none">
                                    <input type="text" name="url" value="<?= e($s['url']) ?>" class="w-32 px-2 py-1 border rounded text-sm bg-gray-50 focus:border-indigo-500 focus:ring-1 outline-none">
                                    <input type="text" name="icon" value="<?= e($s['icon'] ?? '') ?>" placeholder="آیکون" class="w-20 px-2 py-1 border rounded text-sm bg-gray-50 focus:border-indigo-500 focus:ring-1 outline-none">
                                    <input type="number" name="sort_order" value="<?= e($s['sort_order'] ?? 0) ?>" min="0" class="w-16 px-2 py-1 border rounded text-sm bg-gray-50 focus:border-indigo-500 focus:ring-1 outline-none text-center">
                                    <button type="submit" class="px-2 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">ذخیره</button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="<?= e($s['url']) ?>"><?= e($s['url']) ?></td>
                            <td class="px-4 py-3 text-gray-500 text-xs font-mono"><?= e($s['icon'] ?? '-') ?></td>
                            <td class="px-4 py-3 text-gray-600 font-medium"><?= e($s['sort_order']) ?></td>
                            <td class="px-4 py-3">
                                <form action="<?= url('admin/settings/social/' . $s['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن به حذف این شبکه هستید؟');">
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