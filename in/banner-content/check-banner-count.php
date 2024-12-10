<?php
include '../include/connection/connection.php'; 

$countQuery = "SELECT COUNT(*) AS banner_count FROM `banner-content`";
$countResult = $conn->query($countQuery);
$bannerCount = $countResult->fetch_assoc()['banner_count'];

// Return banner count as JSON
echo json_encode(['banner_count' => $bannerCount]);
?>
