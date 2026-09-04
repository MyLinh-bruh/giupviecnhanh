<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../app/helpers/auth.php';

requireAdmin();

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $database = new Database();
    $db = $database->getConnection();

    $stmt = $db->prepare("DELETE FROM bookings WHERE id = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        $_SESSION['deleted_booking'] = true;
    }
}

header("Location: /GiupViecNhanh/admin/bookings.php");
exit;
