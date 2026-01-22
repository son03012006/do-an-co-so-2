<?php
class AdminaddbookModel
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function getCategories()
    {
        $sql = "SELECT * FROM tblcategory WHERE Status = 1";
        return $this->dbh->query($sql)->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAuthors()
    {
        $sql = "SELECT * FROM tblauthors ORDER BY AuthorName";
        return $this->dbh->query($sql)->fetchAll(PDO::FETCH_OBJ);
    }

    public function create($data)
    {
        // Chú ý: Cột lưu ảnh trong DB của bạn tên là gì? 
        // Ở đây mình để là `bookImage`. Nếu DB bạn là `Image` hay `Img` thì sửa lại nhé.
        $sql = "INSERT INTO tblbooks 
                (BookName, CatId, AuthorId, ISBNNumber, BookPrice, Copies, bookImage) 
                VALUES 
                (:bookname, :category, :author, :isbn, :price, :copies, :bookImage)";

        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute($data);
    }
}