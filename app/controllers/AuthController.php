<?php
require_once '../app/models/User.php';

class AuthController {
    
    public function login() {
        require_once '../views/layouts/header.php';
        require_once '../views/auth/login.php';
        require_once '../views/layouts/footer.php';
    }

    public function register() {
        require_once '../views/layouts/header.php';
        require_once '../views/auth/register.php';
        require_once '../views/layouts/footer.php';
    }

    public function authenticate() {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if (empty($email) || empty($password)) {
            setFlash('error', 'Vui lòng nhập đầy đủ Email và Mật khẩu.');
            header("Location: index.php?page=login");
            exit();
        }

        $loggedInUser = $user->login($email, $password);

        if ($loggedInUser) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user'] = [
                'id'    => $loggedInUser['id'],
                'name'  => $loggedInUser['name'],
                'email' => $loggedInUser['email'],
                'role'  => $loggedInUser['role']
            ];

            setFlash('success', 'Đăng nhập thành công!');

            if ($loggedInUser['role'] === 'admin') {
                header("Location: /GiupViecNhanh/admin/index.php");
            } else {
                header("Location: /GiupViecNhanh/public/index.php?page=home");
            }
            exit();

        } else {
            setFlash('error', 'Email hoặc mật khẩu không chính xác.');
            header("Location: index.php?page=login");
            exit();
        }
    }

    public function store() {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $name     = isset($_POST['name']) ? trim($_POST['name']) : '';
        $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if (empty($name) || empty($email) || empty($password)) {
            setFlash('error', 'Vui lòng điền đầy đủ thông tin đăng ký.');
            header("Location: index.php?page=register");
            exit();
        }

        if (strlen($password) < 6) {
            setFlash('error', 'Mật khẩu phải có ít nhất 6 ký tự.');
            header("Location: index.php?page=register");
            exit();
        }

        if ($user->nameExists($name)) {
            setFlash('error', 'Tên đăng kí tài khoản đã tồn tại. Vui lòng chọn tên khác!');
            header("Location: index.php?page=register");
            exit();
        } 

        if ($user->emailExists($email)) {
            setFlash('error', 'Email này đã được sử dụng. Vui lòng chọn email khác.');
            header("Location: index.php?page=register");
            exit();
        }

        if ($user->create($name, $email, $password)) {
            setFlash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
            header("Location: index.php?page=login");
            exit();
        } else {
            setFlash('error', 'Lỗi hệ thống. Không thể tạo tài khoản lúc này.');
            header("Location: index.php?page=register");
            exit();
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['user']);
        session_destroy();
        
        session_start();
        setFlash('success', 'Bạn đã đăng xuất thành công.');
        header("Location: index.php?page=home");
        exit();
    }
}
?>