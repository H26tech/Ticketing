<?php
include '../include/connection/connection.php';

// Fungsi untuk menangani upload gambar
function uploadImage($file) {
    $target_dir = "../uploads/"; // Sesuaikan path
    $target_file = $target_dir . basename($file["name"]);
    move_uploaded_file($file["tmp_name"], $target_file);
    return basename($file["name"]);
}

// Menambahkan data sponsor baru
if (isset($_POST['submit'])) {
    $nama_sponsor = $_POST['nama_sponsor'];
    $foto_logo = uploadImage($_FILES['foto_logo']);

    $query = "INSERT INTO sponsor (nama_sponsor, foto_logo) VALUES ('$nama_sponsor', '$foto_logo')";
    mysqli_query($conn, $query);
    header("Location: index.php?insert_success=1");
}

// Memperbarui data sponsor
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_sponsor = $_POST['nama_sponsor'];

    // Jika ada logo baru, upload dan perbarui
    if (!empty($_FILES['foto_logo']['name'])) {
        $foto_logo = uploadImage($_FILES['foto_logo']);
        $query = "UPDATE sponsor SET nama_sponsor = '$nama_sponsor', foto_logo = '$foto_logo' WHERE id = $id";
    } else {
        $query = "UPDATE sponsor SET nama_sponsor = '$nama_sponsor' WHERE id = $id";
    }

    mysqli_query($conn, $query);
    header("Location: index.php?update_success=1");
}
?>
