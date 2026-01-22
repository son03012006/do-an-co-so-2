<script src="https://cdn.tailwindcss.com"></script>
<?php include __DIR__ . '/../layouts/layoutsadmin/sidebaradmin.php'; ?>

<div class="min-h-screen bg-slate-50 flex items-center justify-center md:ml-64">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Nhập Mã Sinh Viên</h2>
        
        <form action="index.php?c=report&a=userwise" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mã Sinh Viên (Student ID)</label>
                <input type="text" name="sid" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                       placeholder="Ví dụ: SV001">
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">
                Xem & In Báo Cáo
            </button>
            
            <a href="index.php?c=report" class="block text-center text-gray-500 hover:text-gray-700 text-sm">
                Hủy bỏ
            </a>
        </form>
    </div>
</div>