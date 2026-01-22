<?php
// FILE: dangki.php
// GIỮ NGUYÊN GIAO DIỆN - CHỈ CHỈNH VỊ TRÍ CHO CÂN ĐỐI
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibHub - Đăng ký thành viên</title>
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

<body class="h-screen w-full overflow-hidden flex items-center justify-center bg-gray-900 relative">

    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=2000" alt="Library Background"
            class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/40 via-slate-900/80 to-black/90"></div>
    </div>

    <div class="relative z-10 w-full max-w-5xl px-4">
        <div
            class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-white/10 relative">

            <div
                class="md:w-[40%] bg-indigo-950 p-10 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-blue-600 rounded-full opacity-20 blur-[80px]"></div>
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-10">
                        <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-plus text-2xl text-white"></i>
                        </div>
                        <span class="text-2xl font-black tracking-tighter uppercase">THUVIEN<span
                                class="text-indigo-400">SO</span></span>
                    </div>
                    <h2 class="text-3xl font-extrabold leading-tight mb-4">Gia nhập<br><span
                            class="text-indigo-400 italic">Cộng đồng</span>.</h2>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-[200px] mb-8">Tạo tài khoản ngay để nhận được
                        những quyền lợi ưu tiên dành riêng cho độc giả.</p>
                </div>
                <div class="relative z-10 pt-8">
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-4 border border-white/10 text-center">
                        <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest">Đã có tài khoản?</p>
                        <a href="index.php?c=auth&a=index"
                            class="text-xs text-white font-bold hover:text-indigo-400 transition-colors underline underline-offset-4">Đăng
                            nhập ngay</a>
                    </div>
                </div>
            </div>
            <div class="md:w-[60%] p-10 bg-white flex flex-col justify-center">
                <div class="mb-6">
                    <h1 class="text-3xl font-black text-slate-900 mb-1">Đăng ký</h1>
                    <p class="text-slate-500 font-medium text-sm">Điền thông tin bên dưới để bắt đầu.</p>
                </div>

                <?php if (!empty($error)): ?>
                <div
                    class="flex items-center bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-3 mb-4 rounded-r-xl">
                    <i class="fas fa-exclamation-circle mr-3 text-xs"></i>
                    <p class="text-[11px] font-bold"><?php echo $error; ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                <div
                    class="flex items-center bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-3 mb-4 rounded-r-xl">
                    <i class="fas fa-check-circle mr-3 text-xs"></i>
                    <p class="text-[11px] font-bold"><?php echo $success; ?></p>
                </div>
                <?php endif; ?>

                <form method="post" action="index.php?c=auth&a=register" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2 space-y-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Họ và
                            tên</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600">
                                <i class="fas fa-user-circle"></i>
                            </span>
                            <input type="text" name="fullname" required
                                class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:bg-white transition-all shadow-inner"
                                placeholder="Nguyễn Văn A">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Số
                            điện thoại</label>
                        <input type="text" name="mobileno" required
                            class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:bg-white transition-all shadow-inner"
                            placeholder="09xx...">
                    </div>
                    <div class="space-y-1">
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" name="email" required
                            class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:bg-white transition-all shadow-inner"
                            placeholder="user@gmail.com">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mật
                            khẩu</label>
                        <input type="password" name="password" required
                            class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:bg-white transition-all shadow-inner"
                            placeholder="••••••••">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Xác
                            thực mật khẩu</label>
                        <input type="password" name="confirmpassword" required
                            class="block w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-600/20 focus:bg-white transition-all shadow-inner"
                            placeholder="••••••••">
                    </div>
                    <div class="md:col-span-2 pt-2">
                        <button type="submit" name="signup"
                            class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-sm shadow-xl shadow-indigo-100 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                            Xác nhận đăng ký <i class="fas fa-check-double text-[10px]"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center text-slate-500 text-[10px] font-bold uppercase tracking-[0.3em] mt-8">
            &copy; <?= date('Y') ?> LIBHUB LIBRARY SYSTEM
        </div>
    </div>
</body>

</html>