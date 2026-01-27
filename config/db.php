<?php
// Pengaturan Database Online (InfinityFree)
$host = "sql302.infinityfree.com";      // MySQL Hostname dari gambar
$user = "if0_40998880";                // MySQL Username dari gambar
$pass = "Desamrkbatin22M";          // Password yang Anda buat saat Create Account
$db   = "if0_40998880_db_merakbatin";  // Database Name dari gambar

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek Koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Set charset ke utf8 agar karakter khusus tampil benar
mysqli_set_charset($conn, "utf8");
?>