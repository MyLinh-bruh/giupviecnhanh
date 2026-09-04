<?php
ob_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ROOT', dirname(__DIR__)); 

if (file_exists(ROOT . '/config/app.php')) {
    require_once ROOT . '/config/app.php';
}

function require_file($path) {
    if (file_exists($path)) {
        require_once $path;
    } else {
        die("Lỗi hệ thống: Không tìm thấy file quan trọng ($path). Vui lòng kiểm tra lại cấu trúc thư mục.");
    }
}

// Đã xóa dấu backtick (`) bị thừa ở dòng này
require_file(ROOT . '/config/db.php');
require_file(ROOT . '/app/helpers/utils.php');
require_file(ROOT . '/app/helpers/auth.php');

require_file(ROOT . '/app/models/User.php');
require_file(ROOT . '/app/models/Service.php');
require_file(ROOT . '/app/models/Booking.php');

if (file_exists(ROOT . '/app/models/Staff.php')) {
    require_file(ROOT . '/app/models/Staff.php');
}

require_file(ROOT . '/app/controllers/HomeController.php');
require_file(ROOT . '/app/controllers/ServiceController.php');
require_file(ROOT . '/app/controllers/BookingController.php');
require_file(ROOT . '/app/controllers/AuthController.php');

if (file_exists(ROOT . '/app/controllers/StaffController.php')) {
    require_file(ROOT . '/app/controllers/StaffController.php');
}

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$valid_routes = [
    'home' => 'HomeController',
    'services' => 'ServiceController',
    'booking' => 'BookingController',
    'login' => 'AuthController',
    'register' => 'AuthController',
    'auth' => 'AuthController',
    'staff' => 'StaffController', 
    'admin_dashboard' => 'AuthController'
];

if (array_key_exists($page, $valid_routes)) {
    
    if (in_array($page, ['login', 'register']) && function_exists('isLoggedIn') && isLoggedIn()) {
        header("Location: index.php?page=home");
        exit();
    }
    
    $controllerName = $valid_routes[$page];
    $controller = new $controllerName();

    switch ($page) {
        case 'home':
            $controller->index();
            break;
        
        case 'services':
            $controller->list();
            break;

        case 'staff':
            if (method_exists($controller, 'list')) {
                $controller->list();
            } else {
                echo "Lỗi: Method list() chưa được định nghĩa trong StaffController.";
            }
            break;
        
        case 'booking':
            if ($action === 'store') {
                $controller->store();
            } else {
                $controller->create();
            }
            break;

        case 'login':
            $controller->login();
            break;

        case 'register':
            $controller->register();
            break;

        case 'auth':
            if ($action === 'authenticate') {
                $controller->authenticate();
            } elseif ($action === 'store') {
                $controller->store();
            } elseif ($action === 'logout') {
                $controller->logout();
            } else {
                header("Location: index.php?page=home");
            }
            break; // Đã bổ sung break bị thiếu

        case 'admin_dashboard':
            header("Location: /GiupViecNhanh/admin/index.php");
            exit();
            break;
    }

} else {
    $controller = new HomeController();
    $controller->index();
}

ob_end_flush();
?>