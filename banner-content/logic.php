<?php
include '../include/connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $bannerTitle = trim($_POST['banner_title']);
    $description = trim($_POST['banner_desc']);

    // Set status based on the switch
    $status = isset($_POST['status']) ? 'published' : 'draft';

    // Initialize variables for file upload
    $targetDir = "../uploads/"; // Adjust the directory as needed
    $fileName = basename($_FILES["image_content"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Validate file type (optional)
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (!in_array(strtolower($fileType), $allowedTypes)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit;
    }

    // Check if the file was uploaded successfully
    if (move_uploaded_file($_FILES["image_content"]["tmp_name"], $targetFilePath)) {
        date_default_timezone_set('Asia/Jakarta');
        $currentTime = date('Y-m-d H:i:s');

        $query = "INSERT INTO `banner-content` (`banner_title`, `banner_desc`, `image_content`, `status`, `created_at`, `updated_at`) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $bannerTitle, $description, $fileName, $status, $currentTime, $currentTime);


        if ($stmt->execute()) {
            header('Location: ../banner-content/');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Close the database connection
$conn->close();
