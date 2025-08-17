<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Indonesia - Portal Berita Terpercaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }
        
        .news-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .news-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">

<!-- Navbar -->
<nav class="glass-effect border-b border-white/20 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-newspaper text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Berita Indonesia
                    </h1>
                    <p class="text-xs text-gray-500 font-medium">Portal Berita Terpercaya</p>
                </div>
            </div>

            <!-- Search Bar (Desktop) -->
            <div class="hidden md:flex items-center max-w-md w-full mx-8">
                <div class="relative w-full">
                    <input type="text" 
                           id="search-input" 
                           placeholder="Cari berita terbaru..." 
                           class="w-full pl-12 pr-4 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200 text-sm placeholder-gray-400">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <button id="search-button" 
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-1.5 rounded-lg hover:shadow-lg transition-all duration-200 text-sm font-medium">
                        Cari
                    </button>
                </div>
            </div>

            <!-- User Actions -->
            <div class="flex items-center space-x-3">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="flex items-center space-x-3">
                        <div class="hidden sm:flex items-center space-x-2 px-3 py-2 bg-green-50 rounded-lg border border-green-200">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium text-green-700">Online</span>
                        </div>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="dashboard.php" 
                               class="flex items-center space-x-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white px-4 py-2 rounded-xl hover:shadow-lg transition-all duration-200 text-sm font-medium">
                                <i class="fas fa-tachometer-alt"></i>
                                <span class="hidden sm:block">Dashboard</span>
                            </a>
                        <?php endif; ?>
                        <a href="auth/logout.php" 
                           class="flex items-center space-x-2 bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-xl hover:shadow-lg transition-all duration-200 text-sm font-medium">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="hidden sm:block">Logout</span>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="auth/login.php" 
                       class="flex items-center space-x-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-xl hover:shadow-lg transition-all duration-200 text-sm font-medium">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                    <a href="auth/register.php" 
                       class="flex items-center space-x-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-xl hover:shadow-lg transition-all duration-200 text-sm font-medium">
                        <i class="fas fa-user-plus"></i>
                        <span>Register</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mobile Search -->
        <div class="md:hidden pb-4">
            <div class="relative">
                <input type="text" 
                       id="mobile-search-input" 
                       placeholder="Cari berita..." 
                       class="w-full pl-12 pr-20 py-3 bg-white/80 border border-gray-200/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 text-sm">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <button id="mobile-search-button" 
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium">
                    Cari
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-4 animate-fade-in">
            Berita Terkini Indonesia
        </h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto animate-slide-up">
            Dapatkan informasi terbaru dan terpercaya dari seluruh Indonesia
        </p>
        <div class="flex justify-center space-x-6 text-sm">
            <div class="flex items-center space-x-2">
                <i class="fas fa-clock text-blue-200"></i>
                <span>Update Real-time</span>
            </div>
            <div class="flex items-center space-x-2">
                <i class="fas fa-shield-alt text-blue-200"></i>
                <span>Sumber Terpercaya</span>
            </div>
            <div class="flex items-center space-x-2">
                <i class="fas fa-globe text-blue-200"></i>
                <span>Nasional & Internasional</span>
            </div>
        </div>
    </div>
</div>

<!-- Content -->
<div class="max-w-7xl mx-auto px-4 py-12">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h3 class="text-3xl font-bold text-gray-800 mb-2">Berita Terbaru</h3>
            <p class="text-gray-600">Temukan berita terkini dari berbagai sumber terpercaya</p>
        </div>
        <div class="hidden md:flex items-center space-x-4 text-sm text-gray-500">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span>Live Updates</span>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loading" class="hidden">
        <div class="flex justify-center items-center py-20">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                <div class="mt-4 text-center">
                    <p class="text-gray-600 font-medium">Sedang memuat berita...</p>
                    <p class="text-sm text-gray-500">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- News Grid -->
    <div id="berita-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"></div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden text-center py-20">
        <div class="max-w-md mx-auto">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-newspaper text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Tidak Ada Berita Ditemukan</h3>
            <p class="text-gray-600 mb-6">Coba gunakan kata kunci yang berbeda untuk pencarian Anda</p>
            <button onclick="fetchNews()" 
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-200 font-medium">
                <i class="fas fa-refresh mr-2"></i>
                Muat Ulang
            </button>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12 mt-20">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-newspaper text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold">Berita Indonesia</h4>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Portal berita terpercaya yang menghadirkan informasi terkini dan akurat dari seluruh Indonesia.
                </p>
            </div>
            <div>
                <h5 class="font-semibold mb-4">Kategori Berita</h5>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors">Politik</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Ekonomi</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Olahraga</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Teknologi</a></li>
                </ul>
            </div>
            <div>
                <h5 class="font-semibold mb-4">Kontak</h5>
                <div class="space-y-2 text-sm text-gray-400">
                    <p><i class="fas fa-envelope mr-2"></i> info@beritaindonesia.com</p>
                    <p><i class="fas fa-phone mr-2"></i> (021) 1234-5678</p>
                    <p><i class="fas fa-map-marker-alt mr-2"></i> Jakarta, Indonesia</p>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>&copy; 2025 Berita Indonesia. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
    function fetchNews(query = "") {
        let url = "news/get_news.php";
        if (query) {
            url += `?q=${encodeURIComponent(query)}`;
        }

        // Show loading
        document.getElementById("loading").classList.remove("hidden");
        document.getElementById("berita-list").innerHTML = "";
        document.getElementById("empty-state").classList.add("hidden");

        fetch(url)
            .then(response => response.json())
            .then(data => {
                document.getElementById("loading").classList.add("hidden");
                const beritaList = document.getElementById("berita-list");
                const emptyState = document.getElementById("empty-state");

                if (!data.articles || data.articles.length === 0) {
                    emptyState.classList.remove("hidden");
                    return;
                }

                data.articles.forEach((berita, index) => {
                    const publishedDate = new Date(berita.publishedAt);
                    const timeAgo = getTimeAgo(publishedDate);
                    
                    const newsCard = document.createElement('div');
                    newsCard.className = 'news-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-slide-up';
                    newsCard.style.animationDelay = `${index * 0.1}s`;
                    
                    newsCard.innerHTML = `
                        <div class="relative overflow-hidden">
                            <img src="${berita.urlToImage || 'https://via.placeholder.com/400x250?text=No+Image'}" 
                                 alt="${berita.title}" 
                                 class="w-full h-56 object-cover transition-transform duration-300 hover:scale-105"
                                 onerror="this.src='https://via.placeholder.com/400x250?text=No+Image'">
                            <div class="absolute top-4 left-4">
                                <span class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-3 py-1 rounded-full text-xs font-medium">
                                    <i class="fas fa-newspaper mr-1"></i>
                                    Breaking
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-2 text-sm text-gray-500">
                                    <i class="fas fa-clock"></i>
                                    <span>${timeAgo}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button class="text-gray-400 hover:text-blue-600 transition-colors" onclick="shareArticle('${berita.url}', '${berita.title.replace(/'/g, "\\'")}')">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-red-500 transition-colors">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                            <h3 class="font-bold text-lg text-gray-800 mb-3 line-clamp-2 leading-tight">
                                ${berita.title}
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                                ${berita.description || 'Deskripsi tidak tersedia untuk berita ini.'}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500 font-medium">
                                    ${berita.source?.name || 'Unknown Source'}
                                </span>
                                <a href="${berita.url}" 
                                   target="_blank" 
                                   class="inline-flex items-center space-x-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-xl hover:shadow-lg transition-all duration-200 text-sm font-medium">
                                    <span>Baca Selengkapnya</span>
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </div>
                    `;
                    
                    beritaList.appendChild(newsCard);
                });
            })
            .catch(error => {
                document.getElementById("loading").classList.add("hidden");
                console.error("Error fetching news:", error);
                Swal.fire({
                    title: "Oops!",
                    text: "Gagal mengambil berita dari server. Silakan coba lagi nanti.",
                    icon: "error",
                    confirmButtonColor: "#3B82F6"
                });
            });
    }

    function getTimeAgo(date) {
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Baru saja';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} menit yang lalu`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} jam yang lalu`;
        return `${Math.floor(diffInSeconds / 86400)} hari yang lalu`;
    }

    function shareArticle(url, title) {
        if (navigator.share) {
            navigator.share({
                title: title,
                url: url
            });
        } else {
            navigator.clipboard.writeText(url).then(() => {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Link artikel telah disalin ke clipboard",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        }
    }

    // Search functionality for both desktop and mobile
    function handleSearch(input) {
        const query = input.value.trim();
        if (!query) {
            Swal.fire({
                title: "Oops!",
                text: "Masukkan kata kunci pencarian terlebih dahulu.",
                icon: "warning",
                confirmButtonColor: "#3B82F6"
            });
            return;
        }
        fetchNews(query);
    }

    // Desktop search
    document.getElementById("search-button").addEventListener("click", () => {
        handleSearch(document.getElementById("search-input"));
    });

    document.getElementById("search-input").addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            handleSearch(this);
        }
    });

    // Mobile search
    document.getElementById("mobile-search-button").addEventListener("click", () => {
        handleSearch(document.getElementById("mobile-search-input"));
    });

    document.getElementById("mobile-search-input").addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            handleSearch(this);
        }
    });

    // Add global shareArticle function
    window.shareArticle = shareArticle;

    // Initial load
    fetchNews();
});
</script>

</body>
</html>