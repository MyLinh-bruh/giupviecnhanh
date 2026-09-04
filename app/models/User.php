<?php
declare(strict_types=1);

class User
{
    private PDO $conn;
    private string $table_name = "users";

    public ?int $id = null;
    public ?string $name = null;
    public ?string $email = null;
    public ?string $password = null;
    public ?string $role = null;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function create(string $name, string $email, string $password, string $role = 'user'): bool
    {
        $query = "INSERT INTO {$this->table_name} 
              (name, email, password, role) 
              VALUES (:name, :email, :password, :role)";

        try {
            $stmt = $this->conn->prepare($query);
            $name  = strip_tags(trim($name));
            $email = strip_tags(trim($email));
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':role', $role);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error (User::create): " . $e->getMessage());
            return false;
        }
    }

    public function login(string $email, string $password): ?array
    {
        $query = "SELECT id, name, email, password, role 
                  FROM {$this->table_name} 
                  WHERE email = :email LIMIT 1";

        try {
            $stmt = $this->conn->prepare($query);
            $email = strip_tags(trim($email));
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                unset($user['password']);
                return $user;
            }

            return null;
        } catch (PDOException $e) {
            error_log("Database Error (User::login): " . $e->getMessage());
            return null;
        }
    }

    public function nameExists($name) {
         $query = "SELECT id FROM " . $this->table_name . " WHERE name = ? LIMIT 1";
         $stmt = $this->conn->prepare($query);
         $name = htmlspecialchars(strip_tags($name));
         $stmt->bindParam(1, $name);
         $stmt->execute();
        if ($stmt->rowCount() > 0) {
         return true;
        }
        return false;
        }

    public function emailExists(string $email): bool
    {
        $query = "SELECT id FROM {$this->table_name} WHERE email = :email LIMIT 1";

        try {
            $stmt = $this->conn->prepare($query);
            $email = strip_tags(trim($email));
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return (bool) $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Database Error (User::emailExists): " . $e->getMessage());
            return false;
        }
    }

    public function getAllByRole(string $role): array
    {
        $query = "SELECT * FROM {$this->table_name} WHERE role = :role ORDER BY timestamp DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error (User::getAllByRole): " . $e->getMessage());
            return [];
        }
    }

    public function getById(int $id): ?array
    {
        $query = "SELECT * FROM {$this->table_name} WHERE id = :id LIMIT 1";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Database Error (User::getById): " . $e->getMessage());
            return null;
        }
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM {$this->table_name} WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error (User::delete): " . $e->getMessage());
            return false;
        }
    }

    public function countByRole(string $role): int
    {
        $query = "SELECT COUNT(*) AS total FROM {$this->table_name} WHERE role = :role";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($row['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Database Error (User::countByRole): " . $e->getMessage());
            return 0;
        }
    }
}
