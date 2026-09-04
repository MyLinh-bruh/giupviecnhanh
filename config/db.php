<?php
declare(strict_types=1);

class Database {
    private string $host = 'localhost';
    private string $db_name = 'giupviecnhanh_db';
    private string $username = 'root';
    private string $password = '';
    private string $charset = 'utf8mb4';

    public ?PDO $conn = null;

    public function getConnection(): ?PDO {
        $this->conn = null;

        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
            PDO::ATTR_EMULATE_PREPARES   => false,                  
            PDO::ATTR_PERSISTENT         => true,                   
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}" 
        ];

        try {
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $exception) {
            error_log("Connection Error: " . $exception->getMessage());
            if (defined('APP_DEBUG') && APP_DEBUG === true) {
                die("<div style='color:red; font-weight:bold; padding:20px; border:1px solid red; background:#ffe6e6;'>
                        ❌ Lỗi kết nối Database: " . htmlspecialchars($exception->getMessage()) . "
                     </div>");
            } else {
                die("<h1>Hệ thống đang bảo trì</h1><p>Vui lòng quay lại sau ít phút.</p>");
            }
        }

        return $this->conn;
    }
}
?>