<?php
require("../connection/connection.php");
$rowLabels = range('A', 'J');
$auditoriumCount = 7;

$stmt = $mysqli->prepare("INSERT INTO seats (auditorium_id, row_label, seat_number, seat_type) VALUES (?, ?, ?, ?)");

if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

foreach (range(1, $auditoriumCount) as $auditoriumId) {
    foreach ($rowLabels as $rowLabel) {
        for ($seatNumber = 1; $seatNumber <= 10; $seatNumber++) {
            $seatType = ($seatNumber >= 4 && $seatNumber <= 7) ? 'premium' : 'standard';

            $stmt->bind_param('isis', $auditoriumId, $rowLabel, $seatNumber, $seatType);
            if (!$stmt->execute()) {
                echo "Error inserting: " . $stmt->error;
            }
        }
    }
}

$stmt->close();
$mysqli->close();

echo "Seeding complete.\n";
?>
