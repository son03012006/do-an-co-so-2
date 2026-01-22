<?php
// ===== INCLUDE SIDEBAR =====
include __DIR__ . '/../layouts/sidebar.php';
?>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    indigo: { 50: '#eef2ff', 100: '#e0e7ff', 600: '#4f46e5', 700: '#4338ca', 900: '#312e81' },
                    slate: { 50: '#f8fafc', 100: '#f1f5f9', 800: '#1e293b' }
                }
            }
        }
    }
</script>

<div class="max-w-screen-2xl mx-auto md:ml-72 px-4 sm:px-6 lg:px-8 pt-10 pb-24 min-h-screen bg-slate-50">

    <div class="bg-white p-6 rounded-3xl shadow-lg shadow-indigo-100 mb-8 border border-slate-100">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            <input type="hidden" name="c" value="books">
            <input type="hidden" name="a" value="index">

            <div class="relative w-full md:flex-[2]">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="keyword" 
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all placeholder-slate-400 text-slate-700 font-medium text-sm"
                    placeholder="Tìm tên sách, ISBN..." 
                    value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>">
            </div>

            <div class="w-full md:flex-1">
                <select name="category" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all text-slate-700 cursor-pointer text-sm">
                    <option value="">-- Tất cả Thể loại --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c->id ?>" <?= ($filters['category'] == $c->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c->CategoryName) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="w-full md:flex-1">
                <select name="author" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all text-slate-700 cursor-pointer text-sm">
                    <option value="">-- Tất cả Tác giả --</option>
                    <?php foreach ($authors as $a): ?>
                        <option value="<?= $a->id ?>" <?= ($filters['author'] == $a->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a->AuthorName) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="w-full md:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-sm">
                <span>Lọc</span>
            </button>
        </form>
    </div>

    <?php if (empty($books)): ?>
        <div class="text-center py-20 bg-white rounded-3xl shadow-sm border border-slate-100">
            <div class="bg-indigo-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-regular fa-folder-open text-3xl text-indigo-400"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-700">Không tìm thấy kết quả</h3>
            <p class="text-slate-500 mt-2">Thử thay đổi từ khóa hoặc bộ lọc tìm kiếm.</p>
        </div>
    <?php else: ?>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            
            <?php foreach ($books as $b): 
                $remain = $b->Copies - $b->IssuedCopies;
                $badgeColor = ($remain <= 2) ? 'bg-rose-500 shadow-rose-200' : 'bg-emerald-500 shadow-emerald-200';
                $badgeText = ($remain <= 2) ? "Sắp hết ($remain)" : "Còn hàng ($remain)";
                $imgSrc = !empty($b->BookImage) ? BASE_URL . 'public/assets/img/' . $b->BookImage : '';
            ?>
            
            <div class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 flex flex-col h-full relative">
                
                <div class="relative w-full aspect-[2/3] overflow-hidden bg-slate-200 group-hover:brightness-105 transition-all">
                    <div class="absolute top-2 right-2 z-20">
                        <span class="<?= $badgeColor ?> text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-md backdrop-blur-sm bg-opacity-95 uppercase tracking-wide">
                            <?= $badgeText ?>
                        </span>
                    </div>

                    <img src="<?= htmlspecialchars($imgSrc) ?>" 
                         onerror="this.onerror=null; this.src='https://placehold.co/300x450/e2e8f0/475569?text=No+Image'; this.className='w-full h-full object-cover opacity-60';"
                         class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500 ease-in-out" 
                         alt="<?= htmlspecialchars($b->BookName) ?>">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>

                <div class="p-3 flex flex-col flex-1">
                    <h3 class="text-sm font-bold text-slate-800 leading-snug mb-1 line-clamp-2 min-h-[2.5rem] group-hover:text-indigo-600 transition-colors" title="<?= htmlspecialchars($b->BookName) ?>">
                        <?= htmlspecialchars($b->BookName) ?>
                    </h3>

                    <div class="mt-1 space-y-0.5 mb-3">
                        <p class="text-[11px] text-slate-500 flex items-center gap-1">
                            <i class="fa-regular fa-user text-indigo-400 w-3"></i>
                            <span class="font-medium text-slate-600 truncate"><?= htmlspecialchars($b->AuthorName) ?></span>
                        </p>
                        <p class="text-[11px] text-slate-500 flex items-center gap-1">
                            <i class="fa-solid fa-tags text-indigo-400 w-3"></i>
                            <span class="truncate"><?= htmlspecialchars($b->CategoryName) ?></span>
                        </p>
                    </div>

                    <div class="flex items-end justify-between mb-3 mt-auto pt-2 border-t border-slate-50">
                        <div class="cursor-pointer group/rating" onclick="openReviewModal(<?= (int)$b->id ?>)">
                            <?php 
                                $avg = $b->AvgRating !== null ? round($b->AvgRating, 1) : 0;
                                $stars = round($avg);
                            ?>
                            <div class="flex text-amber-400 text-[9px] mb-0.5">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="<?= ($i <= $stars) ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-[9px] text-slate-400 group-hover/rating:text-indigo-600 transition-colors">
                                <?= ($b->ReviewCount > 0) ? "($b->ReviewCount)" : "Chưa có đánh giá" ?>
                            </span>
                        </div>
                        
                        <div class="text-right">
                            <span class="block text-sm font-extrabold text-indigo-700">
                                <?= number_format($b->BookPrice) ?>
                            </span>
                            <span class="text-[9px] text-slate-400 font-medium">VNĐ</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-1.5">
                        <a href="javascript:void(0)" 
                           onclick="confirmBorrow('index.php?c=borrow&a=create&bookid=<?= $b->id ?>', '<?= htmlspecialchars($b->BookName) ?>')"
                           class="py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-[11px] transition-all flex items-center justify-center gap-1 shadow-sm shadow-indigo-200">
                            <i class="fa-solid fa-basket-shopping"></i> Mượn
                        </a>

                        <?php if (!empty($b->CanReview) && empty($b->HasReviewed)): ?>
                            <a href="javascript:void(0)" onclick="openReviewModal(<?= (int)$b->id ?>)"
                               class="py-2 bg-white border border-emerald-200 text-emerald-600 hover:bg-emerald-50 rounded-lg font-bold text-[11px] transition-all flex items-center justify-center gap-1">
                                <i class="fa-regular fa-pen-to-square"></i> Đánh giá
                            </a>
                        <?php elseif (!empty($b->HasReviewed)): ?>
                            <div class="py-2 bg-slate-50 text-slate-400 border border-slate-100 rounded-lg font-medium text-[11px] text-center flex items-center justify-center gap-1 cursor-default">
                                <i class="fa-solid fa-check"></i> Xong
                            </div>
                        <?php else: ?>
                             <div class="py-2"></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($totalPages > 1): ?>
        <div class="mt-12 flex justify-center items-center space-x-2">
            <?php if($page > 1): ?>
                <a href="index.php?c=books&a=index&page=<?= $page-1 ?>&keyword=<?= urlencode($filters['keyword']) ?>" 
                   class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="index.php?c=books&a=index&page=<?= $i ?>&keyword=<?= urlencode($filters['keyword']) ?>&category=<?= urlencode($filters['category']) ?>&author=<?= urlencode($filters['author']) ?>"
                   class="w-9 h-9 flex items-center justify-center rounded-full text-xs font-bold transition-all shadow-sm 
                   <?= ($i == $page) 
                       ? 'bg-indigo-600 text-white shadow-indigo-300' 
                       : 'bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if($page < $totalPages): ?>
                <a href="index.php?c=books&a=index&page=<?= $page+1 ?>&keyword=<?= urlencode($filters['keyword']) ?>" 
                   class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

<div id="reviewModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeReviewModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                <div class="bg-indigo-600 px-4 py-4 sm:px-6 flex justify-between items-center">
                    <h3 class="text-base font-bold leading-6 text-white flex items-center gap-2" id="modal-title">
                        <i class="fa-solid fa-star text-amber-300"></i> Đánh giá & Nhận xét
                    </h3>
                    <button type="button" class="text-indigo-200 hover:text-white transition-colors" onclick="closeReviewModal()">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div class="px-4 py-5 sm:p-6 bg-slate-50" style="max-height: 60vh; overflow-y: auto;" id="reviewContent">
                    <div class="flex flex-col items-center justify-center py-10 space-y-3">
                        <i class="fa-solid fa-circle-notch fa-spin text-3xl text-indigo-500"></i>
                        <span class="text-slate-500 text-sm font-medium">Đang tải đánh giá...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Hàm xác nhận mượn sách
    function confirmBorrow(url, bookName) {
        Swal.fire({
            title: 'Xác nhận mượn?',
            text: `Bạn muốn mượn cuốn sách "${bookName}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fa-solid fa-check mr-1"></i> Đồng ý',
            cancelButtonText: 'Hủy bỏ',
            background: '#fff',
            customClass: {
                popup: 'rounded-3xl border border-slate-100 shadow-xl font-sans',
                title: 'text-slate-800 font-bold',
                htmlContainer: 'text-slate-500',
                confirmButton: 'rounded-xl shadow-lg shadow-indigo-200 px-5 py-2.5',
                cancelButton: 'rounded-xl hover:bg-slate-200 px-5 py-2.5'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Hiệu ứng chờ
                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => { Swal.showLoading(); }
                });
                window.location.href = url;
            }
        });
    }

    // 2. Hàm xử lý Modal Đánh giá
    function openReviewModal(id){
        const modal = document.getElementById("reviewModal");
        modal.classList.remove("hidden");
        fetch("index.php?c=review&a=index&bookid=" + id)
            .then(res => res.text())
            .then(html => { document.getElementById("reviewContent").innerHTML = html; })
            .catch(err => {
                document.getElementById("reviewContent").innerHTML = `
                    <div class="text-center text-red-500 py-4">
                        <i class="fa-solid fa-triangle-exclamation mb-2"></i> <p>Không thể tải dữ liệu.</p>
                    </div>`;
            });
    }
    
    function closeReviewModal(){
        document.getElementById("reviewModal").classList.add("hidden");
        document.getElementById("reviewContent").innerHTML = `
            <div class="flex flex-col items-center justify-center py-10 space-y-3">
                <i class="fa-solid fa-circle-notch fa-spin text-3xl text-indigo-500"></i>
                <span class="text-slate-500 text-sm font-medium">Đang tải đánh giá...</span>
            </div>`;
    }
</script>

<?php if (isset($_SESSION['swal_text'])): ?>
<script>
    Swal.fire({
        title: '<?= $_SESSION['swal_title'] ?? 'Thông báo' ?>',
        text: '<?= $_SESSION['swal_text'] ?>',
        icon: '<?= $_SESSION['swal_icon'] ?? 'info' ?>',
        confirmButtonColor: '#4f46e5',
        confirmButtonText: 'Đã hiểu',
        customClass: {
            popup: 'rounded-3xl border border-slate-100 shadow-xl font-sans',
            title: 'text-slate-800 font-bold',
            htmlContainer: 'text-slate-500',
            confirmButton: 'rounded-xl shadow-lg shadow-indigo-200 px-6 py-2'
        }
    });
</script>
<?php 
    // Xóa session sau khi hiện xong
    unset($_SESSION['swal_text']);
    unset($_SESSION['swal_title']);
    unset($_SESSION['swal_icon']);
?>
<?php endif; ?>