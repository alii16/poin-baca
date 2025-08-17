<?php
session_start();

// Proteksi halaman admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Simulasi data untuk dashboard
$stats = [
    'total_users' => 1250,
    'total_news' => 856,
    'today_visitors' => 3420,
    'monthly_growth' => 15.3
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Berita Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        'slide-in': 'slideIn 0.3s ease-out',
                        'bounce-in': 'bounceIn 0.6s ease-out',
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
        
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }
        
        .stats-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">

<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out" id="sidebar">
    <div class="flex items-center justify-center h-16 bg-gradient-to-r from-blue-600 to-indigo-600">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-tachometer-alt text-blue-600"></i>
            </div>
            <h1 class="text-xl font-bold text-white">Admin Panel</h1>
        </div>
    </div>
    
    <nav class="mt-8">
        <div class="px-4 space-y-2">
            <a href="#" class="flex items-center px-4 py-3 text-blue-600 bg-blue-50 rounded-xl border border-blue-200 font-medium">
                <i class="fas fa-chart-line mr-3"></i>
                Dashboard
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl transition-colors">
                <i class="fas fa-users mr-3"></i>
                Kelola User
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl transition-colors">
                <i class="fas fa-newspaper mr-3"></i>
                Kelola Berita
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl transition-colors">
                <i class="fas fa-chart-bar mr-3"></i>
                Statistik
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl transition-colors">
                <i class="fas fa-cog mr-3"></i>
                Pengaturan
            </a>
        </div>
        
        <div class="px-4 mt-8">
            <div class="border-t pt-4">
                <a href="../auth/logout.php" class="flex items-center px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </a>
            </div>
        </div>
    </nav>
</div>

<!-- Mobile sidebar backdrop -->
<div class="fixed inset-0 z-40 lg:hidden" style="display: none;" id="sidebar-backdrop">
    <div class="fixed inset-0 bg-black opacity-50"></div>
</div>

<!-- Main Content -->
<div class="lg:ml-64">
    <!-- Top Navigation -->
    <nav class="glass-effect border-b border-white/20 px-4 lg:px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button class="lg:hidden text-gray-600 hover:text-gray-800 transition-colors" id="mobile-menu-button">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
                    <p class="text-sm text-gray-600">Selamat datang kembali, Admin!</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <button class="p-2 text-gray-600 hover:text-gray-800 hover:bg-white rounded-xl transition-all">
                        <i class="fas fa-bell"></i>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </button>
                </div>
                
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold">A</span>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-medium text-gray-800">Admin</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <main class="p-4 lg:p-6">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl p-8 mb-8 text-white animate-fade-in">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-2xl md:text-3xl font-bold mb-2">Selamat Datang, Admin! 👋</h3>
                    <p class="text-blue-100 mb-4">Kelola portal berita dengan mudah dan efisien</p>
                    <div class="flex flex-wrap gap-3 text-sm">
                        <div class="flex items-center space-x-2 bg-white/20 px-3 py-1 rounded-full">
                            <i class="fas fa-calendar"></i>
                            <span><?php echo date('d F Y'); ?></span>
                        </div>
                        <div class="flex items-center space-x-2 bg-white/20 px-3 py-1 rounded-full">
                            <i class="fas fa-clock"></i>
                            <span id="current-time"></span>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-4xl text-white/80"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="stats-card bg-white rounded-2xl p-6 shadow-sm border border-gray-100 animate-bounce-in">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="flex items-center space-x-1 text-green-600 text-sm font-medium">
                        <i class="fas fa-arrow-up"></i>
                        <span>+12%</span>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-800 mb-1"><?php echo number_format($stats['total_users']); ?></h4>
                <p class="text-gray-600 text-sm font-medium">Total Pengguna</p>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-500">+150 pengguna bulan ini</p>
                </div>
            </div>

            <!-- Total News -->
            <div class="stats-card bg-white rounded-2xl p-6 shadow-sm border border-gray-100 animate-bounce-in" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-newspaper text-green-600 text-xl"></i>
                    </div>
                    <div class="flex items-center space-x-1 text-green-600 text-sm font-medium">
                        <i class="fas fa-arrow-up"></i>
                        <span>+8%</span>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-800 mb-1"><?php echo number_format($stats['total_news']); ?></h4>
                <p class="text-gray-600 text-sm font-medium">Total Berita</p>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-500">+45 berita minggu ini</p>
                </div>
            </div>

            <!-- Today Visitors -->
            <div class="stats-card bg-white rounded-2xl p-6 shadow-sm border border-gray-100 animate-bounce-in" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-eye text-purple-600 text-xl"></i>
                    </div>
                    <div class="flex items-center space-x-1 text-green-600 text-sm font-medium">
                        <i class="fas fa-arrow-up"></i>
                        <span>+23%</span>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-800 mb-1"><?php echo number_format($stats['today_visitors']); ?></h4>
                <p class="text-gray-600 text-sm font-medium">Pengunjung Hari Ini</p>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-500">Peak: 14:30 WIB</p>
                </div>
            </div>

            <!-- Monthly Growth -->
            <div class="stats-card bg-white rounded-2xl p-6 shadow-sm border border-gray-100 animate-bounce-in" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                    </div>
                    <div class="flex items-center space-x-1 text-green-600 text-sm font-medium">
                        <i class="fas fa-arrow-up"></i>
                        <span>+<?php echo $stats['monthly_growth']; ?>%</span>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-800 mb-1"><?php echo $stats['monthly_growth']; ?>%</h4>
                <p class="text-gray-600 text-sm font-medium">Pertumbuhan Bulanan</p>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-500">Target: 20%</p>
                </div>
            </div>
        </div>

        <!-- Charts and Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Traffic Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800">Statistik Pengunjung</h4>
                        <p class="text-sm text-gray-600">Data 7 hari terakhir</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg text-sm font-medium">7 Hari</button>
                        <button class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium">30 Hari</button>
                    </div>
                </div>
                <canvas id="trafficChartt" width="400" height="200"></canvas>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-6">Aksi Cepat</h4>
                <div class="space-y-3">
                    <button class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 rounded-xl transition-all group">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                                <i class="fas fa-plus text-blue-600"></i>
                            </div>
                            <span class="font-medium text-gray-800">Tambah Berita</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600 transition-colors"></i>
                    </button>

                    <button class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 rounded-xl transition-all group">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center transition-colors">
                                <i class="fas fa-user-plus text-green-600"></i>
                            </div>
                            <span class="font-medium text-gray-800">Tambah User</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600 transition-colors"></i>
                    </button>

                    <button class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 rounded-xl transition-all group">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center transition-colors">
                                <i class="fas fa-chart-bar text-purple-600"></i>
                            </div>
                            <span class="font-medium text-gray-800">Lihat Laporan</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600 transition-colors"></i>
                    </button>

                    <button class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-yellow-50 hover:from-orange-100 hover:to-yellow-100 rounded-xl transition-all group">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-100 group-hover:bg-orange-200 rounded-lg flex items-center justify-center transition-colors">
                                <i class="fas fa-cog text-orange-600"></i>
                            </div>
                            <span class="font-medium text-gray-800">Pengaturan</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600 transition-colors"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent News -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold text-gray-800">Berita Terbaru</h4>
                    <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Lihat Semua</a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-newspaper text-blue-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">Ekonomi Indonesia Mengalami Pertumbuhan Positif</p>
                            <p class="text-xs text-gray-500 mt-1">2 jam yang lalu</p>
                        </div>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Aktif</span>
                    </div>

                    <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-newspaper text-green-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">Update COVID-19: Kasus Menurun Signifikan</p>
                            <p class="text-xs text-gray-500 mt-1">4 jam yang lalu</p>
                        </div>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Aktif</span>
                    </div>

                    <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-newspaper text-purple-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">Teknologi AI Mulai Diterapkan di Sektor Pendidikan</p>
                            <p class="text-xs text-gray-500 mt-1">6 jam yang lalu</p>
                        </div>
                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Review</span>
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold text-gray-800">Pengguna Terbaru</h4>
                    <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Lihat Semua</a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            JD
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">John Doe</p>
                            <p class="text-xs text-gray-500">john.doe@email.com</p>
                        </div>
                        <span class="text-xs text-gray-500">1 jam</span>
                    </div>

                    <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-600 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            AS
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">Andi Susanto</p>
                            <p class="text-xs text-gray-500">andi.susanto@email.com</p>
                        </div>
                        <span class="text-xs text-gray-500">3 jam</span>
                    </div>

                    <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            SM
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">Siti Maharani</p>
                            <p class="text-xs text-gray-500">siti.maharani@email.com</p>
                        </div>
                        <span class="text-xs text-gray-500">5 jam</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const sidebar = document.getElementById('sidebar');
    const sidebarBackdrop = document.getElementById('sidebar-backdrop');

    mobileMenuButton.addEventListener('click', function() {
        sidebar.classList.toggle('-translate-x-full');
        sidebarBackdrop.style.display = sidebar.classList.contains('-translate-x-full') ? 'none' : 'block';
    });

    sidebarBackdrop.addEventListener('click', function() {
        sidebar.classList.add('-translate-x-full');
        sidebarBackdrop.style.display = 'none';
    });

    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        document.getElementById('current-time').textContent = timeString;
    }
    
    updateTime();
    setInterval(updateTime, 1000);

    // Traffic Chart
    const ctx = document.getElementById('trafficChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Pengunjung',
                data: [1200, 1900, 3000, 5000, 2000, 3000, 4500],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        color: '#6B7280'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6B7280'
                    }
                }
            }
        }
    });

    // Add click handlers for quick action buttons
    document.querySelectorAll('.bg-gradient-to-r').forEach(button => {
        if (button.tagName === 'BUTTON') {
            button.addEventListener('click', function() {
                const actionText = this.querySelector('span').textContent;
                Swal.fire({
                    title: actionText,
                    text: 'Fitur ini akan segera tersedia!',
                    icon: 'info',
                    confirmButtonColor: '#3B82F6'
                });
            });
        }
    });
});

</script>

</body>
</html>