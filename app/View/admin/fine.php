<?php 
// ===== INCLUDE SIDEBAR ADMIN =====
include __DIR__ . '/../layouts/layoutsadmin/sidebaradmin.php'; 
?>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                animation: { 'blob': 'blob 7s infinite' },
                keyframes: {
                    blob: {
                        '0%': { transform: 'translate(0px, 0px) scale(1)' },
                        '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                        '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                        '100%': { transform: 'translate(0px, 0px) scale(1)' },
                    }
                },
                colors: {
                    slate: { 50: '#f8fafc', 100: '#f1f5f9', 800: '#1e293b', 900: '#0f172a' },
                    emerald: { 500: '#10b981', 100: '#d1fae5', 700: '#047857' },
                    rose: { 500: '#f43f5e', 100: '#ffe4e6', 700: '#be123c' },
                    indigo: { 500: '#6366f1', 600: '#4f46e5', 50: '#eef2ff', 700: '#4338ca' },
                    purple: { 600: '#9333ea', 700: '#7e22ce' } // Thêm màu tím nếu cần chỉnh sâu
                }
            }
        }
    }
</script>

<div class="md:ml-64 bg-slate-50 min-h-screen flex items-center justify-center p-8 relative overflow-hidden">

    <div class="absolute inset-0 z-0 opacity-[0.4]" style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 32px 32px;"></div>
    <div class="absolute top-0 left-0 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-32 left-20 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

    <div class="relative z-10 w-full max-w-lg bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-indigo-100/50 border border-white/50 overflow-hidden transform transition-all hover:scale-[1.005] duration-500">
        
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-8 flex items-center justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-6 -mr-6 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
            
            <div class="relative z-10">
                <h2 class="text-2xl font-bold text-white tracking-wide">Cấu hình phạt</h2>
                <p class="text-indigo-100 text-sm mt-1 font-medium">Thiết lập số tiền phạt quá hạn mỗi ngày.</p>
            </div>
            <div class="relative z-10 w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white backdrop-blur-md shadow-inner border border-white/10">
                <i class="fa-solid fa-gavel text-xl"></i>
            </div>
        </div>

        <div class="p-8">
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-pulse">
                    <div class="w-8 h-8 bg-rose-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <span class="text-sm font-medium"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['msg'])): ?>
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-pulse">
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <span class="block font-bold text-sm">Thành công!</span>
                        <span class="text-sm"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Mức phạt (VNĐ / Ngày) <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </span>
                        
                        <input type="number" name="finetf" required min="0"
                            value="<?= isset($fineData->fine) ? $fineData->fine : '' ?>"
                            class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all text-slate-800 font-bold text-lg placeholder-slate-400 focus:bg-white"
                            placeholder="Ví dụ: 1000">
                            
                        <span class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400 font-medium text-sm">
                            VNĐ
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                        <i class="fa-solid fa-circle-info text-indigo-500"></i>
                        Số tiền này sẽ được tính nhân với số ngày trễ hạn.
                    </p>
                </div>

                <div class="pt-2">
                    <button type="submit" name="update" 
                        class="w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-base">
                        <i class="fa-solid fa-floppy-disk"></i> Cập nhật ngay
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>