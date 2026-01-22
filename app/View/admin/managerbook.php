<?php 
// ===== INCLUDE SIDEBAR ADMIN =====
include __DIR__ . '/../layouts/layoutsadmin/sidebaradmin.php'; 

// Xử lý biến hiển thị phân trang (Giữ nguyên logic cũ)
$page = $page ?? 1;
$limit = 10;
$totalPages = $totalPages ?? 1;
$totalRows = $totalRows ?? count($books);
$startEntry = ($page - 1) * $limit + 1;
$endEntry = min($page * $limit, $totalRows);
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
                    indigo: { 500: '#6366f1', 600: '#4f46e5', 50: '#eef2ff', 700: '#4338ca' }
                }
            }
        }
    }
</script>

<div class="md:ml-64 bg-slate-50 min-h-screen p-8 relative overflow-hidden">

    <div class="absolute inset-0 z-0 opacity-[0.4]" style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 32px 32px;"></div>
    <div class="absolute top-0 left-0 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

    <div class="relative z-10 max-w-[1400px] mx-auto">

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Quản lý Sách</h2>
                <p class="text-slate-500 mt-1">Quản lý kho sách, giá cả và thông tin chi tiết.</p>
            </div>
            <a href="index.php?c=addbook&a=index" 
               class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                <i class="fa-solid fa-book-medical"></i> Thêm sách mới
            </a>
        </div>

        <?php if (!empty($_SESSION['msg'])): ?>
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-pulse">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <span class="text-sm font-medium"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-pulse">
                <i class="fa-solid fa-circle-exclamation text-xl"></i>
                <span class="text-sm font-medium"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden flex flex-col min-h-[600px]">
            
            <div class="overflow-x-auto flex-grow">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-100">
                            <th class="px-6 py-4 w-12 text-center">#</th>
                            <th class="px-6 py-4 w-80">Tên sách</th> <th class="px-6 py-4">Danh mục</th>
                            <th class="px-6 py-4">Tác giả</th>
                            <th class="px-6 py-4">ISBN</th>
                            <th class="px-6 py-4 text-right">Giá (VNĐ)</th>
                            <th class="px-6 py-4 text-center">Kho</th>
                            <th class="px-6 py-4 text-right w-32">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php if(!empty($books)): ?>
                            <?php foreach ($books as $i => $b): ?>
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    
                                    <td class="px-6 py-4 text-center text-slate-400 font-medium">
                                        <?= ($page - 1) * 10 + ($i + 1) ?>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-lg bg-indigo-100 text-indigo-600 flex flex-shrink-0 items-center justify-center font-bold text-xs shadow-sm overflow-hidden border border-indigo-50 relative">
                                                <?php 
                                                    // Đường dẫn file ảnh
                                                    $imgName = $b->BookImage ?? ''; 
                                                    $imgPath = "public/assets/img/" . $imgName;
                                                ?>
                                                
                                                <?php if (!empty($imgName) && file_exists($imgPath)): ?>
                                                    <img src="<?= $imgPath ?>" alt="Cover" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-300">
                                                <?php else: ?>
                                                    <i class="fa-solid fa-book text-lg"></i>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div>
                                                <span class="block font-bold text-slate-700 group-hover:text-indigo-600 transition-colors line-clamp-2" title="<?= htmlspecialchars($b->BookName) ?>">
                                                    <?= htmlspecialchars($b->BookName) ?>
                                                </span>
                                                <span class="text-xs text-slate-400">ID: <?= $b->id ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                            <?= htmlspecialchars($b->CategoryName) ?>
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-slate-600 font-medium">
                                        <?= htmlspecialchars($b->AuthorName) ?>
                                    </td>

                                    <td class="px-6 py-4 text-slate-500 font-mono text-xs">
                                        <?= htmlspecialchars($b->ISBNNumber) ?>
                                    </td>

                                    <td class="px-6 py-4 text-right font-bold text-slate-700">
                                        <?= number_format($b->BookPrice) ?>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="font-bold text-slate-700"><?= $b->Copies ?></span>
                                            <span class="text-[10px] text-slate-400">Đã mượn: <?= $b->IssuedCopies ?? 0 ?></span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="openEditModal(
                                                <?= $b->id ?>, 
                                                '<?= htmlspecialchars(addslashes($b->BookName)) ?>', 
                                                <?= $b->CatId ?>, 
                                                <?= $b->AuthorId ?>, 
                                                '<?= htmlspecialchars($b->ISBNNumber) ?>', 
                                                <?= $b->BookPrice ?>, 
                                                <?= $b->Copies ?>
                                            )" 
                                               class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white flex items-center justify-center transition-all shadow-sm border border-indigo-100" 
                                               title="Sửa">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>

                                            <a href="index.php?c=managerbook&a=delete&id=<?= $b->id ?>" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa sách: <?= htmlspecialchars($b->BookName) ?>?')"
                                               class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-all shadow-sm border border-rose-100" 
                                               title="Xóa bỏ">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                            <i class="fa-solid fa-book-open text-3xl"></i>
                                        </div>
                                        <p>Chưa có sách nào trong thư viện.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($totalRows > 0): ?>
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                
                <div class="text-xs text-slate-500 font-medium">
                    Hiển thị <span class="text-slate-800 font-bold"><?= $startEntry ?>-<?= $endEntry ?></span> 
                    trong tổng số <span class="text-slate-800 font-bold"><?= $totalRows ?></span> đầu sách
                </div>

                <?php if ($totalPages > 1): ?>
                <div class="flex items-center gap-1">
                    <a href="?c=managerbook&a=index&page=<?= max(1, $page - 1) ?>" 
                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-white hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm <?= ($page <= 1) ? 'opacity-50 pointer-events-none' : '' ?>">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </a>

                    <?php 
                        $range = 2;
                        for ($i = 1; $i <= $totalPages; $i++): 
                            if ($i == 1 || $i == $totalPages || ($i >= $page - $range && $i <= $page + $range)):
                    ?>
                        <a href="?c=managerbook&a=index&page=<?= $i ?>" 
                           class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all shadow-sm
                           <?= ($i == $page) 
                               ? 'bg-indigo-600 text-white shadow-indigo-200 ring-2 ring-indigo-100 border-transparent' 
                               : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200' ?>">
                            <?= $i ?>
                        </a>
                    <?php elseif ($i == $page - $range - 1 || $i == $page + $range + 1): ?>
                        <span class="w-8 h-8 flex items-center justify-center text-slate-400 text-xs">...</span>
                    <?php endif; endfor; ?>

                    <a href="?c=managerbook&a=index&page=<?= min($totalPages, $page + 1) ?>" 
                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-white hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm <?= ($page >= $totalPages) ? 'opacity-50 pointer-events-none' : '' ?>">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
                <?php endif; ?>

            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-100">
                
                <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-book-open"></i> Cập nhật thông tin sách
                    </h3>
                    <button type="button" class="text-indigo-200 hover:text-white transition-colors" onclick="closeEditModal()">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>

                <div class="px-8 py-6 bg-slate-50">
                    <form action="index.php?c=managerbook&a=update" method="POST" class="space-y-5">
                        <input type="hidden" name="id" id="edit_id"> 

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Tên sách <span class="text-rose-500">*</span></label>
                            <input type="text" name="bookname" id="edit_bookname" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>

                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Danh mục</label>
                                <select name="category" id="edit_category" class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <?php if(!empty($categories)): ?>
                                        <?php foreach($categories as $c): ?>
                                            <option value="<?= $c->id ?>"><?= htmlspecialchars($c->CategoryName) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Tác giả</label>
                                <select name="author" id="edit_author" class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <?php if(!empty($authors)): ?>
                                        <?php foreach($authors as $a): ?>
                                            <option value="<?= $a->id ?>"><?= htmlspecialchars($a->AuthorName) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">ISBN</label>
                                <input type="text" name="isbn" id="edit_isbn" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Giá (VNĐ)</label>
                                <input type="number" name="price" id="edit_price" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Số lượng</label>
                                <input type="number" name="copies" id="edit_copies" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3 pt-2 border-t border-slate-200">
                            <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-white text-slate-600 font-bold rounded-lg border border-slate-300 hover:bg-slate-50 transition-all">Hủy</button>
                            <button type="submit" name="update" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-lg shadow hover:bg-indigo-700 transition-all">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function openEditModal(id, name, catId, authId, isbn, price, copies) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_bookname').value = name;
        document.getElementById('edit_category').value = catId;
        document.getElementById('edit_author').value = authId;
        document.getElementById('edit_isbn').value = isbn;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_copies').value = copies;
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>