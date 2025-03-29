<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect jika sudah login
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Berita Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-indigo-600">Login</h2>

    <form id="loginForm" class="mt-4">
        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" id="email" name="email" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-indigo-300">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input type="password" id="password" name="password" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-indigo-300">
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            Login
        </button>
    </form>

    <p class="mt-4 text-center text-gray-600">Belum punya akun? 
        <a href="register.php" class="text-indigo-600 font-semibold">Daftar</a>
    </p>
</div>

<script>
document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch("cek_login.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            Swal.fire({
                icon: "success",
                title: "Login Berhasil!",
                text: "Anda akan diarahkan...",
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = data.redirect; // Redirect sesuai role
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: "error",
            title: "Kesalahan Server!",
            text: "Tidak dapat terhubung ke server. Coba lagi nanti."
        });
    });
});
</script>


</body>
</html>
