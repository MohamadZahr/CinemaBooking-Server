<?php
require_once("../connection/connection.php");
require_once("../models/Movie.php");
require_once("../connection/cors.php");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $sql = "
        SELECT DISTINCT m.*
        FROM movies m
        INNER JOIN showtimes s ON m.id = s.movie_id
        WHERE (CURRENT_DATE() >= s.start_date) AND (s.end_date >= CURRENT_DATE());
    ";

    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $movies = [];
    while ($row = $result->fetch_assoc()) {
        $movie = new Movie($row);
        $movies[] = $movie->toArray();
    }

    echo json_encode([
        "status" => 200,
        "now_showing" => $movies
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => 500,
        "error" => "Server error: " . $e->getMessage()
    ]);
}
