<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../include/connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_name = $_POST['movie_title'];
    $description = $_POST['description'];
    $producer = $_POST['producer'];
    $director = $_POST['director'];
    $writer = $_POST['writer'];
    $date = $_POST['date'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];
    $genre = $_POST['genre']; // New genre field
    $trailer_link = $_POST['trailer_link'] ?? null; // Optional field

    // Calculate duration in minutes
    $start_time = strtotime($time_start);
    $end_time = strtotime($time_end);
    $duration_minutes = ($end_time - $start_time) / 60;

    // Handle file upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $upload_dir = "../uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $thumbnail_path = $upload_dir . basename($_FILES['thumbnail']['name']);
        if (!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail_path)) {
            die("Error uploading file.");
        }
    } else {
        $thumbnail_path = null;
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO movies (title, description, producer, director, writer, release_date, poster_image, time_start, time_end, duration, genre, trailer_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssiss", $movie_name, $description, $producer, $director, $writer, $date, $thumbnail_path, $time_start, $time_end, $duration_minutes, $genre, $trailer_link);

    if ($stmt->execute()) {
        header("Location: ../movie-insert/");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
