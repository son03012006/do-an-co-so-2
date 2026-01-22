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
                animation: {
                    'blob': 'blob 7s infinite',
                },
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
                    emerald: { 500: '#10b981', 100: '#d1fae5' },
                    rose: { 500: '#f43f5e', 100: '#ffe4e6' }
                }
            }
        }
    }
</script>

<div class="md:ml-64 bg-slate-50 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 z-0 opacity-[0.4]" 
         style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 32px 32px;">
    </div>

    <div class="absolute top-0 -left-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute top-0 -right-4 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-32 left-20 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

    <div class="relative z-10 w-full max-w-lg bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-indigo-100/50 border border-white/50 overflow-hidden transform transition-all hover:scale-[1.01] duration-500">
        
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-8 flex items-center justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-6 -mr-6 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
            
            <div class="relative z-10">
                <h2 class="text-2xl font-bold text-white tracking-wide">Thêm danh mục</h2>
                <p class="text-indigo-100 text-sm mt-1 font-medium">Tạo mới phân loại sách cho hệ thống.</p>
            </div>
            <div class="relative z-10 w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white backdrop-blur-md shadow-inner border border-white/10">
                <i class="fa-solid fa-layer-group text-xl"></i>
            </div>
        </div>

        <div class="p-8">
            <?php if (!empty($msg)): ?>
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-pulse">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <span class="text-sm font-medium"><?= $msg ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-pulse">
                    <i class="fa-solid fa-circle-exclamation text-xl"></i>
                    <span class="text-sm font-medium"><?= $error ?></span>
                </div>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                
                <div class="group">
                    <label class="block text-sm font-bold text-slate-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                        Tên danh mục <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <i class="fa-solid fa-tag"></i>
                        </span>
                        <input type="text" name="category" required 
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all text-slate-800 font-medium placeholder-slate-400 focus:bg-white"
                            placeholder="Ví dụ: Khoa học, Văn học...">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Trạng thái hiển thị</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="status" value="1" checked class="peer sr-only">
                            <div class="p-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:ring-1 peer-checked:ring-emerald-500 transition-all flex items-center justify-center gap-2 shadow-sm">
                                <i class="fa-solid fa-circle-check text-emerald-500 opacity-0 peer-checked:opacity-100 absolute left-4 transition-all scale-0 peer-checked:scale-100"></i>
                                <span class="text-sm font-bold text-slate-500 peer-checked:text-emerald-700 transition-colors ml-2">Hoạt động</span>
                            </div>
                        </label>

                        <label class="cursor-pointer relative">
                            <input type="radio" name="status" value="0" class="peer sr-only">
                            <div class="p-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 peer-checked:border-slate-500 peer-checked:bg-slate-100 peer-checked:ring-1 peer-checked:ring-slate-500 transition-all flex items-center justify-center gap-2 shadow-sm">
                                <i class="fa-solid fa-eye-slash text-slate-500 opacity-0 peer-checked:opacity-100 absolute left-4 transition-all scale-0 peer-checked:scale-100"></i>
                                <span class="text-sm font-bold text-slate-500 peer-checked:text-slate-800 transition-colors ml-2">Tạm ẩn</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" name="create" 
                        class="w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-base">
                        <i class="fa-solid fa-plus-circle"></i> Tạo danh mục ngay
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>