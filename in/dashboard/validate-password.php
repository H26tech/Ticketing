<?php
session_start();
include '../include/connection/connection.php';

// Get the current password from the request
$data = json_decode(file_get_contents("php://input"), true);
$currentPassword = $data['currentPassword'];

// Assuming the logged-in user’s ID is stored in the session
$userId = $_SESSION['user_id'];

// Query to fetch the user's stored password hash from the database
$sql = "SELECT password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($storedPasswordHash);
$stmt->fetch();
$stmt->close();

// Verify the entered password against the stored hash
if (password_verify($currentPassword, $storedPasswordHash)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}

$conn->close();
?>
