<?php
require("../connection/connection.php");


$query = "CREATE TABLE auditoriums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    seat_layout_template VARCHAR(255) 
);

";

$execute = $mysqli->prepare($query);
if ($execute->execute()) {
    echo "table created successfully.";
} else {
    echo "Error creating  table: " . $mysqli->error;
}
?>