<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/db.php';
require_once '../../app/helpers/auth.php';
require_once '../../app/helpers/utils.php';

requireAdmin();

if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();
    $id = $_GET['id'];

    try {
        $query = "UPDATE bookings SET status = 'cancelled' WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            setFlash('success', 'Đã hủy đơn đặt lịch.');
        } else {
            setFlash('error', 'Lỗi: Không thể hủy đơn này.');
        }
    } catch (PDOException $e) {
        setFlash('error', 'Lỗi Database: ' . $e->getMessage());
    }
}

header("Location: ../bookings.php");
exit();
?>