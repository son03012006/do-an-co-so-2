<?php
require_once __DIR__ . '/../../Model/AdminModel/AdminAuthorModel.php';

class AdminAuthorController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * ACTION: THÊM MỚI (ADD)
     * - Logic: Thêm xong GIỮ NGUYÊN trang để nhập tiếp
     */
    public function add()
    {
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php?c=adminauth&a=index'); 
            exit;
        }

        global $dbh;
        $model = new AdminAuthorModel($dbh);
        
        $error = null;
        $msg = null; 

        if (isset($_POST['create'])) {
            $author = trim($_POST['author']);

            if (empty($author)) {
                $error = "Tên tác giả không được để trống!";
            } else {
                if ($model->create($author)) {
                    // Thành công: Gán thông báo và KHÔNG chuyển trang
                    $msg = "Thêm nhà xuất bản / tác giả thành công!";
                } else {
                    $error = "Có lỗi xảy ra, vui lòng thử lại!";
                }
            }
        }

        // Load lại View Add
        $this->loadView('admin/authors', [
            'error' => $error,
            'msg'   => $msg
        ]);
    }

    /**
     * ACTION: CẬP NHẬT (UPDATE)
     * - Logic: Sửa xong QUAY VỀ trang danh sách (Manager) để cập nhật bảng
     */
    public function update()
    {
        // 1. Check Login
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php?c=adminauth&a=index');
            exit;
        }

        // 2. Xử lý dữ liệu từ Modal gửi sang
        if (isset($_POST['update'])) {
            global $dbh;
            $model = new AdminAuthorModel($dbh);

            $id = intval($_POST['id']);
            $name = trim($_POST['author']);

            if ($id > 0 && !empty($name)) {
                if ($model->update($id, $name)) {
                    $_SESSION['msg'] = "Cập nhật thành công!";
                } else {
                    $_SESSION['msg'] = "Lỗi: Không thể cập nhật!";
                }
            } else {
                $_SESSION['msg'] = "Lỗi: Tên không hợp lệ!";
            }
        }

        // 3. Quay về trang danh sách (ManagerAuthor)
        header('Location: index.php?c=managerauthor&a=index');
        exit;
    }

    /**
     * ACTION: XÓA (DELETE)
     * - Logic: Xóa xong QUAY VỀ trang danh sách (Manager)
     */
    public function delete()
    {
        // 1. Check Login
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php?c=adminauth&a=index');
            exit;
        }

        global $dbh;
        $model = new AdminAuthorModel($dbh);
        
        // 2. Lấy ID từ URL
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id > 0) {
            if ($model->delete($id)) {
                $_SESSION['msg'] = "Xóa thành công!";
            } else {
                $_SESSION['msg'] = "Lỗi: Không thể xóa dữ liệu này!";
            }
        }

        // 3. Quay về trang danh sách (ManagerAuthor)
        header('Location: index.php?c=managerauthor&a=index');
        exit;
    }

    // Helper load view
    private function loadView($view, $data = [])
    {
        $file = __DIR__ . '/../../View/' . $view . '.php';

        if (!file_exists($file)) {
            die("❌ Không tìm thấy view: $file");
        }

        extract($data);
        include $file;
    }
}
?>