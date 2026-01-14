<?php
include 'config/db.php';

$username = "admin_merakbatin";
$password = "desa2026"; // Silakan ganti password ini
$nama     = "Admin Desa";

// Enkripsi password untuk keamanan tinggi
$hash_password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO users (username, password, nama_lengkap) VALUES ('$username', '$hash_password', '$nama')";

if (mysqli_query($conn, $query)) {
    echo "Admin berhasil dibuat! Username: $username | Password: $password";
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>