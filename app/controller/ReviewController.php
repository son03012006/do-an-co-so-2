<?php
require_once __DIR__ . '/../Model/ReviewModel.php';

class ReviewController
{
    /**
     * ACTION: INDEX
     * Nhiệm vụ: Lấy dữ liệu và hiển thị nội dung bên trong Modal (HTML)
     * Được gọi qua AJAX: index.php?c=review&a=index&bookid=...
     */
    public function index()
    {
        // 1. Kiểm tra session (nếu chưa start ở index.php gốc thì start, nếu rồi thì thôi)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        global $dbh; // Sử dụng kết nối DB toàn cục

        $bookId = isset($_GET['bookid']) ? intval($_GET['bookid']) : 0;
        $studentId = $_SESSION['stdid'] ?? '';

        $model = new ReviewModel($dbh);

        // 2. Lấy danh sách đánh giá cũ để hiển thị phía dưới form
        $reviews = $model->getReviewsByBook($bookId);

        // 3. Kiểm tra quyền đánh giá (Trả về mảng ['allowed'=>bool, 'msg'=>string])
        // Hàm này thay thế cho canReview và hasReviewed cũ
        $eligibility = $model->checkEligibility($bookId, $studentId);

        // 4. Load View (Chỉ load phần nội dung modal, không load header/footer)
        // Đường dẫn này trỏ tới file modal_content.php bạn đã tạo ở bước View
        if (file_exists(__DIR__ . '/../View/books/reviewbook.php')) {
            include __DIR__ . '/../View/books/reviewbook.php';
        } else {
            echo "Lỗi: Không tìm thấy file view reviewbook.php";
        }
    }

    /**
     * ACTION: SUBMIT
     * Nhiệm vụ: Nhận dữ liệu từ Form và lưu vào Database
     */
    public function submit()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        global $dbh;

        // 1. Check đăng nhập
        if (empty($_SESSION['login'])) {
            echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='index.php';</script>";
            exit;
        }

        // 2. Lấy dữ liệu từ POST
        $bookId = intval($_POST['bookid']);
        $rating = intval($_POST['rating']);
        $comment = trim($_POST['comment']);
        $studentId = $_SESSION['stdid'];

        $model = new ReviewModel($dbh);

        // 3. Check lại quyền lần cuối (Backend validation - bảo mật)
        $check = $model->checkEligibility($bookId, $studentId);
        
        if (!$check['allowed']) {
            // Nếu hack form hoặc cố tình gửi khi không đủ quyền
            echo "<script>alert('" . $check['msg'] . "'); window.history.back();</script>";
            exit;
        }

        // 4. Lưu vào DB
        if ($model->addReview($bookId, $studentId, $rating, $comment)) {
            echo "<script>
                alert('Đánh giá thành công! Cảm ơn đóng góp của bạn.');
                window.location.href='index.php?c=books'; 
            </script>";
        } else {
            echo "<script>
                alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
                window.history.back();
            </script>";
        }
    }
}
?>