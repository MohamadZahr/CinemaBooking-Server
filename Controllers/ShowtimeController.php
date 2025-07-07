<?php

require_once __DIR__ . '/../controllers/BaseController.php';
require_once __DIR__ . '/../models/Showtime.php';
require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../connection/cors.php';

class ShowtimeController extends BaseController
{
    public function createShowtime()
    {
        global $mysqli;

        try {
            $data = json_decode(file_get_contents("php://input"), true) ?? $_POST;

            $required = ["movie_id", "auditorium_id", "start_date", "end_date", "time_slot"];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    self::error("Missing field: $field", 400);
                    return;
                }
            }

            $checkSql = "
                SELECT COUNT(*) AS count 
                FROM showtimes 
                WHERE auditorium_id = ? 
                  AND time_slot = ? 
                  AND end_date >= CURDATE()
            ";
            $checkStmt = $mysqli->prepare($checkSql);
            $checkStmt->bind_param("is", $data["auditorium_id"], $data["time_slot"]);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result()->fetch_assoc();

            if ($checkResult["count"] > 0) {
                self::error("Selected time slot is already booked for this auditorium.", 409);
                return;
            }

            $showtime = Showtime::create($mysqli, $data);

            if ($showtime) {
                self::success([
                    "message" => "Showtime created successfully",
                    "showtime" => $showtime->toArray()
                ]);
            } else {
                self::error("Failed to create showtime", 500);
            }
        } catch (Exception $e) {
            self::error("Server error: " . $e->getMessage(), 500);
        }
    }

    public function deleteShowtime()
    {
        global $mysqli;

        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data["id"])) {
                self::error("Missing showtime ID", 400);
                return;
            }

            $showtime = Showtime::find($mysqli, (int)$data["id"]);

            if (!$showtime) {
                self::error("Showtime not found", 404);
                return;
            }

            $success = $showtime->delete($mysqli);

            if ($success) {
                self::success(["message" => "Showtime deleted successfully"]);
            } else {
                self::error("Failed to delete showtime", 500);
            }
        } catch (Exception $e) {
            self::error("Server error: " . $e->getMessage(), 500);
        }
    }

    public function getShowtimes()
    {
        global $mysqli;

        try {
            $movie_id = $_GET['id'] ?? null;

            if ($movie_id) {
                $sql = "
                    SELECT 
                        s.id,
                        s.movie_id,
                        m.title AS movie_title,
                        s.auditorium_id,
                        CONCAT('Auditorium ', CHAR(64 + s.auditorium_id)) AS auditorium_name,
                        s.time_slot,
                        s.start_date,
                        s.end_date
                    FROM showtimes s
                    JOIN movies m ON s.movie_id = m.id
                    WHERE s.movie_id = ?
                      AND CURRENT_DATE() BETWEEN s.start_date AND s.end_date
                    ORDER BY s.auditorium_id, s.time_slot ASC
                ";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("i", $movie_id);
            } else {
                $sql = "
                    SELECT 
                        s.id,
                        s.movie_id,
                        m.title AS movie_title,
                        s.auditorium_id,
                        CONCAT('Auditorium ', CHAR(64 + s.auditorium_id)) AS auditorium_name,
                        s.time_slot,
                        s.start_date,
                        s.end_date
                    FROM showtimes s
                    JOIN movies m ON s.movie_id = m.id
                    WHERE CURRENT_DATE() BETWEEN s.start_date AND s.end_date
                    ORDER BY s.auditorium_id, s.time_slot ASC
                ";
                $stmt = $mysqli->prepare($sql);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            $showtimes = [];
            while ($row = $result->fetch_assoc()) {
                $showtimes[] = $row;
            }

            self::success(["showtimes" => $showtimes]);
        } catch (Exception $e) {
            self::error("Server error: " . $e->getMessage(), 500);
        }
    }
}
