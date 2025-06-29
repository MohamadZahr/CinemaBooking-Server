<?php
require_once("../connection/cors.php");
require_once("../models/Showtime.php");
require_once("../connection/connection.php");

header("Content-Type: application/json");

// if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
//     http_response_code(405);
//     echo json_encode(["status" => 405, "error" => "Method Not Allowed"]);
//     exit;
// }

// Read JSON input (DELETE typically uses raw body)
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id"])) {
    http_response_code(400);
    echo json_encode(["status" => 400, "error" => "Missing showtime ID"]);
    exit;
}

$id = (int) $data["id"];

// Fetch showtime using the find method from the base Model
$record = Showtime::find($mysqli, $id);
if (!$record) {
    http_response_code(404);
    echo json_encode(["status" => 404, "error" => "Showtime not found"]);
    exit;
}

// Convert associative array to Showtime instance
$showtime = $record;
$success = $showtime->delete($mysqli);

if ($success) {
    echo json_encode(["status" => 200, "message" => "Showtime deleted successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => 500, "error" => "Failed to delete showtime"]);
}
