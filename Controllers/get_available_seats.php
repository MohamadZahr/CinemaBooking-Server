<?php
require_once("../connection/connection.php");
require_once("../connection/cors.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["status" => 405, "error" => "Method Not Allowed"]);
    exit;
}

$auditorium_id = $_GET['auditorium_id'] ?? null;
$booking_date = $_GET['booking_date'] ?? null;
$time_slot = $_GET['time_slot'] ?? null;

if (!$auditorium_id || !$booking_date || !$time_slot) {
    http_response_code(400);
    echo json_encode(["status" => 400, "error" => "Missing parameters"]);
    exit;
}

try {
    $sql = "
        SELECT s.*
        FROM seats s
        WHERE s.auditorium_id = ?
        AND s.id NOT IN (
            SELECT b.seat_id
            FROM bookings b
            WHERE b.auditorium_id = ?
              AND b.booking_date = ?
              AND b.time_slot = ?
        )
        ORDER BY s.row_label, s.seat_number;
    ";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iiss", $auditorium_id, $auditorium_id, $booking_date, $time_slot);
    $stmt->execute();
    $result = $stmt->get_result();

    $seats = [];
    while ($row = $result->fetch_assoc()) {
        $seats[] = $row;
    }

    echo json_encode(["status" => 200, "available_seats" => $seats]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => 500, "error" => "Server error: " . $e->getMessage()]);
}
