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
                    sky: { 500: '#0ea5e9', 100: '#e0f2fe', 600: '#0284c7' }
                }
            }
        }
    }
</script>

<div class="md:ml-64 bg-slate-50 min-h-screen p-8 relative overflow-hidden">

    <div class="absolute inset-0 z-0 opacity-[0.4]" style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 32px 32px;"></div>
    <div class="absolute top-0 left-0 w-96 h-96 bg-sky-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-32 left-20 w-96 h-96 bg-emerald-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

    <div class="relative z-10 mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Duyệt yêu cầu mượn sách</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Danh sách sinh viên đang chờ phê duyệt mượn sách.</p>
        </div>
        
        <div class="flex items-center gap-4 bg-white/60 backdrop-blur-md px-6 py-3 rounded-2xl shadow-sm border border-white/50">
            <div class="w-10 h-10 rounded-full bg-sky-100 flex items-center justify-center text-sky-600">
                <i class="fa-solid fa-list-check text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase">Tổng yêu cầu</p>
                <p class="text-xl font-bold text-slate-800"><?= count($requests) ?></p>
            </div>
        </div>
    </div>

    <?php if (!empty($_SESSION['msg'])): ?>
        <div class="relative z-10 mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-4 shadow-sm animate-pulse">
            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shrink-0">
                <i class="fa-solid fa-check text-lg"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm">Thành công!</h4>
                <p class="text-sm"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></p>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="relative z-10 mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-rose-100 rounded-full flex items-center justify-center text-rose-600 shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm">Lỗi!</h4>
                <p class="text-sm"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="relative z-10 bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/50 overflow-hidden">
        
        <?php if (empty($requests)): ?>
            <div class="p-16 text-center">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <i class="fa-solid fa-clipboard-check text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700">Không có yêu cầu nào</h3>
                <p class="text-slate-500 mt-2">Hiện tại không có sinh viên nào gửi yêu cầu mượn sách.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold tracking-wider">
                            <th class="px-6 py-5">Sinh viên</th>
                            <th class="px-6 py-5">Thông tin sách</th>
                            <th class="px-6 py-5">Tác giả / NXB</th>
                            <th class="px-6 py-5 text-right">Giá tiền</th>
                            <th class="px-6 py-5 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($requests as $r): ?>
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                        <?= substr($r->StudName ?? 'U', 0, 1) ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800"><?= $r->StudName ?></div>
                                        <div class="text-xs text-slate-500 font-mono bg-slate-100 px-2 py-0.5 rounded inline-block mt-1">
                                            <?= $r->StudentID ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">
                                        <?= $r->BookName ?>
                                    </span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-indigo-50 text-indigo-600 border border-indigo-100">
                                            <?= $r->CategoryName ?? 'General' ?>
                                        </span>
                                        <span class="text-xs text-slate-400">ISBN: <?= $r->ISBNNumber ?></span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                <?= $r->AuthorName ?>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <span class="text-slate-700 font-bold font-mono">
                                    <?= number_format($r->BookPrice ?? 0, 0, ',', '.') ?> đ
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    
                                                <a href="<?= BASE_URL ?>index.php?c=request&a=issue&rid=<?= $r->id ?>" 
                                       onclick="return confirm('Xác nhận phát sách này cho sinh viên?')"
                                       class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-1 active:scale-95">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Duyệt</span>
                                    </a>

                                                <a href="<?= BASE_URL ?>index.php?c=request&a=cancel&rid=<?= $r->id ?>"
                                       onclick="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn XÓA/TỪ CHỐI yêu cầu này không?');" 
                                       class="inline-flex items-center justify-center w-8 h-8 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl border border-rose-100 hover:border-rose-500 transition-all shadow-sm"
                                       title="Xóa yêu cầu">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>

                                </div>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">Hiển thị <?= count($requests) ?> yêu cầu</span>
                <div class="flex gap-1">
                    <button class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-300 transition-all text-xs">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button class="w-8 h-8 rounded-lg flex items-center justify-center bg-indigo-600 text-white shadow-md text-xs font-bold">1</button>
                    <button class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-300 transition-all text-xs">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>