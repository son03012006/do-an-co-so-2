<?php
class ManagerBookModel
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * 1. THÊM SÁCH MỚI
     * Đã thêm tham số $bookImage để lưu tên file ảnh
     */
    public function create($name, $catId, $authId, $isbn, $price, $copies, $bookImage)
    {
        // Thêm cột BookImage vào câu lệnh INSERT
        $sql = "INSERT INTO tblbooks (BookName, CatId, AuthorId, ISBNNumber, BookPrice, Copies, BookImage, RegDate, UpdationDate) 
                VALUES (:name, :cat, :auth, :isbn, :price, :copies, :image, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        
        $query = $this->dbh->prepare($sql);
        
        return $query->execute([
            ':name'   => $name,
            ':cat'    => $catId,
            ':auth'   => $authId,
            ':isbn'   => $isbn,
            ':price'  => $price,
            ':copies' => $copies,
            ':image'  => $bookImage // Lưu tên file ảnh vào DB
        ]);
    }

    /**
     * 2. LẤY DANH SÁCH (Phân trang)
     * Giữ nguyên, vì select * đã lấy cột BookImage rồi
     */
    public function getAll($offset, $limit)
    {
        $sql = "SELECT tblbooks.*, tblcategory.CategoryName, tblauthors.AuthorName 
                FROM tblbooks 
                LEFT JOIN tblcategory ON tblbooks.CatId = tblcategory.id 
                LEFT JOIN tblauthors ON tblbooks.AuthorId = tblauthors.id 
                ORDER BY tblbooks.id DESC 
                LIMIT :offset, :limit";
        
        $query = $this->dbh->prepare($sql);
        $query->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * 3. ĐẾM TỔNG SỐ SÁCH
     */
    public function countAll()
    {
        $sql = "SELECT COUNT(id) as total FROM tblbooks";
        $query = $this->dbh->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result ? $result->total : 0;
    }

    /**
     * 4. XÓA SÁCH
     */
    public function delete($id) {
        // (Tùy chọn) Nên lấy tên ảnh cũ để xóa file khỏi thư mục trước khi xóa DB
        $sql = "DELETE FROM tblbooks WHERE id = :id";
        $query = $this->dbh->prepare($sql);
        return $query->execute([':id' => $id]);
    }

    /**
     * 5. CẬP NHẬT SÁCH
     * Logic: Nếu người dùng có chọn ảnh mới ($bookImage không rỗng) thì cập nhật cả ảnh.
     * Nếu không chọn ảnh mới (null hoặc rỗng) thì giữ nguyên ảnh cũ.
     */
    public function update($id, $name, $catId, $authId, $isbn, $price, $copies, $bookImage = null) {
        
        if (!empty($bookImage)) {
            // Trường hợp 1: Có upload ảnh mới -> Update cả cột BookImage
            $sql = "UPDATE tblbooks SET 
                    BookName=:name, 
                    CatId=:cat, 
                    AuthorId=:auth, 
                    ISBNNumber=:isbn, 
                    BookPrice=:price, 
                    Copies=:copies, 
                    BookImage=:image, 
                    UpdationDate=CURRENT_TIMESTAMP 
                    WHERE id=:id";
            
            $params = [
                ':name' => $name, ':cat' => $catId, ':auth' => $authId, 
                ':isbn' => $isbn, ':price' => $price, ':copies' => $copies, 
                ':image' => $bookImage, ':id' => $id
            ];
        } else {
            // Trường hợp 2: Không đổi ảnh -> Giữ nguyên cột BookImage cũ
            $sql = "UPDATE tblbooks SET 
                    BookName=:name, 
                    CatId=:cat, 
                    AuthorId=:auth, 
                    ISBNNumber=:isbn, 
                    BookPrice=:price, 
                    Copies=:copies, 
                    UpdationDate=CURRENT_TIMESTAMP 
                    WHERE id=:id";
            
            $params = [
                ':name' => $name, ':cat' => $catId, ':auth' => $authId, 
                ':isbn' => $isbn, ':price' => $price, ':copies' => $copies, 
                ':id' => $id
            ];
        }

        $query = $this->dbh->prepare($sql);
        return $query->execute($params);
    }
}
?>