@extends('layouts.app')

@section('title', 'Laporan Absensi')
@section('header_title', 'Laporan Kehadiran Magang')

@section('content')
<div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-blue-600 to-cyan-500 px-6 py-5">
        <h3 class="font-extrabold text-white text-xl flex items-center gap-2">
            <i class="fas fa-filter text-cyan-200"></i> Filter Laporan
        </h3>
    </div>
    
    <div class="p-6 md:p-8">
        <form method="GET" action="{{ route('admin.laporan') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="search">Cari Nama Peserta</label>
                    <input type="text" id="search" name="search" value="{{ htmlspecialchars($search ?? '') }}" placeholder="Nama peserta..."
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800 text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="kegiatan">Pilih Kegiatan</label>
                    <select id="kegiatan" name="kegiatan" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800 text-sm">
                        <option value="all">Semua Kegiatan</option>
                        @foreach ($kegiatanList as $kegiatan)
                            <option value="{{ $kegiatan->id_qr }}" {{ $selectedKegiatan == $kegiatan->id_qr ? 'selected' : '' }}>
                                {{ htmlspecialchars($kegiatan->nama_kegiatan) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="status_cek_in">Status Cek In</label>
                    <select id="status_cek_in" name="status_cek_in" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800 text-sm">
                        <option value="all" {{ $statusCekIn === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="hadir" {{ $statusCekIn === 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="terlambat" {{ $statusCekIn === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="status_cek_out">Status Cek Out</label>
                    <select id="status_cek_out" name="status_cek_out" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800 text-sm">
                        <option value="all" {{ $statusCekOut === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="hadir" {{ $statusCekOut === 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="pulang_cepat" {{ $statusCekOut === 'pulang_cepat' ? 'selected' : '' }}>Pulang Cepat</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end border-t border-gray-100 pt-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="start_date">Dari Tanggal</label>
                    <input type="date" id="start_date" name="start_date" value="{{ htmlspecialchars($startDate ?? '') }}"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800 text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="end_date">Sampai Tanggal</label>
                    <input type="date" id="end_date" name="end_date" value="{{ htmlspecialchars($endDate ?? '') }}"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800 text-sm">
                </div>
                
                <div class="lg:col-span-2 flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-bold py-3 px-4 rounded-2xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i> Cari Data
                    </button>
                    <a href="{{ route('admin.laporan.export', request()->query()) }}" target="_blank" class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold py-3 px-4 rounded-2xl transition-all shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 hover:-translate-y-1 flex items-center justify-center gap-2 text-center">
                        <i class="fas fa-print"></i> Cetak Laporan
                    </a>
                    <a href="{{ route('admin.laporan') }}" class="sm:w-32 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 font-bold py-3 px-4 rounded-2xl transition-all shadow-sm hover:shadow flex items-center justify-center gap-2 text-center">
                        <i class="fas fa-redo text-slate-400"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-white/40 bg-white/30">
        <h3 class="font-extrabold text-slate-800 text-xl flex items-center gap-2 tracking-tight">
            <i class="fas fa-clipboard-list text-blue-500"></i> Logs Kehadiran
        </h3>
    </div>
    
    @if ($laporan->isEmpty())
        <div class="text-center py-16 flex flex-col justify-center items-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4">
                <i class="fas fa-folder-open text-3xl"></i>
            </div>
            <h4 class="font-medium text-gray-600">Data Tidak Ditemukan</h4>
            <p class="text-sm text-gray-400">Tidak ada data absensi magang yang cocok dengan kriteria filter.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-[13px]">
                        <th class="py-3 px-5 font-semibold text-gray-600 whitespace-nowrap">No</th>
                        <th class="py-3 px-5 font-semibold text-gray-600 whitespace-nowrap">Peserta Magang</th>
                        <th class="py-3 px-5 font-semibold text-gray-600 whitespace-nowrap">Hari / Tanggal</th>
                        <th class="py-3 px-5 font-semibold text-gray-600 whitespace-nowrap">Kegiatan</th>
                        <th class="py-3 px-5 font-semibold text-gray-600 whitespace-nowrap">Check-in</th>
                        <th class="py-3 px-5 font-semibold text-gray-600 whitespace-nowrap">Check-out</th>
                        <th class="py-3 px-5 font-semibold text-gray-600 whitespace-nowrap">Jam Kerja</th>
                        <th class="py-3 px-5 font-semibold text-gray-600 whitespace-nowrap text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($laporan as $index => $log)
                        @php
                            $rowNum = $laporan->firstItem() + $index;
                            $checkInTime = $log->absen_cek_in ? Carbon\Carbon::parse($log->absen_cek_in)->format('H:i') . ' WITA' : '-';
                            $checkOutTime = $log->absen_cek_out ? Carbon\Carbon::parse($log->absen_cek_out)->format('H:i') . ' WITA' : '-';
                            $attendanceDate = Carbon\Carbon::parse($log->created_at)->isoFormat('D MMMM Y');
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-5 text-sm text-gray-500 font-mono">{{ $rowNum }}</td>
                            <td class="py-3 px-5">
                                <div class="font-bold text-gray-800 text-sm">{{ htmlspecialchars($log->user->magang->nama_lengkap ?? $log->user->username ?? 'Magang') }}</div>
                                <div class="text-[11px] text-gray-500 flex items-center gap-1 mt-0.5">
                                    <i class="fas fa-university text-gray-400"></i> {{ htmlspecialchars($log->user->magang->instansi ?? '-') }}
                                </div>
                            </td>
                            <td class="py-3 px-5">
                                <div class="font-bold text-gray-700 text-sm">{{ $log->hari_absen }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $attendanceDate }}</div>
                            </td>
                            <td class="py-3 px-5 text-sm text-gray-600 max-w-[150px] truncate">
                                {{ htmlspecialchars($log->qr->nama_kegiatan ?? 'Kegiatan') }}
                            </td>
                            <td class="py-3 px-5">
                                <div class="font-bold text-emerald-600 text-sm">{{ $checkInTime }}</div>
                                <div class="text-[11px] text-gray-500 mt-0.5 capitalize">Cek in: {{ $log->status_cek_in }}</div>
                            </td>
                            <td class="py-3 px-5">
                                <div class="font-bold text-amber-600 text-sm">{{ $checkOutTime }}</div>
                                @if ($log->absen_cek_out)
                                    <div class="text-[11px] text-gray-500 mt-0.5 capitalize">Cek out: {{ $log->status_cek_out ?? 'hadir' }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-5">
                                <div class="font-bold text-gray-800 text-sm">{{ $log->total_waktu_formatted }}</div>
                                <div class="text-[11px] text-gray-500 mt-0.5">({{ $log->total_waktu }})</div>
                            </td>
                            <td class="py-3 px-5 text-center">
                                @if ($log->status_cek_in === 'hadir' && ($log->status_cek_out === 'hadir' || empty($log->status_cek_out)))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-full border bg-emerald-100 text-emerald-700 border-emerald-200 uppercase">
                                        <i class="fas fa-check-circle"></i> Hadir
                                    </span>
                                @elseif ($log->status_cek_in === 'terlambat')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-full border bg-amber-100 text-amber-700 border-amber-200 uppercase">
                                        <i class="fas fa-clock"></i> Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-full border bg-cyan-100 text-cyan-700 border-cyan-200 uppercase">
                                        <i class="fas fa-running"></i> Pulang Cepat
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Wrapper -->
        <div class="px-6 py-4 border-t border-gray-50 flex justify-center bg-gray-50/30">
            {{ $laporan->links() }}
        </div>
    @endif
</div>
@endsection
