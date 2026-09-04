<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['user']) && !empty($_SESSION['user']);
}

function currentUser(): ?array {
    return isLoggedIn() ? $_SESSION['user'] : null;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        if (function_exists('setFlash')) {
            setFlash('error', 'Vui lòng đăng nhập để tiếp tục.');
        }
        header("Location: ../public/index.php?page=login");
        exit();
    }
}

function isAdmin(): bool {
    $user = currentUser();
    return $user !== null && 
           isset($user['role']) && 
           $user['role'] === 'admin';
}

function requireAdmin(): void {
    requireLogin();

    if (!isAdmin()) {
        error_log("Unauthorized access attempt to Admin panel by User ID: " . ($_SESSION['user']['id'] ?? 'unknown'));

        if (function_exists('setFlash')) {
            setFlash('error', 'Chỉ Quản Trị Viên mới có quyền truy cập!');
        }
        header("Location: ../public/index.php?page=home");
        exit();
    }
}
?>