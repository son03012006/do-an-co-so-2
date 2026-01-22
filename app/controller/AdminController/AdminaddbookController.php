<?php
require_once __DIR__ . '/../../Model/AdminModel/AdminaddbookModel.php';

class AdminaddbookController
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
        $model = new AdminaddbookModel($dbh);

        $msg = null;
        if (isset($_SESSION['msg'])) {
            $msg = $_SESSION['msg'];
            unset($_SESSION['msg']);
        }

        $this->loadView('admin/addbook', [
            'categories' => $model->getCategories(),
            'authors'    => $model->getAuthors(),
            'msg'        => $msg,
            'error'      => null
        ]);
    }

    /* ===== HÀM ADD ĐÃ CHỈNH SỬA PATH ===== */
    public function add()
    {
        if (empty($_SESSION['alogin'])) {
            header('Location: index.php?c=adminauth&a=index');
            exit;
        }

        global $dbh;
        $model = new AdminaddbookModel($dbh);
        $error = null;

        if (isset($_POST['add'])) {
            
            // 1. XỬ LÝ ẢNH
            $bookImageName = ""; 
            
            if (isset($_FILES['bookimg']) && $_FILES['bookimg']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['bookimg']['name'];
                $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($file_ext, $allowed)) {
                    // Đặt tên file ngẫu nhiên để tránh trùng
                    $new_name = "book_" . time() . "_" . rand(100,999) . "." . $file_ext;
                    
                    // --- QUAN TRỌNG: ĐƯỜNG DẪN THEO CẤU TRÚC ẢNH CỦA BẠN ---
                    // Dựa vào ảnh, thư mục là: public -> assets -> img -> book
                    $upload_path = "public/assets/img/";
                    
                    // Kiểm tra nếu thư mục chưa có thì tạo mới
                    if (!file_exists($upload_path)) {
                        mkdir($upload_path, 0777, true);
                    }

                    $destination = $upload_path . $new_name;

                    if (move_uploaded_file($_FILES['bookimg']['tmp_name'], $destination)) {
                        $bookImageName = $new_name; // Chỉ lưu tên file vào DB
                    } else {
                        $error = "Lỗi: Không thể di chuyển file vào thư mục public/assets/img/";
                    }
                } else {
                    $error = "Chỉ chấp nhận file ảnh (JPG, PNG, GIF).";
                }
            }

            // 2. LƯU VÀO DATABASE
            if (!$error) {
                $data = [
                    ':bookname' => $_POST['bookname'],
                    ':category' => $_POST['category'],
                    ':author'   => $_POST['author'],
                    ':isbn'     => $_POST['isbn'],
                    ':price'    => $_POST['price'],
                    ':copies'   => $_POST['copies'],
                    ':bookImage'=> $bookImageName // Truyền tên ảnh sang Model
                ];

                if ($model->create($data)) {
                    $_SESSION['msg'] = "Thêm sách thành công!";
                    header('Location: index.php?c=managerbook&a=index');
                    exit;
                } else {
                    $error = "Lỗi Database: Không thể thêm sách.";
                }
            }
        }

        $this->loadView('admin/addbook', [
            'categories' => $model->getCategories(),
            'authors'    => $model->getAuthors(),
            'error'      => $error
        ]);
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
?>