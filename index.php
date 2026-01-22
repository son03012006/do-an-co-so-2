    <?php
    session_start();
    require_once 'app/Config/database.php';
    require_once 'app/Config/config.php';


    $c = $_GET['c'] ?? 'auth';
    $a = $_GET['a'] ?? 'index'; 

    switch ($c) {
        case 'auth':
            require_once 'app/controller/AuthController.php';
            $controller = new AuthController();
            break;
        case 'books':
            require_once 'app/controller/bangthongkeController.php';
            $controller = new bangthongkecontroller();
            break;
        case 'review':
            require_once 'app/controller/ReviewController.php';
            $controller = new ReviewController();
            break;
        case 'borrow':
            require_once 'app/controller/BorrowController.php';
            $controller = new BorrowController();
            break;
        case 'profile':
            require_once 'app/controller/ProfileController.php';
            $controller = new ProfileController();
            break;
        case 'password':
            require_once 'app/Controller/PasswordController.php';
            $controller = new PasswordController();
            break;
        case 'adminauth':
            require_once 'app/controller/AdminController/AdminAuthController.php';
            $controller = new AdminAuthController();
            break;
        case 'admindashboard':
            require_once 'app/controller/AdminController/AdminDashboardController.php';
            $controller = new AdminDashboardController();
            break;
        case 'category':
            require_once 'app/controller/AdminController/CategoryController.php';
            $controller = new CategoryController();
            break;
        case 'managercategory':
            require_once 'app/controller/AdminController/ManagerCategoryController.php';
            $controller = new ManagerCategoryController();
            break;
        case 'author':
            require_once 'app/controller/AdminController/AdminAuthorController.php';
            $controller = new AdminAuthorController();
            break;
        case 'managerauthor':
            require_once 'app/controller/AdminController/ManagerAuthorController.php';
            $controller = new ManagerAuthorController();
            break;
        case 'addbook':
            require_once 'app/controller/AdminController/AdminaddbookController.php';
            $controller = new AdminaddbookController();
            break;
        case 'managerbook':
            require_once 'app/controller/AdminController/ManagerBookController.php';
            $controller = new ManagerBookController();
            break;
        case 'fine':
            require_once 'app/controller/AdminController/AdminFineController.php';
            $controller = new AdminFineController();
            break;
        case 'request':
            require_once 'app/controller/AdminController/AdminRequestController.php';
            $controller = new AdminRequestController();
            break;
        case 'report':
            require_once 'app/controller/AdminController/AdminReportController.php';
            $controller = new AdminReportController();
            break;
        case 'user':
            require_once 'app/controller/AdminController/UserController.php';
            $controller = new UserController();
            break;
        case 'adminpassword':
            require_once 'app/controller/AdminController/AdminPasswordController.php';
            $controller = new AdminPasswordController();
            break;
        case 'chat':
            // Student chat interface
            require_once 'app/controller/ChatUserController.php';
            $controller = new ChatUserController();
            break;
        case 'admin_chat':
            // Lưu ý: Đảm bảo bạn tạo file ChatController.php đúng đường dẫn này
            require_once 'app/controller/AdminController/ChatController.php';
            $controller = new ChatController();
            break;
        default:
            die('Controller không tồn tại');
    }
    if (!method_exists($controller, $a)) {
        die('Action không tồn tại');
    }
    $controller->$a();
