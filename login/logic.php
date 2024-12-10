<?php
// Include your database connection file
include '../include/connection/connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $login_input = mysqli_real_escape_string($conn, $_POST['login_input']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the input matches either email or full_name
    $sql = "SELECT * FROM users WHERE email = '$login_input' OR full_name = '$login_input'";

    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // If user is found
    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['phone_number'] = $user['phone_number'];
            $_SESSION['profile_image'] = $user['profile_image'];
            $_SESSION['updated_at'] = $user['updated_at'];

            // Role-based redirection
            if ($user['role'] == 'admin') {
                
                header("Location: ../dashboard/");
            } elseif ($user['role'] == 'customer') {
                header("Location: ../homepage/");
            }
            exit;
        } else {
            // Incorrect password
            header("Location: ../login/index.php?error=incorrectpassword");
            exit;
        }
    } else {
        // User not found
        header("Location: ../login/index.php?error=nouser");
        exit;
    }
}
