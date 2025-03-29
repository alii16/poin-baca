<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    
    // Hash password menggunakan password_hash()
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    echo "Password Asli: " . htmlspecialchars($password) . "<br>";
    echo "Password yang sudah di-hash: " . $hashedPassword;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hash Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-indigo-600">Hash Password</h2>

    <form action="" method="POST" class="mt-4">
        <div class="mb-4">
            <label class="block text-gray-700">Masukkan Password</label>
            <input type="text" name="password" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-indigo-300">
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            Hash Password
        </button>
    </form>
</div>

</body>
</html>
