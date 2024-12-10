<?php

date_default_timezone_set('Asia/Jakarta');
// process-scan.php
if (isset($_GET['qr_token'])) {
    $qr_token = $_GET['qr_token'];
    $scanned_by = $_GET['admin_id'];

    // Process the QR code, e.g., validate or mark as scanned
    require_once '../include/connection/connection.php';

    $stmt = $conn->prepare("SELECT status FROM `qr-codes` WHERE qr_token = ?");
    $stmt->bind_param("s", $qr_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['status'] === 'used') {
            // Redirect if QR code is already used
            header("Location: ../scan-invoice/?status=already_used&message=This QR Code has been used and not Valid anymore.");
        } else {
            // Update status to 'used' if not already used
	    $created_at = date('Y-m-d H:i:s');
            $stmt_update = $conn->prepare("UPDATE `qr-codes` SET status = 'used', scanned_by = ?, created_at = ? WHERE qr_token = ?");
            $stmt_update->bind_param("iss", $scanned_by, $created_at, $qr_token);
            $stmt_update->execute();

            // Redirect to scan-invoice with success message
            header("Location: ../scan-invoice/?status=success&message=The QR Code sucessfully Scanned.");
        }
    } else {
        // Redirect with a 'not found' message
        header("Location: ../scan-invoice/?status=notfound&message=The scanned QR Code could not be found in our records.");
    }
} else {
    // Redirect with an error message if no QR code was provided
    header("Location: ../scan-invoice/?status=error&message=No QR code was provided. Please try scanning again.");
}
exit();
