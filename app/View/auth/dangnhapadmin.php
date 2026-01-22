<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập quản trị</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="h-screen w-full overflow-hidden flex items-center justify-center bg-gray-900 relative">

    <div class="absolute inset-0 z-0">
        <img src="<?= BASE_URL ?>public/assets/img/dangnhap.jpg" alt="Background"
            class="w-full h-full object-cover opacity-50">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/40 to-black/60"></div>
    </div>

    <div class="relative z-10 w-full max-w-md px-4">
        <div
            class="bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all hover:scale-[1.01] duration-300">

            <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-600 w-full"></div>

            <div class="p-8">
                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <i class="fas fa-user-shield text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">Quản Trị Hệ Thống</h2>
                    <p class="text-gray-500 text-sm mt-1">Vui lòng đăng nhập để tiếp tục</p>
                </div>

                <?php if (!empty($error)): ?>
                <div class="flex items-center bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm"
                    role="alert">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
                <?php endif; ?>

                <form method="post" action="">

                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">Tên đăng nhập</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="username"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                                placeholder="Nhập tên tài khoản..." required>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">Mật khẩu</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="password"
                                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                                placeholder="Nhập mật khẩu..." required>
                        </div>
                    </div>

                    <button type="submit" name="login"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-3 px-4 rounded-lg shadow-lg transform active:scale-95 transition duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập ngay
                    </button>

                </form>

                <div class="mt-6 text-center">
                    <a href="<?= BASE_URL ?>index.php"
                        class="text-sm text-gray-500 hover:text-blue-600 transition duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i> Quay về trang chủ
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center text-gray-400 text-xs mt-4">
            &copy; <?= date('Y') ?> Library Management System
        </div>
    </div>

</body>

</html>