<?php

require_once __DIR__ . '/../Model/PasswordModel.php';

class PasswordController
{
    public function index()
    {
        if (empty($_SESSION['login'])) {
            header('Location: index.php');
            exit;
        }

        global $dbh;
        $model = new PasswordModel($dbh);

        $msg = '';
        $error = '';

        if (isset($_POST['change'])) {
            $email       = $_SESSION['login'];
            $password    = md5($_POST['password']);
            $newpassword = md5($_POST['newpassword']);

            if ($model->checkCurrentPassword($email, $password)) {
                $model->updatePassword($email, $newpassword);
                $msg = "Đổi mật khẩu thành công!";
            } else {
                $error = "Mật khẩu hiện tại không đúng!";
            }
        }

        $this->loadView('books/Password', compact('msg', 'error'));
    }

    private function loadView($view, $data = [])
    {
        extract($data);
        include __DIR__ . '/../View/' . $view . '.php';
    }
}
