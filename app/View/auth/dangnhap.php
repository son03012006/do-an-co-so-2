<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThuVienSo - Đăng nhập Độc giả</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    </style>
</head>

<body class="h-screen w-full overflow-hidden flex items-start justify-center bg-gray-900 relative">

    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=2000" alt="Library Background"
            class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/40 via-slate-900/80 to-black/90"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl px-4 pt-16">

        <div
            class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-white/10 relative">

            <div class="absolute top-6 right-8 z-20">
                <a href="<?= BASE_URL ?>index.php?c=adminauth&a=index"
                    class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-600 rounded-full text-xs font-bold transition-all duration-300 shadow-sm group">
                    <i class="fas fa-user-shield group-hover:animate-bounce"></i>
                    Quản trị viên
                </a>
            </div>

            <div
                class="md:w-[40%] bg-indigo-950 p-10 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-blue-600 rounded-full opacity-20 blur-[80px]"></div>

                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-10">
                        <div
                            class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                            <i class="fas fa-book-open text-2xl text-white"></i>
                        </div>
                        <span class="text-2xl font-black tracking-tighter uppercase">THUVIEN<span
                                class="text-indigo-400">SO</span></span>
                    </div>

                    <h2 class="text-3xl font-extrabold leading-tight mb-4">Chào mừng<br><span
                            class="text-indigo-400 italic">Độc giả</span>.</h2>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-[200px]">
                        Khám phá kho tri thức khổng lồ và quản lý tài liệu cá nhân.
                    </p>
                </div>

                <div class="relative z-10">
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-4 border border-white/10">
                        <div class="flex items-center space-x-2">
                            <img class="w-8 h-8 rounded-full border border-indigo-400"
                                src="https://ui-avatars.com/api/?name=User&background=4f46e5&color=fff" alt="">
                            <div>
                                <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest">Hệ thống sẵn
                                    sàng</p>
                                <p class="text-[11px] text-slate-100">Cổng sinh viên trực tuyến</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:w-[60%] p-10 md:p-14 bg-white flex flex-col justify-center">
                <div class="mb-8">
                    <h1 class="text-3xl font-black text-slate-900 mb-2">Đăng nhập</h1>
                    <p class="text-slate-500 font-medium text-sm">Vui lòng nhập Mã sinh viên / Email.</p>
                </div>

                <?php if (!empty($error)): ?>
                <div
                    class="flex items-center bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 mb-6 rounded-r-xl">
                    <i class="fas fa-info-circle mr-3"></i>
                    <p class="text-xs font-bold"><?= htmlspecialchars($error) ?></p>
                </div>
                <?php endif; ?>

                <form method="post" class="space-y-5">
                    <div class="space-y-1">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tài
                            khoản</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                <i class="fas fa-id-card"></i>
                            </span>
                            <input type="text" name="emailid" required
                                class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-slate-900 font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:bg-white transition-all shadow-inner"
                                placeholder="Mã SV hoặc Email...">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <div class="flex justify-between items-center px-1">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">Mật
                                khẩu</label>
                            <a href="forgot-password.php"
                                class="text-xs font-bold text-indigo-600 hover:underline transition-all">Quên?</a>
                        </div>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password" required
                                class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-slate-900 font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:bg-white transition-all shadow-inner"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" name="login"
                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-base shadow-xl shadow-indigo-100 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                        Tiếp tục <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <div class="mt-8 text-center border-t border-slate-50 pt-6">
                    <p class="text-sm text-slate-500 mb-2">Chưa có tài khoản?</p>
                    <a href="index.php?c=auth&a=register"
                        class="text-sm font-extrabold text-indigo-600 hover:text-indigo-800 transition-all">
                        Đăng ký ngay tại đây
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center text-slate-500 text-[10px] font-bold uppercase tracking-[0.3em] mt-8">
            &copy; <?= date('Y') ?> LibHub Library Management System
        </div>
    </div>

</body>

</html>