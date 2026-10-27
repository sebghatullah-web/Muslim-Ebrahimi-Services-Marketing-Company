<?php
// $message available
?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">مشاهده پیام</h2>
            <p class="text-sm text-gray-500">جزئیات پیام دریافتی از فرم تماس</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= url('admin/messages') ?>" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg text-sm font-medium">بازگشت به لیست</a>
            <form action="<?= url('admin/messages/' . $message['id'] . '/toggle-read') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن هستید؟');">
                <?= csrfField() ?>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
                    <?= (int) $message['is_read'] === 1 ? 'نشانه‌گذاری خوانده‌نشده' : 'نشانه‌گذاری خوانده‌شده' ?>
                </button>
            </form>
            <form action="<?= url('admin/messages/' . $message['id'] . '/delete') ?>" method="POST" class="inline" onsubmit="return confirm('آیا مطمئن به حذف این پیام هستید؟');">
                <?= csrfField() ?>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">حذف پیام</button>
            </form>
        </div>
    </div>

    <div class="px-6 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase mb-1">نام فرستنده</p>
                <p class="text-gray-900 font-medium"><?= e($message['name']) ?></p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase mb-1">ایمیل</p>
                <p class="text-gray-900 font-medium"><a href="mailto:<?= e($message['email']) ?>" class="text-indigo-600 hover:underline"><?= e($message['email']) ?></a></p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase mb-1">تلفن</p>
                <p class="text-gray-900 font-medium"><?= e($message['phone'] ?? '-') ?></p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase mb-1">وضعیت / تاریخ</p>
                <p class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold <?= (int) $message['is_read'] === 0 ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-green-100 text-green-700 border border-green-200' ?>">
                        <span class="w-1.5 h-1.5 rounded-full <?= (int) $message['is_read'] === 0 ? 'bg-red-700' : 'bg-green-700' ?>"></span>
                        <?= (int) $message['is_read'] === 0 ? 'خوانده‌نشده' : 'خوانده‌شده' ?>
                    </span>
                </p>
                <p class="text-sm text-gray-500 mt-1"><?= e($message['created_at']) ?></p>
            </div>
        </div>

        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase mb-1">موضوع</p>
            <p class="text-gray-900 font-medium text-lg mb-4"><?= e($message['subject']) ?></p>

            <p class="text-xs font-semibold text-gray-400 uppercase mb-1">متن پیام</p>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-700 leading-relaxed whitespace-pre-wrap font-mono text-sm">
                <?= e($message['message']) ?>
            </div>
        </div>
    </div>
</div>