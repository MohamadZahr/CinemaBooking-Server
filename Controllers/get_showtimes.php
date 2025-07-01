<?php
require_once("../connection/cors.php");
require_once("../connection/connection.php");

header('Content-Type: application/json');

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

    echo json_encode([
        "status" => 200,
        "showtimes" => $showtimes
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "error" => "Server error: " . $e->getMessage()
    ]);
}
