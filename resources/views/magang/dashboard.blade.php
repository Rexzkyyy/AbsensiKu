@extends('layouts.app')

@section('title', 'Dashboard')

@section('header_title', 'Dashboard')

@section('styles')
<style>
    /* ═══ Welcome & Time Section ═══ */
    .welcome-time-section {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 30px;
        border-radius: 20px;
        margin-bottom: 28px;
        box-shadow: 0 8px 30px rgba(67, 97, 238, 0.2);
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 30px;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .welcome-time-section::before {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
        top: -60px;
        right: -40px;
    }

    .welcome-content {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .welcome-text h2 {
        font-size: 1.6rem;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .welcome-text p {
        opacity: 0.9;
        font-size: 1rem;
        font-weight: 400;
    }

    .quick-actions-horizontal {
        display: flex;
        gap: 12px;
        margin-top: 8px;
    }

    .action-btn-small {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 12px;
        padding: 10px 18px;
        text-align: center;
        transition: var(--transition);
        cursor: pointer;
        text-decoration: none;
        color: white;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 0.88rem;
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
        font-size: 0.95rem;
        font-weight: 500;
        margin-bottom: 8px;
        opacity: 0.9;
    }

    .time-display-compact .time {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 5px;
        letter-spacing: 2px;
    }

    .time-display-compact .location {
        font-size: 0.85rem;
        opacity: 0.8;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    /* ═══ Notification Card ═══ */
    .notification-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        color: var(--text-dark);
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 16px;
        border-left: 4px solid var(--danger);
        animation: fadeSlideIn 0.5s ease-out;
    }

    .notification-card.info {
        border-left-color: var(--primary);
    }

    .notification-card.warning-style {
        border-left-color: var(--warning);
    }

    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .notification-icon {
        font-size: 1.3rem;
        width: 48px;
        height: 48px;
        background: rgba(67, 97, 238, 0.08);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        flex-shrink: 0;
    }

    .notification-card:not(.info):not(.warning-style) .notification-icon {
        background: rgba(239, 68, 68, 0.08);
        color: var(--danger);
    }

    .notification-card.warning-style .notification-icon {
        background: rgba(245, 158, 11, 0.08);
        color: var(--warning);
    }

    .notification-content { flex: 1; }

    .notification-title {
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 1rem;
        color: var(--text-dark);
    }

    .notification-message {
        color: var(--text-muted);
        font-size: 0.88rem;
    }

    .notification-actions {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }

    .notification-btn {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: none;
        border-radius: 10px;
        padding: 8px 16px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.82rem;
        box-shadow: 0 3px 10px rgba(67, 97, 238, 0.2);
    }

    .notification-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(67, 97, 238, 0.3);
    }

    /* ═══ Attendance List ═══ */
    .attendance-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .attendance-item {
        display: flex;
        align-items: center;
        padding: 18px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 14px;
        transition: var(--transition);
        border-left: 4px solid var(--primary);
        border: 1px solid var(--border-light);
    }

    .attendance-item:hover {
        background: rgba(67, 97, 238, 0.03);
        transform: translateX(3px);
    }

    .attendance-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        margin-right: 14px;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .attendance-details { flex: 1; }

    .attendance-user {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.92rem;
    }

    .attendance-activity {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .attendance-time {
        display: flex;
        gap: 14px;
        font-size: 0.8rem;
        color: var(--text-muted);
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
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .status-checkin { background: rgba(16, 185, 129, 0.08); color: var(--success); }
    .status-checkout { background: rgba(245, 158, 11, 0.08); color: #b45309; }
    .status-complete { background: rgba(6, 182, 212, 0.08); color: var(--info); }

    .total-time {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
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

        .time-display-compact .time { font-size: 1.8rem; }

        .notification-card {
            flex-direction: column;
            text-align: center;
            gap: 12px;
        }

        .notification-actions { justify-content: center; }

        .attendance-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
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
                <i class="fas fa-qrcode"></i> Scan QR Code
            </a>
            <a href="{{ route('magang.peserta') }}" class="action-btn-small">
                <i class="fas fa-users"></i> Data Peserta
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

<!-- Notification: Data Magang belum lengkap -->
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

<!-- Notification: Absensi hari ini -->
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
<div class="notification-card warning-style">
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
            <div style="text-align:center; color:var(--text-muted); padding: 40px;">
                <i class="fas fa-clipboard-list fa-3x" style="margin-bottom: 15px; opacity: 0.3;"></i>
                <p>Belum ada riwayat absensi.</p>
                <p style="margin-top: 10px; font-size: 0.88rem;">Lakukan absen pertama Anda dengan scan QR Code</p>
            </div>
        @else
            @foreach ($myRecentAttendance as $attendance)
                @php
                    $checkInTime = $attendance->absen_cek_in ? Carbon\Carbon::parse($attendance->absen_cek_in)->format('H:i') : '-';
                    $checkOutTime = $attendance->absen_cek_out ? Carbon\Carbon::parse($attendance->absen_cek_out)->format('H:i') : '-';
                    $attendanceDate = Carbon\Carbon::parse($attendance->created_at)->isoFormat('D MMM Y');
                    $attendanceDay = $attendance->hari_absen;

                    if ($attendance->absen_cek_in && $attendance->absen_cek_out) {
                        $status = 'complete'; $statusText = 'Selesai'; $borderColor = 'var(--success)';
                    } elseif ($attendance->absen_cek_in && !$attendance->absen_cek_out) {
                        $status = 'checkin'; $statusText = 'Masuk'; $borderColor = 'var(--warning)';
                    } else {
                        $status = 'checkout'; $statusText = 'Keluar'; $borderColor = 'var(--danger)';
                    }
                @endphp
                <div class="attendance-item" style="border-left: 4px solid {{ $borderColor }}">
                    @php $initials = strtoupper(substr($username, 0, 2)); @endphp
                    <div class="attendance-avatar">{{ $initials }}</div>
                    <div class="attendance-details">
                        <div class="attendance-user">
                            {{ $username }}
                            <span style="font-size:0.78rem; color:var(--text-muted); font-weight:normal;">
                                • {{ $attendanceDay }}, {{ $attendanceDate }}
                            </span>
                        </div>
                        @if ($attendance->qr)
                            <div class="attendance-activity">
                                <i class="fas fa-tasks"></i> {{ $attendance->qr->nama_kegiatan }}
                            </div>
                        @endif
                        <div class="attendance-time">
                            <div class="time-section">
                                <i class="fas fa-sign-in-alt" style="color:var(--success);"></i>
                                <span>Masuk: {{ $checkInTime }} WITA</span>
                            </div>
                            <div class="time-section">
                                <i class="fas fa-sign-out-alt" style="color:var(--warning);"></i>
                                <span>Keluar: {{ $checkOutTime }} WITA</span>
                            </div>
                        </div>
                    </div>
                    <div class="attendance-status">
                        <span class="status-badge status-{{ $status }}">{{ $statusText }}</span>
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
