@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('header_title', 'Dashboard Absensi Mentor')

@section('styles')
<style>
    /* Welcome Section */
    .welcome-section {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 25px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .welcome-text h2 {
        font-size: 1.5rem;
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .welcome-text p {
        opacity: 0.9;
        font-size: 1rem;
    }
    
    .welcome-icon {
        font-size: 3rem;
        opacity: 0.8;
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: var(--transition);
        border: 1px solid rgba(0,0,0,0.03);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
    }
    
    .stat-info {
        flex: 1;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: var(--gray);
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 25px;
    }
    
    @media (max-width: 1400px) {
        .content-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    
    @media (max-width: 1200px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* List Items */
    .list-item {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid var(--light-gray);
    }
    
    .list-item:last-child {
        border-bottom: none;
    }
    
    .list-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--light-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: var(--primary);
    }
    
    .list-details {
        flex: 1;
    }
    
    .list-title {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
    }
    
    .list-subtitle {
        font-size: 0.9rem;
        color: var(--gray);
    }
    
    .list-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: capitalize;
    }
    
    .badge-primary {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }
    
    .badge-success {
        background: rgba(40, 167, 69, 0.1);
        color: var(--hadir);
    }
    
    .badge-warning {
        background: rgba(255, 193, 7, 0.1);
        color: #856404;
    }
    
    .badge-danger {
        background: rgba(220, 53, 69, 0.1);
        color: var(--tidak-hadir);
    }
    
    .badge-info {
        background: rgba(23, 162, 184, 0.1);
        color: var(--early);
    }
    
    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }
    
    .action-btn {
        background: white;
        border: 2px solid var(--light-gray);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: var(--transition);
        cursor: pointer;
        text-decoration: none;
        color: var(--dark);
    }
    
    .action-btn:hover {
        border-color: var(--primary);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .action-icon {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 10px;
    }
    
    .action-label {
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    /* Attendance Status */
    .attendance-status {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
    }
    
    .time-badge {
        font-size: 0.75rem;
        background: rgba(108, 117, 125, 0.1);
        color: var(--gray);
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
            padding: 20px;
        }
        
        .welcome-text h2 {
            font-size: 1.3rem;
        }
        
        .welcome-icon {
            font-size: 2.5rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
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
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--hadir), #20c997);">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $stats['today_attendance'] }}</div>
            <div class="stat-label">Absensi Hari Ini</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--warning), #f15bb5);">
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
            <div class="action-icon">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div class="action-label">Buat QR Baru</div>
        </a>
        
        <a href="{{ route('admin.users') }}" class="action-btn">
            <div class="action-icon">
                <i class="fas fa-user-cog"></i>
            </div>
            <div class="action-label">Kelola User</div>
        </a>
        
        <a href="{{ route('admin.laporan') }}" class="action-btn">
            <div class="action-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
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
                <div style="text-align:center; color:#999; padding: 30px;">
                    <i class="fas fa-user-slash fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
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
                            <div style="font-size:0.8rem; color:#666; margin-top:3px;">
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
            <a href="{{ route('admin.users') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">
                <i class="fas fa-arrow-right"></i> Lihat Semua Pengguna
            </a>
        </div>
    </div>
    
    <!-- Recent QR Codes -->
    <div class="card">
        <h3 class="section-title"><i class="fas fa-qrcode"></i> QR Code Terbaru</h3>
        <div id="recent-qr">
            @if ($recentQrs->isEmpty())
                <div style="text-align:center; color:#999; padding: 30px;">
                    <i class="fas fa-qrcode fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
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
                                <div style="font-size:0.8rem; color:#666;">
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
            <a href="{{ route('admin.buat_qr') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">
                <i class="fas fa-arrow-right"></i> Kelola QR Code
            </a>
        </div>
    </div>

    <!-- Recent Attendance Activity -->
    <div class="card">
        <h3 class="section-title"><i class="fas fa-history"></i> Aktivitas Absensi Terbaru</h3>
        <div id="recent-attendance">
            @if ($recentAttendance->isEmpty())
                <div style="text-align:center; color:#999; padding: 30px;">
                    <i class="fas fa-clipboard-list fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
                    <p>Belum ada aktivitas absensi.</p>
                </div>
            @else
                @foreach ($recentAttendance as $attendance)
                    <div class="list-item">
                        <div class="list-icon" style="background: @if($attendance->status_cek_in === 'hadir') rgba(40, 167, 69, 0.1) @else rgba(255, 193, 7, 0.1) @endif; color: @if($attendance->status_cek_in === 'hadir') #28a745 @else #ffc107 @endif;">
                            <i class="fas @if($attendance->status_cek_in === 'hadir') fa-check @else fa-clock @endif"></i>
                        </div>
                        <div class="list-details">
                            <div class="list-title">{{ htmlspecialchars($attendance->user->username ?? 'Magang') }}</div>
                            <div class="list-subtitle">{{ htmlspecialchars($attendance->qr->nama_kegiatan ?? 'Kegiatan') }}</div>
                            <div style="font-size:0.8rem; color:#666; margin-top:3px;">
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
