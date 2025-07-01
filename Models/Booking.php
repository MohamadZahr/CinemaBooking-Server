<?php
require_once("Model.php");

class Booking extends Model {
    private int $id;
    private int $user_id;
    private int $auditorium_id;
    private int $seat_id;
    private string $time_slot;
    private float $total_price;
    private string $booking_date;
    private string $created_at;

    protected static string $table = "bookings";

    public function __construct(array $data) {
        $this->id = (int) $data['id'];
        $this->user_id = (int) $data['user_id'];
        $this->auditorium_id = (int) $data['auditorium_id'];
        $this->seat_id = (int) $data['seat_id'];
        $this->time_slot = $data['time_slot'];
        $this->total_price = (float) $data['total_price'];
        $this->booking_date = $data['booking_date'];
        $this->created_at = $data['created_at'] ?? '';
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function getAuditoriumId(): int { return $this->auditorium_id; }
    public function getSeatId(): int { return $this->seat_id; }
    public function getTimeSlot(): string { return $this->time_slot; }
    public function getTotalPrice(): float { return $this->total_price; }
    public function getBookingDate(): string { return $this->booking_date; }
    public function getCreatedAt(): string { return $this->created_at; }

    public function setTimeSlot(string $slot): void { $this->time_slot = $slot; }
    public function setTotalPrice(float $price): void { $this->total_price = $price; }
    public function setBookingDate(string $date): void { $this->booking_date = $date; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'auditorium_id' => $this->auditorium_id,
            'seat_id' => $this->seat_id,
            'time_slot' => $this->time_slot,
            'total_price' => $this->total_price,
            'booking_date' => $this->booking_date,
            'created_at' => $this->created_at
        ];
    }

    public static function create(mysqli $mysqli, array $data): ?Booking {
        $sql = "INSERT INTO bookings (user_id, auditorium_id, seat_id, time_slot, total_price, booking_date) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            "iiisds",
            $data["user_id"],
            $data["auditorium_id"],
            $data["seat_id"],
            $data["time_slot"],
            $data["total_price"],
            $data["booking_date"]
        );

        if ($stmt->execute()) {
            $data['id'] = $mysqli->insert_id;
            $getStmt = $mysqli->prepare("SELECT created_at FROM bookings WHERE id = ?");
            $getStmt->bind_param("i", $data['id']);
            $getStmt->execute();
            $result = $getStmt->get_result();
            $row = $result->fetch_assoc();
            $data['created_at'] = $row['created_at'];
            
            return new Booking($data);
        }

        return null;
    }

    public function update(mysqli $mysqli): bool {
        $sql = "UPDATE bookings SET time_slot = ?, total_price = ?, booking_date = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sdsi", $this->time_slot, $this->total_price, $this->booking_date, $this->id);
        return $stmt->execute();
    }

    public function delete(mysqli $mysqli): bool {
        $sql = "DELETE FROM bookings WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    public static function isSeatBooked(mysqli $mysqli, int $seatId, int $auditoriumId, string $timeSlot, string $bookingDate): bool {
        $sql = "SELECT COUNT(*) as count FROM bookings WHERE seat_id = ? AND auditorium_id = ? AND time_slot = ? AND booking_date = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iiss", $seatId, $auditoriumId, $timeSlot, $bookingDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] > 0;
    }
}