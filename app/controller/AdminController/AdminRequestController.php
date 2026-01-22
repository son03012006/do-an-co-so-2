<?php
require_once __DIR__ . '/../../Model/AdminModel/AdminRequestModel.php';

class AdminRequestController
{
    private $dbh;

    public function __construct()
    {
        global $dbh;
        $this->dbh = $dbh;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ===== 1. HIỂN THỊ DANH SÁCH ===== */
    public function index()
    {
        // Check login
        if (empty($_SESSION['alogin'])) {
            header('Location: ' . BASE_URL . 'index.php?c=adminauth&a=index');
            exit;
        }

        $model = new AdminRequestModel($this->dbh);

        $this->loadView('admin/request', [
            'requests' => $model->getAllRequests()
        ]);
    }

    /* ===== 2. XỬ LÝ PHÁT SÁCH (DUYỆT YÊU CẦU) ===== */
    public function issue()
    {
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php?c=adminauth&a=index');
            exit;
        }

        // Kiểm tra xem có ID yêu cầu không
        if (isset($_REQUEST['rid'])) {
            $rid = intval($_REQUEST['rid']);
            $remark = "Admin đã duyệt yêu cầu"; // Mặc định

            $model = new AdminRequestModel($this->dbh);
            
            // Gọi hàm duyệt trong Model
            if ($model->acceptRequest($rid, $remark)) {
                $_SESSION['msg'] = "Duyệt và phát sách thành công!";
            } else {
                $_SESSION['error'] = "Lỗi! Không thể duyệt yêu cầu (Có thể sách không tồn tại hoặc lỗi SQL).";
            }
        }

        // QUAN TRỌNG: Redirect về danh sách yêu cầu (dùng BASE_URL cho đường dẫn tuyệt đối)
        header('Location: ' . BASE_URL . 'index.php?c=request&a=index');
        exit;
    }

    /* ===== 3. XỬ LÝ TỪ CHỐI/HỦY (CANCEL) ===== */
    public function cancel()
    {
        if (empty($_SESSION['alogin'])) {
            header('Location: ' . BASE_URL . 'index.php?c=adminauth&a=index');
            exit;
        }

        if (isset($_REQUEST['rid'])) {
            $rid = intval($_REQUEST['rid']);
            $remark = "Yêu cầu bị từ chối";

            $model = new AdminRequestModel($this->dbh);

            if ($model->rejectRequest($rid, $remark)) {
                $_SESSION['msg'] = "Đã từ chối yêu cầu.";
            } else {
                $_SESSION['error'] = "Lỗi! Không thể từ chối.";
            }
        }

        header('Location: ' . BASE_URL . 'index.php?c=request&a=index');
        exit;
    }

    /* ===== 4. XỬ LÝ XÓA VĨNH VIỄN ===== */
    public function delete()
    {
        if (empty($_SESSION['alogin'])) {
            header('Location: ' . BASE_URL . 'index.php?c=adminauth&a=index');
            exit;
        }

        if (isset($_REQUEST['rid'])) {
            $rid = intval($_REQUEST['rid']);

            $model = new AdminRequestModel($this->dbh);

            if ($model->deleteRequest($rid)) {
                $_SESSION['msg'] = "Đã xóa yêu cầu vĩnh viễn.";
            } else {
                $_SESSION['error'] = "Lỗi! Không thể xóa.";
            }
        }

        header('Location: ' . BASE_URL . 'index.php?c=request&a=index');
        exit;
    }

    /* ===== LOAD VIEW ===== */
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