<?php
require_once __DIR__ . '/../../Model/AdminModel/ManagerAuthorModel.php';

class ManagerAuthorController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ===== DANH SÁCH TÁC GIẢ ===== */
    public function index()
    {
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php?c=auth&a=index');
            exit;
        }

        global $dbh;
        $model = new ManagerAuthorModel($dbh);

        // XÓA
        if (isset($_GET['del'])) {
            $model->delete((int)$_GET['del']);
            $_SESSION['delmsg'] = "Xóa nhà xuất bản / tác giả thành công!";
            header('Location: index.php?c=managerauthor&a=index');
            exit;
        }

        $authors = $model->getAll();

        $this->loadView('admin/managerauthors', [
            'authors' => $authors
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
