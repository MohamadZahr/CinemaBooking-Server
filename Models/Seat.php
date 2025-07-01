<?php
require_once("Model.php");

class Seat extends Model {
    private int $id;
    private int $auditorium_id;
    private string $row_label;
    private int $seat_number;
    private string $seat_type;

    protected static string $table = "seats";

    public function __construct(array $data) {
        $this->id = (int) $data['id'];
        $this->auditorium_id = (int) $data['auditorium_id'];
        $this->row_label = $data['row_label'];
        $this->seat_number = (int) $data['seat_number'];
        $this->seat_type = $data['seat_type'];
    }

    public function getId(): int { return $this->id; }
    public function getAuditoriumId(): int { return $this->auditorium_id; }
    public function getRowLabel(): string { return $this->row_label; }
    public function getSeatNumber(): int { return $this->seat_number; }
    public function getSeatType(): string { return $this->seat_type; }

    public function toArray(): array {
        return [
            "id" => $this->id,
            "auditorium_id" => $this->auditorium_id,
            "row_label" => $this->row_label,
            "seat_number" => $this->seat_number,
            "seat_type" => $this->seat_type
        ];
    }
}
