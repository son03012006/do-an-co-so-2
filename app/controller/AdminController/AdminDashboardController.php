<?php
require_once __DIR__ . '/../../Model/AdminModel/AdminDashboardModel.php';

class AdminDashboardController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        /* ===== CHƯA LOGIN → VỀ LOGIN ADMIN ===== */
        if (empty($_SESSION['alogin'])) {
            header("Location: index.php?c=adminauth&a=index");
            exit;
        }

        global $dbh;
        $model = new AdminDashboardModel($dbh);

        /* ===== LẤY DỮ LIỆU DASHBOARD ===== */
        $data = [
            'totalBooks'   => $model->countBooks(),
            'totalBorrow'  => $model->countBorrow(),
            'totalReturn'  => $model->countReturned(),
            'totalUsers'   => $model->countUsers(),

            // 🔥 THIẾU DÒNG NÀY
            'topBooks'     => $model->getTopBooks(),
            'overdueBooks' => $model->getOverdueBooks(),

            'borrowChart'  => $model->borrowByMonth(),
            'returnChart'  => $model->returnByMonth()
        ];

        $this->loadView('admin/bangthongkeadmin', $data);
    }

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
