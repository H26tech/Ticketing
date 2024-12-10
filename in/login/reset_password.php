<?php
require '../include/connection/connection.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token'])) {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT); // Hash password baru

    // Update password dan reset token di database
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $new_password, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Password has been reset successfully.";
    } else {
        echo "Failed to reset password.";
    }

    $stmt->close();
}
$conn->close();
?>
