<?php
require("../connection/connection.php");


$query = "CREATE TABLE seats_booked (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT,
    seat_id INT,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (seat_id) REFERENCES seats(id)
);
";

$execute = $mysqli->prepare($query);
if ($execute->execute()) {
    echo "table created successfully.";
} else {
    echo "Error creating  table: " . $mysqli->error;
}
?>