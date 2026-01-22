<?php
require_once __DIR__ . '/../../Model/AdminModel/ReportModel.php';

class AdminReportController
{
    private $dbh;

    public function __construct()
    {
        global $dbh; // Lấy kết nối DB từ file config
        $this->dbh = $dbh;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ===== TRANG CHỌN LOẠI BÁO CÁO (Giữ nguyên code cũ của bạn) ===== */
    public function index()
    {
        if (empty($_SESSION['alogin'])) {
              header('Location: ' . BASE_URL . 'index.php?c=adminauth&a=index');
            exit;
        }

        if (isset($_POST['view'])) {
            $type = $_POST['type'];
            if ($type === 'userwise') {
                    header('Location: ' . BASE_URL . 'index.php?c=report&a=userwise');
                exit;
            }
            if ($type === 'overdue') {
                    header('Location: ' . BASE_URL . 'index.php?c=report&a=overdue');
                exit;
            }
        }
        $this->loadView('admin/report');
    }

    /* ===== 1. BÁO CÁO SÁCH QUÁ HẠN ===== */
    public function overdue()
    {
        $model = new ReportModel($this->dbh);
        $dataReport = $model->getOverdueBooks();

        // Gửi dữ liệu sang View
        $data = [
            'title' => 'Báo cáo sách quá hạn',
            'reportType' => 'overdue',
            'rows' => $dataReport
        ];
        $this->loadView('admin/report_result', $data);
    }

    /* ===== 2. BÁO CÁO THEO NGƯỜI DÙNG ===== */
    public function userwise()
    {
        // Nếu chưa nhập mã sinh viên -> Hiển thị form nhập
        if (!isset($_GET['sid']) && !isset($_POST['sid'])) {
            $this->loadView('admin/report_user_input');
            return;
        }

        // Nếu đã có mã SV -> Lấy dữ liệu
        $studentId = isset($_POST['sid']) ? $_POST['sid'] : $_GET['sid'];
        
        $model = new ReportModel($this->dbh);
        $dataReport = $model->getHistoryByStudent($studentId);
        $studentName = $model->getStudentName($studentId);

        $data = [
            'title' => 'Lịch sử mượn trả: ' . $studentName . ' (' . $studentId . ')',
            'reportType' => 'userwise',
            'rows' => $dataReport
        ];
        $this->loadView('admin/report_result', $data);
    }

    private function loadView($view, $data = [])
    {
        extract($data);
        // Đường dẫn file view
            $file = __DIR__ . '/../../View/' . $view . '.php';
        if (file_exists($file)) {
            include $file;
        } else {
            echo "Lỗi: Không tìm thấy view " . $view;
        }
    }
}
?>