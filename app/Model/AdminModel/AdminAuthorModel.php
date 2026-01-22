<?php

class AdminAuthorModel
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * 1. THÊM MỚI TÁC GIẢ
     */
    public function create($author)
    {
        $sql = "INSERT INTO tblauthors (AuthorName) VALUES (:author)";
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute([
            ':author' => $author
        ]);
    }

    /**
     * 2. CẬP NHẬT TÁC GIẢ (Hàm này đang thiếu, cần thêm vào)
     */
    public function update($id, $authorName)
    {
        // Cập nhật tên và thời gian cập nhật (UpdationDate)
        $sql = "UPDATE tblauthors 
                SET AuthorName = :author, 
                    UpdationDate = CURRENT_TIMESTAMP 
                WHERE id = :id";
        
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':author', $authorName, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * 3. XÓA TÁC GIẢ (Hàm này đang thiếu, cần thêm vào)
     */
    public function delete($id)
    {
        $sql = "DELETE FROM tblauthors WHERE id = :id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * 4. LẤY TẤT CẢ (Dùng cho trang danh sách ManagerAuthor)
     */
    public function getAll()
    {
        $sql = "SELECT * FROM tblauthors ORDER BY creationDate DESC";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * 5. LẤY 1 TÁC GIẢ THEO ID (Dùng để check tồn tại nếu cần)
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM tblauthors WHERE id = :id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}