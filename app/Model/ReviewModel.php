<?php
class ReviewModel
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * 1. KIỂM TRA QUYỀN ĐÁNH GIÁ (QUAN TRỌNG)
     * Hàm này trả về mảng ['allowed' => bool, 'msg' => string]
     * để Controller dễ dàng hiển thị thông báo lỗi.
     */
    public function checkEligibility($bookId, $studentId)
    {
        // BƯỚC 1: Kiểm tra xem sinh viên đã từng mượn và ĐÃ TRẢ sách chưa?
        // Điều kiện: Tìm trong bảng mượn trả, kết hợp StudentID, BookID và (ReturnDate không rỗng hoặc ReturnStatus=1)
        $sql1 = "SELECT id FROM tblissuedbookdetails 
                 WHERE BookId = :bid 
                 AND StudentID = :sid 
                 AND (ReturnStatus = 1 OR (ReturnDate IS NOT NULL AND ReturnDate != ''))
                 LIMIT 1";
        
        $q1 = $this->dbh->prepare($sql1);
        $q1->execute([':bid' => $bookId, ':sid' => $studentId]);

        if ($q1->rowCount() == 0) {
            return [
                'allowed' => false, 
                'msg' => 'Bạn chưa mượn cuốn sách này hoặc chưa trả sách (chưa đọc xong).'
            ];
        }

        // BƯỚC 2: Kiểm tra xem sinh viên đã đánh giá cuốn này trước đó chưa?
        // (Tránh spam 1 người đánh giá nhiều lần cho 1 sách)
        $sql2 = "SELECT id FROM tblbookreviews 
                 WHERE BookId = :bid AND StudentId = :sid 
                 LIMIT 1";
        
        $q2 = $this->dbh->prepare($sql2);
        $q2->execute([':bid' => $bookId, ':sid' => $studentId]);

        if ($q2->rowCount() > 0) {
            return [
                'allowed' => false, 
                'msg' => 'Bạn đã đánh giá cuốn sách này rồi.'
            ];
        }

        // Nếu qua được cả 2 bước trên -> Đủ điều kiện
        return ['allowed' => true, 'msg' => ''];
    }

    /**
     * 2. LẤY DANH SÁCH ĐÁNH GIÁ CỦA 1 CUỐN SÁCH
     * Dùng để hiển thị list review trong Modal
     */
    public function getReviewsByBook($bookId)
    {
        // Join với bảng tblstudents để lấy Tên sinh viên (FullName)
        $sql = "SELECT r.*, s.FullName 
                FROM tblbookreviews r
                JOIN tblstudents s ON r.StudentId = s.StudentId
                WHERE r.BookId = :bid
                ORDER BY r.ReviewDate DESC"; // Mới nhất lên đầu
        
        $q = $this->dbh->prepare($sql);
        $q->bindParam(':bid', $bookId, PDO::PARAM_INT);
        $q->execute();
        
        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * 3. THÊM ĐÁNH GIÁ MỚI
     */
    public function addReview($bookId, $studentId, $rating, $comment)
    {
        try {
            $sql = "INSERT INTO tblbookreviews(BookId, StudentId, Rating, Comment)
                    VALUES(:bid, :sid, :rating, :comment)";
            
            $q = $this->dbh->prepare($sql);
            $q->bindParam(':bid', $bookId, PDO::PARAM_INT);
            $q->bindParam(':sid', $studentId, PDO::PARAM_STR);
            $q->bindParam(':rating', $rating, PDO::PARAM_INT);
            $q->bindParam(':comment', $comment, PDO::PARAM_STR);
            
            return $q->execute();
        } catch (PDOException $e) {
            // Log lỗi nếu cần
            return false;
        }
    }
}
?>