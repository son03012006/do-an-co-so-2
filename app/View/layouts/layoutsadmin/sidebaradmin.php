<?php
$currentC = $_GET['c'] ?? '';
$currentA = $_GET['a'] ?? '';

// --- LOGIC KIỂM TRA TIN NHẮN MỚI ---
// Đếm số tin nhắn từ student mà chưa đọc (is_read = 0)
$unread_msg_count = 0;
if (isset($dbh)) { // Đảm bảo biến kết nối DB $dbh đã tồn tại
    try {
        $sql_chat = "SELECT COUNT(*) FROM tblmessages WHERE sender_type = 'student' AND is_read = 0";
        $query_chat = $dbh->prepare($sql_chat);
        $query_chat->execute();
        $unread_msg_count = $query_chat->fetchColumn();
    } catch (Exception $e) {
        // Bỏ qua lỗi nếu bảng chưa tồn tại
        $unread_msg_count = 0;
    }
}
// ------------------------------------

// Helper function để check active
function isActive($c, $currentC)
{
    return ($c === $currentC) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white';
}

function isDropdownOpen($arrC, $currentC)
{
    return in_array($currentC, $arrC) ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0 overflow-hidden';
}

function isDropdownActive($arrC, $currentC)
{
    return in_array($currentC, $arrC) ? 'text-indigo-400' : 'text-slate-400 hover:text-white';
}
?>

<aside
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-slate-900 border-r border-slate-800">

    <div class="flex flex-col items-center justify-center p-6 border-b border-slate-800 min-h-[100px]">
        <h2 class="text-xl font-bold text-white tracking-wide">Administrator</h2>
        <span class="text-xs text-slate-500 uppercase font-semibold mt-1">ThuvienSo</span>
    </div>

    <div class="h-full px-3 py-4 overflow-y-auto pb-20 custom-scrollbar">
        <ul class="space-y-1 font-medium">

            <li>
                <a href="<?= BASE_URL ?>index.php?c=admindashboard&a=index"
                    class="flex items-center p-3 rounded-xl transition-all duration-200 group <?= isActive('admindashboard', $currentC) ?>">
                    <i
                        class="fa-solid fa-chart-pie w-6 text-center text-lg transition-transform group-hover:scale-110"></i>
                    <span class="ml-3">Thống kê</span>
                </a>
            </li>

            <?php $catActive = in_array($currentC, ['category', 'managercategory']); ?>
            <li>
                <button type="button"
                    class="flex items-center w-full p-3 transition-all duration-200 rounded-xl group <?= isDropdownActive(['category', 'managercategory'], $currentC) ?>"
                    onclick="toggleDropdown('dropdown-category', this)">
                    <i
                        class="fa-solid fa-layer-group w-6 text-center text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Danh mục</span>
                    <i
                        class="fa-solid fa-chevron-down w-3 h-3 transition-transform duration-300 <?= $catActive ? 'rotate-180' : '' ?>"></i>
                </button>
                <ul id="dropdown-category"
                    class="transition-all duration-300 ease-in-out pl-4 space-y-1 <?= isDropdownOpen(['category', 'managercategory'], $currentC) ?>">
                    <li>
                        <a href="<?= BASE_URL ?>index.php?c=category&a=add"
                            class="flex items-center p-2 pl-11 w-full text-sm text-slate-400 rounded-lg hover:text-white hover:bg-slate-800 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-2"></span> Thêm mới
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>index.php?c=managercategory&a=index"
                            class="flex items-center p-2 pl-11 w-full text-sm text-slate-400 rounded-lg hover:text-white hover:bg-slate-800 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-2"></span> Quản lý
                        </a>
                    </li>
                </ul>
            </li>

            <?php $authActive = in_array($currentC, ['author', 'managerauthor']); ?>
            <li>
                <button type="button"
                    class="flex items-center w-full p-3 transition-all duration-200 rounded-xl group <?= isDropdownActive(['author', 'managerauthor'], $currentC) ?>"
                    onclick="toggleDropdown('dropdown-author', this)">
                    <i
                        class="fa-solid fa-building-columns w-6 text-center text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Nhà xuất bản</span>
                    <i
                        class="fa-solid fa-chevron-down w-3 h-3 transition-transform duration-300 <?= $authActive ? 'rotate-180' : '' ?>"></i>
                </button>
                <ul id="dropdown-author"
                    class="transition-all duration-300 ease-in-out pl-4 space-y-1 <?= isDropdownOpen(['author', 'managerauthor'], $currentC) ?>">
                    <li>
                        <a href="<?= BASE_URL ?>index.php?c=author&a=add"
                            class="flex items-center p-2 pl-11 w-full text-sm text-slate-400 rounded-lg hover:text-white hover:bg-slate-800 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-2"></span> Thêm mới
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>index.php?c=managerauthor&a=index"
                            class="flex items-center p-2 pl-11 w-full text-sm text-slate-400 rounded-lg hover:text-white hover:bg-slate-800 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-2"></span> Quản lý
                        </a>
                    </li>
                </ul>
            </li>

            <?php $bookActive = in_array($currentC, ['addbook', 'managerbook', 'fine']); ?>
            <li>
                <button type="button"
                    class="flex items-center w-full p-3 transition-all duration-200 rounded-xl group <?= isDropdownActive(['addbook', 'managerbook', 'fine'], $currentC) ?>"
                    onclick="toggleDropdown('dropdown-book', this)">
                    <i class="fa-solid fa-book w-6 text-center text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Quản lý sách</span>
                    <i
                        class="fa-solid fa-chevron-down w-3 h-3 transition-transform duration-300 <?= $bookActive ? 'rotate-180' : '' ?>"></i>
                </button>
                <ul id="dropdown-book"
                    class="transition-all duration-300 ease-in-out pl-4 space-y-1 <?= isDropdownOpen(['addbook', 'managerbook', 'fine'], $currentC) ?>">
                    <li>
                        <a href="<?= BASE_URL ?>index.php?c=addbook&a=add"
                            class="flex items-center p-2 pl-11 w-full text-sm text-slate-400 rounded-lg hover:text-white hover:bg-slate-800 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-2"></span> Thêm sách
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>index.php?c=managerbook&a=index"
                            class="flex items-center p-2 pl-11 w-full text-sm text-slate-400 rounded-lg hover:text-white hover:bg-slate-800 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-2"></span> Danh sách
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>index.php?c=fine&a=index"
                            class="flex items-center p-2 pl-11 w-full text-sm text-slate-400 rounded-lg hover:text-white hover:bg-slate-800 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-2"></span> Cấu hình phạt
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="<?= BASE_URL ?>index.php?c=request&a=index"
                    class="flex items-center p-3 rounded-xl transition-all duration-200 group <?= isActive('request', $currentC) ?>">
                    <i
                        class="fa-solid fa-envelope-open-text w-6 text-center text-lg transition-transform group-hover:scale-110"></i>
                    <span class="ml-3">Duyệt yêu cầu</span>
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>index.php?c=report&a=index"
                    class="flex items-center p-3 rounded-xl transition-all duration-200 group <?= isActive('report', $currentC) ?>">
                    <i
                        class="fa-solid fa-file-invoice w-6 text-center text-lg transition-transform group-hover:scale-110"></i>
                    <span class="ml-3">Báo cáo</span>
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>index.php?c=user&a=index"
                    class="flex items-center p-3 rounded-xl transition-all duration-200 group <?= isActive('user', $currentC) ?>">
                    <i
                        class="fa-solid fa-users-gear w-6 text-center text-lg transition-transform group-hover:scale-110"></i>
                    <span class="ml-3">Người dùng</span>
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>index.php?c=admin_chat&a=index"
                    class="flex items-center p-3 rounded-xl transition-all duration-200 group <?= isActive('chat', $currentC) ?>">
                    <div class="relative">
                        <i
                            class="fa-solid fa-comments w-6 text-center text-lg transition-transform group-hover:scale-110"></i>

                        <?php if ($unread_msg_count > 0): ?>
                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                        </span>
                        <?php endif; ?>
                    </div>

                    <span class="ml-3">Trò chuyện</span>

                    <?php if ($unread_msg_count > 0): ?>
                    <span
                        class="ml-auto bg-rose-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-lg shadow-rose-500/20">
                        <?= $unread_msg_count > 99 ? '99+' : $unread_msg_count ?>
                    </span>
                    <?php endif; ?>
                </a>
            </li>
            <div class="pt-4 mt-4 space-y-1 border-t border-slate-800">
                <li>
                    <a href="<?= BASE_URL ?>index.php?c=adminpassword&a=index"
                        class="flex items-center p-3 rounded-xl transition-all duration-200 group <?= isActive('adminpassword', $currentC) ?>">
                        <i
                            class="fa-solid fa-key w-6 text-center text-lg transition-transform group-hover:scale-110"></i>
                        <span class="ml-3">Đổi mật khẩu</span>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>index.php?c=adminauth&a=logout"
                        onclick="return confirm('Bạn có chắc muốn đăng xuất?')"
                        class="flex items-center p-3 rounded-xl text-rose-400 hover:bg-rose-500/10 hover:text-rose-500 transition-all duration-200 group">
                        <i
                            class="fa-solid fa-right-from-bracket w-6 text-center text-lg transition-transform group-hover:scale-110"></i>
                        <span class="ml-3">Đăng xuất</span>
                    </a>
                </li>
            </div>

        </ul>
    </div>
</aside>

<script>
function toggleDropdown(id, btn) {
    const el = document.getElementById(id);
    const arrow = btn.querySelector('.fa-chevron-down');

    if (el.classList.contains('max-h-0')) {
        el.classList.remove('max-h-0', 'opacity-0', 'overflow-hidden');
        el.classList.add('max-h-96', 'opacity-100');
        arrow.classList.add('rotate-180');
    } else {
        el.classList.add('max-h-0', 'opacity-0', 'overflow-hidden');
        el.classList.remove('max-h-96', 'opacity-100');
        arrow.classList.remove('rotate-180');
    }
}
</script>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #0f172a;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #475569;
}
</style>