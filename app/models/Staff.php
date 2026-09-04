<?php
declare(strict_types=1);

class Staff {
    private PDO $conn;
    private string $table_name = "staff";

    public ?int $id = null;
    public ?string $name = null;
    public ?string $gender = null;
    public ?int $age = null;
    public ?float $rating = null;
    public ?string $service_names = null; 
    public ?float $price = null;
    public ?string $image = null;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function search(string $keyword = '', string $gender = ''): array {
        $query = "SELECT s.* FROM " . $this->table_name . " s";
        $where = ["s.is_available = 1"]; 
        $params = [];

        if (!empty($keyword)) {
            $keyword = trim($keyword);
            $searchTerm = "%{$keyword}%";

            $searchConditions = [];

            $searchConditions[] = "s.name LIKE :k_name";
            $params[':k_name'] = $searchTerm;

            $searchConditions[] = "s.service_names LIKE :k_services";
            $params[':k_services'] = $searchTerm;

            if (!empty($searchConditions)) {
                $where[] = "(" . implode(" OR ", $searchConditions) . ")";
            }
        }

        if (!empty($gender) && in_array($gender, ['Nam', 'Nữ'])) {
            $where[] = "s.gender = :gender";
            $params[':gender'] = $gender;
        }

        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }

        $query .= " ORDER BY s.rating DESC, s.price ASC";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Database Error (Staff::search): " . $e->getMessage());
            return [];
        }
    }

    public function getById(int $id): array|false {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error (Staff::getById): " . $e->getMessage());
            return false;
        }
    }
}