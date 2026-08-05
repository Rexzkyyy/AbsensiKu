<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'AbsensiKu - Sistem Presensi Digital Badan Pusat Statistik Provinsi Sulawesi Tenggara (BPS Sultra). Pantau absensi magang real-time secara aman.')">
    <title>@yield('title') - AbsensiKu</title>
    
    <!-- Google Fonts Preconnect and display=swap for high performance FCP/LCP -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #ff7b00; 
            --primary-light: #ffa600;
            --primary-dark: #cc6200;
            --secondary: #7209b7;
            --success: #00b4d8;
            --warning: #f72585; 
            --light: #0f132a; 
            --dark: #ffffff;
            --gray: rgba(255, 255, 255, 0.55); 
            --light-gray: rgba(255, 255, 255, 0.08);
            
            --bg-dark: #050711;
            --card-bg: rgba(15, 19, 42, 0.6);
            --border-color: rgba(255, 255, 255, 0.06);
            --text-bright: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.55);
            
            --hadir: #00b4d8; 
            --terlambat: #ffc107; 
            --tidak-hadir: #dc3545;
            --early: #17a2b8; 
            --weekend: #6f42c1;
            --jumat: #9c27b0;
            --minggu: #ff6b6b;
            --total-waktu: #fd7e14;
            --card-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
            --transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            
            --font-heading: 'Space Grotesk', sans-serif;
            --font-body: 'Outfit', sans-serif;
        }
        
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: var(--font-body); 
        }
        
        body { 
            background-color: var(--bg-dark); 
            color: var(--text-bright); 
            line-height: 1.6;
            min-height: 100vh;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.012) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.012) 1px, transparent 1px);
            background-size: 60px 60px;
        }
        
        .container { 
            display: flex; 
            min-height: 100vh; 
        }
        
        /* Main Content */
        .main-content { 
            flex: 1; 
            padding: 30px; 
            margin-left: 250px;
            width: calc(100% - 250px);
            padding-bottom: 40px;
            transition: var(--transition); /* Efek transisi halus saat sidebar dibuka/tutup */
        }

        /* Saat sidebar ditutup (collapsed) di Desktop */
        body.sidebar-collapsed .main-content {
            margin-left: 0;
            width: 100%;
        }
        
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 30px; 
            gap: 15px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            color: var(--primary);
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-size: 1.1rem;
        }

        .sidebar-toggle-btn:hover {
            background-color: var(--primary);
            color: white;
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(255, 123, 0, 0.25);
        }

        /* Overlay gelap di mobile saat sidebar drawer terbuka */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1500;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        body.sidebar-mobile-open .sidebar-overlay {
            display: block;
            opacity: 1;
        }

        /* Custom Pagination Styling (Bootstrap 5 global integration) */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            width: 100%;
        }

        /* Laravel Paginator custom responsive logic */
        nav[role="navigation"] {
            display: flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        /* Sembunyikan link khusus mobile pada layar desktop */
        nav[role="navigation"] .flex-fill.d-sm-none {
            display: none !important;
        }

        /* Tampilkan layout desktop secara horizontal dan rapi */
        nav[role="navigation"] .d-none.flex-fill.d-sm-flex {
            display: flex !important;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            flex-wrap: wrap;
            gap: 15px;
        }

        /* Hias teks info "Showing X to Y of Z results" */
        nav[role="navigation"] p.text-muted, 
        nav[role="navigation"] .text-muted {
            font-size: 0.9rem;
            color: var(--text-muted) !important;
            margin: 0;
            font-weight: 500;
        }

        /* Perilaku responsif ketika di layar Handphone (Mobile) */
        @media (max-width: 576px) {
            nav[role="navigation"] .flex-fill.d-sm-none {
                display: flex !important;
                width: 100%;
                justify-content: space-between;
                background: var(--card-bg);
                backdrop-filter: blur(15px);
                border: 1px solid var(--border-color);
                padding: 10px 15px;
                border-radius: 12px;
                box-shadow: var(--card-shadow);
            }
            nav[role="navigation"] .d-none.flex-fill.d-sm-flex {
                display: none !important;
            }
            
            nav[role="navigation"] .flex-fill.d-sm-none a,
            nav[role="navigation"] .flex-fill.d-sm-none span {
                padding: 8px 16px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 600;
                font-size: 0.9rem;
                transition: var(--transition);
            }
            
            nav[role="navigation"] .flex-fill.d-sm-none a {
                color: var(--primary);
                background: rgba(255, 123, 0, 0.05);
            }
            
            nav[role="navigation"] .flex-fill.d-sm-none a:hover {
                background: var(--primary);
                color: white;
            }
            
            nav[role="navigation"] .flex-fill.d-sm-none span {
                color: var(--text-muted);
                background: rgba(255, 255, 255, 0.03);
                cursor: not-allowed;
                opacity: 0.6;
            }
        }

        .pagination {
            display: flex;
            list-style: none;
            border-radius: 12px;
            overflow: hidden;
            background: var(--card-bg);
            backdrop-filter: blur(15px);
            padding: 6px;
            gap: 6px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            align-items: center;
            margin: 0;
        }

        .page-item {
            display: inline-block;
        }

        .page-item .page-link {
            color: var(--text-bright);
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            font-size: 0.95rem;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white !important;
            box-shadow: 0 4px 12px rgba(255, 123, 0, 0.3);
            font-weight: 600;
        }

        .page-item:hover .page-link:not(.active) {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--primary);
        }

        .page-item.disabled .page-link {
            color: var(--text-muted);
            opacity: 0.5;
            cursor: not-allowed;
            background: transparent;
        }
        
        .header h1 {
            font-family: var(--font-heading);
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-bright);
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar { 
            width: 45px; 
            height: 45px; 
            border-radius: 50%; 
            background: linear-gradient(135deg, var(--primary), var(--secondary)); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            font-weight: bold; 
            box-shadow: 0 4px 10px rgba(255, 123, 0, 0.3);
            text-transform: uppercase;
        }
        
        /* Cards & Forms */
        .card { 
            background: var(--card-bg); 
            border-radius: 24px; 
            padding: 28px; 
            box-shadow: var(--card-shadow); 
            margin-bottom: 30px; 
            transition: var(--transition);
            border: 1px solid var(--border-color);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            color: var(--text-bright);
            position: relative;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: 0 25px 60px rgba(255, 123, 0, 0.04), var(--card-shadow);
            border-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-4px);
        }
        
        .section-title { 
            font-family: var(--font-heading);
            font-size: 1.3rem; 
            margin-bottom: 20px; 
            color: var(--text-bright); 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            font-weight: 600;
        }
        
        .section-title i { 
            color: var(--primary); 
            background: rgba(255, 123, 0, 0.08);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 123, 0, 0.15);
        }
        
        .form-group { 
            margin-bottom: 20px; 
        }
        
        .form-label { 
            font-family: var(--font-heading);
            display: block; 
            margin-bottom: 8px; 
            font-weight: 500; 
            color: var(--text-muted);
            font-size: 0.9rem;
            letter-spacing: 0.03em;
        }
        
        .form-input { 
            width: 100%; 
            padding: 14px 18px; 
            border: 1px solid var(--border-color); 
            border-left: 3px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px; 
            transition: var(--transition);
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.02);
            color: var(--text-bright);
        }
        
        .form-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--primary);
            box-shadow: 0 8px 20px rgba(255, 123, 0, 0.08);
        }
        
        select.form-input option {
            background-color: #0f132a;
            color: white;
        }
        
        .btn { 
            padding: 12px 22px; 
            border: none; 
            border-radius: 12px; 
            cursor: pointer; 
            font-family: var(--font-heading);
            font-weight: 700; 
            transition: var(--transition); 
            display: inline-flex; 
            align-items: center; 
            gap: 8px; 
            text-decoration: none; 
            color: white;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .btn-primary { 
            background: linear-gradient(135deg, var(--primary), var(--primary-light)); 
            box-shadow: 0 4px 15px rgba(255, 123, 0, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 123, 0, 0.35);
        }
        
        .btn-success { 
            background: linear-gradient(135deg, var(--hadir), #00d8b4); 
            box-shadow: 0 4px 15px rgba(0, 180, 216, 0.2);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 180, 216, 0.35);
        }
        
        .btn-warning { 
            background: linear-gradient(135deg, var(--terlambat), #ffa600); 
            color: #000; 
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.35);
        }
        
        .btn-danger { 
            background: linear-gradient(135deg, var(--tidak-hadir), #e35d6a); 
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.35);
        }
        
        .btn-outline { 
            background-color: transparent; 
            border: 2px solid var(--primary); 
            color: var(--primary); 
        }
        
        .btn-outline:hover {
            background-color: rgba(255, 123, 0, 0.05);
        }

        /* Time Display */
        .time-display {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            backdrop-filter: blur(25px);
            color: white;
            padding: 22px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            position: relative;
        }
        
        .time-display::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--success));
            border-radius: 20px 20px 0 0;
        }
        
        .time-display .date {
            font-family: var(--font-heading);
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary);
        }
        
        .time-display .time {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            font-family: 'Space Grotesk', monospace;
            letter-spacing: 2px;
            background: linear-gradient(135deg, white, var(--text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .time-display .location {
            font-size: 0.95rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Alerts */
        .alert { 
            padding: 16px 20px; 
            border-radius: 14px; 
            margin-bottom: 25px; 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            box-shadow: var(--card-shadow);
            animation: slideIn 0.5s ease-out;
            backdrop-filter: blur(15px);
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert-success { 
            background: rgba(0, 180, 216, 0.08); 
            color: var(--success); 
            border: 1px solid rgba(0, 180, 216, 0.2); 
        }
        
        .alert-danger, .alert-error { 
            background: rgba(247, 37, 133, 0.08); 
            color: var(--warning); 
            border: 1px solid rgba(247, 37, 133, 0.2); 
        }
        
        /* Gorgeous modern table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            color: var(--text-bright);
        }
        
        th {
            background: rgba(255, 255, 255, 0.02);
            font-family: var(--font-heading);
            font-weight: 600;
            text-align: left;
            padding: 16px 18px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        td {
            padding: 16px 18px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.85);
            transition: var(--transition);
        }
        
        tr:hover td {
            background: rgba(255, 255, 255, 0.015);
            color: white;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            font-family: var(--font-heading);
        }
        
        /* Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content { 
                margin-left: 0;
                width: 100%;
                padding: 20px 15px 100px; /* Tambah bottom padding agar tidak tertutup mobile navigation tabs */
            }
            
            .header h1 {
                font-size: 1.6rem;
            }
            
            .time-display .time {
                font-size: 2rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <script>
        // Anti-flicker: Terapkan class collapsed sebelum halaman di-render jika disimpan di localStorage
        if (window.innerWidth > 768 && localStorage.getItem('sidebar-collapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }
    </script>

    <!-- Overlay latar belakang gelap saat sidebar drawer terbuka di mobile -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="container">
        <!-- Include Sidebar & Navigation -->
        @include('layouts.sidebar')
        
        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Header section -->
            <div class="header">
                <div class="header-left">
                    <button id="sidebar-toggle" class="sidebar-toggle-btn" title="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>@yield('header_title')</h1>
                </div>
                <div class="user-info">
                    @php
                        $username = Auth::user()->username ?? 'User';
                        $initials = strtoupper(substr($username, 0, 2));
                    @endphp
                    <div class="user-avatar">{{ $initials }}</div>
                    @if(Auth::user() && Auth::user()->role !== 'magang')
                        <span style="font-weight:500;">Hai, {{ $username }}</span>
                    @endif
                </div>
            </div>
            
            <!-- Session Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Yield Content -->
            @yield('content')
        </main>
    </div>

    <!-- Script to keep the time updated dynamically -->
    <script>
        function updateTime() {
            const dateElement = document.getElementById('current-date');
            const timeElement = document.getElementById('current-time');
            
            if (!dateElement || !timeElement) return;
            
            const now = new Date();
            
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const date = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            
            // Format time with 2 digits
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            dateElement.textContent = `${dayName}, ${date} ${monthName} ${year} - ${hours}:${minutes}:${seconds} WITA`;
            timeElement.textContent = `${hours}:${minutes}:${seconds}`;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(updateTime, 1000);
            updateTime();

            // Logic untuk Toggle Sidebar (Open/Close)
            const toggleBtn = document.getElementById('sidebar-toggle');
            const overlay = document.getElementById('sidebar-overlay');
            const body = document.body;

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    if (window.innerWidth > 768) {
                        // Desktop toggle: slide dan simpan status di localStorage
                        body.classList.toggle('sidebar-collapsed');
                        localStorage.setItem('sidebar-collapsed', body.classList.contains('sidebar-collapsed'));
                    } else {
                        // Mobile toggle: slide-in drawer
                        body.classList.toggle('sidebar-mobile-open');
                    }
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    body.classList.remove('sidebar-mobile-open');
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
