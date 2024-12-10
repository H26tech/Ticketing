<?php
include '../include/connection/connection.php';

// Define rows and number of seats for each row
$rows = [
    'B' => 16, // 16 seats for row B
    'C' => 14, 'D' => 14, 'E' => 14, 'F' => 14, 
    'G' => 14, 'H' => 14, 'I' => 14, 'J' => 14, 'K' => 14 // 14 seats for rows C-K
];

// Prepare the SQL insert statement
$sql = "INSERT INTO seats (movie_id, seat_number, row_letter, is_booked, showtime, created_at, updated_at) VALUES ";

$values = [];
foreach ($rows as $row => $seatsPerRow) {
    $startSeat = ($row === 'B') ? 1 : 3; // Start from 1 for row B, 3 for other rows
    for ($seat = $startSeat; $seat < $startSeat + $seatsPerRow; $seat++) {
        $seatNumber = str_pad($seat, 2, '0', STR_PAD_LEFT); // Format seat number as 01, 02, etc.
        $values[] = "(1, '$seatNumber', '$row', 0, NOW(), NOW(), NOW())";
    }
}

$sql .= implode(', ', $values);

if (mysqli_query($conn, $sql)) {    
    echo "128 seats successfully inserted!";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
