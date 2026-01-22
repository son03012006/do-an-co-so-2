<?php
// Lấy controller hiện tại từ URL để active menu
$c = $_GET['c'] ?? 'books'; // Mặc định là books nếu không có biến c
?>

<aside class="fixed top-0 left-0 h-full w-64 bg-white border-r border-slate-200 shadow-xl z-50 flex flex-col transition-all duration-300">
    
    <div class="h-20 flex items-center justify-center border-b border-slate-100 bg-white">
        <a href="<?= BASE_URL ?>index.php" class="flex items-center gap-2 no-underline">
            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                <i class="fa fa-book-open text-xl"></i>
            </div>
            <span class="text-xl font-extrabold text-slate-800 tracking-tight">ThuVien<span class="text-indigo-600">So</span></span>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 flex flex-col gap-2 px-3">
        
        <?php 
            // STYLE CHUNG CHO MENU
            $activeClass = "bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200";
            $normalClass = "text-slate-500 hover:bg-slate-50 hover:text-indigo-600";
        ?>

        <?php $isActive = ($c == 'books' || $c == ''); ?>
        <a href="<?= BASE_URL ?>index.php?c=books&a=index" 
           class="flex items-center px-4 py-3.5 rounded-xl font-medium transition-all duration-200 group <?= $isActive ? $activeClass : $normalClass ?>">
            <i class="fa fa-home w-6 text-center text-lg <?= $isActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' ?>"></i>
            <span class="ml-3">Trang chủ</span>
        </a>

        <?php $isActive = ($c == 'borrow'); ?>
        <a href="<?= BASE_URL ?>index.php?c=borrow&a=index" 
           class="flex items-center px-4 py-3.5 rounded-xl font-medium transition-all duration-200 group <?= $isActive ? $activeClass : $normalClass ?>">
            <i class="fa fa-book-reader w-6 text-center text-lg <?= $isActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' ?>"></i>
            <span class="ml-3">Sách đang mượn</span>
        </a>
        <?php $isActive = ($c == 'profile'); ?>
        <a href="<?= BASE_URL ?>index.php?c=profile&a=index" 
           class="flex items-center px-4 py-3.5 rounded-xl font-medium transition-all duration-200 group <?= $isActive ? $activeClass : $normalClass ?>">
            <i class="fa fa-user-circle w-6 text-center text-lg <?= $isActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' ?>"></i>
            <span class="ml-3">Hồ sơ cá nhân</span>
        </a>
        <?php $isActive = ($c == 'chat'); ?>
        <a href="<?= BASE_URL ?>index.php?c=chat&a=index" 
           class="flex items-center px-4 py-3.5 rounded-xl font-medium transition-all duration-200 group <?= $isActive ? $activeClass : $normalClass ?>">
            <i class="fa fa-comments w-6 text-center text-lg <?= $isActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' ?>"></i>
            <span class="ml-3">Hỗ trợ & Chat</span>
        </a>
        <?php $isActive = ($c == 'password'); ?>
        <a href="<?= BASE_URL ?>index.php?c=password&a=index" 
           class="flex items-center px-4 py-3.5 rounded-xl font-medium transition-all duration-200 group <?= $isActive ? $activeClass : $normalClass ?>">
            <i class="fa fa-shield-alt w-6 text-center text-lg <?= $isActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' ?>"></i>
            <span class="ml-3">Đổi mật khẩu</span>
        </a>
    </nav>
    <div class="p-4 border-t border-slate-100">
        <a href="<?= BASE_URL ?>index.php?c=auth&a=logout" 
           class="flex items-center justify-center w-full px-4 py-3 rounded-xl text-red-600 bg-red-50 hover:bg-red-100 hover:shadow-md transition-all font-bold">
            <i class="fa fa-sign-out-alt mr-2"></i>
            Đăng xuất
        </a>
    </div>

</aside>