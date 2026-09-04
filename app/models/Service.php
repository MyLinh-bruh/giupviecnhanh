<?php
declare(strict_types=1);

class Service
{
    private PDO $conn;
    private string $table_name = 'services';
    
    public ?int $id = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $price_text = null;
    public ?string $icon = null;
    public ?string $bg_color = null;
    public ?string $text_color = null;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM {$this->table_name} ORDER BY id ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error (Service::getAll): " . $e->getMessage());
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
            error_log("Database Error (Service::getById): " . $e->getMessage());
            return null;
        }
    }

    public function create(array $data): bool
    {
        $query = "INSERT INTO {$this->table_name} 
                  (name, description, price_text, icon, bg_color, text_color) 
                  VALUES (:name, :description, :price_text, :icon, :bg_color, :text_color)";
        try {
            $stmt = $this->conn->prepare($query);
            $this->name        = strip_tags(trim($data['name'] ?? ''));
            $this->description = strip_tags(trim($data['description'] ?? ''));
            $this->price_text  = strip_tags(trim($data['price_text'] ?? ''));
            $this->icon        = strip_tags(trim($data['icon'] ?? ''));
            $this->bg_color    = strip_tags(trim($data['bg_color'] ?? ''));
            $this->text_color  = strip_tags(trim($data['text_color'] ?? ''));

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':price_text', $this->price_text);
            $stmt->bindParam(':icon', $this->icon);
            $stmt->bindParam(':bg_color', $this->bg_color);
            $stmt->bindParam(':text_color', $this->text_color);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error (Service::create): " . $e->getMessage());
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        $query = "UPDATE {$this->table_name} SET
                  name = :name,
                  description = :description,
                  price_text = :price_text,
                  icon = :icon,
                  bg_color = :bg_color,
                  text_color = :text_color
                  WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $this->name        = strip_tags(trim($data['name'] ?? ''));
            $this->description = strip_tags(trim($data['description'] ?? ''));
            $this->price_text  = strip_tags(trim($data['price_text'] ?? ''));
            $this->icon        = strip_tags(trim($data['icon'] ?? ''));
            $this->bg_color    = strip_tags(trim($data['bg_color'] ?? ''));
            $this->text_color  = strip_tags(trim($data['text_color'] ?? ''));

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':price_text', $this->price_text);
            $stmt->bindParam(':icon', $this->icon);
            $stmt->bindParam(':bg_color', $this->bg_color);
            $stmt->bindParam(':text_color', $this->text_color);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error (Service::update): " . $e->getMessage());
            return false;
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
            error_log("Database Error (Service::delete): " . $e->getMessage());
            return false;
        }
    }

    public function countAll(): int
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table_name}";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($row['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Database Error (Service::countAll): " . $e->getMessage());
            return 0;
        }
    }
}
