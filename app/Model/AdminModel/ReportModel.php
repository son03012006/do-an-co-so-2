<?php
class ReportModel
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    // 1. Lấy danh sách sách quá hạn
    public function getOverdueBooks()
    {
        $today = date('Y-m-d');
        $sql = "SELECT i.StudentID, s.FullName, b.BookName, i.IssuesDate, i.DueDate, 
                       DATEDIFF(:today, i.DueDate) as days_overdue
                FROM tblissuedbookdetails i
                JOIN tblstudents s ON s.StudentId = i.StudentID
                JOIN tblbooks b ON b.id = i.BookId
                WHERE (i.ReturnStatus = 0 OR i.ReturnStatus IS NULL) 
                AND i.DueDate < :today
                ORDER BY i.DueDate ASC";
        
        $query = $this->dbh->prepare($sql);
        $query->execute([':today' => $today]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    // 2. Lấy lịch sử mượn của 1 sinh viên cụ thể
    public function getHistoryByStudent($studentId)
    {
        $sql = "SELECT b.BookName, b.ISBNNumber, i.IssuesDate, i.DueDate, i.ReturnDate, i.ReturnStatus, i.fine
                FROM tblissuedbookdetails i
                JOIN tblbooks b ON b.id = i.BookId
                WHERE i.StudentID = :sid
                ORDER BY i.IssuesDate DESC";
        
        $query = $this->dbh->prepare($sql);
        $query->execute([':sid' => $studentId]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    
    // Lấy tên sinh viên để hiển thị trên tiêu đề
    public function getStudentName($studentId) {
        $sql = "SELECT FullName FROM tblstudents WHERE StudentId = :sid";
        $query = $this->dbh->prepare($sql);
        $query->execute([':sid' => $studentId]);
        $res = $query->fetch(PDO::FETCH_OBJ);
        return $res ? $res->FullName : "Không xác định";
    }
}
?>