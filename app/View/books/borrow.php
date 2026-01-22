<?php
// ===== INCLUDE SIDEBAR =====
if (file_exists(__DIR__ . '/../layouts/sidebar.php')) {
    include __DIR__ . '/../layouts/sidebar.php';
}
?>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                colors: {
                    indigo: { 50: '#eef2ff', 100: '#e0e7ff', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 900: '#312e81' },
                    slate: { 50: '#f8fafc', 100: '#f1f5f9', 200:'#e2e8f0', 500: '#64748b', 800: '#1e293b' },
                }
            }
        }
    }
</script>

<style>
    body { font-family: 'Inter', sans-serif; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>

<div class="max-w-7xl mx-auto md:ml-80 px-4 sm:px-8 py-10 min-h-screen bg-slate-50 text-slate-800">

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Quản lý Sách mượn</h1>
            <p class="text-slate-500 mt-1">Theo dõi lịch sử mượn trả và trạng thái hồ sơ của bạn.</p>
        </div>
        <a href="index.php?c=books" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-all shadow-lg shadow-indigo-200 gap-2">
            <i class="fa-solid fa-plus"></i> Mượn sách mới
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow-sm border-t-4 border-blue-500 flex items-start justify-between group">
            <div>
                <p class="text-slate-500 text-sm font-semibold uppercase mb-1">Tổng sách đã mượn</p>
                <h3 class="text-3xl font-bold text-slate-800"><?= number_format($totalBorrowed) ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i class="fa-solid fa-book-bookmark"></i></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border-t-4 border-emerald-500 flex items-start justify-between group">
            <div>
                <p class="text-slate-500 text-sm font-semibold uppercase mb-1">Sách đã trả</p>
                <h3 class="text-3xl font-bold text-slate-800"><?= number_format($totalReturned) ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl"><i class="fa-solid fa-check-circle"></i></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border-t-4 border-rose-500 flex items-start justify-between group">
            <div>
                <p class="text-slate-500 text-sm font-semibold uppercase mb-1">Tổng tiền phạt</p>
                <h3 class="text-3xl font-bold text-rose-600"><?= number_format($totalFine) ?> <span class="text-base font-medium text-rose-400">đ</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
        </div>
    </div>

    <?php if ($totalBorrowed > $totalReturned): ?>
        <div class="mb-8 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3 shadow-sm">
            <div class="flex-shrink-0 text-amber-500 mt-0.5"><i class="fa-solid fa-circle-info text-lg"></i></div>
            <div>
                <h4 class="text-amber-800 font-bold text-sm">Cần chú ý</h4>
                <p class="text-amber-700 text-sm mt-0.5">Bạn đang có sách chưa trả. Vui lòng kiểm tra hạn trả bên dưới.</p>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="font-bold text-slate-800 text-lg">Danh sách chi tiết</h3>
            
            <form method="GET" action="index.php" id="filterForm">
                <input type="hidden" name="c" value="borrow">
                <select name="status" onchange="document.getElementById('filterForm').submit()" 
                        class="text-xs border-slate-200 rounded-lg text-slate-600 focus:ring-indigo-500 py-1.5 pl-2 pr-8 bg-slate-50 cursor-pointer">
                    <option value="" <?= $filterStatus == '' ? 'selected' : '' ?>>Tất cả trạng thái</option>
                    <option value="issued" <?= $filterStatus == 'issued' ? 'selected' : '' ?>>Đang mượn</option>
                    <option value="pending" <?= $filterStatus == 'pending' ? 'selected' : '' ?>>Chờ duyệt</option>
                    <option value="returned" <?= $filterStatus == 'returned' ? 'selected' : '' ?>>Đã trả</option>
                    <option value="rejected" <?= $filterStatus == 'rejected' ? 'selected' : '' ?>>Đã hủy/Từ chối</option>
                </select>
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-left">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase w-12">#</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Thông tin sách</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Lịch trình</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Phạt (VND)</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (!empty($books)): $i = $offset + 1; foreach ($books as $b): ?>
                        <?php 
                            $now = new DateTime();
                            $due = isset($b->DueDate) ? new DateTime($b->DueDate) : null;
                            $statusCode = isset($b->status_code) ? $b->status_code : 'issued'; 
                            $isReturned = ($statusCode == 'returned');

                            // Badge Logic
                            if ($statusCode == 'pending') {
                                $statusBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Chờ duyệt</span>';
                            } elseif ($statusCode == 'rejected') {
                                $statusBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">Đã từ chối</span>';
                            } elseif ($isReturned) {
                                $statusBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">Đã trả</span>';
                            } elseif ($due && $now > $due) {
                                $statusBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 border border-rose-200 animate-pulse">Quá hạn</span>';
                            } else {
                                $statusBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">Đang mượn</span>';
                            }

                            // Renew Logic
                            $isRenewable = ($statusCode == 'issued' && !$isReturned && isset($b->RenewCount) && $b->RenewCount < 1 && !empty($b->IssueID));
                        ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 text-sm text-slate-400 font-medium"><?= $i++ ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-14 bg-slate-100 rounded border border-slate-200 flex-shrink-0 flex items-center justify-center text-slate-300"><i class="fa-solid fa-book"></i></div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 group-hover:text-indigo-700 line-clamp-1"><?= htmlentities($b->BookName) ?></p>
                                        <p class="text-xs text-slate-500 mt-0.5 font-mono">ISBN: <?= $b->ISBNNumber ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1.5 text-xs">
                                    <?php if ($statusCode == 'pending' || $statusCode == 'rejected'): ?>
                                        <div class="flex items-center"><span class="w-20 text-slate-400">Gửi YC:</span><span class="font-medium text-amber-600"><?= date('d/m/Y H:i', strtotime($b->RequestDate)) ?></span></div>
                                    <?php else: ?>
                                        <div class="flex items-center"><span class="w-16 text-slate-400">Mượn:</span><span class="font-medium"><?= date('d/m/Y', strtotime($b->IssuesDate)) ?></span></div>
                                        <div class="flex items-center"><span class="w-16 text-slate-400">Hạn trả:</span><span class="font-medium <?= ($due && $now > $due && !$isReturned) ? 'text-rose-600' : '' ?>"><?= date('d/m/Y', strtotime($b->DueDate)) ?></span></div>
                                        <?php if($isReturned): ?><div class="flex items-center text-emerald-600"><span class="w-16 text-slate-400">Đã trả:</span><span class="font-bold"><?= date('d/m/Y', strtotime($b->ReturnDate)) ?></span></div><?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center"><?= $statusBadge ?></td>
                            <td class="px-6 py-4 text-right font-bold text-rose-600"><?= ($b->fine > 0) ? number_format($b->fine) : '-' ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($isRenewable): ?>
                                    <a href="index.php?c=borrow&a=renew&id=<?= $b->IssueID ?>" onclick="return confirm('Gia hạn thêm 7 ngày?')" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all border border-indigo-100 text-xs font-medium">
                                        <i class="fa-solid fa-rotate mr-1.5"></i> Gia hạn
                                    </a>
                                <?php elseif ($isReturned): ?>
                                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                                <?php else: ?>
                                    <span class="text-slate-300 text-xs italic">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400"><p>Chưa có dữ liệu mượn sách.</p></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-500">
                    Hiển thị <span class="font-bold text-slate-700"><?= $totalRecords > 0 ? $offset + 1 : 0 ?></span> đến <span class="font-bold text-slate-700"><?= min($offset + $limit, $totalRecords) ?></span> của <span class="font-bold text-slate-700"><?= $totalRecords ?></span>
                </div>

                <div class="flex items-center gap-1">
                    <?php 
                    // FIX: Tạo helper build URL để giữ lại status khi chuyển trang
                    $baseUrl = "index.php?c=borrow&status=" . urlencode($filterStatus);
                    ?>
                    
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= $baseUrl ?>&page=1" class="px-2 py-1.5 rounded border border-slate-200 text-xs hover:bg-white transition-all"><i class="fa-solid fa-angles-left"></i></a>
                        <a href="<?= $baseUrl ?>&page=<?= $currentPage - 1 ?>" class="px-3 py-1.5 rounded border border-slate-200 text-xs hover:bg-white transition-all">Trước</a>
                    <?php endif; ?>

                    <?php for ($p = max(1, $currentPage-2); $p <= min($totalPages, $currentPage+2); $p++): ?>
                        <a href="<?= $baseUrl ?>&page=<?= $p ?>" class="px-3 py-1.5 rounded border <?= $p == $currentPage ? 'bg-indigo-600 text-white border-indigo-600' : 'border-slate-200 text-slate-600 bg-white hover:bg-slate-50' ?> text-xs font-bold transition-all">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= $baseUrl ?>&page=<?= $currentPage + 1 ?>" class="px-3 py-1.5 rounded border border-slate-200 text-xs hover:bg-white transition-all">Sau</a>
                        <a href="<?= $baseUrl ?>&page=<?= $totalPages ?>" class="px-2 py-1.5 rounded border border-slate-200 text-xs hover:bg-white transition-all"><i class="fa-solid fa-angles-right"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>