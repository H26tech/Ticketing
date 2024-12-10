<?php

include '../include/connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = isset($_POST['movie_id']) ? (int)$_POST['movie_id'] : 0;
    $movie_title = isset($_POST['movie_title']) ? trim($_POST['movie_title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $producer = isset($_POST['producer']) ? trim($_POST['producer']) : '';
    $director = isset($_POST['director']) ? trim($_POST['director']) : '';
    $writer = isset($_POST['writer']) ? trim($_POST['writer']) : '';
    $release_date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $time_start = isset($_POST['time_start']) ? trim($_POST['time_start']) : '';
    $time_end = isset($_POST['time_end']) ? trim($_POST['time_end']) : '';
    $trailer_link = isset($_POST['trailer_link']) ? trim($_POST['trailer_link']) : null;
    $genre = isset($_POST['genre']) ? trim($_POST['genre']) : '';

    // Handle file upload if a thumbnail is provided
    $thumbnail_path = null;
    if (!empty($_FILES['thumbnail']['name'])) {
        $target_dir = "../uploads/"; // Set your upload directory
        $thumbnail_path = basename($_FILES['thumbnail']['name']);
	$upload_path = $target_dir . basename($_FILES['thumbnail']['name']);
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_path);
    }

    // Prepare the SQL update statement
    $sql = "UPDATE movies SET 
                title = ?, 
                description = ?, 
                producer = ?,
                director = ?,
                writer = ?,
                release_date = ?, 
                time_start = ?, 
                time_end = ?, 
                trailer_link = ?, 
                genre = ? " . (!is_null($thumbnail_path) ? ", poster_image = ?" : '') . " 
            WHERE id = ?";

    // Prepare and bind the statement
    if ($stmt = $conn->prepare($sql)) {
        if (!is_null($thumbnail_path)) {
            // Bind parameters including thumbnail
            $stmt->bind_param("sssssssssssi", $movie_title, $description, $producer, $director, $writer, $release_date, $time_start, $time_end, $trailer_link, $genre, $thumbnail_path, $movie_id);
        } else {
            // Bind parameters without thumbnail
            $stmt->bind_param("ssssssssssi", $movie_title, $description, $producer, $director, $writer, $release_date, $time_start, $time_end, $trailer_link, $genre, $movie_id);
        }

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: ../movie-insert/?message=Movie updated successfully.");
            exit;
        } else {
            echo "Error updating movie: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    header("Location: ../movie-insert/?error=Invalid request.");
    exit;
}
