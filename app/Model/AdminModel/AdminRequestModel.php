<?php
class AdminRequestModel
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    /* 1. LẤY DANH SÁCH YÊU CẦU (CHỈ LẤY ĐƠN CHỜ DUYỆT) */
    public function getAllRequests()
    {
        $sql = "SELECT * FROM tblrequestedbookdetails 
                WHERE status = 0 
                ORDER BY id DESC";
                
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /* 2. CHỨC NĂNG PHÁT SÁCH (DUYỆT - CÓ TRỪ KHO) */
    public function acceptRequest($requestId, $remark)
    {
        try {
            // Bắt đầu transaction (Giao dịch)
            $this->dbh->beginTransaction();

            // --- BƯỚC A: Lấy thông tin BookId và StudentID từ yêu cầu ---
            $sqlGet = "SELECT BookId, StudentID FROM tblrequestedbookdetails WHERE id = :id";
            $stmtGet = $this->dbh->prepare($sqlGet);
            $stmtGet->execute([':id' => $requestId]);
            $requestData = $stmtGet->fetch(PDO::FETCH_OBJ);

            if (!$requestData) {
                $this->dbh->rollBack();
                return false; 
            }

            // --- BƯỚC B (MỚI): KIỂM TRA SỐ LƯỢNG TRONG KHO ---
            // Phải kiểm tra lại lần nữa phòng trường hợp sách vừa bị mượn hết
            $sqlCheckStock = "SELECT Copies FROM tblbooks WHERE id = :bid";
            $stmtCheck = $this->dbh->prepare($sqlCheckStock);
            $stmtCheck->execute([':bid' => $requestData->BookId]);
            $book = $stmtCheck->fetch(PDO::FETCH_OBJ);

            if (!$book || $book->Copies <= 0) {
                // Nếu hết sách, rollback và trả về false
                $this->dbh->rollBack();
                return false; 
            }

            // --- BƯỚC C: TÍNH NGÀY HẾT HẠN (DueDate) ---
            $dueDate = date('Y-m-d H:i:s', strtotime('+7 days')); 

            // --- BƯỚC D: Thêm vào bảng ĐANG MƯỢN (tblissuedbookdetails) ---
            $sqlInsert = "INSERT INTO tblissuedbookdetails 
                          (BookId, StudentID, IssuesDate, ReturnStatus, DueDate) 
                          VALUES (:bid, :sid, NOW(), 0, :due)";
            
            $stmtInsert = $this->dbh->prepare($sqlInsert);
            $stmtInsert->execute([
                ':bid' => $requestData->BookId,
                ':sid' => $requestData->StudentID,
                ':due' => $dueDate
            ]);

            // --- BƯỚC E: Cập nhật trạng thái bảng YÊU CẦU thành 1 (Đã duyệt) ---
            $sqlUpdate = "UPDATE tblrequestedbookdetails 
                          SET status = 1, remark = :remark 
                          WHERE id = :id";
            $stmtUpdate = $this->dbh->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':remark' => $remark,
                ':id'     => $requestId
            ]);

            // --- BƯỚC F (MỚI): TRỪ SỐ LƯỢNG SÁCH TRONG KHO ---
            $sqlDeduct = "UPDATE tblbooks SET Copies = Copies - 1 WHERE id = :bid";
            $stmtDeduct = $this->dbh->prepare($sqlDeduct);
            $stmtDeduct->execute([':bid' => $requestData->BookId]);

            // Hoàn tất mọi thứ thành công
            $this->dbh->commit();
            return true;

        } catch (PDOException $e) {
            // Có lỗi xảy ra ở bất kỳ bước nào -> Hủy toàn bộ
            $this->dbh->rollBack();
            return false;
        }
    }

    /* 3. CHỨC NĂNG TỪ CHỐI/HỦY (Status = 2) */
    public function rejectRequest($requestId, $remark)
    {
        try {
            // Từ chối thì không cần cộng/trừ kho vì lúc yêu cầu chưa trừ kho
            $sql = "UPDATE tblrequestedbookdetails 
                    SET status = 2, remark = :remark 
                    WHERE id = :id";
            
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':remark', $remark, PDO::PARAM_STR);
            $stmt->bindParam(':id', $requestId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /* 4. CHỨC NĂNG XÓA HẲN (DELETE) */
    public function deleteRequest($requestId)
    {
        try {
            $sql = "DELETE FROM tblrequestedbookdetails WHERE id = :id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':id', $requestId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>