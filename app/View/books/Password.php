<?php
// ===== INCLUDE SIDEBAR =====
include __DIR__ . '/../layouts/sidebar.php';
?>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                indigo: {
                    50: '#eef2ff',
                    100: '#e0e7ff',
                    600: '#4f46e5',
                    700: '#4338ca',
                    900: '#312e81'
                },
                slate: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    800: '#1e293b'
                },
                emerald: {
                    50: '#ecfdf5',
                    600: '#059669',
                    700: '#047857'
                },
                rose: {
                    50: '#fff1f2',
                    600: '#e11d48',
                    700: '#be123c'
                },
                amber: {
                    50: '#fffbeb',
                    600: '#d97706',
                    700: '#b45309'
                }
            }
        }
    }
}
</script>

<div class="max-w-7xl mx-auto md:ml-80 px-4 sm:px-8 py-10 min-h-screen bg-slate-50">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Đổi mật khẩu</h1>
        <p class="text-slate-500 mt-1">Cập nhật mật khẩu thường xuyên để bảo vệ tài khoản của bạn.</p>
    </div>

    <?php if (!empty($error)): ?>
    <div
        class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-bounce-short">
        <i class="fa-solid fa-circle-xmark text-xl"></i>
        <div>
            <span class="font-bold">Lỗi:</span> <?= htmlspecialchars($error) ?>
        </div>
    </div>
    <?php elseif (!empty($msg)): ?>
    <div
        class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-bounce-short">
        <i class="fa-solid fa-circle-check text-xl"></i>
        <div>
            <span class="font-bold">Thành công:</span> <?= htmlspecialchars($msg) ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
                <div class="mb-6 pb-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Thông tin mật khẩu</h3>
                    <p class="text-slate-500 text-sm">Vui lòng nhập chính xác mật khẩu hiện tại để tiếp tục.</p>
                </div>

                <form method="post" onsubmit="return valid();" class="space-y-6" autocomplete="off">

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Mật khẩu hiện tại</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" name="password" required
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700 font-medium placeholder-slate-400"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="border-t border-slate-100 my-4"></div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Mật khẩu mới</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                <i class="fa-solid fa-key"></i>
                            </span>
                            <input type="password" name="newpassword" required
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700 font-medium placeholder-slate-400"
                                placeholder="Nhập mật khẩu mới">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Xác nhận mật khẩu mới</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                <i class="fa-solid fa-circle-check"></i>
                            </span>
                            <input type="password" name="confirmpassword" required
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700 font-medium placeholder-slate-400"
                                placeholder="Nhập lại mật khẩu mới">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" name="change"
                            class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-indigo-50 rounded-3xl p-6 border border-indigo-100">
                <h4 class="text-indigo-900 font-bold mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved"></i> Lưu ý bảo mật
                </h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-check text-indigo-500 mt-1 text-sm"></i>
                        <span class="text-sm text-indigo-800">Mật khẩu nên có ít nhất 8 ký tự.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-check text-indigo-500 mt-1 text-sm"></i>
                        <span class="text-sm text-indigo-800">Nên kết hợp chữ hoa, chữ thường và số.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-check text-indigo-500 mt-1 text-sm"></i>
                        <span class="text-sm text-indigo-800">Không dùng mật khẩu dễ đoán như ngày sinh, số điện
                            thoại.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-check text-indigo-500 mt-1 text-sm"></i>
                        <span class="text-sm text-indigo-800">Đổi mật khẩu định kỳ mỗi 3-6 tháng.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>

<script>
function valid() {
    const newpass = document.querySelector('[name="newpassword"]').value;
    const confirm = document.querySelector('[name="confirmpassword"]').value;

    if (newpass !== confirm) {
        // Có thể thay bằng alert đẹp hơn hoặc hiển thị lỗi vào div, nhưng alert cũng ok
        alert("Mật khẩu mới và phần xác nhận không trùng khớp! Vui lòng kiểm tra lại.");
        return false;
    }

    // Kiểm tra độ dài (tuỳ chọn)
    if (newpass.length < 6) {
        alert("Mật khẩu mới phải có ít nhất 6 ký tự!");
        return false;
    }

    return true;
}
</script>