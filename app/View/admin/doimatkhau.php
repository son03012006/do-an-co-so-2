<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php include __DIR__ . '/../layouts/layoutsadmin/sidebaradmin.php'; ?>

<div class="min-h-screen bg-slate-50 font-sans md:ml-64 transition-all duration-300 flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
        
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-center">
            <div class="mx-auto bg-white/20 w-16 h-16 rounded-full flex items-center justify-center backdrop-blur-sm mb-4">
                <i class="fa fa-shield-alt text-2xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">Đổi Mật Khẩu</h2>
            <p class="text-indigo-100 text-sm mt-2">Bảo mật tài khoản quản trị viên</p>
        </div>

        <div class="p-8">
            
            <?php if (!empty($error)): ?>
                <div class="mb-5 bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded text-sm flex items-center shadow-sm">
                    <i class="fa fa-exclamation-circle mr-2 text-lg"></i>
                    <span><?= $error ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($msg)): ?>
                <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded text-sm flex items-center shadow-sm">
                    <i class="fa fa-check-circle mr-2 text-lg"></i>
                    <span><?= $msg ?></span>
                </div>
            <?php endif; ?>

            <form method="post" name="chngpwd" onsubmit="return valid();" class="space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu hiện tại</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" required 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-700 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200 outline-none"
                            placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu mới</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa fa-key text-gray-400"></i>
                        </div>
                        <input type="password" name="newpassword" required 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-700 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200 outline-none"
                            placeholder="Nhập mật khẩu mới">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Xác nhận mật khẩu</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa fa-check-circle text-gray-400"></i>
                        </div>
                        <input type="password" name="confirmpassword" required 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-700 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200 outline-none"
                            placeholder="Nhập lại mật khẩu mới">
                    </div>
                </div>

                <button type="submit" name="change" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:shadow-indigo-500/30 transition duration-300 transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    <i class="fa fa-refresh spin-hover"></i> Cập nhật mật khẩu
                </button>

            </form>
        </div>
    </div>
</div>

<script>
    function valid() {
        if (document.chngpwd.newpassword.value !== document.chngpwd.confirmpassword.value) {
            // Sử dụng alert đẹp hơn hoặc giữ nguyên alert mặc định
            alert("Mật khẩu mới và xác nhận mật khẩu không trùng khớp!");
            document.chngpwd.confirmpassword.focus();
            return false;
        }
        return true;
    }
</script>

<style>
    /* Animation nhẹ nhàng khi tải trang */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translate3d(0, 20px, 0); }
        to { opacity: 1; transform: translate3d(0, 0, 0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }
    /* Hiệu ứng xoay icon khi hover nút */
    button:hover .spin-hover {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        100% { transform: rotate(360deg); }
    }
</style>