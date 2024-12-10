<?php
include '../include/connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $movie_id = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : 0;

    if ($movie_id > 0) {
        // Prepare the SQL delete statement
        $sql = "DELETE FROM movies WHERE id = ?";

        // Prepare and bind the statement
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $movie_id); // Bind the movie ID

            // Execute the statement
            if ($stmt->execute()) {
                header("Location: ../movie-insert/?message=Movie deleted successfully.");
                exit;
            } else {
                echo "Error deleting movie: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        header("Location: ../movie-insert/?error=Invalid movie ID.");
        exit;
    }

    // Close the database connection
    $conn->close();
} else {
    header("Location: ../movie-insert/?error=Invalid request.");
    exit;
}
?>
