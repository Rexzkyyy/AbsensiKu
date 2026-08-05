@php
    $role = Auth::user()->role ?? 'magang';
@endphp

<style>
    /* ═══════════ SIDEBAR ═══════════ */
    .sidebar {
        width: 260px;
        background: var(--glass-bg-strong);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-right: 1px solid var(--border-light);
        color: var(--text-dark);
        padding: 24px 0;
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.04);
        z-index: 1600;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        overflow-y: auto;
        transition: var(--transition);
    }

    body.sidebar-collapsed .sidebar {
        left: -260px;
    }

    .sidebar-logo {
        padding: 0 24px 24px;
        border-bottom: 1px solid var(--border-light);
        margin-bottom: 16px;
    }

    .sidebar-logo h2 {
        font-size: 1.35rem;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.02em;
    }

    .sidebar-logo h2 i {
        color: var(--primary);
        font-size: 1.3rem;
    }

    .sidebar-logo h2 span {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .sidebar-role-badge {
        display: inline-block;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 4px 12px;
        border-radius: 20px;
        margin-top: 10px;
        background: rgba(67, 97, 238, 0.08);
        color: var(--primary);
        border: 1px solid rgba(67, 97, 238, 0.12);
    }

    .menu {
        list-style: none;
        padding: 0 12px;
    }

    .menu li {
        margin-bottom: 2px;
    }

    .menu-item {
        padding: 12px 16px;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        color: var(--text-muted);
        gap: 12px;
        border-radius: 12px;
        font-weight: 500;
        font-size: 0.92rem;
        border: 1px solid transparent;
    }

    .menu-item:hover {
        background: rgba(67, 97, 238, 0.05);
        color: var(--primary);
        border-color: rgba(67, 97, 238, 0.08);
    }

    .menu-item.active {
        background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(114, 9, 183, 0.06));
        color: var(--primary);
        font-weight: 700;
        border-color: rgba(67, 97, 238, 0.12);
        box-shadow: 0 2px 8px rgba(67, 97, 238, 0.08);
    }

    .menu-item i {
        font-size: 1.1rem;
        width: 22px;
        text-align: center;
    }

    .menu-item.active i {
        color: var(--primary);
    }

    .menu-divider {
        height: 1px;
        background: var(--border-light);
        margin: 12px 16px;
    }

    .menu-item.logout-item {
        color: var(--danger);
        margin-top: 8px;
    }

    .menu-item.logout-item:hover {
        background: rgba(239, 68, 68, 0.06);
        color: var(--danger);
        border-color: rgba(239, 68, 68, 0.1);
    }

    /* ═══════════ MOBILE TABS ═══════════ */
    .mobile-tabs {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--glass-bg-strong);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-top: 1px solid var(--border-light);
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.06);
        z-index: 1000;
        padding: 6px 0;
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
        font-size: 0.7rem;
        transition: var(--transition);
        font-weight: 500;
    }

    .mobile-tab i {
        font-size: 1.15rem;
        margin-bottom: 3px;
    }

    .mobile-tab.active {
        color: var(--primary);
        font-weight: 700;
    }

    .mobile-tab.active i {
        color: var(--primary);
    }

    /* ═══════════ RESPONSIVE ═══════════ */
    @media (max-width: 768px) {
        .sidebar {
            left: -270px;
            box-shadow: 5px 0 30px rgba(0, 0, 0, 0.1);
        }

        body.sidebar-mobile-open .sidebar {
            left: 0;
        }

        .mobile-tabs {
            display: flex;
        }
    }

    @media (max-width: 480px) {
        .mobile-tab {
            font-size: 0.65rem;
        }

        .mobile-tab i {
            font-size: 1rem;
        }
    }
</style>

<!-- Sidebar - Desktop -->
<div class="sidebar">
    <div class="sidebar-logo">
        <h2><i class="fas fa-fingerprint"></i> <span>AbsensiKu</span></h2>
        <div class="sidebar-role-badge">
            <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle; margin-right: 4px;"></i>
            {{ $role === 'mentor' ? 'Mentor / Admin' : ucfirst($role) }}
        </div>
    </div>

    <ul class="menu">
        @if ($role === 'mentor' || $role === 'admin')
            <!-- Menu Mentor / Admin -->
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
            <!-- Menu Magang -->
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

        <div class="menu-divider"></div>

        <li>
            <a href="{{ route('logout') }}" class="menu-item logout-item" onclick="return confirmLogout()">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<!-- Mobile Tabs -->
<div class="mobile-tabs">
    @if ($role === 'mentor' || $role === 'admin')
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
