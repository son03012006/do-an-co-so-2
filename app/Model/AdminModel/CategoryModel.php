<?php

class CategoryModel
{
    private $db;

    public function __construct($dbh)
    {
        $this->db = $dbh;
    }

    /**
     * 1. LẤY TẤT CẢ DANH MỤC (Hàm này đang thiếu, cần thêm vào)
     * Dùng để hiển thị trong Dropdown khi Thêm/Sửa Sách
     */
    public function getAll()
    {
        // Lấy danh sách danh mục, nên chọn những danh mục đang hoạt động (Status=1) nếu muốn
        // Ở đây mình lấy tất cả để Admin dễ quản lý
        $sql = "SELECT * FROM tblcategory ORDER BY id DESC";
        $q = $this->db->prepare($sql);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * 2. KIỂM TRA TỒN TẠI (Dùng khi thêm mới để tránh trùng tên)
     */
    public function exists($name)
    {
        $sql = "SELECT id FROM tblcategory WHERE CategoryName = :name LIMIT 1";
        $q = $this->db->prepare($sql);
        $q->bindParam(':name', $name, PDO::PARAM_STR);
        $q->execute();
        return $q->rowCount() > 0;
    }

    /**
     * 3. THÊM MỚI DANH MỤC
     */
    public function create($name, $status)
    {
        $sql = "INSERT INTO tblcategory(CategoryName, Status) VALUES (:name, :status)";
        $q = $this->db->prepare($sql);
        $q->bindParam(':name', $name, PDO::PARAM_STR);
        $q->bindParam(':status', $status, PDO::PARAM_INT);
        return $q->execute();
    }

    /**
     * 4. LẤY THÔNG TIN THEO ID (Dùng để hiển thị dữ liệu cũ khi Sửa)
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM tblcategory WHERE id = :id LIMIT 1";
        $q = $this->db->prepare($sql);
        $q->bindParam(':id', $id, PDO::PARAM_INT);
        $q->execute();
        return $q->fetch(PDO::FETCH_OBJ);
    }

    /**
     * 5. CẬP NHẬT DANH MỤC
     */
    public function update($id, $name, $status)
    {
        // Cập nhật tên, trạng thái và thời gian cập nhật (UpdationDate)
        $sql = "UPDATE tblcategory 
                SET CategoryName = :name, 
                    Status = :status, 
                    UpdationDate = CURRENT_TIMESTAMP 
                WHERE id = :id";
        
        $q = $this->db->prepare($sql);
        $q->bindParam(':name', $name, PDO::PARAM_STR);
        $q->bindParam(':status', $status, PDO::PARAM_INT);
        $q->bindParam(':id', $id, PDO::PARAM_INT);
        return $q->execute();
    }

    /**
     * 6. XÓA DANH MỤC
     */
    public function delete($id)
    {
        $sql = "DELETE FROM tblcategory WHERE id = :id";
        $q = $this->db->prepare($sql);
        $q->bindParam(':id', $id, PDO::PARAM_INT);
        return $q->execute();
    }
}
?>