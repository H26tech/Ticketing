<?php
session_start();
include '../include/connection/connection.php'; // Adjust the path as necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['action'])) {
    $payment_id = (int)$_POST['id'];
    $action = $_POST['action'];

    // Validate the action
    if ($action === 'approve') {
        $status = 'approved';
    } elseif ($action === 'reject') {
        $status = 'rejected';

        // Retrieve the selected seats for this payment
        $stmt = $conn->prepare("SELECT selected_seats FROM payments WHERE id = ?");
        $stmt->bind_param('i', $payment_id);
        $stmt->execute();
        $stmt->bind_result($selected_seats);
        $stmt->fetch();
        $stmt->close();

        // Split seats into an array
        $seats = explode(',', $selected_seats);

        // Update each seat in the seats table
        foreach ($seats as $seat) {
            $seat = trim($seat); // Remove any extra spaces
            if ($seat) {
                list($row_letter, $seat_number) = sscanf($seat, "%[A-Za-z]%d");
                $updateSeatStmt = $conn->prepare("UPDATE seats SET is_booked = 0 WHERE row_letter = ? AND seat_number = ?");
                $updateSeatStmt->bind_param('si', $row_letter, $seat_number);
                $updateSeatStmt->execute();
                $updateSeatStmt->close();
            }
        }
    } else {
        die('Invalid action');
    }

    // Update the payment status
    $stmt = $conn->prepare("UPDATE payments SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $payment_id);
    if ($stmt->execute()) {
        echo 'Success';
    } else {
        echo 'Error';
    }
    $stmt->close();
    $conn->close();
}
?>
