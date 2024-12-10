<?php
// process_forgotpass.php

include '../include/connection/connection.php';

// Fungsi untuk membuat password acak 6 karakter
function generateRandomPassword($length = 6)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomPassword = '';

    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomPassword;
}

// Buat password acak 6 karakter
$random_password = generateRandomPassword();
$new_password_hashed = password_hash($random_password, PASSWORD_BCRYPT);

// Mengecek apakah pengguna memasukkan username dan nomor telepon atau hanya email
if (!empty($_POST['username']) && !empty($_POST['phone_number'])) {
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];

    // Cek kecocokan username, nomor telepon, dan pastikan role-nya adalah customer
    $stmt = $conn->prepare("SELECT id, role FROM users WHERE username = ? AND phone_number = ?");
    $stmt->bind_param("ss", $username, $phone_number);
    $stmt->execute();
    $stmt->bind_result($user_id, $user_role);

    if ($stmt->fetch() && $user_role === "customer") {
        // Reset password jika data cocok dan role adalah customer
        $stmt->close();
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password_hashed, $user_id);
        $stmt->execute();

        echo "<script>alert('Password anda telah direset menjadi $random_password. Ingat Password ini dan jangan lupa segera ganti password anda di profile!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Data tidak valid.'); window.history.back();</script>";
    }

    $stmt->close();
} elseif (!empty($_POST['email'])) {
    $email = $_POST['email'];

    // Cek kecocokan email dan pastikan role-nya adalah customer
    $stmt = $conn->prepare("SELECT id, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $user_role);

    if ($stmt->fetch() && $user_role === "customer") {
        // Reset password jika data cocok dan role adalah customer
        $stmt->close();
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password_hashed, $user_id);
        $stmt->execute();

        echo "<script>alert('Password anda telah direset menjadi $random_password. Ingat Password ini dan jangan lupa segera ganti password anda di profile!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Email tidak ditemukan.'); window.history.back();</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Silakan masukkan username dan nomor telepon atau email.'); window.history.back();</script>";
}

$conn->close();
