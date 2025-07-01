<?php
require("../connection/connection.php");

$apiKey = "00c29e97f72e52684a540a605ffc831a";
$apiUrl = "https://api.themoviedb.org/3/discover/movie?api_key={$apiKey}&primary_release_year=2025&sort_by=popularity.desc&page=5";

$json = file_get_contents($apiUrl);
$data = json_decode($json, true);

if (!isset($data['results'])) {
    die("Error: Invalid API response - " . print_r($data, true));
}

$stmt = $mysqli->prepare("INSERT INTO movies (
    title, 
    description, 
    rating, 
    poster_path, 
    popularity, 
    release_date,
    now_showing
) VALUES (?, ?, ?, ?, ?, ?, ?)"); 

if (!$stmt) {
    die("Prepare failed: " . $mysqli->error); 
}

$stmt->bind_param(
    "ssdsssi",  
    $title, 
    $description, 
    $rating, 
    $poster_path, 
    $popularity, 
    $release_date,  
    $now_showing
);

$count = 0;
foreach ($data['results'] as $movie) {

    $title = substr($movie['title'], 0, 150);
    $description = $movie['overview'] ?: '';
    $rating = $movie['vote_average'] ?: 0.0;
    $poster_path = $movie['poster_path'] ?: '';
    $popularity = $movie['popularity'] ?: 0.0;
    $release_date = $movie['release_date'] ?: null;
    $now_showing = 0;
    if ($stmt->execute()) {
        $count++;
    } else {
        echo "Error inserting " . $title . ": " . $stmt->error . "\n";
    }
}

echo "Successfully inserted $count movies\n";

$stmt->close();
$mysqli->close();
?>