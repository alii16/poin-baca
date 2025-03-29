<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-white shadow-md p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold text-indigo-600">Berita Indonesia</h1>
        <div class="flex items-center space-x-4">
            <input type="text" id="search-input" placeholder="Cari berita..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button id="search-button" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">Cari</button>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="auth/logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg">Logout</a>
            <?php else: ?>
                <a href="auth/login.php" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">Login</a>
                <a href="auth/register.php" class="bg-green-600 text-white px-4 py-2 rounded-lg">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Konten -->
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Berita Terbaru</h2>
    
    <!-- Indikator Loading -->
    <div id="loading" class="text-center hidden">
        <span class="text-gray-600">Sedang memuat berita...</span>
    </div>
    
    <div id="berita-list" class="grid grid-cols-1 md:grid-cols-3 gap-4"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    function fetchNews(query = "") {
        let url = "news/get_news.php";
        if (query) {
            url += `?q=${encodeURIComponent(query)}`;
        }

        // Tampilkan loading
        document.getElementById("loading").classList.remove("hidden");
        document.getElementById("berita-list").innerHTML = "";

        fetch(url)
            .then(response => response.json())
            .then(data => {
                document.getElementById("loading").classList.add("hidden");
                const beritaList = document.getElementById("berita-list");

                if (!data.articles || data.articles.length === 0) {
                    Swal.fire("Info", "Tidak ada berita ditemukan.", "info");
                    return;
                }

                data.articles.forEach(berita => {
                    beritaList.innerHTML += `
                        <div class='bg-white p-4 shadow-lg rounded-lg transition transform hover:scale-105 hover:shadow-xl'>
                            <img src="${berita.urlToImage || 'https://via.placeholder.com/300'}" alt="" class="w-full h-48 object-cover rounded-t-lg">
                            <div class="p-4">
                                <h3 class='font-bold text-lg text-gray-800 truncate'>${berita.title}</h3>
                                <p class='text-gray-600 text-sm mt-1'>${new Date(berita.publishedAt).toLocaleString()}</p>
                                <a href="${berita.url}" target="_blank" class="text-indigo-600 mt-3 inline-block font-medium hover:underline">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    `;
                });
            })
            .catch(error => {
                document.getElementById("loading").classList.add("hidden");
                console.error("Gagal mencari berita", error);
                Swal.fire("Error!", "Gagal mengambil berita dari server.", "error");
            });
    }

    document.getElementById("search-button").addEventListener("click", function () {
        const query = document.getElementById("search-input").value.trim();
        if (!query) {
            Swal.fire("Oops!", "Masukkan kata kunci pencarian.", "warning");
            return;
        }
        fetchNews(query);
    });

    document.getElementById("search-input").addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            fetchNews(this.value.trim());
        }
    });

    fetchNews(); // Ambil berita saat halaman dimuat
});
</script>

</body>
</html>
