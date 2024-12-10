<?php
session_start();
include '../include/connection/connection.php'; // Include your database connection file

// Decode the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);
$newPassword = $data['newPassword'];

// Assuming the logged-in user’s ID is stored in the session
$userId = $_SESSION['user_id'];

// Validate the new password meets certain criteria (e.g., minimum 8 characters)
if (strlen($newPassword) < 6) {
    echo json_encode(["success" => false, "message" => "Password must be at least 6 characters long."]);
    exit;
}

// Hash the new password
$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the user’s password in the database
$sql = "UPDATE users SET password = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $newPasswordHash, $userId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Password updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update password."]);
}

$stmt->close();
$conn->close();
?>
