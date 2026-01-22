<?php

class bangthongkemodel
{
    private $db;

    public function __construct($dbh)
    {
        $this->db = $dbh;
    }

    public function getCategories()
    {
        return $this->db->query(
            "SELECT id, CategoryName FROM tblcategory ORDER BY CategoryName"
        )->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAuthors()
    {
        return $this->db->query(
            "SELECT id, AuthorName FROM tblauthors ORDER BY AuthorName"
        )->fetchAll(PDO::FETCH_OBJ);
    }

    public function countBooks($filters)
    {
        $sql = "SELECT COUNT(*) FROM tblbooks WHERE Copies > IssuedCopies";

        if ($filters['keyword'])  $sql .= " AND BookName LIKE :keyword";
        if ($filters['category']) $sql .= " AND CatId = :cat";
        if ($filters['author'])   $sql .= " AND AuthorId = :auth";

        $q = $this->db->prepare($sql);

        if ($filters['keyword'])  $q->bindValue(':keyword', '%'.$filters['keyword'].'%');
        if ($filters['category']) $q->bindValue(':cat', $filters['category']);
        if ($filters['author'])   $q->bindValue(':auth', $filters['author']);

        $q->execute();
        return $q->fetchColumn();
    }

    public function getBooks($filters, $sid, $start, $limit)
    {
        $sql = "
            SELECT b.*, c.CategoryName, a.AuthorName,
            (SELECT AVG(Rating) FROM tblbookreviews r WHERE r.BookId=b.id) AvgRating,
            (SELECT COUNT(*) FROM tblbookreviews r WHERE r.BookId=b.id) ReviewCount
            FROM tblbooks b
            JOIN tblcategory c ON c.id=b.CatId
            JOIN tblauthors a ON a.id=b.AuthorId
            WHERE b.Copies > b.IssuedCopies
        ";

        if ($filters['keyword'])  $sql .= " AND b.BookName LIKE :keyword";
        if ($filters['category']) $sql .= " AND b.CatId = :cat";
        if ($filters['author'])   $sql .= " AND b.AuthorId = :auth";

        $sql .= " ORDER BY b.id DESC LIMIT :start, :limit";

        $q = $this->db->prepare($sql);

        if ($filters['keyword'])  $q->bindValue(':keyword', '%'.$filters['keyword'].'%');
        if ($filters['category']) $q->bindValue(':cat', $filters['category']);
        if ($filters['author'])   $q->bindValue(':auth', $filters['author']);

        $q->bindValue(':start', $start, PDO::PARAM_INT);
        $q->bindValue(':limit', $limit, PDO::PARAM_INT);

        $q->execute();
        return $q->fetchAll(PDO::FETCH_OBJ);
    }
}
