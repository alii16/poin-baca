<?php
session_start();
include "../config/db.php"; // Pastikan file koneksi ada

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sesuaikan variabel sesuai dengan form register.php
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $kategori = isset($_POST['kategori']) ? $_POST['kategori'] : [];
    
    // Set default role
    $role = "user";

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Cek apakah email sudah terdaftar
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email sudah digunakan!"]);
        exit();
    }

    // Insert user ke tabel `users` dengan role default "user"
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id; // Ambil ID user yang baru dibuat

        // Simpan kategori favorit di `user_preferences`
        if (!empty($kategori)) {
            $stmt = $conn->prepare("INSERT INTO user_preferences (user_id, category_id) VALUES (?, ?)");
            foreach ($kategori as $cat_id) {
                $stmt->bind_param("ii", $user_id, $cat_id);
                $stmt->execute();
            }
        }

        echo json_encode(["status" => "success", "message" => "Registrasi berhasil!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal mendaftar, coba lagi!"]);
    }
    $stmt->close();
}
?>