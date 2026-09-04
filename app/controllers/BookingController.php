<?php
require_once '../app/models/Service.php';
require_once '../app/models/Booking.php';
require_once '../app/models/Staff.php';

class BookingController {
    
    public function create() {
        if (!function_exists('isLoggedIn') || !isLoggedIn()) {
            if (function_exists('setFlash')) {
                setFlash('error', 'Bạn cần đăng nhập để thực hiện đặt lịch!');
            }
            header("Location: index.php?page=login");
            exit();
        }

        $database = new Database();
        $db = $database->getConnection();

        $serviceModel = new Service($db);
        $staffModel = new Staff($db); 

        $staff = null; 
        $services = $serviceModel->getAll(); 
        
        $staff_id = $_GET['staff_id'] ?? null;
        
        if ($staff_id) {
            $staff = $staffModel->getById((int)$staff_id);
            
            if ($staff) {
                $staffSkillsString = strtolower($staff['service_names'] ?? ''); 
                
                if (!empty($staffSkillsString)) {
                    $filteredServices = [];
                    foreach ($services as $service) {
                        $serviceNameLower = strtolower($service['name']);
                        if (str_contains($staffSkillsString, $serviceNameLower)) {
                            $filteredServices[] = $service;
                        }
                    }
                    $services = $filteredServices;
                } else {
                    $services = []; 
                }
            }
        }
        require_once '../views/layouts/header.php';
        require_once '../views/booking/form.php';
        require_once '../views/layouts/footer.php';
    }

    public function store() {
        if (!function_exists('isLoggedIn') || !isLoggedIn()) {
            header("Location: index.php?page=login");
            exit();
        }

        $database = new Database();
        $db = $database->getConnection();

        $bookingModel = new Booking($db);
        
        $data = $_POST;
        $currentUser = function_exists('currentUser') ? currentUser() : null;
        
        if ($currentUser && isset($currentUser['id'])) {
            $data['user_id'] = $currentUser['id'];
        } elseif (isset($_SESSION['user']['id'])) {
            $data['user_id'] = $_SESSION['user']['id'];
        }

        if ($bookingModel->create($data)) {
            if (function_exists('setFlash')) {
                setFlash('success', 'Đặt lịch thành công! Chúng tôi sẽ liên hệ lại với bạn trong vòng 10 phút.');
            }

            $serviceModel = new Service($db);
            $services = $serviceModel->getAll();
            $staff = null; 

            require_once '../views/layouts/header.php';
            require_once '../views/booking/form.php';
            require_once '../views/layouts/footer.php';
        } else {
            if (function_exists('setFlash')) {
                setFlash('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
            }
            $serviceModel = new Service($db);
            $services = $serviceModel->getAll();
            $staff = null; 
            
            require_once '../views/layouts/header.php';
            require_once '../views/booking/form.php';
            require_once '../views/layouts/footer.php';
        }
    }
}