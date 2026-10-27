<?php use App\Core\Session; ?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ورود مدیر' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/3.4.0/tailwind.min.css">
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">ورود مدیر</h2>
        
        <?php if (Session::has('_errors')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4" role="alert">
                <?= Session::get('_errors') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/admin/login" class="space-y-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    ایمیل
                </label>
                <input id="email" name="email" type="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    رمز عبور
                </label>
                <input id="password" name="password" type="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    ورود
                </button>
            </div>
        </form>
    </div>
</body>
</html>