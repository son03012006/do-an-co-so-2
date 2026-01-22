<?php
require_once __DIR__ . '/../Model/bangthongkeModel.php';

class bangthongkecontroller
{
    public function index()
    {
        global $dbh;

        // kiểm tra đăng nhập
        $sid = $_SESSION['stdid'] ?? null;
        if (!$sid) {
            header('Location: index.php?c=auth&a=dangnhap');
            exit;
        }

        $model = new bangthongkemodel($dbh);

        $filters = [
            'keyword'  => $_GET['keyword']  ?? '',
            'category' => $_GET['category'] ?? '',
            'author'   => $_GET['author']   ?? ''
        ];

        $limit = 20;
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $start = ($page - 1) * $limit;

        $totalBooks = $model->countBooks($filters);
        $totalPages = max(1, ceil($totalBooks / $limit));

        $books      = $model->getBooks($filters, $sid, $start, $limit);
        $categories = $model->getCategories();
        $authors    = $model->getAuthors();

        $data = compact(
            'books',
            'categories',
            'authors',
            'filters',
            'page',
            'totalPages'
        );

        $this->loadView('books/bangthongke', $data);
    }

    private function loadView($viewPath, $data = [])
    {
        extract($data);
        require __DIR__ . '/../View/' . $viewPath . '.php';
    }
}
