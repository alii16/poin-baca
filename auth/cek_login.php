<?php
session_start();
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Simpan session dengan nama yang benar
                $_SESSION['user_id'] = $id;
                $_SESSION['role'] = $role;

                // Tentukan redirect berdasarkan role
                $redirect = ($role === 'admin') ? "../admin/dashboard.php" : "../index.php";

                echo json_encode([
                    "status" => "success",
                    "message" => "Login berhasil!",
                    "redirect" => $redirect
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "Email atau password salah!"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Email atau password salah!"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Terjadi kesalahan pada server."]);
    }

    $conn->close();
}
?>
