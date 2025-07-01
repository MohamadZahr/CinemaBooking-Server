<?php
require_once("../connection/connection.php");
require_once("../connection/cors.php");
require_once("../models/Booking.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status" => 405, "error" => "Method Not Allowed"]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$user_id = $input['user_id'] ?? null;
$auditorium_id = $input['auditorium_id'] ?? null;
$seat_id = $input['seat_id'] ?? null;
$time_slot = $input['time_slot'] ?? null;
$total_price = $input['total_price'] ?? null;
$booking_date = $input['booking_date'] ?? null;

if (!$user_id || !$auditorium_id || !$seat_id || !$time_slot || !$total_price || !$booking_date) {
    http_response_code(400);
    echo json_encode(["status" => 400, "error" => "Missing required fields"]);
    exit;
}

try {
    if (Booking::isSeatBooked($mysqli, $seat_id, $auditorium_id, $time_slot, $booking_date)) {
        http_response_code(409);
        echo json_encode(["status" => 409, "error" => "Seat already booked"]);
        exit;
    }

    $booking = Booking::create($mysqli, [
        'user_id' => $user_id,
        'auditorium_id' => $auditorium_id,
        'seat_id' => $seat_id,
        'time_slot' => $time_slot,
        'total_price' => $total_price,
        'booking_date' => $booking_date
    ]);

    if ($booking) {
        echo json_encode(["status" => 201, "booking" => $booking->toArray()]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => 500, "error" => "Failed to create booking"]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => 500, "error" => "Server error: " . $e->getMessage()]);
}