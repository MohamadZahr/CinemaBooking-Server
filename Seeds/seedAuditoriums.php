<?php
require("../connection/connection.php");


$query = "INSERT into auditoriums (name, seat_layout_template) VALUES
('Auditorium 1', 'A'),
('Auditorium 2', 'B'),
('Auditorium 3', 'C'),
('Auditorium 4', 'D'),
('Auditorium 5', 'E'),
('Auditorium 6', 'F'),
('Auditorium 7', 'G')
";

$execute = $mysqli->prepare($query);
if ($execute->execute()) {
    echo "Data inserted successfully.";
} else {
    echo "Error inserting data: " . $mysqli->error;
}
?>