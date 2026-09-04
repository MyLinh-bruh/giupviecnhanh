<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/db.php';
require_once '../../app/helpers/auth.php';
require_once '../../app/helpers/utils.php';

if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();
    
    $id = $_GET['id'];
    
    try {
        $query = "DELETE FROM services WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $id);
        
        if ($stmt->execute()) {
            setFlash('success', 'Đã xóa dịch vụ thành công!');
        } else {
            setFlash('error', 'Không thể xóa dịch vụ này. Vui lòng thử lại.');
        }
    } catch (PDOException $e) {
        setFlash('error', 'Lỗi: Dịch vụ này đang có đơn đặt lịch, không thể xóa ngay.');
    }
} else {
    setFlash('error', 'Thiếu ID dịch vụ cần xóa.');
}

header("Location: /GiupViecNhanh/admin/services.php");
exit();
?>