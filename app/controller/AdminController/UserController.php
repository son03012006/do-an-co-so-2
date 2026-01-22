<?php
require_once __DIR__ . '/../../Model/AdminModel/UserModel.php';

class UserController
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
        $model = new UserModel($dbh);

        /* KHÓA */
        if (isset($_GET['lock'])) {
            $model->lock($_GET['lock']);
            $_SESSION['msg'] = "Khóa tài khoản thành công!";
            header("Location: index.php?c=user&a=index");
            exit;
        }

        /* MỞ KHÓA */
        if (isset($_GET['unlock'])) {
            $model->unlock($_GET['unlock']);
            $_SESSION['msg'] = "Kích hoạt tài khoản thành công!";
            header("Location: index.php?c=user&a=index");
            exit;
        }

        $users = $model->getAll();

        $this->loadView('admin/quanlytaikhoan', [
            'users' => $users
        ]);
    }

    private function loadView($view, $data = [])
    {
        extract($data);
        include __DIR__ . '/../../View/' . $view . '.php';
    }
}
