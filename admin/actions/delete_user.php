<?php
session_start();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../app/helpers/auth.php';

requireAdmin();

$database = new Database();
$conn = $database->getConnection();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $id) {
        $_SESSION['error'] = "Bạn không thể tự xóa tài khoản của chính mình!";
        header("Location: ../users.php");
        exit();
    }
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Đã xóa khách hàng thành công!";
    } else {
        $_SESSION['error'] = "Lỗi hệ thống: Không thể xóa khách hàng này (có thể do ràng buộc dữ liệu).";
    }
} else {
    $_SESSION['error'] = "ID khách hàng không hợp lệ.";
}

header("Location: ../users.php");
exit();
?>