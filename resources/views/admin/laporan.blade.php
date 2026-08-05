@extends('layouts.app')

@section('title', 'Laporan Absensi')

@section('header_title', 'Laporan Kehadiran Magang')

@section('styles')
<style>
    /* Filter Laporan Styles */
    .report-filter-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        margin-bottom: 25px;
        border: 1px solid rgba(0,0,0,0.03);
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .btn-search {
        padding: 12px 20px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-print-report {
        padding: 12px 20px;
        background: linear-gradient(135deg, #17a2b8, #3dcad8);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .btn-print-report:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(23, 162, 184, 0.3);
    }

    /* Table styles */
    .table-container {
        width: 100%;
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        text-align: left;
    }
    
    th, td {
        padding: 15px 20px;
        border-bottom: 1px solid var(--light-gray);
    }
    
    th {
        background-color: var(--light);
        font-weight: 600;
        color: var(--dark);
        font-size: 0.95rem;
    }
    
    tr:hover {
        background-color: rgba(67, 97, 238, 0.02);
    }
    
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .status-hadir {
        background: rgba(40, 167, 69, 0.15);
        color: var(--hadir);
    }
    
    .status-terlambat {
        background: rgba(255, 193, 7, 0.15);
        color: #856404;
    }
    
    .status-pulang-cepat {
        background: rgba(23, 162, 184, 0.15);
        color: var(--early);
    }

    /* Pagination container */
    .pagination-wrapper {
        margin-top: 25px;
        display: flex;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="report-filter-card">
    <h3 class="section-title"><i class="fas fa-filter"></i> Filter Laporan</h3>
    
    <form method="GET" action="{{ route('admin.laporan') }}">
        <div class="filter-grid">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="search">Cari Nama Peserta</label>
                <input type="text" class="form-input" id="search" name="search" value="{{ htmlspecialchars($search ?? '') }}" placeholder="Nama peserta...">
            </div>
            
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="kegiatan">Pilih Kegiatan</label>
                <select class="form-input" id="kegiatan" name="kegiatan">
                    <option value="all">Semua Kegiatan</option>
                    @foreach ($kegiatanList as $kegiatan)
                        <option value="{{ $kegiatan->id_qr }}" {{ $selectedKegiatan == $kegiatan->id_qr ? 'selected' : '' }}>
                            {{ htmlspecialchars($kegiatan->nama_kegiatan) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="status_cek_in">Status Cek In</label>
                <select class="form-input" id="status_cek_in" name="status_cek_in">
                    <option value="all" {{ $statusCekIn === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="hadir" {{ $statusCekIn === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="terlambat" {{ $statusCekIn === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="status_cek_out">Status Cek Out</label>
                <select class="form-input" id="status_cek_out" name="status_cek_out">
                    <option value="all" {{ $statusCekOut === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="hadir" {{ $statusCekOut === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="pulang_cepat" {{ $statusCekOut === 'pulang_cepat' ? 'selected' : '' }}>Pulang Cepat</option>
                </select>
            </div>
        </div>

        <div class="filter-grid">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="start_date">Dari Tanggal</label>
                <input type="date" class="form-input" id="start_date" name="start_date" value="{{ htmlspecialchars($startDate ?? '') }}">
            </div>
            
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="end_date">Sampai Tanggal</label>
                <input type="date" class="form-input" id="end_date" name="end_date" value="{{ htmlspecialchars($endDate ?? '') }}">
            </div>
            
            <div class="form-group" style="margin-bottom:0; display:flex; gap:10px; align-items:flex-end; grid-column: span 2;">
                <button type="submit" class="btn-search" style="flex:1; justify-content:center; padding: 14px;">
                    <i class="fas fa-search"></i> Cari Data
                </button>
                <a href="{{ route('admin.laporan.export', request()->query()) }}" target="_blank" class="btn-print-report" style="flex:1; justify-content:center; padding: 14px;">
                    <i class="fas fa-print"></i> Cetak Laporan
                </a>
                <a href="{{ route('admin.laporan') }}" class="btn btn-reset" style="padding: 14px 20px;">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <h3 class="section-title"><i class="fas fa-clipboard-list"></i> Logs Kehadiran</h3>
    
    <div class="table-container">
        @if ($laporan->isEmpty())
            <div style="text-align:center; color:#999; padding: 40px;">
                <i class="fas fa-folder-open fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
                <p>Tidak ada data absensi magang yang cocok dengan kriteria filter.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peserta Magang</th>
                        <th>Hari / Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Jam Kerja</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $index => $log)
                        @php
                            $rowNum = $laporan->firstItem() + $index;
                            $checkInTime = $log->absen_cek_in ? Carbon\Carbon::parse($log->absen_cek_in)->format('H:i') . ' WITA' : '-';
                            $checkOutTime = $log->absen_cek_out ? Carbon\Carbon::parse($log->absen_cek_out)->format('H:i') . ' WITA' : '-';
                            $attendanceDate = Carbon\Carbon::parse($log->created_at)->isoFormat('D MMMM Y');
                        @endphp
                        <tr>
                            <td>{{ $rowNum }}</td>
                            <td>
                                <strong>{{ htmlspecialchars($log->user->magang->nama_lengkap ?? $log->user->username ?? 'Magang') }}</strong>
                                <div style="font-size:0.8rem; color:#666; margin-top:2px;">
                                    <i class="fas fa-university"></i> {{ htmlspecialchars($log->user->magang->instansi ?? '-') }}
                                </div>
                            </td>
                            <td>
                                <strong>{{ $log->hari_absen }}</strong>
                                <div style="font-size:0.8rem; color:#666; margin-top:2px;">{{ $attendanceDate }}</div>
                            </td>
                            <td>{{ htmlspecialchars($log->qr->nama_kegiatan ?? 'Kegiatan') }}</td>
                            <td>
                                <span style="color:var(--hadir); font-weight:500;">{{ $checkInTime }}</span>
                                <div style="font-size:0.75rem; color:#888;">Cek in: {{ $log->status_cek_in }}</div>
                            </td>
                            <td>
                                <span style="color:var(--total-waktu); font-weight:500;">{{ $checkOutTime }}</span>
                                @if ($log->absen_cek_out)
                                    <div style="font-size:0.75rem; color:#888;">Cek out: {{ $log->status_cek_out ?? 'hadir' }}</div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $log->total_waktu_formatted }}</strong>
                                <div style="font-size:0.75rem; color:#888;">({{ $log->total_waktu }})</div>
                            </td>
                            <td>
                                @if ($log->status_cek_in === 'hadir' && ($log->status_cek_out === 'hadir' || empty($log->status_cek_out)))
                                    <span class="status-badge status-hadir"><i class="fas fa-check-circle"></i> Hadir</span>
                                @elseif ($log->status_cek_in === 'terlambat')
                                    <span class="status-badge status-terlambat"><i class="fas fa-clock"></i> Terlambat</span>
                                @else
                                    <span class="status-badge status-pulang-cepat"><i class="fas fa-running"></i> Pulang Cepat</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination Wrapper -->
            <div class="pagination-wrapper">
                {{ $laporan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
