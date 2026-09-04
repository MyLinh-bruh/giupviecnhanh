<?php
require_once ROOT . '/app/models/Staff.php';

class StaffController {

    public function list() {
        try {
            $database = new Database();
            $db = $database->getConnection();
            $staffModel = new Staff($db);

            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $gender = isset($_GET['gender']) ? trim($_GET['gender']) : '';

            $staffList = $staffModel->search($search, $gender);

            require_once ROOT . '/views/layouts/header.php';
            require_once ROOT . '/views/staff/list.php';
            require_once ROOT . '/views/layouts/footer.php';

        } catch (Exception $e) {
            error_log("Error in StaffController::list: " . $e->getMessage());
            echo "Đang xảy ra lỗi hệ thống tìm kiếm. Vui lòng thử lại sau.";
        }
    }
}
?>