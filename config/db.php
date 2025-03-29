<?php
$host = "localhost";
$user = "root";  // Sesuaikan dengan user database Anda
$password = "";  // Sesuaikan dengan password database Anda
$database = "poin_baca"; // Sesuaikan dengan nama database Anda

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
