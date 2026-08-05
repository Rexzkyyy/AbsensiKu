@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('header_title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="bg-gradient-to-br from-blue-600 via-primary-600 to-cyan-500 rounded-3xl p-6 md:p-10 text-white shadow-[0_8px_30px_rgb(0,0,0,0.12)] relative overflow-hidden mb-8 flex flex-col md:flex-row items-center justify-between gap-6">
    <!-- Decorative Background -->
    <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/20 rounded-full blur-3xl mix-blend-overlay"></div>
    <div class="absolute right-32 -bottom-20 w-48 h-48 bg-cyan-300/30 rounded-full blur-2xl mix-blend-overlay"></div>
    <div class="absolute left-10 top-10 w-24 h-24 bg-blue-300/20 rounded-full blur-xl mix-blend-overlay"></div>
    
    <div class="relative z-10 text-center md:text-left">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-3 tracking-tight">Halo, {{ htmlspecialchars($username) }}! 👋</h2>
        <p class="text-blue-100 md:text-lg font-medium opacity-90">Selamat datang di Panel Admin Sistem Presensi BPS Sultra</p>
    </div>
    <div class="relative z-10 hidden md:block opacity-30 transform hover:scale-110 transition-transform duration-500">
        <i class="fas fa-shield-alt text-8xl text-white drop-shadow-2xl"></i>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white/70 backdrop-blur-xl rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 flex items-center gap-5 hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 group">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 text-blue-600 text-2xl flex-shrink-0 shadow-inner group-hover:scale-110 transition-transform">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div class="text-3xl font-extrabold text-slate-800 leading-tight">{{ $stats['total_users'] }}</div>
            <div class="text-sm font-semibold text-slate-500 mt-1">Total User</div>
        </div>
    </div>
    <!-- Absen Hari Ini -->
    <div class="bg-white/70 backdrop-blur-xl rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 flex items-center gap-5 hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 group">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-gradient-to-br from-emerald-100 to-emerald-200 text-emerald-600 text-2xl flex-shrink-0 shadow-inner group-hover:scale-110 transition-transform">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <div class="text-3xl font-extrabold text-slate-800 leading-tight">{{ $stats['hadir_hari_ini'] }}</div>
            <div class="text-sm font-semibold text-slate-500 mt-1">Hadir Hari Ini</div>
        </div>
    </div>
    <!-- Terlambat -->
    <div class="bg-white/70 backdrop-blur-xl rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 flex items-center gap-5 hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 group">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-gradient-to-br from-amber-100 to-amber-200 text-amber-600 text-2xl flex-shrink-0 shadow-inner group-hover:scale-110 transition-transform">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <div class="text-3xl font-extrabold text-slate-800 leading-tight">{{ $stats['terlambat_hari_ini'] }}</div>
            <div class="text-sm font-semibold text-slate-500 mt-1">Terlambat Hari Ini</div>
        </div>
    </div>
    <!-- Aktif QR -->
    <div class="bg-white/70 backdrop-blur-xl rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 flex items-center gap-5 hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 group">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-gradient-to-br from-purple-100 to-purple-200 text-purple-600 text-2xl flex-shrink-0 shadow-inner group-hover:scale-110 transition-transform">
            <i class="fas fa-qrcode"></i>
        </div>
        <div>
            <div class="text-3xl font-extrabold text-slate-800 leading-tight">{{ $stats['qr_aktif'] }}</div>
            <div class="text-sm font-semibold text-slate-500 mt-1">QR Code Aktif</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 p-6 md:p-8 mb-8 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-6">
    <div>
        <h3 class="font-extrabold text-slate-800 text-xl mb-1 tracking-tight">Aksi Cepat</h3>
        <p class="text-sm text-slate-500 font-medium">Jalan pintas untuk tugas administratif harian</p>
    </div>
    <div class="flex flex-wrap justify-center gap-3 w-full sm:w-auto">
        <a href="{{ route('admin.buat_qr') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 hover:from-blue-100 hover:to-blue-200 font-bold px-5 py-3 rounded-2xl transition-all shadow-sm text-sm whitespace-nowrap">
            <i class="fas fa-plus-circle"></i> Buat QR Baru
        </a>
        <a href="{{ route('admin.users') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-gradient-to-r from-slate-50 to-slate-100 text-slate-700 hover:from-slate-100 hover:to-slate-200 font-bold px-5 py-3 rounded-2xl transition-all shadow-sm text-sm whitespace-nowrap border border-slate-200/50">
            <i class="fas fa-user-cog"></i> Kelola User
        </a>
        <a href="{{ route('admin.laporan') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700 hover:from-emerald-100 hover:to-emerald-200 font-bold px-5 py-3 rounded-2xl transition-all shadow-sm text-sm whitespace-nowrap">
            <i class="fas fa-chart-bar"></i> Laporan
        </a>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    
    <!-- Recent Users -->
    <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-white/40 flex justify-between items-center bg-white/30">
            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2"><i class="fas fa-users text-blue-500"></i> Pengguna Terbaru</h3>
        </div>
        <div class="p-2 divide-y divide-slate-100/50 flex-1">
            @if ($recentUsers->isEmpty())
                <div class="text-center py-10">
                    <i class="fas fa-user-slash text-slate-200 text-5xl mb-4 drop-shadow-sm"></i>
                    <p class="text-slate-500 font-medium text-sm">Belum ada pengguna terdaftar.</p>
                </div>
            @else
                @foreach ($recentUsers as $user)
                    <div class="p-3 mx-2 my-1 rounded-2xl hover:bg-white/50 transition-colors flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 text-slate-500 flex items-center justify-center shrink-0 shadow-inner">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-800 truncate">{{ htmlspecialchars($user->username) }}</div>
                            <div class="text-xs font-medium text-slate-500 truncate">{{ htmlspecialchars($user->email) }}</div>
                            <div class="text-[10px] font-semibold text-slate-400 mt-1 flex items-center gap-1"><i class="fas fa-calendar-alt"></i> {{ Carbon\Carbon::parse($user->created_at)->isoFormat('D MMM Y') }}</div>
                        </div>
                        <div>
                            @php
                                $roleColor = $user->role === 'mentor' ? 'bg-blue-100 text-blue-700' : ($user->role === 'admin' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700');
                            @endphp
                            <span class="px-3 py-1 text-[10px] font-extrabold rounded-lg {{ $roleColor }} uppercase tracking-wider">{{ $user->role }}</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="p-4 border-t border-white/40 text-center bg-slate-50/50">
            <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-sm font-bold transition-colors">
                Lihat Semua Pengguna <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>

    <!-- Recent QR Codes -->
    <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-white/40 flex justify-between items-center bg-white/30">
            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2"><i class="fas fa-qrcode text-purple-500"></i> QR Code Terbaru</h3>
        </div>
        <div class="p-2 divide-y divide-slate-100/50 flex-1">
            @if ($recentQrs->isEmpty())
                <div class="text-center py-10">
                    <i class="fas fa-qrcode text-slate-200 text-5xl mb-4 drop-shadow-sm"></i>
                    <p class="text-slate-500 font-medium text-sm">Belum ada QR Code yang dibuat.</p>
                </div>
            @else
                @foreach ($recentQrs as $qr)
                    @php $isExpired = Carbon\Carbon::parse($qr->expired_at)->isPast(); @endphp
                    <div class="p-3 mx-2 my-1 rounded-2xl hover:bg-white/50 transition-colors flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-100 to-purple-200 text-purple-600 flex items-center justify-center shrink-0 shadow-inner">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-800 truncate text-sm font-mono bg-white border border-slate-200 px-2 py-1 rounded-lg shadow-sm inline-block mb-1">{{ htmlspecialchars($qr->kode_qr) }}</div>
                            <div class="text-xs font-bold text-slate-600 truncate">{{ htmlspecialchars($qr->nama_kegiatan ?? 'Tanpa Nama') }}</div>
                            @if ($qr->cek_in && $qr->cek_out)
                                <div class="text-[10px] font-semibold text-slate-400 mt-1 flex items-center gap-1"><i class="fas fa-clock"></i> {{ Carbon\Carbon::parse($qr->cek_in)->format('H:i') }} - {{ Carbon\Carbon::parse($qr->cek_out)->format('H:i') }} WITA</div>
                            @endif
                        </div>
                        <div>
                            <span class="px-3 py-1 text-[10px] font-extrabold rounded-lg {{ $isExpired ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }} uppercase tracking-wider shadow-sm">
                                {{ $isExpired ? 'Expired' : 'Aktif' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="p-4 border-t border-white/40 text-center bg-slate-50/50">
            <a href="{{ route('admin.buat_qr') }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-sm font-bold transition-colors">
                Kelola QR Code <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 overflow-hidden flex flex-col xl:col-span-1 lg:col-span-2">
        <div class="px-6 py-5 border-b border-white/40 flex justify-between items-center bg-white/30">
            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2"><i class="fas fa-history text-cyan-500"></i> Aktivitas Absensi Terbaru</h3>
        </div>
        <div class="p-2 divide-y divide-slate-100/50 flex-1 max-h-[420px] overflow-y-auto custom-scrollbar">
            @if ($recentAttendance->isEmpty())
                <div class="text-center py-10">
                    <i class="fas fa-clipboard-list text-gray-200 text-4xl mb-3"></i>
                    <p class="text-gray-400 text-sm">Belum ada aktivitas absensi.</p>
                </div>
            @else
                @foreach ($recentAttendance as $attendance)
                    @php 
                        $isHadir = $attendance->status_cek_in === 'hadir';
                        $iconColor = $isHadir ? 'text-emerald-500 bg-gradient-to-br from-emerald-100 to-emerald-200' : 'text-amber-500 bg-gradient-to-br from-amber-100 to-amber-200';
                        $iconClass = $isHadir ? 'fa-check' : 'fa-clock';
                        $badgeColor = $isHadir ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700';
                    @endphp
                    <div class="p-3 mx-2 my-1 rounded-2xl hover:bg-white/50 transition-colors flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl {{ $iconColor }} flex items-center justify-center shrink-0 text-sm shadow-inner mt-1">
                            <i class="fas {{ $iconClass }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-800 text-sm truncate">{{ htmlspecialchars($attendance->user->username ?? 'Magang') }}</div>
                            <div class="text-xs font-semibold text-blue-600 truncate mb-2">{{ htmlspecialchars($attendance->qr->nama_kegiatan ?? 'Kegiatan') }}</div>
                            <div class="flex flex-wrap gap-2 text-[10px] text-slate-500 font-semibold">
                                @if ($attendance->absen_cek_in)
                                    <span class="bg-white border border-slate-200 px-2 py-1 rounded-lg shadow-sm flex items-center gap-1"><i class="fas fa-sign-in-alt text-emerald-500"></i> {{ Carbon\Carbon::parse($attendance->absen_cek_in)->format('H:i') }}</span>
                                @endif
                                @if ($attendance->absen_cek_out)
                                    <span class="bg-white border border-slate-200 px-2 py-1 rounded-lg shadow-sm flex items-center gap-1"><i class="fas fa-sign-out-alt text-amber-500"></i> {{ Carbon\Carbon::parse($attendance->absen_cek_out)->format('H:i') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-1 shrink-0">
                            <span class="px-3 py-1 text-[10px] font-extrabold rounded-lg {{ $badgeColor }} uppercase tracking-wider shadow-sm">{{ $attendance->status_cek_in }}</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="p-4 border-t border-white/40 text-center bg-slate-50/50">
            <a href="{{ route('admin.laporan') }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-sm font-bold transition-colors">
                Lihat Detail Laporan <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</div>

<style>
/* Custom Scrollbar for the activity list */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent; 
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1; 
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8; 
}
</style>
@endsection
