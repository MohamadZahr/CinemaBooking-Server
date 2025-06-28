<?php
require_once("../models/Showtime.php");
require_once("../connection/connection.php");
require_once("../connection/cors.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status" => 405, "error" => "Method Not Allowed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    $data = $_POST;
}

$required = ["movie_id", "auditorium_id", "start_date", "end_date", "time_slot"];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(["status" => 400, "error" => "Missing field: $field"]);
        exit;
    }
}

$showtime = Showtime::create($mysqli, $data);

if ($showtime) {
    echo json_encode([
        "status" => 200,
        "message" => "Showtime created successfully",
        "showtime" => $showtime->toArray()
    ]);
} else {
    echo json_encode([
        "status" => 500,
        "error" => "Failed to create showtime"
    ]);
}
