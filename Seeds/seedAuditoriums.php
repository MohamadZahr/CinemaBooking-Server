<?php
require("../connection/connection.php");


$query = "INSERT into auditoriums (name, seat_layout_template) VALUES
('Auditorium A', 'A'),
('Auditorium B', 'B'),
('Auditorium C', 'C'),
('Auditorium D', 'D'),
('Auditorium E', 'E'),
('Auditorium F', 'F'),
('Auditorium G', 'G')
";

$execute = $mysqli->prepare($query);
if ($execute->execute()) {
    echo "Data inserted successfully.";
} else {
    echo "Error inserting data: " . $mysqli->error;
}
?>