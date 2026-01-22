<?php
require_once __DIR__ . '/../../Model/AdminModel/ManagerCategoryModel.php';

class ManagerCategoryController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        // 1. Kiểm tra đăng nhập Admin
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php?c=adminauth&a=index');
            exit;
        }

        global $dbh; // Sử dụng biến kết nối DB toàn cục

        // ==========================================
        // LOGIC PHÂN TRANG (PAGINATION)
        // ==========================================
        
        $limit = 10; // Giới hạn 10 mục mỗi trang
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; // Trang hiện tại (mặc định là 1)
        $offset = ($page - 1) * $limit; // Vị trí bắt đầu lấy dữ liệu

        // Bước 1: Đếm tổng số bản ghi (Để tính tổng số trang)
        $sqlCount = "SELECT COUNT(id) as total FROM tblcategory";
        $queryCount = $dbh->prepare($sqlCount);
        $queryCount->execute();
        $totalRows = $queryCount->fetch(PDO::FETCH_OBJ)->total;
        
        // Tính tổng số trang (làm tròn lên)
        $totalPages = ceil($totalRows / $limit);

        // Bước 2: Lấy dữ liệu phân trang (Thêm LIMIT và OFFSET)
        $sql = "SELECT * FROM tblcategory ORDER BY CreationDate DESC LIMIT :offset, :limit";
        $query = $dbh->prepare($sql);
        $query->bindValue(':offset', $offset, PDO::PARAM_INT);
        $query->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query->execute();
        $categories = $query->fetchAll(PDO::FETCH_OBJ);

        // ==========================================

        // ✅ GỬI SANG VIEW (Kèm các biến phân trang)
        $this->loadView('admin/managercategory', [
            'categories' => $categories,
            'page'       => $page,
            'totalPages' => $totalPages,
            'totalRows'  => $totalRows
        ]);
    }

    public function delete()
    {
        global $dbh;
        $model = new ManagerCategoryModel($dbh);

        if (isset($_GET['id'])) {
            // Thực hiện xóa
            $model->delete($_GET['id']);
            
            // Tạo thông báo session để hiển thị ở View
            $_SESSION['msg'] = "Xóa danh mục thành công!";
        }

        header('Location: index.php?c=managercategory&a=index');
        exit;
    }

    private function loadView($view, $data = [])
    {
        extract($data);
        // Kiểm tra file view tồn tại để tránh lỗi
        $viewPath = __DIR__ . '/../../View/' . $view . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "Lỗi: Không tìm thấy file view: $view";
        }
    }
}