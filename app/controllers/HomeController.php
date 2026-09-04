<?php
require_once ROOT . '/app/models/Service.php';

class HomeController {

    public function index() {
        try {
            $database = new Database();
            $db = $database->getConnection();
            $serviceModel = new Service($db);

            $allServices = $serviceModel->getAll();
            $featuredServices = array_slice($allServices, 0, 4);

            $this->render('home/index', [
                'services' => $featuredServices,
                'pageTitle' => 'Trang chủ - Giúp Việc Nhanh'
            ]);

        } catch (Exception $e) {
            error_log("Error in HomeController: " . $e->getMessage());
            echo "Đang bảo trì hệ thống. Vui lòng quay lại sau.";
        }
    }

    private function render($viewPath, $data = []) {
        if (!empty($data)) {
            extract($data);
        }

        require_once ROOT . '/views/layouts/header.php';
        
        $file = ROOT . '/views/' . $viewPath . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            echo "Lỗi: Không tìm thấy view file ($viewPath)";
        }

        require_once ROOT . '/views/layouts/footer.php';
    }
}
?>