<?php
include '../include/connection/connection.php';

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define a test seat number that you know exists in the database
$testSeat = 'B02'; // Change this to any seat number that exists in your database

// Prepare the SQL update statement
$testUpdate = $conn->prepare("UPDATE seats SET is_booked = 1 WHERE seat_number = ?");

// Check if the statement was prepared successfully
if (!$testUpdate) {
    die("Failed to prepare update statement: " . $conn->error);
}

// Bind the seat number parameter
$testUpdate->bind_param("s", $testSeat);

// Execute the update statement
if ($testUpdate->execute()) {
    echo "Seat $testSeat has been successfully updated to booked.<br>";
} else {
    echo "Failed to update seat $testSeat: " . $testUpdate->error . "<br>";
}

// Close the statement
$testUpdate->close();

// Close the database connection
$conn->close();
?>
