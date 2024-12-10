<?php
session_start();
include '../include/connection/connection.php';

$user_id = $_SESSION['user_id']; // Assuming you store the user id in session

// Initialize an array to store updated fields
$update_fields = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['full_name']) && !empty($_POST['full_name']) && $_POST['full_name'] != $_SESSION['full_name']) {
        $full_name = $_POST['full_name'];
        $update_fields[] = "full_name='$full_name'";
        $_SESSION['full_name'] = $full_name; // Update session
    }

    if (isset($_POST['email']) && !empty($_POST['email']) && $_POST['email'] != $_SESSION['email']) {
        $email = $_POST['email'];
        $update_fields[] = "email='$email'";
        $_SESSION['email'] = $email; // Update session
    }

    if (isset($_POST['username']) && !empty($_POST['username']) && $_POST['username'] != $_SESSION['username']) {
        $username = $_POST['username'];
        $update_fields[] = "username='$username'";
        $_SESSION['username'] = $username; // Update session
    }

    if (isset($_POST['phone_number']) && !empty($_POST['phone_number']) && $_POST['phone_number'] != $_SESSION['phone_number']) {
        $phone_number = $_POST['phone_number'];
        $update_fields[] = "phone_number='$phone_number'";
        $_SESSION['phone_number'] = $phone_number; // Update session
    }

    // Process file upload for profile picture
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $upload_dir = "../uploads/"; // Directory to save profile pictures
        $file_path = $upload_dir . basename($file_name);

        // Ensure directory exists and has write permissions
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory if not exists
        }

        if (move_uploaded_file($file_tmp, $file_path)) {
            $update_fields[] = "profile_image   ='$file_path'";
            $_SESSION['profile_image'] = $file_path;
        } else {
            echo "Error moving uploaded file.";
        }
    } else {
        if (isset($_FILES['profile_picture'])) {
            echo "File upload error: " . $_FILES['profile_picture']['error'];
        } else {
            echo "No file uploaded.";
        }
    }

    // If any fields were updated, execute the update query
    if (!empty($update_fields)) {
        $sql = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id='$user_id'";
        if ($conn->query($sql) === TRUE) {
            header("Location: ../dashboard/profile.php");
            exit;
        } else {
            echo "Error updating profile: " . $conn->error; // Show SQL error
        }
    } else {
        header("Location: ../dashboard/profile.php");
        exit;
    }
}

$conn->close();
?>
