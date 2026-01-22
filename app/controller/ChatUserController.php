<?php
class ChatUserController {
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        // Kiểm tra đăng nhập
        if (empty($_SESSION['login'])) {
            header('Location: ' . (defined('BASE_URL') ? BASE_URL : '') . 'index.php?c=auth&a=login');
            exit;
        }

        // Load Sidebar
        $sidebar_path = __DIR__ . '/../View/layouts/sidebar.php';
        if (file_exists($sidebar_path)) {
            include $sidebar_path;
        }

        // Load View chat
        $view_path = __DIR__ . '/../View/books/chat.php';
        if (file_exists($view_path)) {
            include $view_path;
        } else {
            die("Lỗi: Không tìm thấy view tại $view_path");
        }
    }
}
?>