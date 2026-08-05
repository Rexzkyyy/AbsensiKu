@extends('layouts.app')

@section('title', 'Dashboard')

@section('header_title', 'Dashboard Absensi')

@section('styles')
<style>
    /* Welcome & Time Section */
    .welcome-time-section {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 30px;
        border-radius: 20px;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.25);
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 30px;
        align-items: center;
    }
    
    .welcome-content {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .welcome-text h2 {
        font-size: 1.8rem;
        margin-bottom: 8px;
        font-weight: 700;
    }
    
    .welcome-text p {
        opacity: 0.9;
        font-size: 1.1rem;
        font-weight: 400;
    }
    
    .quick-actions-horizontal {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }
    
    .action-btn-small {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        padding: 12px 20px;
        text-align: center;
        transition: var(--transition);
        cursor: pointer;
        text-decoration: none;
        color: white;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        backdrop-filter: blur(10px);
    }
    
    .action-btn-small:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }
    
    .time-display-compact {
        text-align: center;
        min-width: 200px;
    }
    
    .time-display-compact .date {
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 8px;
        opacity: 0.9;
    }
    
    .time-display-compact .time {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 5px;
        font-family: 'Courier New', monospace;
        letter-spacing: 2px;
    }
    
    .time-display-compact .location {
        font-size: 0.9rem;
        opacity: 0.8;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    
    /* Notification Card */
    .notification-card {
        background: linear-gradient(135deg, var(--warning), #f15bb5);
        color: white;
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 25px;
        box-shadow: 0 10px 25px rgba(247, 37, 133, 0.2);
        display: flex;
        align-items: center;
        gap: 15px;
        animation: pulse 2s infinite;
    }
    
    .notification-card.info {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        animation: pulse-info 2s infinite;
    }
    
    .notification-icon {
        font-size: 1.5rem;
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .notification-content {
        flex: 1;
    }
    
    .notification-title {
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 1.1rem;
    }
    
    .notification-message {
        opacity: 0.9;
        font-size: 0.95rem;
    }
    
    .notification-actions {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }
    
    .notification-btn {
        background: rgba(255, 255, 255, 0.3);
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }
    
    .notification-btn:hover {
        background: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
    }
    
    /* Attendance List */
    .attendance-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .attendance-item {
        display: flex;
        align-items: center;
        padding: 20px;
        background: var(--light);
        border-radius: 12px;
        transition: var(--transition);
        border-left: 4px solid var(--primary);
    }
    
    .attendance-item:hover {
        background: #f0f2f5;
        transform: translateX(5px);
    }
    
    .attendance-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-right: 15px;
        font-size: 1.1rem;
    }
    
    .attendance-details {
        flex: 1;
    }
    
    .attendance-user {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .attendance-activity {
        font-size: 0.9rem;
        color: var(--gray);
        margin-bottom: 5px;
    }
    
    .attendance-time {
        display: flex;
        gap: 15px;
        font-size: 0.85rem;
        color: var(--gray);
    }
    
    .time-section {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .attendance-status {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-checkin {
        background: rgba(40, 167, 69, 0.1);
        color: var(--hadir);
    }
    
    .status-checkout {
        background: rgba(255, 193, 7, 0.1);
        color: #856404;
    }
    
    .status-complete {
        background: rgba(23, 162, 184, 0.1);
        color: var(--early);
    }
    
    .total-time {
        font-size: 0.8rem;
        color: var(--gray);
        font-weight: 500;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 10px 25px rgba(247, 37, 133, 0.2); }
        50% { box-shadow: 0 10px 30px rgba(247, 37, 133, 0.4); }
        100% { box-shadow: 0 10px 25px rgba(247, 37, 133, 0.2); }
    }
    
    @keyframes pulse-info {
        0% { box-shadow: 0 10px 25px rgba(67, 97, 238, 0.2); }
        50% { box-shadow: 0 10px 30px rgba(67, 97, 238, 0.4); }
        100% { box-shadow: 0 10px 25px rgba(67, 97, 238, 0.2); }
    }
    
    @media (max-width: 768px) {
        .welcome-time-section {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 20px;
            padding: 25px;
        }
        
        .quick-actions-horizontal {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .time-display-compact .time {
            font-size: 1.8rem;
        }
        
        .notification-card {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }
        
        .notification-actions {
            justify-content: center;
        }
        
        .attendance-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .attendance-status {
            align-items: flex-start;
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<!-- Welcome & Time Section -->
<div class="welcome-time-section">
    <div class="welcome-content">
        <div class="welcome-text">
            <h2>Halo, {{ htmlspecialchars($username) }}! 👋</h2>
            <p>Selamat datang di sistem absensi digital</p>
        </div>
        <div class="quick-actions-horizontal">
            <a href="{{ route('magang.scan') }}" class="action-btn-small">
                <i class="fas fa-qrcode"></i>
                Scan QR Code
            </a>
            <a href="{{ route('magang.peserta') }}" class="action-btn-small">
                <i class="fas fa-users"></i>
                Data Peserta
            </a>
        </div>
    </div>
    <div class="time-display-compact">
        <div class="date" id="current-date">{{ Carbon\Carbon::now('Asia/Makassar')->isoFormat('dddd, D MMMM Y') }}</div>
        <div class="time" id="current-time">{{ Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') }}</div>
        <div class="location">
            <i class="fas fa-map-marker-alt"></i>
            <span>Kendari - WITA</span>
        </div>
    </div>
</div>

<!-- Notification Card untuk Data Magang yang belum lengkap -->
@if (!$sudahIsiDataMagang)
<div class="notification-card info">
    <div class="notification-icon">
        <i class="fas fa-info-circle"></i>
    </div>
    <div class="notification-content">
        <div class="notification-title">Data Magang Belum Lengkap</div>
        <div class="notification-message">Anda belum melengkapi biodata magang Anda. Silakan isi data diri terlebih dahulu untuk mempermudah administrasi.</div>
        <div class="notification-actions">
            <a href="{{ route('magang.peserta') }}" class="notification-btn">
                <i class="fas fa-edit"></i> Isi Data Magang
            </a>
        </div>
    </div>
</div>
@endif

<!-- Notification Card untuk status Absensi hari ini -->
@if (!$todayAttendance)
<div class="notification-card">
    <div class="notification-icon">
        <i class="fas fa-exclamation-triangle"></i>
    </div>
    <div class="notification-content">
        <div class="notification-title">Perhatian!</div>
        <div class="notification-message">Anda belum melakukan absen masuk hari ini. Silakan scan QR Code untuk mencatat kehadiran Anda.</div>
        <div class="notification-actions">
            <a href="{{ route('magang.scan') }}" class="notification-btn">
                <i class="fas fa-qrcode"></i> Scan QR Code
            </a>
        </div>
    </div>
</div>
@elseif ($todayAttendance && empty($todayAttendance->absen_cek_out))
<div class="notification-card" style="background: linear-gradient(135deg, var(--terlambat), #fd7e14);">
    <div class="notification-icon">
        <i class="fas fa-clock"></i>
    </div>
    <div class="notification-content">
        <div class="notification-title">Absen Masuk Tercatat</div>
        <div class="notification-message">Anda sudah melakukan absen masuk pada jam {{ Carbon\Carbon::parse($todayAttendance->absen_cek_in)->format('H:i') }} WITA. Jangan lupa untuk melakukan absen keluar saat jam pulang kerja!</div>
        <div class="notification-actions">
            <a href="{{ route('magang.scan') }}" class="notification-btn">
                <i class="fas fa-qrcode"></i> Scan QR Code Pulang
            </a>
        </div>
    </div>
</div>
@endif

<!-- Riwayat Absensi Saya -->
<div class="card">
    <h3 class="section-title"><i class="fas fa-history"></i> Riwayat Absensi Saya</h3>
    <div class="attendance-list">
        @if ($myRecentAttendance->isEmpty())
            <div style="text-align:center; color:#999; padding: 40px;">
                <i class="fas fa-clipboard-list fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
                <p>Belum ada riwayat absensi.</p>
                <p style="margin-top: 10px; font-size: 0.9rem;">Lakukan absen pertama Anda dengan scan QR Code</p>
            </div>
        @else
            @foreach ($myRecentAttendance as $attendance)
                @php
                    $checkInTime = $attendance->absen_cek_in ? Carbon\Carbon::parse($attendance->absen_cek_in)->format('H:i') : '-';
                    $checkOutTime = $attendance->absen_cek_out ? Carbon\Carbon::parse($attendance->absen_cek_out)->format('H:i') : '-';
                    $attendanceDate = Carbon\Carbon::parse($attendance->created_at)->isoFormat('D MMM Y');
                    $attendanceDay = $attendance->hari_absen;
                    
                    // Tentukan status badge
                    if ($attendance->absen_cek_in && $attendance->absen_cek_out) {
                        $status = 'complete';
                        $statusText = 'Selesai';
                        $borderColor = 'var(--hadir)';
                    } elseif ($attendance->absen_cek_in && !$attendance->absen_cek_out) {
                        $status = 'checkin';
                        $statusText = 'Masuk';
                        $borderColor = 'var(--terlambat)';
                    } else {
                        $status = 'checkout';
                        $statusText = 'Keluar';
                        $borderColor = 'var(--tidak-hadir)';
                    }
                @endphp
                <div class="attendance-item" style="border-left-color: {{ $borderColor }}">
                    @php
                        $initials = strtoupper(substr($username, 0, 2));
                    @endphp
                    <div class="attendance-avatar">
                        {{ $initials }}
                    </div>
                    <div class="attendance-details">
                        <div class="attendance-user">
                            {{ $username }}
                            <span style="font-size:0.8rem; color:#666; font-weight:normal;">
                                • {{ $attendanceDay }}, {{ $attendanceDate }}
                            </span>
                        </div>
                        @if ($attendance->qr)
                            <div class="attendance-activity">
                                <i class="fas fa-tasks"></i>
                                {{ $attendance->qr->nama_kegiatan }}
                            </div>
                        @endif
                        <div class="attendance-time">
                            <div class="time-section">
                                <i class="fas fa-sign-in-alt" style="color:var(--hadir);"></i>
                                <span>Masuk: {{ $checkInTime }} WITA</span>
                            </div>
                            <div class="time-section">
                                <i class="fas fa-sign-out-alt" style="color:var(--terlambat);"></i>
                                <span>Keluar: {{ $checkOutTime }} WITA</span>
                            </div>
                        </div>
                    </div>
                    <div class="attendance-status">
                        <span class="status-badge status-{{ $status }}">
                            {{ $statusText }}
                        </span>
                        @if ($attendance->absen_cek_out)
                            <span class="total-time">
                                <i class="fas fa-hourglass-half"></i> Total: {{ $attendance->total_waktu_formatted }}
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
