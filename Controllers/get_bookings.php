<?php
require_once("../connection/cors.php");
require_once("../connection/connection.php");
require_once("../models/Booking.php");

header('Content-Type: application/json');

try {
    $user_id = $_GET['id'] ?? null;

    $query = "
        SELECT 
            b.id,
            b.user_id,
            b.auditorium_id,
            CONCAT('Auditorium ', CHAR(64 + b.auditorium_id)) AS auditorium_name,
            b.seat_id,
            s.row_label,
            s.seat_number,
            s.seat_type,
            b.time_slot,
            b.total_price,
            b.booking_date,
            b.created_at,
            m.title AS movie_title,
            m.poster_path
        FROM bookings b
        JOIN showtimes st ON b.auditorium_id = st.auditorium_id AND b.time_slot = st.time_slot
        JOIN movies m ON st.movie_id = m.id
        JOIN seats s ON b.seat_id = s.id
    ";

    if ($user_id) {
        $query .= " WHERE b.user_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $user_id);
    } else {
        $stmt = $mysqli->prepare($query);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $row['id'] = (int)$row['id'];
        $row['user_id'] = (int)$row['user_id'];
        $row['auditorium_id'] = (int)$row['auditorium_id'];
        $row['seat_id'] = (int)$row['seat_id'];
        $row['total_price'] = (float)$row['total_price'];
        
        if ($row['poster_path']) {
            $row['poster_url'] = "https://image.tmdb.org/t/p/w300{$row['poster_path']}";
        }
        
        $bookings[] = $row;
    }

    echo json_encode([
        "status" => 200,
        "bookings" => $bookings
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => 500,
        "error" => "Server error: " . $e->getMessage()
    ]);
}