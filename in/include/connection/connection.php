<?php
$servername = "localhost";
$username = "u993542331_adxuser";
$password = "BMXRider123";
$database = "u993542331_digital";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);


// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
