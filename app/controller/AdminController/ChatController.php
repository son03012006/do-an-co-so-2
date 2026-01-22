<?php
class ChatController {
    public function __construct()
    {
        // Kiểm tra session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        // Kiểm tra đăng nhập Admin
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php'); 
            exit;
        }

        // Đường dẫn tới file View (Giao diện)
        // Đường dẫn tính từ file index.php ở thư mục gốc
        $viewPath = 'app/View/admin/chat.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "Lỗi: Không tìm thấy file view tại " . $viewPath;
        }
    }
}
?>