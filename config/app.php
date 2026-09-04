<?php
declare(strict_types=1);

define('APP_NAME', 'Giúp Việc Nhanh');
define('APP_VERSION', '1.0.0');

$whitelist_ip = ['127.0.0.1', '::1', 'localhost'];
$is_local = in_array($_SERVER['REMOTE_ADDR'] ?? '', $whitelist_ip);

define('APP_ENV', $is_local ? 'local' : 'production');

define('APP_DEBUG', APP_ENV === 'local');

if (APP_DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
}

$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || 
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') 
            ? 'https://' : 'http://';

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$baseUrl = rtrim($protocol . $host . $scriptPath, '/\\') . '/';

define('APP_URL', $baseUrl);

date_default_timezone_set('Asia/Ho_Chi_Minh');
setlocale(LC_ALL, 'vi_VN.UTF-8');

if (session_status() === PHP_SESSION_NONE) {
    
    $cookieParams = [
        'lifetime' => 3600,                 
        'path'     => '/',                  
        'domain'   => '',                   
        'secure'   => (APP_URL !== 'http://localhost/'), 
        'httponly' => true,                 
        'samesite' => 'Lax'                
    ];

    session_set_cookie_params($cookieParams);
    session_name('GVN_SESSION_ID');
}
?>