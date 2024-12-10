<?php
// Include your database connection file
include '../include/connection/connection.php';

if (isset($_POST['type']) && isset($_POST['value'])) {
    $type = $_POST['type'];
    $value = mysqli_real_escape_string($conn, $_POST['value']);

    if ($type === 'name') {
        // Check if the full name already exists
        $sql = "SELECT id FROM users WHERE full_name = '$value'";
    } elseif ($type === 'email') {
        // Check if the email already exists
        $sql = "SELECT id FROM users WHERE email = '$value'";
    }

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "exists"; // Name or email exists
    } else {
        echo ""; // Name or email is available
    }
}

