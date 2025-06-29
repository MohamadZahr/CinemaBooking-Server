<?php
require_once("../connection/cors.php");
require_once("../connection/connection.php");

try {
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
        ORDER BY s.auditorium_id, s.time_slot ASC
    ";

    $stmt = $mysqli->prepare($sql);
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
