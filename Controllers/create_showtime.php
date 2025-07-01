<?php
require_once("../connection/cors.php");
require_once("../models/Showtime.php");
require_once("../connection/connection.php");

header("Content-Type: application/json");

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
        http_response_code(400);
        echo json_encode(["status" => 400, "error" => "Missing field: $field"]);
        exit;
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
    http_response_code(409);
    echo json_encode([
        "status" => 409,
        "error" => "Selected time slot is already booked for this auditorium."
    ]);
    exit;
}

$showtime = Showtime::create($mysqli, $data);

if ($showtime) {
    echo json_encode([
        "status" => 200,
        "message" => "Showtime created successfully",
        "showtime" => $showtime->toArray()
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "error" => "Failed to create showtime"
    ]);
}
