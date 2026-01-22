<?php
require_once __DIR__ . '/../../Model/AdminModel/AdminFineModel.php';

class AdminFineController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ===== FORM THIẾT LẬP MỨC PHẠT ===== */
    public function index()
    {
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php?c=adminauth&a=index');
            exit;
        }

        global $dbh;
        $model = new AdminFineModel($dbh);

        if (isset($_POST['update'])) {
            $fine = trim($_POST['finetf']);

            if ($model->updateFine($fine)) {
                $_SESSION['msg'] = "Cập nhật mức phạt thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra!";
            }

            header('Location: index.php?c=fine&a=index');
            exit;
        }

        $this->loadView('admin/fine', [
            'fineData' => $model->getFine()
        ]);
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
