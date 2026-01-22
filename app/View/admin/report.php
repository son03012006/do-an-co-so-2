<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>

<?php include __DIR__ . '/../layouts/layoutsadmin/sidebaradmin.php'; ?>

<div class="min-h-screen bg-slate-50 relative font-sans overflow-hidden">

    <div class="absolute inset-0 w-full h-full pointer-events-none md:ml-64">
        <div class="absolute top-0 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-10 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <div class="flex items-center justify-center min-h-screen md:ml-64 px-4 relative z-10">
        
        <div class="w-full max-w-lg bg-white rounded-3xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl border border-gray-100">
            
            <div class="bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 p-8 text-white relative">
                <div class="absolute top-6 right-6 bg-white/20 p-3 rounded-full backdrop-blur-md shadow-inner">
                    <i class="fa fa-chart-pie text-2xl text-white"></i>
                </div>

                <h2 class="text-3xl font-bold mb-2 tracking-tight">Báo cáo hệ thống</h2>
                <p class="text-indigo-100 opacity-90 text-sm font-medium">
                    Xuất dữ liệu mượn trả và thống kê chi tiết
                </p>
            </div>

            <div class="p-8 bg-white">
                <form method="post" class="space-y-6">
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2 pl-1">
                            Chọn loại báo cáo <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa fa-filter text-gray-400 group-focus-within:text-purple-500 transition-colors"></i>
                            </div>

                            <select name="type" required 
                                    class="w-full bg-gray-50 text-gray-800 border border-gray-200 rounded-xl py-3.5 pl-11 pr-4 
                                           focus:outline-none focus:bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent 
                                           transition-all duration-200 appearance-none cursor-pointer font-medium shadow-sm hover:bg-gray-100">
                                <option value="" class="text-gray-400">-- Vui lòng chọn --</option>
                                <option value="userwise">📊 Thống kê theo người dùng</option>
                                <option value="overdue">⏰ Sách quá hạn chưa trả</option>
                            </select>

                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <i class="fa fa-chevron-down text-xs text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="view" 
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 
                                   text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-indigo-500/40 
                                   transform hover:-translate-y-1 transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Xem báo cáo ngay</span>
                        <i class="fa fa-arrow-right"></i>
                    </button>

                </form>
            </div>
            
        </div>
    </div>
</div>