<?php
require_once("Model.php");

class Showtime extends Model {
    private int $id;
    private int $movie_id;
    private int $auditorium_id;
    private string $start_date;
    private string $end_date;
    private string $time_slot;

    protected static string $table = "showtimes";

    public function __construct(array $data) {
        $this->id = (int) $data['id'];
        $this->movie_id = (int) $data['movie_id'];
        $this->auditorium_id = (int) $data['auditorium_id'];
        $this->start_date = $data['start_date'];
        $this->end_date = $data['end_date'];
        $this->time_slot = $data['time_slot'];
    }
    public function getId(): int { return $this->id; }
    public function getMovieId(): int { return $this->movie_id; }
    public function getAuditoriumId(): int { return $this->auditorium_id; }
    public function getStartDate(): string { return $this->start_date; }
    public function getEndDate(): string { return $this->end_date; }
    public function getTimeSlot(): string { return $this->time_slot; }

    public function setStartDate(string $date) { $this->start_date = $date; }
    public function setEndDate(string $date) { $this->end_date = $date; }
    public function setTimeSlot(string $slot) { $this->time_slot = $slot; }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'movie_id' => $this->movie_id,
            'auditorium_id' => $this->auditorium_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'time_slot' => $this->time_slot
        ];
    }

    public static function create(mysqli $mysqli, array $data): ?Showtime {
        $sql = "INSERT INTO showtimes (movie_id, auditorium_id, start_date, end_date, time_slot) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            "iisss",
            $data["movie_id"],
            $data["auditorium_id"],
            $data["start_date"],
            $data["end_date"],
            $data["time_slot"]
        );

        if ($stmt->execute()) {
            $data['id'] = $mysqli->insert_id;
            return new Showtime($data);
        }

        return null;
    }

    public function update(mysqli $mysqli): bool {
        $sql = "UPDATE showtimes SET movie_id=?, auditorium_id=?, start_date=?, end_date=?, time_slot=? WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            "iisssi",
            $this->movie_id,
            $this->auditorium_id,
            $this->start_date,
            $this->end_date,
            $this->time_slot,
            $this->id
        );

        return $stmt->execute();
    }

    public function delete(mysqli $mysqli): bool {
        $sql = "DELETE FROM showtimes WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}
