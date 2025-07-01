<?php
require("../connection/connection.php");


$query = "ALTER TABLE bookings
ADD COLUMN auditorium_id INT AFTER user_id,
ADD CONSTRAINT fk_bookings_auditorium
    FOREIGN KEY (auditorium_id)
    REFERENCES auditoriums(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
  
";

$execute = $mysqli->prepare($query);
if ($execute->execute()) {
    echo "table altered successfully.";
} else {
    echo "Error creating  table: " . $mysqli->error;
}
