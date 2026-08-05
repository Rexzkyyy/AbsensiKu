@extends('layouts.app')

@section('title', 'Dashboard Magang')
@section('header_title', 'Dashboard')

@section('content')

    <style>
        /* Efek tilt lebih subtle */
        .tilt-card {
            transform-style: preserve-3d;
            transform: perspective(1000px);
        }

        .tilt-content {
            transform: translateZ(15px);
        }

        /* Animasi fade up untuk item */
        .stagger-item {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp 0.5s ease forwards;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hover efek yang tidak berlebihan */
        .glow-hover {
            transition: all 0.25s ease;
        }

        .glow-hover:hover {
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.25);
            transform: translateY(-2px);
        }

        /* Background gradient untuk kartu atas */
        .animated-bg-card {
            background: linear-gradient(135deg, #2563eb, #6366f1, #8b5cf6);
            background-size: 200% 200%;
            animation: gradient-shift 10s ease infinite;
        }

        @keyframes gradient-shift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Perbaikan responsif untuk jam */
        #current-time {
            font-size: 2.5rem;
        }

        @media (min-width: 640px) {
            #current-time {
                font-size: 3.5rem;
            }
        }

        @media (min-width: 1024px) {
            #current-time {
                font-size: 4.5rem;
            }
        }

        /* Card riwayat absensi */
        .attendance-item {
            transition: all 0.2s ease;
        }

        .attendance-item:hover {
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }
    </style>

    <!-- Welcome & Time Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
        <!-- Kartu Welcome -->
        <div class="lg:col-span-2 animated-bg-card rounded-2xl p-6 md:p-8 text-white shadow-lg relative overflow-hidden flex flex-col justify-center tilt-card"
            data-tilt data-tilt-max="3" data-tilt-speed="300" data-tilt-glare data-tilt-max-glare="0.15">
            <!-- Decorative elements (lebih subtle) -->
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute right-20 -bottom-16 w-36 h-36 bg-cyan-300/20 rounded-full blur-2xl"></div>

            <div class="relative z-10 tilt-content">
                <h2 class="text-2xl md:text-3xl font-bold mb-2">Halo, {{ htmlspecialchars($username) }}! 👋</h2>
                <p class="text-blue-100 text-sm md:text-base mb-5">Selamat datang di sistem absensi digital</p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('magang.scan') }}"
                        class="inline-flex items-center gap-2 bg-white text-primary-600 font-semibold px-5 py-2.5 rounded-xl hover:bg-gray-50 transition shadow-md text-sm">
                        <i class="fas fa-qrcode"></i> Scan QR
                    </a>
                    <a href="{{ route('magang.peserta') }}"
                        class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white font-semibold px-5 py-2.5 rounded-xl border border-white/20 backdrop-blur-sm transition text-sm">
                        <i class="fas fa-users"></i> Data Peserta
                    </a>
                </div>
            </div>
        </div>

        <!-- Kartu Jam Digital -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-md border border-white/60 p-6 flex flex-col items-center justify-center text-center tilt-card"
            data-tilt data-tilt-max="5" data-tilt-speed="300" data-tilt-scale="1.02">
            <div class="tilt-content flex flex-col items-center">
                <div class="text-xs font-bold text-primary-500 uppercase tracking-wider mb-1" id="current-date">
                    {{ Carbon\Carbon::now('Asia/Makassar')->isoFormat('dddd, D MMMM Y') }}</div>
                <div class="font-black text-slate-800 tracking-tight drop-shadow-sm" id="current-time">
                    {{ Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') }}</div>
                <div
                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 rounded-full text-[10px] font-semibold text-slate-600 mt-3 border border-white/50">
                    <i class="fas fa-map-marker-alt text-blue-500 text-xs"></i>
                    <span>Kendari - WITA</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifikasi: Data Magang belum lengkap -->
    @if (!$sudahIsiDataMagang)
        <div
            class="bg-blue-50/80 border border-blue-100 rounded-2xl p-4 mb-6 shadow-sm flex flex-col sm:flex-row gap-4 items-start sm:items-center">
            <div
                class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 text-base">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-blue-900 text-sm">Data Magang Belum Lengkap</h4>
                <p class="text-blue-700 text-xs">Silakan lengkapi biodata magang Anda untuk administrasi.</p>
            </div>
            <div class="flex-shrink-0 mt-2 sm:mt-0 w-full sm:w-auto">
                <a href="{{ route('magang.peserta') }}"
                    class="inline-flex justify-center items-center gap-1.5 w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-xl transition text-xs">
                    <i class="fas fa-edit"></i> Isi Data
                </a>
            </div>
        </div>
    @endif

    <!-- Notifikasi: Belum absen masuk -->
    @if (!$todayAttendance)
        <div
            class="bg-red-50/80 border border-red-100 rounded-2xl p-4 mb-6 shadow-sm flex flex-col sm:flex-row gap-4 items-start sm:items-center">
            <div
                class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 text-base">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-red-900 text-sm">Belum Absen Masuk</h4>
                <p class="text-red-700 text-xs">Anda belum melakukan absen masuk hari ini. Scan QR sekarang!</p>
            </div>
            <div class="flex-shrink-0 mt-2 sm:mt-0 w-full sm:w-auto">
                <a href="{{ route('magang.scan') }}"
                    class="inline-flex justify-center items-center gap-1.5 w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-xl transition text-xs">
                    <i class="fas fa-qrcode"></i> Scan QR
                </a>
            </div>
        </div>
    @elseif ($todayAttendance && empty($todayAttendance->absen_cek_out))
        <div
            class="bg-amber-50/80 border border-amber-100 rounded-2xl p-4 mb-6 shadow-sm flex flex-col sm:flex-row gap-4 items-start sm:items-center">
            <div
                class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 text-base">
                <i class="fas fa-clock"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-amber-900 text-sm">Sudah Absen Masuk</h4>
                <p class="text-amber-700 text-xs">Masuk pukul
                    <strong>{{ Carbon\Carbon::parse($todayAttendance->absen_cek_in)->format('H:i') }} WITA</strong>. Jangan lupa
                    absen keluar.</p>
            </div>
            <div class="flex-shrink-0 mt-2 sm:mt-0 w-full sm:w-auto">
                <a href="{{ route('magang.scan') }}"
                    class="inline-flex justify-center items-center gap-1.5 w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white font-semibold px-4 py-2 rounded-xl transition text-xs">
                    <i class="fas fa-qrcode"></i> Scan Pulang
                </a>
            </div>
        </div>
    @endif

    <!-- Riwayat Absensi -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-md border border-white/60 overflow-hidden mb-24">
        <div class="px-5 py-4 border-b border-white/40 bg-white/30">
            <h3 class="font-bold text-slate-800 flex items-center gap-2 text-lg">
                <i class="fas fa-history text-cyan-500"></i> Riwayat Absensi
            </h3>
        </div>
        <div class="p-4 divide-y divide-slate-100/50">
            @if ($myRecentAttendance->isEmpty())
                <div class="text-center py-10 px-4">
                    <div
                        class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3 text-slate-300 shadow-inner">
                        <i class="fas fa-clipboard-list text-xl"></i>
                    </div>
                    <h4 class="font-medium text-gray-600 text-sm">Belum ada riwayat absensi.</h4>
                    <p class="text-xs text-gray-400">Lakukan absen pertama dengan scan QR Code</p>
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

                    <div class="stagger-item attendance-item flex flex-col md:flex-row md:items-center justify-between gap-3 py-3 px-3 {{ $borderLeft }} pl-4 -ml-4 bg-gray-50/50 rounded-r-xl mb-2 hover:bg-white transition"
                        style="animation-delay: {{ $loop->index * 100 }}ms;">

                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 text-gray-600 flex flex-shrink-0 items-center justify-center font-bold shadow-inner text-xs">
                                {{ $initials }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800 text-sm">{{ $username }}</div>
                                <div class="text-[10px] text-gray-500">
                                    <i class="far fa-calendar-alt mr-1"></i> {{ $attendance->hari_absen }}, {{ $attendanceDate }}
                                </div>
                                @if ($attendance->qr)
                                    <div
                                        class="text-[10px] text-primary-600 bg-primary-50/80 px-2 py-0.5 rounded flex items-center mt-1 border border-primary-100 w-fit">
                                        <i class="fas fa-tasks mr-1"></i> {{ $attendance->qr->nama_kegiatan }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-4 bg-white/80 p-2 rounded-lg border border-gray-100 shadow-sm w-full md:w-auto">
                            <div class="flex items-center gap-1.5 text-xs">
                                <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                                    <i class="fas fa-sign-in-alt text-[10px]"></i>
                                </div>
                                <div>
                                    <div class="text-[8px] text-gray-400 font-bold uppercase">Masuk</div>
                                    <div class="font-semibold text-gray-700">{{ $checkInTime }}</div>
                                </div>
                            </div>
                            <div class="w-px h-6 bg-gray-200"></div>
                            <div class="flex items-center gap-1.5 text-xs">
                                <div class="w-6 h-6 rounded-full bg-amber-50 flex items-center justify-center text-amber-500">
                                    <i class="fas fa-sign-out-alt text-[10px]"></i>
                                </div>
                                <div>
                                    <div class="text-[8px] text-gray-400 font-bold uppercase">Keluar</div>
                                    <div class="font-semibold text-gray-700">{{ $checkOutTime }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:flex-col md:items-end gap-1.5 w-full md:w-auto">
                            <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border {{ $statusBadge }}">
                                {{ $statusText }}
                            </span>
                            @if ($attendance->absen_cek_out)
                                <div class="text-[10px] font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded flex items-center">
                                    <i class="fas fa-hourglass-half mr-1 text-gray-400"></i> {{ $attendance->total_waktu_formatted }}
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Load Vanilla-tilt.js for 3D effects -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>
    <script>
        // Inisialisasi Vanilla Tilt dengan nilai lebih subtle
        VanillaTilt.init(document.querySelectorAll(".tilt-card"), {
            max: 5,
            speed: 300,
            glare: true,
            "max-glare": 0.15,
        });
    </script>
@endsection