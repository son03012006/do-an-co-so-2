<?php
class BorrowModel
{
    private $db;

    public function __construct($dbh)
    {
        $this->db = $dbh;
    }

    /* ==========================================================
       1. LẤY SÁCH ĐÃ ĐƯỢC CẤP (ISSUED / RETURNED)
       ========================================================== */
    public function getBorrowedBooks($studentId)
    {
        // Lấy thông tin từ bảng issued kết hợp với bảng books
        $sql = "SELECT 
                    i.id AS IssueID,
                    b.id AS BookId,  
                    b.BookName,
                    b.ISBNNumber,
                    i.IssuesDate,      
                    i.ReturnDate,
                    i.DueDate,
                    i.fine,
                    i.RenewCount,
                    i.ReturnStatus     
                FROM tblissuedbookdetails i
                JOIN tblbooks b ON b.id = i.BookId
                WHERE i.StudentID = :sid
                ORDER BY i.IssuesDate DESC"; // Sắp xếp theo ngày mượn mới nhất

        $q = $this->db->prepare($sql);
        $q->bindParam(':sid', $studentId, PDO::PARAM_INT); // Dùng INT nếu ID là số
        $q->execute();
        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    /* ==========================================================
       2. LẤY SÁCH ĐANG YÊU CẦU (PENDING / REJECTED)
       (Thêm hàm này để Controller không phải viết SQL thô)
       ========================================================== */
    public function getRequestedBooks($studentId)
    {
        // Lấy status = 0 (Chờ duyệt) hoặc 2 (Từ chối)
        // status = 1 là đã duyệt -> sẽ chuyển sang bảng issued nên ko lấy ở đây
        $sql = "SELECT 
                    r.id, 
                    r.BookId, 
                    r.BookName, 
                    r.ISBNNumber, 
                    r.RequestDate, 
                    r.status,
                    NULL as IssueDate, 
                    NULL as DueDate, 
                    NULL as ReturnDate, 
                    0 as fine, 
                    0 as ReturnStatus
                FROM tblrequestedbookdetails r
                WHERE r.StudentID = :sid AND (r.status = 0 OR r.status = 2)
                ORDER BY r.RequestDate DESC";

        $q = $this->db->prepare($sql);
        $q->bindParam(':sid', $studentId, PDO::PARAM_INT);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    /* ==========================================================
       3. CÁC HÀM HỖ TRỢ GIA HẠN (RENEW)
       ========================================================== */
    
    // Kiểm tra thông tin 1 lượt mượn cụ thể
    public function getIssueById($issueId, $studentId)
    {
        $sql = "SELECT * FROM tblissuedbookdetails WHERE id=:id AND StudentID=:sid";
        $q = $this->db->prepare($sql);
        $q->bindParam(':id', $issueId, PDO::PARAM_INT);
        $q->bindParam(':sid', $studentId, PDO::PARAM_INT);
        $q->execute();
        return $q->fetch(PDO::FETCH_OBJ);
    }

    // Thực hiện gia hạn
    public function renewBook($issueId, $newDueDate)
    {
        $sql = "UPDATE tblissuedbookdetails 
                SET DueDate = :due, 
                    RenewCount = COALESCE(RenewCount, 0) + 1 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':due' => $newDueDate,
            ':id'  => $issueId
        ]);
    }
}
?>