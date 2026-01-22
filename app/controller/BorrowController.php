<?php

require_once __DIR__ . '/../Model/BorrowModel.php';

class BorrowController
{
    private $dbh;

    public function __construct()
    {
        global $dbh;
        $this->dbh = $dbh;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Chức năng: Hiển thị danh sách (Lịch sử mượn)
     */
    // File: controllers/BorrowController.php

    public function index()
    {
        // 1. Kiểm tra đăng nhập
        if (empty($_SESSION['login'])) {
            header('Location: index.php');
            exit;
        }

        $studentId = $_SESSION['stdid'];
        $model = new BorrowModel($this->dbh);

        /* ========== 1. LẤY TẤT CẢ DỮ LIỆU TỪ MODEL ========== */
        // Lấy sách đã mượn (đã duyệt)
        $issuedBooks = $model->getBorrowedBooks($studentId);

        $requestedBooks = $model->getRequestedBooks($studentId);

        /* ========== 2. CHUẨN HÓA DỮ LIỆU & TÍNH TOÁN STATS ========== */
        $totalReturned = 0;
        $totalFine = 0;
        $totalBorrowed = 0;

        // Xử lý danh sách MƯỢN (Issued)
        // Xử lý danh sách MƯỢN (Issued)
        foreach ($issuedBooks as $book) {
            $book->is_issued = 1;

            /* FIX Ở ĐÂY: Sử dụng ReturnStatus thay vì ReturnDate */
            // status_code = 'returned' nếu ReturnStatus là 1
            // status_code = 'issued' nếu ReturnStatus là 0 hoặc NULL
            if (isset($book->ReturnStatus) && $book->ReturnStatus == 1) {
                $book->status_code = 'returned';
                $totalReturned++;
            } else {
                $book->status_code = 'issued'; // Khi gia hạn, ReturnStatus vẫn là 0 nên nó sẽ ở lại đây
            }

            $totalFine += isset($book->fine) ? $book->fine : 0;
            $totalBorrowed++;
        }

        // Xử lý danh sách YÊU CẦU (Requested)
        foreach ($requestedBooks as $req) {
            $req->is_issued = 0;
            if ($req->status == 2) {
                $req->status_code = 'rejected';
            } else {
                $req->status_code = 'pending';
            }
            // Gán giá trị mặc định để tránh lỗi View
            $req->RenewCount = 0;
            $req->IssueID = 0;
        }

        // Gộp tất cả lại thành 1 danh sách tổng
        $allBooks = array_merge($issuedBooks, $requestedBooks);

        /* ========== 3. XỬ LÝ BỘ LỌC (FILTER) ========== */
        $filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
        $filteredData = [];

        if ($filterStatus == '') {
            $filteredData = $allBooks; // Không lọc -> lấy hết
        } else {
            foreach ($allBooks as $b) {
                if ($b->status_code == $filterStatus) {
                    $filteredData[] = $b;
                }
            }
        }

        // Sắp xếp: Mới nhất lên đầu (Dựa vào IssuesDate hoặc RequestDate)
        usort($filteredData, function ($a, $b) {
            $dateA = isset($a->IssuesDate) ? $a->IssuesDate : (isset($a->RequestDate) ? $a->RequestDate : '');
            $dateB = isset($b->IssuesDate) ? $b->IssuesDate : (isset($b->RequestDate) ? $b->RequestDate : '');
            return strtotime($dateB) - strtotime($dateA);
        });

        /* ========== 4. XỬ LÝ PHÂN TRANG ========== */
        $limit = 10;
        $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

        $totalRecords = count($filteredData);
        $totalPages = ceil($totalRecords / $limit);
        $offset = ($currentPage - 1) * $limit;

        // Cắt dữ liệu cho trang hiện tại
        $books = array_slice($filteredData, $offset, $limit);

        /* ========== 5. TRUYỀN RA VIEW ========== */
        $data = compact(
            'books',
            'totalBorrowed',
            'totalReturned',
            'totalFine',
            'currentPage',
            'totalPages',
            'totalRecords',
            'limit',
            'filterStatus',
            'offset'
        );

        $this->loadView('books/borrow', $data);
    }
    public function renew()
    {
        // 1. Kiểm tra đăng nhập
        if (empty($_SESSION['login'])) {
            header('Location: index.php');
            exit;
        }

        // 2. Lấy ID
        $issueId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $studentId = $_SESSION['stdid'];

        if ($issueId <= 0) {
            $this->setFlashMessage('error', 'Lỗi', 'Dữ liệu không hợp lệ!');
            header('Location: index.php?c=borrow');
            exit;
        }

        $model = new BorrowModel($this->dbh);
        $issue = $model->getIssueById($issueId, $studentId);

        // 3. Kiểm tra tồn tại
        if (!$issue) {
            $this->setFlashMessage('error', 'Lỗi', 'Không tìm thấy thông tin mượn!');
            header('Location: index.php?c=borrow');
            exit;
        }

        // 4. KIỂM TRA ĐIỀU KIỆN: Chỉ gia hạn 1 lần
        if ($issue->RenewCount >= 1) {
            $this->setFlashMessage('warning', 'Không thể gia hạn', 'Bạn chỉ được gia hạn tối đa 1 lần!');
            header('Location: index.php?c=borrow');
            exit;
        }

        // 5. KIỂM TRA ĐIỀU KIỆN: Sách chưa trả mới được gia hạn
        if (isset($issue->ReturnStatus) && $issue->ReturnStatus == 1) {
            $this->setFlashMessage('info', 'Thông báo', 'Sách đã trả, không thể gia hạn!');
            header('Location: index.php?c=borrow');
            exit;
        }

        // 6. LOGIC TÍNH NGÀY
        $currentDueDate = $issue->DueDate;
        $today = date('Y-m-d');

        if ($currentDueDate < $today) {
            // Đã hết hạn: Tính từ hôm nay
            $newDueDate = date('Y-m-d', strtotime('+7 days'));
        } else {
            // Chưa hết hạn: Cộng dồn
            $newDueDate = date('Y-m-d', strtotime($currentDueDate . ' +7 days'));
        }

        // 7. Gọi Model cập nhật
        if ($model->renewBook($issueId, $newDueDate)) {
            $newDateFormatted = date('d-m-Y', strtotime($newDueDate));
            $this->setFlashMessage('success', 'Thành công', "Gia hạn thành công! Hạn mới là: $newDateFormatted");
        } else {
            $this->setFlashMessage('error', 'Thất bại', 'Lỗi hệ thống! Vui lòng thử lại.');
        }

        header('Location: index.php?c=borrow');
        exit;
    }

    /**
     * Chức năng: Gửi yêu cầu mượn sách mới
     */
    /**
     * Chức năng: Gửi yêu cầu mượn sách mới
     */
    /**
     * Chức năng: Gửi yêu cầu mượn sách mới
     */
    public function create()
    {
        // 1. Xác định trang quay về
        $backUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php?c=books&a=index';

        if (empty($_SESSION['login'])) {
            $this->setFlashMessage('warning', 'Yêu cầu đăng nhập', 'Vui lòng đăng nhập để thực hiện chức năng này.');
            header("Location: $backUrl");
            exit;
        }

        $studentId = $_SESSION['stdid'];
        $bookId = isset($_GET['bookid']) ? intval($_GET['bookid']) : 0;

        if ($bookId <= 0) {
            $this->setFlashMessage('error', 'Lỗi', 'Sách không hợp lệ!');
            header("Location: $backUrl");
            exit;
        }

        try {
            // Lấy thông tin sách và số lượng (Copies)
            $sqlBook = "SELECT b.BookName, b.ISBNNumber, b.BookPrice, b.Copies, c.CategoryName, a.AuthorName 
                        FROM tblbooks b
                        LEFT JOIN tblcategory c ON c.id = b.CatId
                        LEFT JOIN tblauthors a ON a.id = b.AuthorId
                        WHERE b.id = :bid";
            $stmtBook = $this->dbh->prepare($sqlBook);
            $stmtBook->execute([':bid' => $bookId]);
            $bookInfo = $stmtBook->fetch(PDO::FETCH_OBJ);

            if (!$bookInfo) {
                $this->setFlashMessage('error', 'Lỗi', 'Sách không tồn tại!');
                header("Location: $backUrl");
                exit;
            }

            // KIỂM TRA: Chỉ cho phép gửi yêu cầu nếu trong kho CÒN sách
            // (Tuy nhiên KHÔNG TRỪ NGAY, chỉ chặn nếu hết sách)
            if ($bookInfo->Copies <= 0) {
                $this->setFlashMessage('error', 'Hết sách', 'Sách này hiện tại đã hết trong kho, không thể tạo yêu cầu!');
                header("Location: $backUrl");
                exit;
            }

            // Lấy tên sinh viên
            $sqlStud = "SELECT FullName FROM tblstudents WHERE StudentId = :sid";
            $stmtStud = $this->dbh->prepare($sqlStud);
            $stmtStud->execute([':sid' => $studentId]);
            $studInfo = $stmtStud->fetch(PDO::FETCH_OBJ);
            $studName = $studInfo ? $studInfo->FullName : $studentId;

            // Kiểm tra đã gửi yêu cầu chưa (tránh spam)
            $sqlCheckReq = "SELECT id FROM tblrequestedbookdetails 
                            WHERE BookId = :bid AND StudentID = :sid AND status = 0";
            $stmtCheckReq = $this->dbh->prepare($sqlCheckReq);
            $stmtCheckReq->execute([':bid' => $bookId, ':sid' => $studentId]);

            if ($stmtCheckReq->rowCount() > 0) {
                $this->setFlashMessage('info', 'Đã gửi yêu cầu', 'Bạn đã gửi yêu cầu mượn sách này rồi. Vui lòng chờ duyệt!');
                header("Location: $backUrl");
                exit;
            }

            // Kiểm tra đang giữ sách này không
            $sqlCheckIssue = "SELECT id FROM tblissuedbookdetails 
                              WHERE BookId = :bid AND StudentID = :sid AND (ReturnStatus = 0 OR ReturnStatus IS NULL)";
            $stmtCheckIssue = $this->dbh->prepare($sqlCheckIssue);
            $stmtCheckIssue->execute([':bid' => $bookId, ':sid' => $studentId]);

            if ($stmtCheckIssue->rowCount() > 0) {
                $this->setFlashMessage('warning', 'Không thể mượn', 'Bạn đang giữ cuốn sách này rồi!');
                header("Location: $backUrl");
                exit;
            }

            // === CHỈ INSERT YÊU CẦU (KHÔNG UPDATE TRỪ KHO) ===
            $sqlInsert = "INSERT INTO tblrequestedbookdetails 
                          (StudentID, StudName, BookId, BookName, CategoryName, AuthorName, ISBNNumber, BookPrice, status, RequestDate) 
                          VALUES (:sid, :sname, :bid, :bname, :cname, :aname, :isbn, :price, 0, NOW())";

            $stmtInsert = $this->dbh->prepare($sqlInsert);
            $result = $stmtInsert->execute([
                ':sid'   => $studentId,
                ':sname' => $studName,
                ':bid'   => $bookId,
                ':bname' => $bookInfo->BookName,
                ':cname' => $bookInfo->CategoryName,
                ':aname' => $bookInfo->AuthorName,
                ':isbn'  => $bookInfo->ISBNNumber,
                ':price' => $bookInfo->BookPrice
            ]);

            if ($result) {
                $this->setFlashMessage('success', 'Thành công', 'Gửi yêu cầu thành công! Vui lòng chờ Admin duyệt.');
                header("Location: $backUrl");
            } else {
                $this->setFlashMessage('error', 'Lỗi', 'Lỗi hệ thống! Không thể tạo yêu cầu.');
                header("Location: $backUrl");
            }

        } catch (Exception $e) {
            $this->setFlashMessage('error', 'Lỗi ngoại lệ', $e->getMessage());
            header("Location: $backUrl");
        }
    }
    private function setFlashMessage($icon, $title, $text)
    {
        $_SESSION['swal_icon'] = $icon;   // success, error, warning, info
        $_SESSION['swal_title'] = $title;
        $_SESSION['swal_text'] = $text;
    }

    private function loadView($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . '/../View/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        }
    }
}
