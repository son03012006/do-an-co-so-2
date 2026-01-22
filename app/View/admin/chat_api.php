<?php
// =====================================================
// CHAT API – ADMIN
// FILE: app/View/admin/chat_api.php
// =====================================================

// 1. Header + UTF-8
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);
ini_set('display_errors', 0);

// 2. Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Kết nối DB
$db_file = __DIR__ . '/../../Config/database.php';
if (file_exists($db_file)) {
    require_once $db_file;
}

if (!isset($dbh)) {
    try {
        $dbh = new PDO(
            "mysql:host=localhost;dbname=library;charset=utf8mb4",
            "root",
            "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'msg' => 'Lỗi kết nối CSDL']);
        exit;
    }
}

// 4. Check đăng nhập admin
if (empty($_SESSION['alogin'])) {
    echo json_encode(['status' => 'error', 'msg' => 'Phiên đăng nhập hết hạn']);
    exit;
}

$action = $_POST['action'] ?? '';

// =====================================================
// ACTION: GET CONTACTS
// =====================================================
if ($action === 'get_contacts') {
    try {
        $sql = "
            SELECT 
                s.StudentId,
                s.FullName,
                s.ProfileImage,

                (SELECT COUNT(*) 
                 FROM tblmessages m 
                 WHERE m.student_id = s.StudentId 
                   AND m.sender_type = 'student' 
                   AND m.is_read = 0) AS unread,

                (SELECT message 
                 FROM tblmessages m2 
                 WHERE m2.student_id = s.StudentId 
                 ORDER BY m2.created_at DESC 
                 LIMIT 1) AS last_msg,

                (SELECT file_path 
                 FROM tblmessages m3 
                 WHERE m3.student_id = s.StudentId 
                 ORDER BY m3.created_at DESC 
                 LIMIT 1) AS last_file,

                (SELECT created_at 
                 FROM tblmessages m4 
                 WHERE m4.student_id = s.StudentId 
                 ORDER BY m4.created_at DESC 
                 LIMIT 1) AS last_time
            FROM tblstudents s
            ORDER BY COALESCE(last_time, s.RegDate) DESC
        ";

        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        echo json_encode([
            'status' => 'success',
            'data'   => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'msg' => 'Không load được danh sách']);
    }
    exit;
}

// =====================================================
// ACTION: GET MESSAGES
// =====================================================
if ($action === 'get_messages') {
    $student_id = $_POST['student_id'] ?? '';

    if ($student_id === '') {
        echo json_encode(['status' => 'error', 'msg' => 'Thiếu student_id']);
        exit;
    }

    try {
        // Đánh dấu tin sinh viên đã đọc
        $dbh->prepare("
            UPDATE tblmessages 
            SET is_read = 1 
            WHERE student_id = ? AND sender_type = 'student'
        ")->execute([$student_id]);

        // Lấy tin nhắn
        $stmt = $dbh->prepare("
            SELECT * 
            FROM tblmessages 
            WHERE student_id = ?
            ORDER BY created_at ASC
        ");
        $stmt->execute([$student_id]);

        echo json_encode([
            'status' => 'success',
            'data'   => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'msg' => 'Không load được tin nhắn']);
    }
    exit;
}

// =====================================================
// ACTION: SEND MESSAGE (TEXT / IMAGE)
// =====================================================
if ($action === 'send_message') {
    $student_id = $_POST['student_id'] ?? '';
    $message    = trim($_POST['message'] ?? '');
    $filePath   = null;

    if ($student_id === '') {
        echo json_encode(['status' => 'error', 'msg' => 'Thiếu người nhận']);
        exit;
    }

    // ===== KIỂM TRA NỘI DUNG (FIX LỖI TRỐNG) =====
    $hasImage = isset($_FILES['image']) && $_FILES['image']['error'] !== 4;

    if ($message === '' && !$hasImage) {
        echo json_encode(['status' => 'error', 'msg' => 'Nội dung trống']);
        exit;
    }

    // ===== UPLOAD ẢNH =====
    if ($hasImage) {

        if ($_FILES['image']['error'] !== 0) {
            echo json_encode(['status' => 'error', 'msg' => 'Lỗi upload ảnh']);
            exit;
        }

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            echo json_encode(['status' => 'error', 'msg' => 'Định dạng ảnh không hợp lệ']);
            exit;
        }

        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            echo json_encode(['status' => 'error', 'msg' => 'Ảnh vượt quá 5MB']);
            exit;
        }

        // Xác định root project
        $rootDir   = dirname(dirname(dirname(__DIR__)));
        $uploadDir = $rootDir . '/public/assets/uploads/chat/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newName = time() . '_' . uniqid() . '.' . $ext;
        $target  = $uploadDir . $newName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo json_encode(['status' => 'error', 'msg' => 'Không lưu được ảnh']);
            exit;
        }

        $filePath = 'public/assets/uploads/chat/' . $newName;
    }

    // ===== LƯU DATABASE =====
    try {
        $sql = "
            INSERT INTO tblmessages
            (student_id, sender_type, message, file_path, is_read, created_at)
            VALUES (:sid, 'admin', :msg, :file, 0, NOW())
        ";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':sid'  => $student_id,
            ':msg'  => $message,
            ':file' => $filePath
        ]);

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'msg' => 'Lỗi lưu tin nhắn']);
    }
    exit;
}

// =====================================================
echo json_encode(['status' => 'error', 'msg' => 'Action không hợp lệ']);
exit;
