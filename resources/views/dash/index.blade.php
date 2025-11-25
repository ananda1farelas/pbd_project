<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-bolt"></i>
                <span>Dashboard</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-chart-line"></i>
                <span>Statistik</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Pengguna</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-folder"></i>
                <span>Proyek</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name ?? 'Admin User' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-wrapper">
        <!-- Top Bar -->
        <header class="topbar">
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="topbar-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari sesuatu...">
            </div>

            <div class="topbar-actions">
                <button class="topbar-btn">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </button>
                <button class="topbar-btn">
                    <i class="fas fa-envelope"></i>
                    <span class="badge">5</span>
                </button>
            </div>
        </header>

        <!-- Content -->
        <main class="content">
            <!-- Welcome Section -->
            <div class="welcome-section">
                <div class="welcome-content">
                    <h1>Selamat Datang Kembali! 👋</h1>
                    <p>Berikut ringkasan aktivitas Anda hari ini</p>
                </div>
                <div class="welcome-date">
                    <i class="fas fa-calendar"></i>
                    <span id="currentDate"></span>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">2,543</div>
                        <div class="stat-label">Total Pengguna</div>
                    </div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>12%</span>
                    </div>
                </div>

                <div class="stat-card green">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">$45,678</div>
                        <div class="stat-label">Total Revenue</div>
                    </div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>8.5%</span>
                    </div>
                </div>

                <div class="stat-card orange">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">1,234</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-trend negative">
                        <i class="fas fa-arrow-down"></i>
                        <span>3.2%</span>
                    </div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">4.8</div>
                        <div class="stat-label">Rating</div>
                    </div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>0.3</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Quick Actions -->
            <div class="dashboard-grid">
                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <h3>Aktivitas Terbaru</h3>
                        <button class="btn-text">Lihat Semua</button>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon blue">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Pengguna baru terdaftar</div>
                                    <div class="activity-time">2 menit yang lalu</div>
                                </div>
                            </div>
                            
                            <div class="activity-item">
                                <div class="activity-icon green">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Proyek berhasil diselesaikan</div>
                                    <div class="activity-time">15 menit yang lalu</div>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon orange">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Peringatan sistem terdeteksi</div>
                                    <div class="activity-time">1 jam yang lalu</div>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon purple">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Laporan bulanan dibuat</div>
                                    <div class="activity-time">3 jam yang lalu</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3>Aksi Cepat</h3>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <button class="action-btn">
                                <i class="fas fa-plus"></i>
                                <span>Tambah Pengguna</span>
                            </button>
                            <button class="action-btn">
                                <i class="fas fa-file-export"></i>
                                <span>Export Data</span>
                            </button>
                            <button class="action-btn">
                                <i class="fas fa-chart-bar"></i>
                                <span>Buat Laporan</span>
                            </button>
                            <button class="action-btn">
                                <i class="fas fa-cog"></i>
                                <span>Pengaturan</span>
                            </button>
                        </div>

                        <div class="quick-stats">
                            <div class="quick-stat-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <div class="quick-stat-value">24h</div>
                                    <div class="quick-stat-label">Uptime</div>
                                </div>
                            </div>
                            <div class="quick-stat-item">
                                <i class="fas fa-server"></i>
                                <div>
                                    <div class="quick-stat-value">98%</div>
                                    <div class="quick-stat-label">Server Load</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laravel Resources (Original Content) -->
            <div class="card">
                <div class="card-header">
                    <h3>Laravel Resources</h3>
                </div>
                <div class="card-body">
                    <div class="resources-grid">
                        <a href="https://laravel.com/docs" target="_blank" class="resource-card">
                            <i class="fas fa-book"></i>
                            <h4>Documentation</h4>
                            <p>Comprehensive Laravel documentation</p>
                        </a>
                        <a href="https://laracasts.com" target="_blank" class="resource-card">
                            <i class="fas fa-video"></i>
                            <h4>Laracasts</h4>
                            <p>Video tutorials and screencasts</p>
                        </a>
                        <a href="https://cloud.laravel.com" target="_blank" class="resource-card">
                            <i class="fas fa-cloud"></i>
                            <h4>Laravel Cloud</h4>
                            <p>Deploy and scale your apps</p>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Mobile Menu Toggle
        const sidebar = document.getElementById('sidebar');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebarToggle = document.getElementById('sidebarToggle');

        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
        });

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });

        // Current Date
        const currentDate = document.getElementById('currentDate');
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        currentDate.textContent = new Date().toLocaleDateString('id-ID', options);

        // Click outside sidebar to close on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>