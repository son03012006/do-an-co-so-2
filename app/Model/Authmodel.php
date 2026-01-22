<?php

class AuthModel
{
    private $db;

    public function __construct($dbh)
    {
        $this->db = $dbh;
    }

    /* ===== ĐĂNG NHẬP ===== */
    public function login($email, $password)
    {
        // Sử dụng PDO::FETCH_OBJ để khớp với Controller ($user->Status)
        $sql = "SELECT FullName, EmailId, StudentId, Status 
                FROM tblstudents 
                WHERE EmailId = :email AND Password = :password";

        $q = $this->db->prepare($sql);
        $q->bindParam(':email', $email, PDO::PARAM_STR);
        $q->bindParam(':password', $password, PDO::PARAM_STR);
        $q->execute();

        return $q->fetch(PDO::FETCH_OBJ);
    }

    /* ===== ĐĂNG KÝ ===== */
    public function register($studentId, $name, $phone, $email, $password)
    {
        try {
            // 1. Kiểm tra Email hoặc Số điện thoại đã tồn tại chưa (Tránh lỗi Duplicate Entry)
            $checkSql = "SELECT id FROM tblstudents WHERE EmailId = :email OR MobileNumber = :phone";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([':email' => $email, ':phone' => $phone]);

            if ($checkStmt->rowCount() > 0) {
                return false; // Email hoặc SĐT đã tồn tại
            }

            // 2. Tiến hành thêm mới
            $status = 1; // 1: Active, 0: Blocked
            $sql = "INSERT INTO tblstudents 
                    (StudentId, FullName, MobileNumber, EmailId, Password, Status) 
                    VALUES (:sid, :name, :phone, :email, :pass, :status)";

            $q = $this->db->prepare($sql);
            $result = $q->execute([
                ':sid'    => $studentId,
                ':name'   => $name,
                ':phone'  => $phone,
                ':email'  => $email,
                ':pass'   => $password,
                ':status' => $status
            ]);

            return $result;

        } catch (PDOException $e) {
            // Log lỗi nếu cần: error_log($e->getMessage());
            return false;
        }
    }
}
