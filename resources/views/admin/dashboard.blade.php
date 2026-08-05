@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('header_title', 'Dashboard')

@section('styles')
<style>
    /* ═══ Welcome Section ═══ */
    .welcome-section {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 30px;
        border-radius: 20px;
        margin-bottom: 28px;
        box-shadow: 0 8px 30px rgba(67, 97, 238, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
        top: -60px;
        right: -40px;
    }

    .welcome-text h2 {
        font-size: 1.5rem;
        margin-bottom: 5px;
        font-weight: 700;
    }

    .welcome-text p {
        opacity: 0.9;
        font-size: 0.95rem;
        font-weight: 400;
    }

    .welcome-icon {
        font-size: 3rem;
        opacity: 0.7;
    }

    /* ═══ Stats Grid ═══ */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border-radius: 18px;
        padding: 24px;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: var(--transition);
        border: 1px solid var(--glass-border);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.08);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: white;
        flex-shrink: 0;
    }

    .stat-info { flex: 1; }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 2px;
        line-height: 1.2;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* ═══ Content Grid ═══ */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 24px;
    }

    @media (max-width: 1400px) {
        .content-grid { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 1200px) {
        .content-grid { grid-template-columns: 1fr; }
    }

    /* ═══ List Items ═══ */
    .list-item {
        display: flex;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid var(--border-light);
    }

    .list-item:last-child { border-bottom: none; }

    .list-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(67, 97, 238, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
        color: var(--primary);
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .list-details { flex: 1; }

    .list-title {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 3px;
        font-size: 0.92rem;
    }

    .list-subtitle {
        font-size: 0.82rem;
        color: var(--text-muted);
    }

    .list-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .badge-primary { background: rgba(67, 97, 238, 0.08); color: var(--primary); }
    .badge-success { background: rgba(16, 185, 129, 0.08); color: var(--success); }
    .badge-warning { background: rgba(245, 158, 11, 0.08); color: #b45309; }
    .badge-danger { background: rgba(239, 68, 68, 0.08); color: var(--danger); }
    .badge-info { background: rgba(6, 182, 212, 0.08); color: var(--info); }

    /* ═══ Quick Actions ═══ */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-top: 16px;
    }

    .action-btn {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 14px;
        padding: 22px;
        text-align: center;
        transition: var(--transition);
        cursor: pointer;
        text-decoration: none;
        color: var(--text-dark);
    }

    .action-btn:hover {
        border-color: rgba(67, 97, 238, 0.2);
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        background: rgba(67, 97, 238, 0.03);
    }

    .action-icon {
        font-size: 1.8rem;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .action-label {
        font-weight: 700;
        font-size: 0.88rem;
        color: var(--text-body);
    }

    /* ═══ Attendance Status ═══ */
    .attendance-status {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
    }

    .time-badge {
        font-size: 0.72rem;
        background: rgba(0, 0, 0, 0.03);
        color: var(--text-muted);
        padding: 3px 8px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    @media (max-width: 768px) {
        .welcome-section {
            flex-direction: column;
            text-align: center;
            gap: 15px;
            padding: 24px;
        }

        .welcome-text h2 { font-size: 1.3rem; }
        .welcome-icon { font-size: 2.5rem; }
        .stats-grid { grid-template-columns: 1fr; }

        .list-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .attendance-status {
            align-items: flex-start;
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-text">
        <h2>Halo, {{ htmlspecialchars($username) }}! 👋</h2>
        <p>Selamat datang di panel dashboard absensi BPS Provinsi Sulawesi Tenggara</p>
    </div>
    <div class="welcome-icon">
        <i class="fas fa-fingerprint"></i>
    </div>
</div>

<!-- Time Display -->
<div class="time-display">
    <div class="date" id="current-date">{{ Carbon\Carbon::now('Asia/Makassar')->isoFormat('dddd, D MMMM Y') }} - {{ Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') }} WITA</div>
    <div class="time" id="current-time">{{ Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') }}</div>
    <div class="location">
        <i class="fas fa-map-marker-alt"></i>
        <span>Kendari, Sulawesi Tenggara - WITA</span>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $stats['total_users'] }}</div>
            <div class="stat-label">Total Pengguna</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--secondary), #9d4edd);">
            <i class="fas fa-qrcode"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $stats['total_qr'] }}</div>
            <div class="stat-label">Total QR Code</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--success), #34d399);">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $stats['today_attendance'] }}</div>
            <div class="stat-label">Absensi Hari Ini</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--accent), #f15bb5);">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $stats['active_qr'] }}</div>
            <div class="stat-label">QR Code Aktif</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <h3 class="section-title"><i class="fas fa-bolt"></i> Quick Actions</h3>
    <div class="quick-actions">
        <a href="{{ route('admin.buat_qr') }}" class="action-btn">
            <div class="action-icon"><i class="fas fa-plus-circle"></i></div>
            <div class="action-label">Buat QR Baru</div>
        </a>
        <a href="{{ route('admin.users') }}" class="action-btn">
            <div class="action-icon"><i class="fas fa-user-cog"></i></div>
            <div class="action-label">Kelola User</div>
        </a>
        <a href="{{ route('admin.laporan') }}" class="action-btn">
            <div class="action-icon"><i class="fas fa-chart-bar"></i></div>
            <div class="action-label">Lihat Laporan</div>
        </a>
    </div>
</div>

<!-- Main Content Grid -->
<div class="content-grid">
    <!-- Recent Users -->
    <div class="card">
        <h3 class="section-title"><i class="fas fa-users"></i> Pengguna Terbaru</h3>
        <div id="recent-users">
            @if ($recentUsers->isEmpty())
                <div style="text-align:center; color:var(--text-muted); padding: 30px;">
                    <i class="fas fa-user-slash fa-3x" style="margin-bottom: 15px; opacity: 0.3;"></i>
                    <p>Belum ada pengguna terdaftar.</p>
                </div>
            @else
                @foreach ($recentUsers as $user)
                    <div class="list-item">
                        <div class="list-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="list-details">
                            <div class="list-title">{{ htmlspecialchars($user->username) }}</div>
                            <div class="list-subtitle">{{ htmlspecialchars($user->email) }}</div>
                            <div style="font-size:0.78rem; color:var(--text-muted); margin-top:3px;">
                                <i class="fas fa-calendar"></i>
                                {{ Carbon\Carbon::parse($user->created_at)->isoFormat('D MMM Y') }}
                            </div>
                        </div>
                        <div>
                            <span class="list-badge
                                @if($user->role === 'mentor') badge-primary @elseif($user->role === 'admin') badge-success @else badge-warning @endif">
                                {{ $user->role }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div style="margin-top: 15px; text-align: center;">
            <a href="{{ route('admin.users') }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.88rem;">
                <i class="fas fa-arrow-right"></i> Lihat Semua Pengguna
            </a>
        </div>
    </div>

    <!-- Recent QR Codes -->
    <div class="card">
        <h3 class="section-title"><i class="fas fa-qrcode"></i> QR Code Terbaru</h3>
        <div id="recent-qr">
            @if ($recentQrs->isEmpty())
                <div style="text-align:center; color:var(--text-muted); padding: 30px;">
                    <i class="fas fa-qrcode fa-3x" style="margin-bottom: 15px; opacity: 0.3;"></i>
                    <p>Belum ada QR Code yang dibuat.</p>
                </div>
            @else
                @foreach ($recentQrs as $qr)
                    @php
                        $isExpired = Carbon\Carbon::parse($qr->expired_at)->isPast();
                    @endphp
                    <div class="list-item">
                        <div class="list-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="list-details">
                            <div class="list-title">{{ htmlspecialchars($qr->kode_qr) }}</div>
                            <div class="list-subtitle">{{ htmlspecialchars($qr->nama_kegiatan ?? 'Tanpa Nama') }}</div>
                            @if ($qr->cek_in && $qr->cek_out)
                                <div style="font-size:0.78rem; color:var(--text-muted);">
                                    <i class="fas fa-clock"></i>
                                    {{ Carbon\Carbon::parse($qr->cek_in)->format('H:i') }} - {{ Carbon\Carbon::parse($qr->cek_out)->format('H:i') }} WITA
                                </div>
                            @endif
                        </div>
                        <div>
                            <span class="list-badge @if($isExpired) badge-danger @else badge-success @endif">
                                {{ $isExpired ? 'expired' : 'aktif' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div style="margin-top: 15px; text-align: center;">
            <a href="{{ route('admin.buat_qr') }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.88rem;">
                <i class="fas fa-arrow-right"></i> Kelola QR Code
            </a>
        </div>
    </div>

    <!-- Recent Attendance Activity -->
    <div class="card">
        <h3 class="section-title"><i class="fas fa-history"></i> Aktivitas Absensi Terbaru</h3>
        <div id="recent-attendance">
            @if ($recentAttendance->isEmpty())
                <div style="text-align:center; color:var(--text-muted); padding: 30px;">
                    <i class="fas fa-clipboard-list fa-3x" style="margin-bottom: 15px; opacity: 0.3;"></i>
                    <p>Belum ada aktivitas absensi.</p>
                </div>
            @else
                @foreach ($recentAttendance as $attendance)
                    <div class="list-item">
                        <div class="list-icon" style="background: @if($attendance->status_cek_in === 'hadir') rgba(16, 185, 129, 0.08) @else rgba(245, 158, 11, 0.08) @endif; color: @if($attendance->status_cek_in === 'hadir') var(--success) @else var(--warning) @endif;">
                            <i class="fas @if($attendance->status_cek_in === 'hadir') fa-check @else fa-clock @endif"></i>
                        </div>
                        <div class="list-details">
                            <div class="list-title">{{ htmlspecialchars($attendance->user->username ?? 'Magang') }}</div>
                            <div class="list-subtitle">{{ htmlspecialchars($attendance->qr->nama_kegiatan ?? 'Kegiatan') }}</div>
                            <div style="font-size:0.78rem; color:var(--text-muted); margin-top:3px;">
                                @if ($attendance->absen_cek_in)
                                    <i class="fas fa-sign-in-alt"></i>
                                    {{ Carbon\Carbon::parse($attendance->absen_cek_in)->format('H:i') }} WITA
                                @endif
                                @if ($attendance->absen_cek_out)
                                    | <i class="fas fa-sign-out-alt"></i>
                                    {{ Carbon\Carbon::parse($attendance->absen_cek_out)->format('H:i') }} WITA
                                @endif
                            </div>
                        </div>
                        <div class="attendance-status">
                            <span class="list-badge @if($attendance->status_cek_in === 'hadir') badge-success @else badge-warning @endif">
                                {{ $attendance->status_cek_in }}
                            </span>
                            @if ($attendance->absen_cek_out)
                                <span class="time-badge">
                                    <i class="fas fa-clock"></i> {{ Carbon\Carbon::parse($attendance->total_waktu)->format('G\j i\m') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
