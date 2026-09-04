<?php
require_once ROOT . '/app/models/Service.php';

class ServiceController {

    public function list() {
        try {
            $database = new Database();
            $db = $database->getConnection();
            $serviceModel = new Service($db);
            $services = $serviceModel->getAll();
            $this->render('services/list', [
                'services' => $services,
                'pageTitle' => 'Danh sách dịch vụ - Giúp Việc Nhanh'
            ]);

        } catch (Exception $e) {
            error_log("Error in ServiceController::list: " . $e->getMessage());
            echo "Hệ thống đang gặp sự cố gián đoạn. Vui lòng thử lại sau ít phút.";
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
            echo "Lỗi: Không tìm thấy file giao diện ($viewPath)";
        }
        require_once ROOT . '/views/layouts/footer.php';
    }
}
?>