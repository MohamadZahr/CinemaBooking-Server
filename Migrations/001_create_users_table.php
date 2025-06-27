<?php
require("../connection/connection.php");


$query = "CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

$execute = $mysqli->prepare($query);
if ($execute->execute()) {
    echo "table created successfully.";
} else {
    echo "Error creating  table: " . $mysqli->error;
}
?>