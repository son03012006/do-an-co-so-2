<?php

class PasswordModel
{
    private $db;

    public function __construct($dbh)
    {
        $this->db = $dbh;
    }

    // Kiểm tra mật khẩu hiện tại
    public function checkCurrentPassword($email, $password)
    {
        $sql = "SELECT Password FROM tblstudents 
                WHERE EmailId = :email AND Password = :password";
        $q = $this->db->prepare($sql);
        $q->bindParam(':email', $email);
        $q->bindParam(':password', $password);
        $q->execute();
        return $q->rowCount() > 0;
    }

    // Cập nhật mật khẩu mới
    public function updatePassword($email, $newpassword)
    {
        $sql = "UPDATE tblstudents 
                SET Password = :newpassword 
                WHERE EmailId = :email";
        $q = $this->db->prepare($sql);
        $q->bindParam(':email', $email);
        $q->bindParam(':newpassword', $newpassword);
        return $q->execute();
    }
}
