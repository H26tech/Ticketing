<?php
include '../include/connection/connection.php';

// Set timezone to Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

if (isset($_POST['edit_post'])) {
    $post_id = $_POST['post_id'];
    $post_name = $_POST['post_name'];
    $post_link = $_POST['post_link'];

    // Update query with updated_at set to current Asia/Jakarta time
    $query = "UPDATE `instagram-post` SET post_name = ?, post_link = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi', $post_name, $post_link, $post_id);

    if ($stmt->execute()) {
        // Redirect on successful update
        header('Location: ../instagram-post/');
    } else {
        // Redirect on failure
        header('Location: ../instagram-post/');
    }
    exit();
}
?>
