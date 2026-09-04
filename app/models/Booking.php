<?php 
declare(strict_types=1);

class Booking
{
    private PDO $conn;
    private string $table_name = 'bookings';

    public ?int $id = null;
    public ?int $user_id = null;
    public ?int $staff_id = null;
    public ?string $customer_name = null;
    public ?string $customer_phone = null;
    public ?int $service_id = null;
    public ?float $total_amount = 0;
    public ?string $booking_date = null;
    public ?string $start_time = null;
    public ?string $address = null;
    public ?string $note = null;
    public ?string $status = null;
    public ?string $created_at = null;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function create(array $data): bool
    {
        $price = 0;
        if (!empty($data['service_id'])) {
            try {
                $sqlService = "SELECT price_value FROM services WHERE id = :sid LIMIT 1";
                $stmtService = $this->conn->prepare($sqlService);
                $stmtService->bindValue(':sid', $data['service_id']);
                $stmtService->execute();
                $service = $stmtService->fetch(PDO::FETCH_ASSOC);
                
                if ($service) {
                    $price = (float)$service['price_value'];
                }
            } catch (Exception $e) {
                $price = 0;
            }
        }

        $query = "INSERT INTO {$this->table_name} 
                  (user_id, staff_id, customer_name, customer_phone, service_id, total_amount, booking_date, start_time, address, note, status, created_at) 
                  VALUES 
                  (:user_id, :staff_id, :customer_name, :customer_phone, :service_id, :total_amount, :booking_date, :start_time, :address, :note, 'pending', NOW())";

        try {
            $stmt = $this->conn->prepare($query);

            $this->user_id = !empty($data['user_id']) ? (int)$data['user_id'] : null;
            $this->staff_id = !empty($data['staff_id']) ? (int)$data['staff_id'] : null;
            
            $this->customer_name  = strip_tags(trim($data['customer_name'] ?? ''));
            $this->customer_phone = strip_tags(trim($data['customer_phone'] ?? ''));
            $this->service_id     = (int)($data['service_id'] ?? 0);
            $this->total_amount   = $price; 
            $this->booking_date   = strip_tags(trim($data['booking_date'] ?? ''));
            $this->start_time     = strip_tags(trim($data['start_time'] ?? ''));
            $this->address        = strip_tags(trim($data['address'] ?? ''));
            $this->note           = strip_tags(trim($data['note'] ?? ''));
            $this->status         = 'pending';

            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':staff_id', $this->staff_id, PDO::PARAM_INT);
            $stmt->bindParam(':customer_name', $this->customer_name);
            $stmt->bindParam(':customer_phone', $this->customer_phone);
            $stmt->bindParam(':service_id', $this->service_id, PDO::PARAM_INT);
            $stmt->bindParam(':total_amount', $this->total_amount);
            $stmt->bindParam(':booking_date', $this->booking_date);
            $stmt->bindParam(':start_time', $this->start_time);
            $stmt->bindParam(':address', $this->address);
            $stmt->bindParam(':note', $this->note);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error (Booking::create): " . $e->getMessage());
            return false;
        }
    }
    
    public function getAll(): array
    {
        $query = "SELECT 
                    b.id, 
                    b.user_id,
                    b.staff_id,
                    b.customer_name, 
                    b.customer_phone, 
                    b.total_amount, 
                    b.booking_date, 
                    b.start_time, 
                    b.address, 
                    b.note,
                    b.status,
                    b.created_at,
                    s.name AS service_name,
                    st.name AS staff_name 
                  FROM {$this->table_name} b
                  LEFT JOIN services s ON b.service_id = s.id
                  LEFT JOIN staff st ON b.staff_id = st.id 
                  ORDER BY b.created_at DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error (Booking::getAll): " . $e->getMessage());
            return [];
        }
    }
}