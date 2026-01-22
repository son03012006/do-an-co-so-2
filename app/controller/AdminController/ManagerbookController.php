<?php
// =======================================================
// CONTROLLER: MANAGER BOOK (Quản lý sách)
// =======================================================

// Load các Model cần thiết
// Lưu ý: Đảm bảo các file này tồn tại đúng đường dẫn
require_once __DIR__ . '/../../Model/AdminModel/ManagerBookModel.php';
require_once __DIR__ . '/../../Model/AdminModel/CategoryModel.php';
require_once __DIR__ . '/../../Model/AdminModel/AdminAuthorModel.php';

class ManagerBookController
{
    // Biến kết nối DB dùng chung trong class
    private $db;

    public function __construct()
    {
        // 1. Khởi động Session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Lấy kết nối DB từ biến toàn cục (được include từ config/db.php ở index)
        global $dbh;
        $this->db = $dbh;
    }

    /**
     * ACTION: INDEX (Hiển thị danh sách)
     */
    public function index()
    {
        // Check Login Admin
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php?c=adminauth&a=index');
            exit;
        }

        // Khởi tạo Model
        $bookModel = new ManagerBookModel($this->db);
        $catModel  = new CategoryModel($this->db);
        $authModel = new AdminAuthorModel($this->db);

        // --- LOGIC PHÂN TRANG ---
        $limit = 10; 
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $offset = ($page - 1) * $limit;

        // Đếm tổng số sách
        $totalRows = $bookModel->countAll();
        // Tính tổng số trang
        $totalPages = ceil($totalRows / $limit);

        // Lấy danh sách sách (kèm Offset, Limit)
        $books = $bookModel->getAll($offset, $limit);

        // --- LẤY DỮ LIỆU CHO DROPDOWN (MODAL SỬA) ---
        $categories = $catModel->getAll(); 
        $authors    = $authModel->getAll();

        // Load View
        $this->loadView('admin/managerbook', [
            'books'      => $books,
            'categories' => $categories,
            'authors'    => $authors,
            'page'       => $page,
            'totalPages' => $totalPages,
            'totalRows'  => $totalRows
        ]);
    }

    /**
     * ACTION: UPDATE (Cập nhật thông tin sách)
     */
    public function update()
    {
        // Check Login
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php?c=adminauth&a=index');
            exit;
        }

        if (isset($_POST['update'])) {
            $model = new ManagerBookModel($this->db);

            // Lấy và làm sạch dữ liệu
            $id     = intval($_POST['id']);
            $name   = trim($_POST['bookname']);
            $catId  = intval($_POST['category']);
            $authId = intval($_POST['author']);
            $isbn   = trim($_POST['isbn']);
            $price  = floatval($_POST['price']); // Dùng float cho giá tiền
            $copies = intval($_POST['copies']);

            // Validate cơ bản
            if ($id > 0 && !empty($name)) {
                // Gọi Model cập nhật
                if ($model->update($id, $name, $catId, $authId, $isbn, $price, $copies)) {
                    $_SESSION['msg'] = "Cập nhật sách thành công!";
                } else {
                    $_SESSION['error'] = "Lỗi Database: Không thể cập nhật sách.";
                }
            } else {
                $_SESSION['error'] = "Lỗi: Tên sách không được để trống.";
            }
        }

        // Quay lại trang danh sách
        header('Location: index.php?c=managerbook&a=index');
        exit;
    }

    /**
     * ACTION: DELETE (Xóa sách)
     */
    public function delete()
    {
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php?c=adminauth&a=index');
            exit;
        }

        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "Không tìm thấy ID sách cần xóa.";
            header('Location: index.php?c=managerbook&a=index');
            exit;
        }

        $id = intval($_GET['id']);
        $model = new ManagerBookModel($this->db);

        // Gọi hàm xóa trong Model (lưu ý Model cần xử lý xóa ảnh nếu cần)
        if ($model->delete($id)) {
            $_SESSION['msg'] = "Xóa sách thành công!";
        } else {
            $_SESSION['error'] = "Lỗi: Không thể xóa sách (Có thể sách đang được mượn).";
        }

        header('Location: index.php?c=managerbook&a=index');
        exit;
    }

    /**
     * HELPER: Load View
     */
    private function loadView($viewPath, $data = [])
    {
        // Chuyển mảng data thành các biến riêng biệt
        extract($data);

        // Tạo đường dẫn file view
        $file = __DIR__ . '/../../View/' . $viewPath . '.php';

        if (file_exists($file)) {
            require $file;
        } else {
            // Thông báo lỗi rõ ràng nếu sai đường dẫn view
            echo "<div style='background:red; color:white; padding:10px;'>ERROR: Không tìm thấy View: <b>$file</b></div>";
            exit;
        }
    }
}
?>