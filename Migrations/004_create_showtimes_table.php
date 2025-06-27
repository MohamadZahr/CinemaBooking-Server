<?php
require("../connection/connection.php");


$query = "CREATE TABLE showtimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT,
    auditorium_id INT,
    start_date DATE,
    end_date DATE,
    time_slot ENUM('16:30 - 19:00', '19:00 - 21:30', '21:30 - 00:00') NOT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id),
    FOREIGN KEY (auditorium_id) REFERENCES auditoriums(id)
);

";

$execute = $mysqli->prepare($query);
if ($execute->execute()) {
    echo "table created successfully.";
} else {
    echo "Error creating  table: " . $mysqli->error;
}
?>