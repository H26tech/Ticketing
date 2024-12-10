<?php
require '../include/connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Cek apakah email terdaftar
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Generate token dan waktu kedaluwarsa
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Simpan token dan waktu kedaluwarsa ke database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Kirim email reset password
        $subject = "Reset Password";
        $message = "Click this link to reset your password: http://https://project.budiluhurdigital.com/blu-ticket/login/reset.php?token=" . $token;

        if (mail($email, $subject, $message)) {
            echo "An email has been sent to your email address. Please check your inbox.";
        } else {
            echo "Failed to send the email.";
        }
    } else {
        echo "Email not found.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
