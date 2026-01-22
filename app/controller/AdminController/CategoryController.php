<?php
require_once __DIR__ . '/../../Model/AdminModel/CategoryModel.php';

class CategoryController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * ACTION: THÊM MỚI (ADD)
     */
    public function add()
    {
        if (empty($_SESSION['alogin'])) {
            header("Location: index.php?c=adminauth&a=index");
            exit;
        }

        global $dbh;
        $model = new CategoryModel($dbh);
        $msg = null;
        $error = null;

        if (isset($_POST['create'])) {
            $category = trim($_POST['category']);
            $status   = (int)$_POST['status'];

            if ($model->exists($category)) {
                $error = "Danh mục đã tồn tại!";
            } else {
                $model->create($category, $status);
                $msg = "Thêm danh mục thành công!";
            }
        }

        $this->loadView('admin/category_add', [
            'msg'   => $msg,
            'error' => $error
        ]);
    }

    /**
     * ACTION: UPDATE (Xử lý dữ liệu từ Modal Sửa gửi về)
     * Thay thế cho hàm edit() cũ
     */
    public function update()
    {
        // 1. Check quyền Admin
        if (empty($_SESSION['alogin'])) {
            header("Location: index.php?c=adminauth&a=index");
            exit;
        }

        // 2. Chỉ xử lý khi có dữ liệu POST gửi lên
        if (isset($_POST['update'])) {
            global $dbh;
            $model = new CategoryModel($dbh);

            // Lấy dữ liệu từ form modal
            $id      = intval($_POST['id']);
            $catName = trim($_POST['category']);
            $status  = (int)$_POST['status'];

            // Validate cơ bản
            if (!empty($catName) && $id > 0) {
                // Gọi model cập nhật
                if ($model->update($id, $catName, $status)) {
                    $_SESSION['msg'] = "Cập nhật danh mục thành công!";
                } else {
                    $_SESSION['msg'] = "Lỗi: Không thể cập nhật danh mục.";
                }
            } else {
                $_SESSION['msg'] = "Lỗi: Tên danh mục không được để trống.";
            }
        }

        // 3. Xử lý xong quay về trang Quản lý (nơi có Modal)
        header("Location: index.php?c=managercategory&a=index");
        exit;
    }

    /**
     * ACTION: XÓA (DELETE)
     */
    public function delete()
    {
        if (empty($_SESSION['alogin'])) {
            header("Location: index.php?c=adminauth&a=index");
            exit;
        }

        global $dbh;
        $model = new CategoryModel($dbh);
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id > 0) {
            $model->delete($id);
            $_SESSION['msg'] = "Xóa danh mục thành công!";
        }

        // Xóa xong quay về trang danh sách
        header("Location: index.php?c=managercategory&a=index");
        exit;
    }

    // Hàm load view helper
    private function loadView($view, $data = [])
    {
        $file = __DIR__ . '/../../View/' . $view . '.php';

        if (!file_exists($file)) {
            die("❌ Lỗi: Không tìm thấy file view tại: $file");
        }

        extract($data);
        include $file;
    }
}
?>