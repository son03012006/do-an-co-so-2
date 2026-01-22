<?php
require_once __DIR__ . '/../../Model/AdminModel/AdminAuthModel.php';

class AdminAuthController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ===== FORM ĐĂNG NHẬP ADMIN ===== */
    public function index()
    {
        global $dbh;
        $model = new AdminAuthModel($dbh);
        $error = null;

        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = md5($_POST['password']);

            $admin = $model->login($username, $password);

            if ($admin) {
                $_SESSION['alogin'] = $admin->UserName;

                // ✅ LOGIN THÀNH CÔNG → DASHBOARD
                header('Location: index.php?c=admindashboard&a=index');
                exit;
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
            }
        }

        $this->loadView('auth/dangnhapadmin', [
            'error' => $error
        ]);
    }

    /* ===== ĐĂNG XUẤT ===== */
    public function logout()
    {
        unset($_SESSION['alogin']);
        session_destroy();
        header('Location: index.php?c=adminauth&a=index');
        exit;
    }

    private function loadView($view, $data = [])
    {
        extract($data);
        include __DIR__ . '/../../View/' . $view . '.php';
    }
}
