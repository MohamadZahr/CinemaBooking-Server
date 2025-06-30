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
        SELECT DISTINCT *
        FROM movies
        WHERE (CURRENT_DATE() <= release_date)
        ORDER BY popularity DESC, release_date ASC
        LIMIT 10;
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
        "upcoming" => $movies ?? []
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => 500,
        "error" => "Server error: " . $e->getMessage()
    ]);
}
