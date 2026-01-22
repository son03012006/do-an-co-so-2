<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Thư viện Trực tuyến</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif;
    }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">

    <header class="bg-white/95 backdrop-blur-sm border-bottom border-slate-200 shadow-sm fixed top-0 w-full z-50">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center h-16 sm:h-20">

                <a href="index.php" class="flex items-center space-x-3 group">
                    <div class="bg-blue-900 p-2 rounded-lg group-hover:bg-blue-800 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.246 18.477 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-bold text-slate-800 leading-none uppercase tracking-tight">Library
                            System</span>
                        <span class="text-xs text-slate-500 font-medium">Hệ thống quản lý trực tuyến</span>
                    </div>
                </a>

                <nav class="flex items-center space-x-2">
                    <?php
                    $current_c = $_GET['c'] ?? '';
?>

                    <a href="<?= BASE_URL ?>index.php?c=auth&a=index" class="px-4 py-2 text-sm font-semibold rounded-lg transition-all 
                   <?= $current_c === 'auth'
       ? 'bg-blue-100 text-blue-700'
       : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                        Người dùng
                    </a>

                    <a href="<?= BASE_URL ?>index.php?c=adminauth&a=index" class="px-4 py-2 text-sm font-semibold rounded-lg transition-all border border-transparent
                   <?= $current_c === 'adminauth'
       ? 'bg-slate-800 text-white'
       : 'bg-blue-900 text-white hover:bg-blue-800 shadow-md shadow-blue-200' ?>">
                        Quản trị viên
                    </a>
                </nav>

            </div>
        </div>
    </header>

    <div class="h-20 sm:h-24"></div>

    <main class="container mx-auto md:ml-64 px-4 py-8">