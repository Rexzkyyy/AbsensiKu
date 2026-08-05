@extends('layouts.app')
@section('title', 'Dashboard Magang')
@section('header_title', 'Dashboard')

@section('content')
<!-- Welcome & Time Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-gradient-to-br from-blue-600 via-primary-600 to-cyan-500 rounded-3xl p-6 md:p-10 text-white shadow-[0_8px_30px_rgb(0,0,0,0.12)] relative overflow-hidden flex flex-col justify-center">
        <!-- Decorative Background -->
        <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/20 rounded-full blur-3xl mix-blend-overlay"></div>
        <div class="absolute right-32 -bottom-20 w-48 h-48 bg-cyan-300/30 rounded-full blur-2xl mix-blend-overlay"></div>
        <div class="absolute left-10 top-10 w-24 h-24 bg-blue-300/20 rounded-full blur-xl mix-blend-overlay"></div>
        
        <div class="relative z-10">
            <h2 class="text-3xl md:text-4xl font-extrabold mb-3 tracking-tight">Halo, {{ htmlspecialchars($username) }}! 👋</h2>
            <p class="text-blue-100 mb-6 md:text-lg">Selamat datang di sistem absensi digital</p>
            
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('magang.scan') }}" class="inline-flex items-center gap-2 bg-white text-primary-600 font-semibold px-5 py-2.5 rounded-xl hover:bg-gray-50 transition shadow-sm">
                    <i class="fas fa-qrcode"></i> Scan QR Code
                </a>
                <a href="{{ route('magang.peserta') }}" class="inline-flex items-center gap-2 bg-primary-700/50 hover:bg-primary-700 text-white font-semibold px-5 py-2.5 rounded-xl border border-primary-500/30 transition backdrop-blur-sm shadow-sm">
                    <i class="fas fa-users"></i> Data Peserta
                </a>
            </div>
        </div>
    </div>

    <!-- Digital Clock Card -->
    <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 p-6 flex flex-col items-center justify-center text-center">
        <div class="text-sm font-bold text-blue-600 mb-2 uppercase tracking-widest" id="current-date">{{ Carbon\Carbon::now('Asia/Makassar')->isoFormat('dddd, D MMMM Y') }}</div>
        <div class="text-5xl md:text-6xl font-black text-slate-800 tracking-tighter mb-4 drop-shadow-sm" id="current-time">{{ Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') }}</div>
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-gradient-to-r from-slate-100 to-slate-200 rounded-full text-xs font-bold text-slate-600 border border-white/50 shadow-sm">
            <i class="fas fa-map-marker-alt text-blue-500"></i>
            <span>Kendari - WITA</span>
        </div>
    </div>
</div>

<!-- Notification: Data Magang belum lengkap -->
@if (!$sudahIsiDataMagang)
<div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mb-8 shadow-sm flex flex-col md:flex-row gap-5 items-start md:items-center">
    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 text-xl">
        <i class="fas fa-info-circle"></i>
    </div>
    <div class="flex-1">
        <h4 class="font-bold text-blue-900 mb-1">Data Magang Belum Lengkap</h4>
        <p class="text-blue-700 text-sm">Anda belum melengkapi biodata magang Anda. Silakan isi data diri terlebih dahulu untuk mempermudah administrasi.</p>
    </div>
    <div class="flex-shrink-0 mt-3 md:mt-0 w-full md:w-auto">
        <a href="{{ route('magang.peserta') }}" class="inline-flex justify-center items-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl transition shadow-sm text-sm">
            <i class="fas fa-edit"></i> Isi Data Magang
        </a>
    </div>
</div>
@endif

<!-- Notification: Absensi hari ini -->
@if (!$todayAttendance)
<div class="bg-red-50 border border-red-100 rounded-2xl p-5 mb-8 shadow-sm flex flex-col md:flex-row gap-5 items-start md:items-center">
    <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 text-xl">
        <i class="fas fa-exclamation-triangle"></i>
    </div>
    <div class="flex-1">
        <h4 class="font-bold text-red-900 mb-1">Perhatian!</h4>
        <p class="text-red-700 text-sm">Anda belum melakukan absen masuk hari ini. Silakan scan QR Code untuk mencatat kehadiran Anda.</p>
    </div>
    <div class="flex-shrink-0 mt-3 md:mt-0 w-full md:w-auto">
        <a href="{{ route('magang.scan') }}" class="inline-flex justify-center items-center gap-2 w-full bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-xl transition shadow-sm text-sm">
            <i class="fas fa-qrcode"></i> Scan QR Code
        </a>
    </div>
</div>
@elseif ($todayAttendance && empty($todayAttendance->absen_cek_out))
<div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 mb-8 shadow-sm flex flex-col md:flex-row gap-5 items-start md:items-center">
    <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 text-xl">
        <i class="fas fa-clock"></i>
    </div>
    <div class="flex-1">
        <h4 class="font-bold text-amber-900 mb-1">Absen Masuk Tercatat</h4>
        <p class="text-amber-700 text-sm">Anda sudah melakukan absen masuk pada jam <strong>{{ Carbon\Carbon::parse($todayAttendance->absen_cek_in)->format('H:i') }} WITA</strong>. Jangan lupa untuk melakukan absen keluar saat jam pulang kerja!</p>
    </div>
    <div class="flex-shrink-0 mt-3 md:mt-0 w-full md:w-auto">
        <a href="{{ route('magang.scan') }}" class="inline-flex justify-center items-center gap-2 w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold px-5 py-2.5 rounded-xl transition shadow-sm text-sm">
            <i class="fas fa-qrcode"></i> Scan QR Pulang
        </a>
    </div>
</div>
@endif

<!-- Riwayat Absensi Saya -->
<div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-white/40 bg-white/30">
        <h3 class="font-extrabold text-slate-800 flex items-center gap-2 text-xl tracking-tight">
            <i class="fas fa-history text-cyan-500"></i> Riwayat Absensi Saya
        </h3>
    </div>
    <div class="p-4 md:p-6 divide-y divide-slate-100/50">
        @if ($myRecentAttendance->isEmpty())
            <div class="text-center py-10 px-4">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-inner">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                </div>
                <h4 class="font-medium text-gray-600 mb-1">Belum ada riwayat absensi.</h4>
                <p class="text-sm text-gray-400">Lakukan absen pertama Anda dengan scan QR Code</p>
            </div>
        @else
            @foreach ($myRecentAttendance as $attendance)
                @php
                    $checkInTime = $attendance->absen_cek_in ? Carbon\Carbon::parse($attendance->absen_cek_in)->format('H:i') : '-';
                    $checkOutTime = $attendance->absen_cek_out ? Carbon\Carbon::parse($attendance->absen_cek_out)->format('H:i') : '-';
                    $attendanceDate = Carbon\Carbon::parse($attendance->created_at)->isoFormat('D MMM Y');
                    
                    if ($attendance->absen_cek_in && $attendance->absen_cek_out) {
                        $statusBadge = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                        $statusText = 'Selesai'; 
                        $borderLeft = 'border-l-4 border-l-emerald-500';
                    } elseif ($attendance->absen_cek_in && !$attendance->absen_cek_out) {
                        $statusBadge = 'bg-amber-100 text-amber-700 border-amber-200';
                        $statusText = 'Masuk'; 
                        $borderLeft = 'border-l-4 border-l-amber-500';
                    } else {
                        $statusBadge = 'bg-red-100 text-red-700 border-red-200';
                        $statusText = 'Keluar'; 
                        $borderLeft = 'border-l-4 border-l-red-500';
                    }
                    $initials = strtoupper(substr($username, 0, 2));
                @endphp
                
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 py-4 px-2 {{ $borderLeft }} pl-4 -ml-4 bg-gray-50/30 rounded-r-xl mb-2 hover:bg-gray-50 transition">
                    
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 text-gray-600 flex flex-shrink-0 items-center justify-center font-bold shadow-inner text-sm">
                            {{ $initials }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">{{ $username }}</div>
                            <div class="text-xs font-medium text-gray-500 mt-0.5">
                                <i class="far fa-calendar-alt mr-1"></i> {{ $attendance->hari_absen }}, {{ $attendanceDate }}
                            </div>
                            @if ($attendance->qr)
                                <div class="text-xs text-primary-600 bg-primary-50 px-2 py-0.5 rounded flex w-fit items-center mt-1.5 border border-primary-100">
                                    <i class="fas fa-tasks mr-1.5"></i> {{ $attendance->qr->nama_kegiatan }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6 bg-white p-3 rounded-lg border border-gray-100 shadow-sm w-full lg:w-auto">
                        <div class="flex items-center gap-2 text-sm">
                            <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Masuk</div>
                                <div class="font-semibold text-gray-800">{{ $checkInTime }} <span class="text-[10px] text-gray-500 font-normal">WITA</span></div>
                            </div>
                        </div>
                        <div class="hidden sm:block w-px h-8 bg-gray-200"></div>
                        <div class="flex items-center gap-2 text-sm">
                            <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 shrink-0">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Keluar</div>
                                <div class="font-semibold text-gray-800">{{ $checkOutTime }} <span class="text-[10px] text-gray-500 font-normal">WITA</span></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between lg:flex-col lg:items-end lg:justify-center gap-2 w-full lg:w-auto mt-2 lg:mt-0">
                        <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $statusBadge }}">
                            {{ $statusText }}
                        </span>
                        
                        @if ($attendance->absen_cek_out)
                            <div class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-md flex items-center">
                                <i class="fas fa-hourglass-half mr-1.5 text-gray-400"></i> Total: {{ $attendance->total_waktu_formatted }}
                            </div>
                        @endif
                    </div>
                    
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
