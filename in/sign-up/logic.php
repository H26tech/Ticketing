<?php
// Include your database connection file
include '../include/connection/connection.php';

error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Validate input data
    $errors = [];
    
    // Check if the email already exists
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Email is already registered.";
    }

    // If no errors, hash the password and insert into the database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user data into the database
        $sql = "INSERT INTO users (username, full_name, phone_number, email, password) VALUES ('$full_name', '$full_name', '$phone_number', '$email', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            // Registration successful, redirect to login page
            header("Location: ../login/");
            exit(); // Ensure no further code is executed
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        // Store errors in a session to display on the form
        session_start();
        $_SESSION['errors'] = $errors;
        print_r($errors);   
        header("Location: ../sign-up/"); // Redirect back to the signup form
        exit();
    }
}

