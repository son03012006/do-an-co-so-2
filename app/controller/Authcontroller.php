<?php

require_once __DIR__ . '/../Model/AuthModel.php';

class AuthController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ================= ĐĂNG NHẬP ================= */
    public function index()
    {
        global $dbh;
        $model = new AuthModel($dbh);

        $error = null;

        if (isset($_POST['login'])) {
            $email = trim($_POST['emailid']);
            $password = md5($_POST['password']);

            $user = $model->login($email, $password);

            if ($user) {
                if ($user->Status == 1) {
                    $_SESSION['login']    = $email;
                    $_SESSION['stdid']    = $user->StudentId;
                    $_SESSION['username'] = $user->FullName;

                    header('Location: index.php?c=books&a=index');
                    exit;
                } else {
                    $error = "Tài khoản của bạn đã bị khóa!";
                }
            } else {
                $error = "Sai thông tin đăng nhập hoặc mật khẩu!";
            }
        }

        $this->loadView('auth/dangnhap', [
            'error' => $error
        ]);
    }

    /* ================= ĐĂNG KÝ ================= */
    public function register()
    {
        global $dbh;
        $model = new AuthModel($dbh);

        $success = null;
        $error   = null;

        if (isset($_POST['signup'])) {
            $name  = trim($_POST['fullname']);
            $phone = trim($_POST['mobileno']);
            $email = trim($_POST['email']);
            $pass  = $_POST['password'];
            $confirmpass = $_POST['confirmpassword'];

            // 1. Kiểm tra mật khẩu
            if ($pass !== $confirmpass) {
                $error = "Mật khẩu xác nhận không khớp!";
            } else {
                // 2. Kiểm tra trùng lặp Email/SĐT trước khi làm bất cứ việc gì khác
                $stmt = $dbh->prepare("SELECT id FROM tblstudents WHERE EmailId = :email OR MobileNumber = :phone LIMIT 1");
                $stmt->execute(['email' => $email, 'phone' => $phone]);

                if ($stmt->rowCount() > 0) {
                    $error = "Email hoặc Số điện thoại này đã được sử dụng!";
                } else {
                    // 3. Sinh mã sinh viên tự động từ DB (An toàn hơn file .txt)
                    $query = $dbh->query("SELECT StudentId FROM tblstudents WHERE StudentId LIKE 'SID%' ORDER BY CAST(SUBSTRING(StudentId, 4) AS UNSIGNED) DESC LIMIT 1");
                    $last = $query->fetch(PDO::FETCH_OBJ);

                    $num = 1;
                    if ($last) {
                        $num = (int)substr($last->StudentId, 3) + 1;
                    }
                    $sid = "SID" . str_pad($num, 3, '0', STR_PAD_LEFT);

                    // 4. Lưu vào Database
                    if ($model->register($sid, $name, $phone, $email, md5($pass))) {
                        $success = "Đăng ký thành công! Mã sinh viên của bạn là: $sid";
                        // Clear POST để tránh lặp dữ liệu khi F5
                        $_POST = [];
                    } else {
                        $error = "Đã xảy ra lỗi hệ thống. Vui lòng thử lại!";
                    }
                }
            }
        }

        $this->loadView('auth/dangki', [
            'success' => $success,
            'error'   => $error
        ]);
    }

    /* ================= ĐĂNG XUẤT ================= */
    public function logout()
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        header('Location: index.php?c=auth&a=index');
        exit;
    }

    private function loadView($view, $data = [])
    {
        extract($data);
        include __DIR__ . '/../View/' . $view . '.php';
    }
}
