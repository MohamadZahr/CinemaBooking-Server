<?php

require_once __DIR__ . '/../controllers/BaseController.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../connection/cors.php';

class BookingController extends BaseController
{
    public function createBooking()
    {
        global $mysqli;

        try {
            $input = json_decode(file_get_contents('php://input'), true);

            $required = ['user_id', 'auditorium_id', 'seat_id', 'time_slot', 'total_price', 'booking_date'];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    self::error("Missing required field: $field", 400);
                    return;
                }
            }

            if (Booking::isSeatBooked($mysqli, $input['seat_id'], $input['auditorium_id'], $input['time_slot'], $input['booking_date'])) {
                self::error("Seat already booked", 409);
                return;
            }

            $booking = Booking::create($mysqli, $input);
            if ($booking) {
                self::success(["status" => 201, "booking" => $booking->toArray()]);
            } else {
                self::error("Failed to create booking", 500);
            }
        } catch (Exception $e) {
            self::error("Server error: " . $e->getMessage(), 500);
        }
    }

    public function getAvailableSeats()
    {
        global $mysqli;

        try {
            $auditorium_id = $_GET['auditorium_id'] ?? null;
            $booking_date = $_GET['booking_date'] ?? null;
            $time_slot = $_GET['time_slot'] ?? null;

            if (!$auditorium_id || !$booking_date || !$time_slot) {
                self::error("Missing parameters", 400);
                return;
            }

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
                ORDER BY s.row_label, s.seat_number
            ";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("iiss", $auditorium_id, $auditorium_id, $booking_date, $time_slot);
            $stmt->execute();
            $result = $stmt->get_result();

            $seats = [];
            while ($row = $result->fetch_assoc()) {
                $seats[] = $row;
            }

            self::success(["available_seats" => $seats]);
        } catch (Exception $e) {
            self::error("Server error: " . $e->getMessage(), 500);
        }
    }

    public function getBookings()
    {
        global $mysqli;

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

            self::success(["bookings" => $bookings]);
        } catch (Exception $e) {
            self::error("Server error: " . $e->getMessage(), 500);
        }
    }
}
