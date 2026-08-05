@php
    $role = Auth::user()->role ?? 'magang';
@endphp

<style>
    /* Sidebar Styles */
    .sidebar {
        width: 250px;
        background: rgba(15, 19, 42, 0.75);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border-right: 1px solid var(--border-color);
        color: white;
        padding: 20px 0;
        box-shadow: 10px 0 30px rgba(0, 0, 0, 0.3);
        z-index: 1600; /* Ditinggikan agar di atas overlay */
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        overflow-y: auto;
        transition: var(--transition);
    }

    /* Desktop Collapsed State */
    body.sidebar-collapsed .sidebar {
        left: -250px;
    }
    
    .sidebar-logo {
        padding: 0 25px 25px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 15px;
    }
    
    .sidebar-logo h2 {
        font-family: var(--font-heading);
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .menu {
        list-style: none;
    }
    
    .menu-item {
        padding: 14px 25px;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        color: var(--text-muted);
        gap: 12px;
        border-left: 4px solid transparent;
        font-weight: 500;
        font-family: var(--font-heading);
    }
    
    .menu-item:hover, .menu-item.active {
        background-color: rgba(255, 123, 0, 0.06);
        border-left: 4px solid var(--primary);
        padding-left: 30px;
        color: white;
    }
    
    .menu-item i {
        font-size: 1.2rem;
        width: 20px;
        text-align: center;
    }
    
    /* Mobile Tabs - Hanya untuk Mobile */
    .mobile-tabs {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #0f132a;
        border-top: 1px solid var(--border-color);
        box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        padding: 8px 0;
    }
    
    .mobile-tab {
        flex: 1;
        padding: 8px 0;
        text-align: center;
        color: var(--text-muted);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 0.75rem;
        transition: var(--transition);
        font-weight: 500;
    }
    
    .mobile-tab i {
        font-size: 1.2rem;
        margin-bottom: 3px;
    }
    
    .mobile-tab.active {
        color: var(--primary);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar {
            left: -260px; /* Sembunyikan sidebar ke kiri di mobile */
            box-shadow: 5px 0 25px rgba(0, 0, 0, 0.4);
        }
        
        body.sidebar-mobile-open .sidebar {
            left: 0; /* Tampilkan (slide in) di mobile */
        }
        
        .mobile-tabs {
            display: flex; /* Tampilkan mobile tabs di mobile */
        }
    }
    
    @media (max-width: 480px) {
        .mobile-tab {
            font-size: 0.7rem;
        }
        
        .mobile-tab i {
            font-size: 1rem;
        }
    }
</style>

<!-- Sidebar - Hanya untuk Desktop -->
<div class="sidebar">
    <div class="sidebar-logo">
        <h2><i class="fas fa-fingerprint"></i> AbsensiKu</h2>
    </div>
    
    <ul class="menu">     
        @if ($role === 'mentor' || $role === 'admin')
            <!-- Menu untuk Mentor dan Admin -->
            <li>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ Request::is('admin') || Request::is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.buat_qr') }}" class="menu-item {{ Request::is('admin/buat-qr*') ? 'active' : '' }}">
                    <i class="fas fa-qrcode"></i>
                    <span>Buat QR Code</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users') }}" class="menu-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i>
                    <span>Kelola User</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.prestasi') }}" class="menu-item {{ Request::is('admin/prestasi*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Prestasi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.laporan') }}" class="menu-item {{ Request::is('admin/laporan*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Laporan</span>
                </a>
            </li>
        @elseif ($role === 'magang')
            <!-- Menu untuk Magang -->
            <li>
                <a href="{{ route('magang.dashboard') }}" class="menu-item {{ Request::is('magang') || Request::is('magang/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('magang.scan') }}" class="menu-item {{ Request::is('magang/scan*') ? 'active' : '' }}">
                    <i class="fas fa-camera"></i>
                    <span>Scan QR Absensi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('magang.riwayat') }}" class="menu-item {{ Request::is('magang/riwayat*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Riwayat Absensi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('magang.peserta') }}" class="menu-item {{ Request::is('magang/peserta*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Data Peserta</span>
                </a>
            </li>
        @endif
        
        <li>
            <a href="{{ route('logout') }}" class="menu-item" onclick="return confirmLogout()">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<!-- Mobile Tabs - Hanya untuk Mobile -->
<div class="mobile-tabs">
    @if ($role === 'mentor' || $role === 'admin')
        <!-- Mobile Tabs untuk Mentor dan Admin -->
        <a href="{{ route('admin.dashboard') }}" class="mobile-tab {{ Request::is('admin') || Request::is('admin/dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.buat_qr') }}" class="mobile-tab {{ Request::is('admin/buat-qr*') ? 'active' : '' }}">
            <i class="fas fa-qrcode"></i>
            <span>Buat QR</span>
        </a>
        <a href="{{ route('admin.users') }}" class="mobile-tab {{ Request::is('admin/users*') ? 'active' : '' }}">
            <i class="fas fa-user-cog"></i>
            <span>User</span>
        </a>
        <a href="{{ route('admin.prestasi') }}" class="mobile-tab {{ Request::is('admin/prestasi*') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            <span>Prestasi</span>
        </a>
        <a href="{{ route('admin.laporan') }}" class="mobile-tab {{ Request::is('admin/laporan*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            <span>Laporan</span>
        </a>
    @elseif ($role === 'magang')
        <!-- Mobile Tabs untuk Magang -->
        <a href="{{ route('magang.dashboard') }}" class="mobile-tab {{ Request::is('magang') || Request::is('magang/dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('magang.scan') }}" class="mobile-tab {{ Request::is('magang/scan*') ? 'active' : '' }}">
            <i class="fas fa-camera"></i>
            <span>Scan QR</span>
        </a>
        <a href="{{ route('magang.riwayat') }}" class="mobile-tab {{ Request::is('magang/riwayat*') ? 'active' : '' }}">
            <i class="fas fa-history"></i>
            <span>Riwayat</span>
        </a>
        <a href="{{ route('magang.peserta') }}" class="mobile-tab {{ Request::is('magang/peserta*') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Peserta</span>
        </a>
        <a href="{{ route('logout') }}" class="mobile-tab" onclick="return confirmLogout()">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    @endif
</div>

<script>
    // Konfirmasi logout
    function confirmLogout() {
        if (confirm('Apakah Anda yakin ingin logout?')) {
            // Kita gunakan post form untuk logout demi keamanan Laravel CSRF
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('logout') }}";
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = "{{ csrf_token() }}";
            
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
            return false;
        }
        return false;
    }
</script>
