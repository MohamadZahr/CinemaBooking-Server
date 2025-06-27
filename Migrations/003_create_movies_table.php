<?php
require("../connection/connection.php");


$query = "CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    rating DECIMAL(3,1),
    poster_path VARCHAR(255),
    popularity DECIMAL(10,4),
    release_date DATE,
    now_showing BOOLEAN DEFAULT FALSE
);

";

$execute = $mysqli->prepare($query);
if ($execute->execute()) {
    echo "table created successfully.";
} else {
    echo "Error creating  table: " . $mysqli->error;
}
?>