<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'AbsensiKu - Sistem Presensi Digital Badan Pusat Statistik Provinsi Sulawesi Tenggara (BPS Sultra). Pantau absensi magang real-time secara aman.')">
    <title>@yield('title') - AbsensiKu</title>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #5e7bff;
            --primary-dark: #3651d4;
            --secondary: #7209b7;
            --accent: #f72585;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;

            --bg-base: #f0f2f8;
            --bg-white: #ffffff;
            --glass-bg: rgba(255, 255, 255, 0.6);
            --glass-bg-strong: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.7);
            --glass-shadow: 0 8px 32px rgba(31, 38, 135, 0.08);
            --card-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);

            --text-dark: #1a1d2e;
            --text-body: #4a4d60;
            --text-muted: #8b8fa3;
            --border-light: rgba(0, 0, 0, 0.06);

            --hadir: #10b981;
            --terlambat: #f59e0b;
            --tidak-hadir: #ef4444;
            --early: #06b6d4;

            --transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--bg-base);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* ═══════════ MAIN CONTENT ═══════════ */
        .main-content {
            flex: 1;
            padding: 30px;
            margin-left: 260px;
            width: calc(100% - 260px);
            padding-bottom: 40px;
            transition: var(--transition);
        }

        body.sidebar-collapsed .main-content {
            margin-left: 0;
            width: 100%;
        }

        /* ═══════════ HEADER ═══════════ */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 15px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-light);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sidebar-toggle-btn {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-light);
            color: var(--primary);
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.1rem;
        }

        .sidebar-toggle-btn:hover {
            background: var(--primary);
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(4px);
            z-index: 1500;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        body.sidebar-mobile-open .sidebar-overlay {
            display: block;
            opacity: 1;
        }

        .header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.01em;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }

        /* ═══════════ CARDS ═══════════ */
        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 28px;
            box-shadow: var(--card-shadow);
            margin-bottom: 28px;
            transition: var(--transition);
            border: 1px solid var(--glass-border);
            color: var(--text-dark);
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .section-title {
            font-size: 1.15rem;
            margin-bottom: 20px;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
        }

        .section-title i {
            color: var(--primary);
            background: rgba(67, 97, 238, 0.08);
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
        }

        /* ═══════════ FORM ELEMENTS ═══════════ */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-body);
            font-size: 0.88rem;
            letter-spacing: 0.02em;
        }

        .form-input {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid var(--border-light);
            border-radius: 12px;
            transition: var(--transition);
            font-size: 0.95rem;
            background: rgba(255, 255, 255, 0.7);
            color: var(--text-dark);
            font-family: 'Inter', sans-serif;
        }

        .form-input:focus {
            outline: none;
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.08);
        }

        select.form-input option {
            background-color: white;
            color: var(--text-dark);
        }

        /* ═══════════ BUTTONS ═══════════ */
        .btn {
            padding: 12px 22px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: white;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.02em;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            box-shadow: 0 4px 14px rgba(67, 97, 238, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.35);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success), #34d399);
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning), #fbbf24);
            color: #78350f;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.25);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.35);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #f87171);
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.25);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.35);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: rgba(67, 97, 238, 0.05);
        }

        /* ═══════════ TIME DISPLAY ═══════════ */
        .time-display {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: var(--text-dark);
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
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--accent));
            border-radius: 20px 20px 0 0;
        }

        .time-display .date {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary);
        }

        .time-display .time {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 5px;
            letter-spacing: 2px;
            color: var(--text-dark);
        }

        .time-display .location {
            font-size: 0.9rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* ═══════════ ALERTS ═══════════ */
        .alert {
            padding: 16px 20px;
            border-radius: 14px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.08);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.18);
        }

        .alert-danger, .alert-error {
            background: rgba(239, 68, 68, 0.06);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.15);
        }

        /* ═══════════ TABLE ═══════════ */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            color: var(--text-dark);
        }

        th {
            background: rgba(67, 97, 238, 0.04);
            font-weight: 700;
            text-align: left;
            padding: 14px 18px;
            border-bottom: 2px solid rgba(67, 97, 238, 0.1);
            font-size: 0.82rem;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-light);
            font-size: 0.92rem;
            color: var(--text-body);
            transition: var(--transition);
        }

        tr:hover td {
            background: rgba(67, 97, 238, 0.02);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        /* ═══════════ PAGINATION ═══════════ */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            width: 100%;
        }

        nav[role="navigation"] {
            display: flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        nav[role="navigation"] .flex-fill.d-sm-none {
            display: none !important;
        }

        nav[role="navigation"] .d-none.flex-fill.d-sm-flex {
            display: flex !important;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            flex-wrap: wrap;
            gap: 15px;
        }

        nav[role="navigation"] p.text-muted,
        nav[role="navigation"] .text-muted {
            font-size: 0.88rem;
            color: var(--text-muted) !important;
            margin: 0;
            font-weight: 500;
        }

        @media (max-width: 576px) {
            nav[role="navigation"] .flex-fill.d-sm-none {
                display: flex !important;
                width: 100%;
                justify-content: space-between;
                background: var(--glass-bg);
                backdrop-filter: blur(15px);
                border: 1px solid var(--glass-border);
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
                font-size: 0.88rem;
                transition: var(--transition);
            }

            nav[role="navigation"] .flex-fill.d-sm-none a {
                color: var(--primary);
                background: rgba(67, 97, 238, 0.06);
            }

            nav[role="navigation"] .flex-fill.d-sm-none a:hover {
                background: var(--primary);
                color: white;
            }

            nav[role="navigation"] .flex-fill.d-sm-none span {
                color: var(--text-muted);
                background: rgba(0, 0, 0, 0.02);
                cursor: not-allowed;
                opacity: 0.5;
            }
        }

        .pagination {
            display: flex;
            list-style: none;
            border-radius: 14px;
            overflow: hidden;
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            padding: 5px;
            gap: 4px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--glass-border);
            align-items: center;
            margin: 0;
        }

        .page-item .page-link {
            color: var(--text-body);
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            border: none;
            background: transparent;
            font-size: 0.9rem;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white !important;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }

        .page-item:hover .page-link:not(.active) {
            background: rgba(67, 97, 238, 0.06);
            color: var(--primary);
        }

        .page-item.disabled .page-link {
            color: var(--text-muted);
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* ═══════════ SCROLLBAR ═══════════ */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* ═══════════ RESPONSIVE ═══════════ */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px 15px 100px;
            }

            .header h1 {
                font-size: 1.3rem;
            }

            .time-display .time {
                font-size: 2rem;
            }

            .user-info span {
                display: none;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <script>
        // Anti-flicker: apply sidebar state before render
        if (window.innerWidth > 768 && localStorage.getItem('sidebar-collapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }
    </script>

    <!-- Overlay for mobile sidebar drawer -->
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
                        <span style="font-weight:600; color: var(--text-body); font-size: 0.9rem;">Hai, {{ $username }}</span>
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

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            dateElement.textContent = `${dayName}, ${date} ${monthName} ${year} - ${hours}:${minutes}:${seconds} WITA`;
            timeElement.textContent = `${hours}:${minutes}:${seconds}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            setInterval(updateTime, 1000);
            updateTime();

            // Sidebar toggle logic
            const toggleBtn = document.getElementById('sidebar-toggle');
            const overlay = document.getElementById('sidebar-overlay');
            const body = document.body;

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    if (window.innerWidth > 768) {
                        body.classList.toggle('sidebar-collapsed');
                        localStorage.setItem('sidebar-collapsed', body.classList.contains('sidebar-collapsed'));
                    } else {
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
