<?php
include '../include/connection/connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data dari database
    $query = "DELETE FROM sponsor WHERE id = $id";
    $result = mysqli_query($conn, $query);

    // Redirect ke halaman index dengan pesan berhasil
    header("Location: index.php?delete_success=1");
} else {
    header("Location: index.php?delete_error=1");
}
?>
