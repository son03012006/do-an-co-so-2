<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kết nối database
require_once __DIR__ . '/../../Config/database.php';
require_once __DIR__ . '/../../Config/config.php';

// Kiểm tra đăng nhập
if (empty($_SESSION['login'])) {
    echo json_encode(['status' => 'error', 'msg' => 'Chưa đăng nhập']);
    exit;
}

global $dbh;
$email = $_SESSION['login'];
$studentId = $_SESSION['stdid'] ?? null;

// Nếu chưa có studentId trong session, thử lấy lại từ DB (dự phòng)
if (!$studentId) {
    // Logic lấy ID nếu cần, hoặc trả lỗi
    echo json_encode(['status' => 'error', 'msg' => 'Không có ID sinh viên']);
    exit;
}

$action = $_POST['action'] ?? '';

// --- 1. LẤY TIN NHẮN ---
if ($action == 'get_messages') {
    try {
        $sql = "SELECT * FROM tblmessages WHERE student_id = :sid ORDER BY created_at ASC";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([':sid' => $studentId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Cập nhật trạng thái đã xem (logic cũ của bạn)
        // Lưu ý: Thường thì sinh viên xem tin nhắn của ADMIN thì update tin của Admin thành đã đọc
        // Câu lệnh dưới đây đang update tin của 'student' thành đã đọc.
        $update = "UPDATE tblmessages SET is_read = 1 WHERE student_id = :sid AND sender_type = 'student'";
        $upd = $dbh->prepare($update);
        $upd->execute([':sid' => $studentId]);
        
        echo json_encode(['status' => 'success', 'data' => $messages]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
    }
}

// --- 2. GỬI TIN NHẮN (Đã thêm xử lý ảnh) ---
elseif ($action == 'send_message') {
    $msg = trim($_POST['message'] ?? '');
    $filePath = null; // Mặc định không có ảnh

    // Xử lý Upload Ảnh
    if (isset($_FILES['image'])) {
        // Kiểm tra lỗi upload file
        if ($_FILES['image']['error'] !== 0 && $_FILES['image']['error'] !== 4) {
            // Error code 4 = UPLOAD_ERR_NO_FILE (bình thường khi không chọn file)
            $upload_errors = [
                1 => 'File quá lớn (vượt quá upload_max_filesize)',
                2 => 'File quá lớn (vượt quá MAX_FILE_SIZE)',
                3 => 'Chỉ một phần của file được tải lên',
                6 => 'Thư mục tạm bị thiếu',
                7 => 'Không thể ghi file',
                8 => 'Phần mở rộng PHP dừng tải file'
            ];
            $error_msg = $upload_errors[$_FILES['image']['error']] ?? 'Lỗi upload không xác định';
            echo json_encode(['status' => 'error', 'msg' => $error_msg]);
            exit;
        }
        
        // Nếu có file được chọn (error === 0)
        if ($_FILES['image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            // Kiểm tra đuôi file
            if (!in_array($fileExt, $allowed)) {
                echo json_encode(['status' => 'error', 'msg' => 'Định dạng file không được phép. Chỉ hỗ trợ: jpg, jpeg, png, gif, webp']);
                exit;
            }
            
            // Kiểm tra kích thước (< 5MB)
            if ($fileSize > 5000000) {
                echo json_encode(['status' => 'error', 'msg' => 'File quá lớn. Tối đa 5MB']);
                exit;
            }
            
            // Tạo tên file duy nhất
            $newFilename = time() . '_' . uniqid() . '.' . $fileExt;
            $rootDir = dirname(dirname(dirname(__DIR__)));
            $uploadDir = $rootDir . '/public/assets/uploads/chat/';
            
            // Tạo thư mục nếu chưa có
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    echo json_encode(['status' => 'error', 'msg' => 'Không thể tạo thư mục upload. Kiểm tra folder permission']);
                    exit;
                }
            }

            // Di chuyển file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFilename)) {
                $filePath = 'public/assets/uploads/chat/' . $newFilename;
            } else {
                // move_uploaded_file thất bại
                if (!is_writable($uploadDir)) {
                    echo json_encode(['status' => 'error', 'msg' => 'Thư mục upload không có quyền ghi']);
                } else if (!is_uploaded_file($_FILES['image']['tmp_name'])) {
                    echo json_encode(['status' => 'error', 'msg' => 'File không phải là file tải lên hợp lệ']);
                } else {
                    echo json_encode(['status' => 'error', 'msg' => 'Lỗi di chuyển file']);
                }
                exit;
            }
        }
    }

    // Chỉ lưu nếu có tin nhắn text HOẶC có đường dẫn ảnh
    if (!empty($msg) || !empty($filePath)) {
        try {
            $sql = "INSERT INTO tblmessages (student_id, sender_type, message, file_path, is_read, created_at) 
                    VALUES (:sid, 'student', :msg, :fpath, 0, NOW())";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                ':sid'   => $studentId,
                ':msg'   => $msg,     
                ':fpath' => $filePath   
            ]);

            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Nội dung trống']);
    }
}
else {
    echo json_encode(['status' => 'error', 'msg' => 'Action không hợp lệ']);
}
?>