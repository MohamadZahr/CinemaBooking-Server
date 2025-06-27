<?php
require("../connection/connection.php");


$query = "CREATE TABLE seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    auditorium_id INT,
    row_label CHAR(1),
    seat_number INT,
    seat_type ENUM('standard', 'premium') DEFAULT 'standard',
    FOREIGN KEY (auditorium_id) REFERENCES auditoriums(id)
);
";

$execute = $mysqli->prepare($query);
if ($execute->execute()) {
    echo "table created successfully.";
} else {
    echo "Error creating  table: " . $mysqli->error;
}
