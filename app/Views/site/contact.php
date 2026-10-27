<section class="py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">تماس با ما</h1>
            <p class="text-gray-600 mb-8">برای درخواست مشاوره یا سفارش با ما در تماس باشید.</p>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <form action="<?= url('/contact') ?>" method="POST" class="space-y-6">
                    <?= \App\Core\Csrf::field() ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نام و نام‌خانوادگی</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">پست الکترونیک</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">موضوع</label>
                        <input type="text" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">پیام شما</label>
                        <textarea name="message" rows="5" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                        ارسال پیام
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>