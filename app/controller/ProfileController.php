<?php
require_once __DIR__ . '/../Model/ProfileModel.php';

class ProfileController
{
    public function index()
    {
        if (empty($_SESSION['login'])) {
            header('Location: index.php');
            exit;
        }

        global $dbh;
        $model = new ProfileModel($dbh);

        $sid = $_SESSION['stdid'];

        /* ================= XỬ LÝ CẬP NHẬT ================= */
        if (isset($_POST['update'])) {

            $fullname = trim($_POST['fullname']);
            $mobileno = trim($_POST['mobileno']);
            $avatarName = null;

            /* ===== UPLOAD AVATAR ===== */
            if (
                isset($_FILES['avatar']) &&
                $_FILES['avatar']['error'] === UPLOAD_ERR_OK
            ) {
                $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                $allow = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($ext, $allow)) {

                    // Thư mục upload tuyệt đối
                    $uploadDir = __DIR__ . '/../../public/assets/img/profile/';

                    // Tạo thư mục nếu chưa có
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $avatarName = 'avatar_' . $sid . '.' . $ext;

                    move_uploaded_file(
                        $_FILES['avatar']['tmp_name'],
                        $uploadDir . $avatarName
                    );
                }
            }

            // Update DB
            $model->updateProfile($sid, $fullname, $mobileno, $avatarName);

            header("Location: index.php?c=profile&a=index&updated=1");
            exit;
        }

        /* ================= LẤY THÔNG TIN ================= */
        $profile = $model->getProfile($sid);

        $this->loadView('books/Profile', [
            'profile' => $profile
        ]);
    }

    private function loadView($view, $data = [])
    {
        $file = __DIR__ . '/../View/' . $view . '.php';

        if (!file_exists($file)) {
            die("Không tìm thấy view: $file");
        }

        extract($data);
        include $file;
    }
}
