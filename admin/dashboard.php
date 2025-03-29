<?php
session_start();

// Proteksi halaman admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Hai Admin, Anda Berhasil Login!</h1>
    <a href="../auth/logout.php">Logout</a> <!-- Tombol Logout -->
</body>
</html>
