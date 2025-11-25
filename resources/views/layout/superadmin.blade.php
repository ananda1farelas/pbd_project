<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Superadmin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            flex-shrink: 0;
            box-shadow: 4px 0 20px rgba(0,0,0,0.3);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 25px 20px;
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sidebar-header .logo {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .sidebar-header h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar .nav {
            padding: 15px 10px;
        }

        .sidebar .nav-link {
            color: #94a3b8;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 10px;
            margin: 4px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background: rgba(102, 126, 234, 0.15);
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar .nav-link:hover::before {
            transform: translateX(0);
        }

        .sidebar .nav-link.active {
            background: rgba(102, 126, 234, 0.2);
            color: #fff;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .dropdown-menu-custom {
            background: rgba(15, 23, 42, 0.6);
            border: none;
            margin-left: 20px;
            border-radius: 8px;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease, padding 0.3s ease;
            padding: 0;
        }

        .dropdown-menu-custom.show {
            max-height: 500px;
            padding: 8px 0;
        }

        .dropdown-menu-custom .nav-link {
            font-size: 14px;
            padding: 10px 16px;
            color: #94a3b8;
        }

        .dropdown-toggle::after {
            float: right;
            margin-top: 5px;
            transition: transform 0.3s ease;
        }

        .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .logout-link {
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 15px;
        }

        .logout-link .nav-link {
            color: #ef4444;
        }

        .logout-link .nav-link:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
        }

        .content {
            flex-grow: 1;
            background: #f1f5f9;
            margin-left: 280px;
            min-height: 100vh;   /* penting */
            padding: 0;          /* biar full */
        }

        .top-navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-navbar h5 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            color: #1e293b;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: #f8fafc;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            background: #e2e8f0;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .main-container {
            padding: 30px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-crown"></i>
            </div>
            <h4>Superadmin</h4>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('superadmin/dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Dropdown Data Master -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#dataMasterMenu" role="button" aria-expanded="false">
                    <i class="fas fa-database"></i>
                    <span>Data Master</span>
                </a>
                <div class="collapse dropdown-menu-custom" id="dataMasterMenu">
                    <a class="nav-link {{ Request::is('superadmin/datamaster/barang*') ? 'active' : '' }}" href="{{ route('superadmin.barang.index') }}">
                        <i class="fas fa-box"></i>
                        <span>Barang</span>
                    </a>
                    <a class="nav-link {{ Request::is('superadmin/datamaster/vendor*') ? 'active' : '' }}" href="{{ route('vendor.index') }}">
                        <i class="fas fa-truck"></i>
                        <span>Vendor</span>
                    </a>
                    <a class="nav-link {{ Request::is('superadmin/datamaster/satuan*') ? 'active' : '' }}" href="{{ route('satuan.index') }}">
                        <i class="fas fa-balance-scale"></i>
                        <span>Satuan</span>
                    </a>
                    <a class="nav-link {{ Request::is('superadmin/datamaster/user*') ? 'active' : '' }}" href="{{ route('user.index') }}">
                        <i class="fas fa-users"></i>
                        <span>User</span>
                    </a>
                    <a class="nav-link {{ Request::is('superadmin/datamaster/margin*') ? 'active' : '' }}" href="{{ route('superadmin.margin.index') }}">
                        <i class="fas fa-percentage"></i>
                        <span>Margin Penjualan</span>
                    </a>
                    <a class="nav-link {{ Request::is('superadmin/datamaster/role*') ? 'active' : '' }}" href="{{ route('role.index') }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Role</span>
                    </a>
                </div>
            </li>

            <!-- Dropdown Transaksi -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#transaksiMenu" role="button" aria-expanded="false">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Transaksi</span>
                </a>
                <div class="collapse dropdown-menu-custom" id="transaksiMenu">
                    <a class="nav-link {{ Request::is('pengadaan*') ? 'active' : '' }}" href="{{ route('pengadaan.index') }}">
                        <i class="fas fa-file-invoice"></i>
                        <span>Pengadaan</span>
                    </a>
                    <a class="nav-link {{ Request::is('superadmin/transaksi/penerimaan*') ? 'active' : '' }}" href="{{ route('penerimaan.index') }}">
                        <i class="fas fa-inbox"></i>
                        <span>Penerimaan</span>
                    </a>
                    <a class="nav-link {{ Request::is('superadmin/transaksi/retur*') ? 'active' : '' }}" href="{{ route('retur.index') }}">
                        <i class="fas fa-undo"></i>
                        <span>Retur Barang</span>
                    </a>
                    <a class="nav-link {{ Request::is('superadmin/transaksi/penjualan*') ? 'active' : '' }}" href="{{ route('superadmin.penjualan.index') }}">
                        <i class="fas fa-cash-register"></i>
                        <span>Penjualan</span>
                    </a>
                    <a class="nav-link {{ Request::is('superadmin/transaksi/kartustok*') ? 'active' : '' }}" href="{{ route('kartustok.index') }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Kartu Stok</span>
                    </a>
                </div>
            </li>

            <li class="nav-item logout-link">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link">Logout</button>
                </form>
            </li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">
        <nav class="top-navbar">
            <h5>@yield('page-title', 'Dashboard Superadmin')</h5>
            <div class="user-profile">
                <div class="user-avatar">SA</div>
                <span style="font-weight: 500; color: #475569;">Superadmin</span>
            </div>
        </nav>

        <div class="main-container">
            @yield('content')
        </div>
    </div>

    <!-- jQuery HARUS SEBELUM Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
    
    <script>
        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
            });
        });

        // Add active state to nav links
        document.querySelectorAll('.sidebar .nav-link:not(.dropdown-toggle)').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelectorAll('.sidebar .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>