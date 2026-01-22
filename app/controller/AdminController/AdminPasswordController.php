<?php
require_once __DIR__ . '/../../Model/AdminModel/AdminPasswordModel.php';

class AdminPasswordController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php?c=adminauth&a=index');
            exit;
        }

        global $dbh;
        $model = new AdminPasswordModel($dbh);

        $msg = null;
        $error = null;

        if (isset($_POST['change'])) {
            $password    = md5($_POST['password']);
            $newpassword = md5($_POST['newpassword']);
            $username    = $_SESSION['alogin'];

            if ($model->checkPassword($username, $password)) {
                $model->updatePassword($username, $newpassword);
                $msg = "Đổi mật khẩu thành công!";
            } else {
                $error = "Mật khẩu hiện tại không chính xác!";
            }
        }

        $this->loadView('admin/doimatkhau', [
            'msg'   => $msg,
            'error' => $error
        ]);
    }

    private function loadView($view, $data = [])
    {
        extract($data);
        include __DIR__ . '/../../View/' . $view . '.php';
    }
}
