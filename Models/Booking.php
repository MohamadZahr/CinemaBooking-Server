<?php
require_once("Model.php");

class Booking extends Model {
    private int $id;
    private int $user_id;
    private int $showtime_id;
    private string $booking_date;
    private string $time_slot;
    private float $total_price;
    private string $created_at;

    protected static string $table = "bookings";

    public function __construct(array $data) {
        $this->id = (int) $data['id'];
        $this->user_id = (int) $data['user_id'];
        $this->showtime_id = (int) $data['showtime_id'];
        $this->booking_date = $data['booking_date'];
        $this->time_slot = $data['time_slot'];
        $this->total_price = (float) $data['total_price'];
        $this->created_at = $data['created_at'] ?? date("Y-m-d H:i:s");
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->user_id; }
    public function getShowtimeId(): int { return $this->showtime_id; }
    public function getBookingDate(): string { return $this->booking_date; }
    public function getTimeSlot(): string { return $this->time_slot; }
    public function getTotalPrice(): float { return $this->total_price; }
    public function getCreatedAt(): string { return $this->created_at; }

    public static function create(mysqli $mysqli, array $data): ?Booking {
        $sql = "INSERT INTO bookings (user_id, showtime_id, booking_date, time_slot, total_price)
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            "iissd",
            $data["user_id"],
            $data["showtime_id"],
            $data["booking_date"],
            $data["time_slot"],
            $data["total_price"]
        );

        if ($stmt->execute()) {
            $data['id'] = $mysqli->insert_id;
            return new Booking($data);
        }

        return null;
    }

    public function toArray(): array {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "showtime_id" => $this->showtime_id,
            "booking_date" => $this->booking_date,
            "time_slot" => $this->time_slot,
            "total_price" => $this->total_price,
            "created_at" => $this->created_at,
        ];
    }

}
