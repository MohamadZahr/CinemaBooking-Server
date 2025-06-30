<?php
require("../connection/connection.php");


$query = "ALTER TABLE bookings
    ADD COLUMN booking_date DATE AFTER showtime_id,
    ADD COLUMN time_slot VARCHAR(10) AFTER booking_date,   
";

$execute = $mysqli->prepare($query);
if ($execute->execute()) {
    echo "table altered successfully.";
} else {
    echo "Error creating  table: " . $mysqli->error;
}
