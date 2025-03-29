<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect jika sudah login
    exit();
}

require_once "../config/db.php";

// Ambil kategori berita dari database
$categories = [];
$result = $conn->query("SELECT id, name FROM categories");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Berita Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-indigo-600">Register</h2>

    <form id="registerForm" class="mt-4">
        <div class="mb-4">
            <label class="block text-gray-700">Username</label>
            <input type="text" id="username" name="username" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-indigo-300">
        </div>

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

        <div class="mb-4">
            <label class="block text-gray-700">Pilih Kategori Berita Favorit:</label>
            <div class="grid grid-cols-2 gap-2 mt-2">
                <?php foreach ($categories as $category): ?>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="kategori[]" value="<?= $category['id']; ?>"
                               class="rounded border-gray-300 focus:ring-indigo-300">
                        <span><?= $category['name']; ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            Daftar
        </button>
    </form>

    <p class="mt-4 text-center text-gray-600">Sudah punya akun? 
        <a href="login.php" class="text-indigo-600 font-semibold">Login</a>
    </p>
</div>

<script>
document.getElementById("registerForm").addEventListener("submit", async function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    try {
        const response = await fetch("register_process.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();
        console.log("Response dari server:", data);

        if (data.status === "success") {
            Swal.fire({
                icon: "success",
                title: "Registrasi Berhasil!",
                text: "Anda akan diarahkan ke halaman login...",
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "login.php";
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: data.message
            });
        }
    } catch (error) {
        console.error("Fetch Error:", error);
        Swal.fire({
            icon: "error",
            title: "Terjadi Kesalahan",
            text: "Coba lagi nanti."
        });
    }
});
</script>

</body>
</html>
