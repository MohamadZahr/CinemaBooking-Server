<?php
require_once("../models/Movie.php");
require_once("../connection/connection.php");
require_once("../connection/cors.php");

try {
    $sql = "SELECT * FROM movies WHERE release_date <= CURRENT_DATE() ORDER BY popularity DESC";
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
        "movies" => $movies
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => 500,
        "error" => "Server error: " . $e->getMessage()
    ]);
}
