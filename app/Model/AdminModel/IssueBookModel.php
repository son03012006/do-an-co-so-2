<?php
class IssueBookModel
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * PHÊ DUYỆT YÊU CẦU (PHÁT SÁCH)
     */
    public function approveRequest($isbn, $studentId)
    {
        try {
            $this->dbh->beginTransaction(); // Bắt đầu giao dịch để đảm bảo dữ liệu nhất quán

            // 1. Lấy ID sách từ ISBN (Vì bảng Issued dùng BookId chứ không phải ISBN)
            $sqlGetBook = "SELECT id FROM tblbooks WHERE ISBNNumber = :isbn LIMIT 1";
            $stmt = $this->dbh->prepare($sqlGetBook);
            $stmt->execute([':isbn' => $isbn]);
            $book = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$book) {
                return false; // Không tìm thấy sách
            }

            $bookId = $book->id;

            // 2. Thêm vào bảng đã mượn (tblissuedbookdetails)
            // ReturnStatus = 0 nghĩa là chưa trả
            $sqlInsert = "INSERT INTO tblissuedbookdetails (BookId, StudentID, IssuesDate, ReturnStatus) 
                          VALUES (:bid, :sid, CURRENT_TIMESTAMP, 0)";
            $stmtInsert = $this->dbh->prepare($sqlInsert);
            $stmtInsert->execute([
                ':bid' => $bookId,
                ':sid' => $studentId
            ]);

            // 3. Xóa khỏi bảng yêu cầu (tblrequestedbookdetails)
            // Để danh sách yêu cầu không hiện dòng này nữa
            $sqlDelete = "DELETE FROM tblrequestedbookdetails 
                          WHERE StudentID = :sid AND ISBNNumber = :isbn";
            $stmtDelete = $this->dbh->prepare($sqlDelete);
            $stmtDelete->execute([
                ':sid' => $studentId,
                ':isbn' => $isbn
            ]);

            // 4. (Tùy chọn) Trừ số lượng sách trong kho nếu cần
            // $sqlUpdate = "UPDATE tblbooks SET Copies = Copies - 1 WHERE id = :bid";
            // ...

            $this->dbh->commit(); // Xác nhận thành công
            return true;

        } catch (Exception $e) {
            $this->dbh->rollBack(); // Nếu lỗi thì hoàn tác
            return false;
        }
    }

    /**
     * LẤY DANH SÁCH YÊU CẦU (Cho hàm index)
     */
    public function getAllRequests()
    {
        $sql = "SELECT 
                    tblstudents.StudentId as StudentID,
                    tblstudents.FullName as StudName,
                    tblbooks.BookName,
                    tblbooks.ISBNNumber,
                    tblbooks.BookPrice,
                    tblcategory.CategoryName,
                    tblauthors.AuthorName
                FROM tblrequestedbookdetails
                JOIN tblstudents ON tblstudents.StudentId = tblrequestedbookdetails.StudentID
                JOIN tblbooks ON tblbooks.ISBNNumber = tblrequestedbookdetails.ISBNNumber
                LEFT JOIN tblcategory ON tblbooks.CatId = tblcategory.id
                LEFT JOIN tblauthors ON tblbooks.AuthorId = tblauthors.id";
        
        $query = $this->dbh->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}
?>