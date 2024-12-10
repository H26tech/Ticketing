<?php
session_start();
include '../include/connection/connection.php';
include '../include/phpqrcode/qrlib.php';

// Set the timezone
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['action'])) {
    $payment_id = (int)$_POST['id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $status = 'approved';

        // Generate a random QR code token
        $qr_token = bin2hex(random_bytes(16));

        // Path to save QR code image
        $qr_image_path = '../assets/images/qrcodes/' . $qr_token . '.png';

        // Generate QR code and save to file
        QRcode::png($qr_token, $qr_image_path);

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
            $seat = trim($seat);
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

    // Update the payment status in the payments table
    $stmt = $conn->prepare("UPDATE payments SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $payment_id);
    if ($stmt->execute()) {
        echo 'Payment status updated. ';
        $stmt->close();

        // If approved, insert into qr_codes table
        if ($status === 'approved') {
            $created_at = date('Y-m-d H:i:s');
            $stmt = $conn->prepare("INSERT INTO `qr-codes` (invoice_id, qr_token, created_at) VALUES (?, ?, ?)");
            $stmt->bind_param('iss', $payment_id, $qr_token, $created_at);
            if ($stmt->execute()) {
                echo 'QR code entry added.';
            } else {
                echo 'Error adding QR code entry.';
            }
            $stmt->close();
        }
    } else {
        echo 'Error updating payment status. ';
    }


    $conn->close();
}
?>
