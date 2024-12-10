<?php
include '../include/connection/connection.php'; 

if (isset($_GET['id'])) {
    $bannerId = $_GET['id'];

    // Delete banner from the `banner-content` table
    $deleteQuery = "DELETE FROM `banner-content` WHERE `id` = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $bannerId);

    if ($stmt->execute()) {
        // Redirect back to the banner management page with a success message
        header("Location: ../banner-content/?delete=success");
    } else {
        // Redirect back with an error message if deletion fails
        header("Location: ../banner-content/?delete=error");
    }

    $stmt->close();
}
?>
