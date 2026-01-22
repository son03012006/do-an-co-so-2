<?php

class UserModel
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    /* LẤY TẤT CẢ SINH VIÊN */
    public function getAll()
    {
        $sql = "SELECT * FROM tblstudents ORDER BY id DESC";
        return $this->dbh->query($sql)->fetchAll(PDO::FETCH_OBJ);
    }

    /* KHÓA TÀI KHOẢN */
    public function lock($id)
    {
        $sql = "UPDATE tblstudents SET Status = 0 WHERE id = :id";
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /* MỞ KHÓA TÀI KHOẢN */
    public function unlock($id)
    {
        $sql = "UPDATE tblstudents SET Status = 1 WHERE id = :id";
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
}
