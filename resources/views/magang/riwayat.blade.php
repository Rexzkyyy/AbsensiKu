@extends('layouts.app')

@section('title', 'Riwayat Absensi')
@section('header_title', 'Riwayat Kehadiran')

@section('content')

<style>
    /* Staggered Fade Up for Table Rows */
    .table-row-animate {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeUpRow 0.5s ease-out forwards;
    }
    @keyframes fadeUpRow {
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Interactive Hover Rows */
    .interactive-row {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border-left: 3px solid transparent;
    }
    .interactive-row:hover {
        background-color: #ffffff !important;
        transform: scale(1.01) translateX(5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        z-index: 10;
        position: relative;
    }
    
    /* Glowing Border based on status */
    .row-hadir:hover { border-left-color: #34d399; }
    .row-terlambat:hover { border-left-color: #fbbf24; }
    .row-pulangcepat:hover { border-left-color: #38bdf8; }

    /* Pulsing Badges */
    .pulse-badge {
        position: relative;
    }
    .pulse-badge::before {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 9999px;
        background: inherit;
        opacity: 0.4;
        z-index: -1;
        animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse-ring {
        0% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(1.4); opacity: 0; }
    }

    /* Responsive table wrapper */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin: 0 -4px;
    }
    .table-responsive table {
        min-width: 700px; /* agar tidak terlalu sempit di HP */
        width: 100%;
    }

    /* Mobile card style for very small screens (optional) - kita tetap pakai tabel dengan overflow */
    @media (max-width: 640px) {
        .table-responsive table {
            min-width: 600px;
            font-size: 0.8rem;
        }
        .table-responsive td, .table-responsive th {
            padding: 0.5rem 0.75rem !important;
        }
        .table-responsive .btn-pdf {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.7rem !important;
        }
        .table-responsive .badge-status {
            font-size: 0.6rem !important;
            padding: 0.15rem 0.5rem !important;
        }
        .pulse-badge::before {
            display: none; /* matikan pulse di HP agar tidak berlebihan */
        }
    }

    /* Pagination styling agar rapi */
    .pagination-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    .pagination-container .page-item {
        list-style: none;
    }
    .pagination-container .page-link {
        display: inline-block;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        background: white;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .pagination-container .page-link:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    .pagination-container .active .page-link {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    .pagination-container .disabled .page-link {
        opacity: 0.5;
        pointer-events: none;
    }

    /* Kosmetik untuk tombol PDF */
    .btn-pdf {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        padding: 0.3rem 0.8rem;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 0.7rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        white-space: nowrap;
    }
    .btn-pdf:hover {
        background: #059669;
        color: white;
        border-color: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(5, 150, 105, 0.2);
    }

    /* Card header */
    .header-card {
        background: white/70;
        backdrop-filter: blur(12px);
        border-radius: 1.5rem;
        border: 1px solid rgba(255,255,255,0.6);
        box-shadow: 0 8px 30px rgba(0,0,0,0.04);
        overflow: hidden;
    }
</style>

<div class="header-card mb-8">
    <div class="px-6 py-5 border-b border-white/40 bg-white/30">
        <h3 class="font-extrabold text-slate-800 text-xl flex items-center gap-2 tracking-tight">
            <i class="fas fa-history text-cyan-500"></i> Seluruh Kehadiran Saya
        </h3>
        <p class="text-sm text-slate-500 font-medium mt-1">
            Berikut adalah daftar riwayat kehadiran Anda yang tercatat pada sistem absensi digital.
        </p>
    </div>

    @if ($riwayat->isEmpty())
        <div class="text-center py-16 flex flex-col justify-center items-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4">
                <i class="fas fa-folder-open text-3xl"></i>
            </div>
            <h4 class="font-medium text-gray-600">Belum Ada Riwayat</h4>
            <p class="text-sm text-gray-400">Belum ada riwayat kehadiran yang tercatat untuk Anda.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-[13px]">
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">No</th>
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">Hari / Tanggal</th>
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">Kegiatan</th>
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">Check-in</th>
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">Check-out</th>
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">Durasi</th>
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">Status</th>
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($riwayat as $index => $r)
                        @php
                            $rowNum = $riwayat->firstItem() + $index;
                            $checkInTime = $r->absen_cek_in ? Carbon\Carbon::parse($r->absen_cek_in)->format('H:i') . ' WITA' : '-';
                            $checkOutTime = $r->absen_cek_out ? Carbon\Carbon::parse($r->absen_cek_out)->format('H:i') . ' WITA' : '-';
                            $attendanceDay = $r->hari_absen;
                            $attendanceDate = Carbon\Carbon::parse($r->created_at)->isoFormat('D MMMM Y');
                            
                            $rowStatusClass = 'row-hadir';
                            if ($r->status_cek_in === 'terlambat') $rowStatusClass = 'row-terlambat';
                            if ($r->status_cek_out === 'pulang_cepat') $rowStatusClass = 'row-pulangcepat';
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition interactive-row table-row-animate {{ $rowStatusClass }}" style="animation-delay: {{ $index * 100 }}ms;">
                            <td class="py-4 px-5 text-sm text-gray-500 font-mono">{{ $rowNum }}</td>
                            <td class="py-4 px-5">
                                <div class="font-bold text-gray-700 text-sm">{{ $attendanceDay }}</div>
                                <div class="text-[11px] text-gray-500 mt-0.5">{{ $attendanceDate }}</div>
                            </td>
                            <td class="py-4 px-5 text-sm text-gray-600 max-w-[150px] truncate">
                                {{ htmlspecialchars($r->qr->nama_kegiatan ?? 'Kegiatan') }}
                            </td>
                            <td class="py-4 px-5">
                                <div class="font-bold text-emerald-600 text-sm">{{ $checkInTime }}</div>
                                <div class="text-[11px] text-gray-500 mt-0.5 capitalize">Cek in: {{ $r->status_cek_in }}</div>
                            </td>
                            <td class="py-4 px-5">
                                <div class="font-bold text-amber-600 text-sm">{{ $checkOutTime }}</div>
                                @if ($r->absen_cek_out)
                                    <div class="text-[11px] text-gray-500 mt-0.5 capitalize">Cek out: {{ $r->status_cek_out ?? 'hadir' }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-5">
                                <div class="font-bold text-gray-800 text-sm">{{ $r->total_waktu_formatted }}</div>
                                <div class="text-[11px] text-gray-500 mt-0.5">({{ $r->total_waktu }})</div>
                            </td>
                            <td class="py-4 px-5">
                                @if ($r->status_cek_in === 'hadir' && ($r->status_cek_out === 'hadir' || empty($r->status_cek_out)))
                                    <span class="pulse-badge inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-full border bg-emerald-100 text-emerald-700 border-emerald-200 uppercase badge-status">
                                        <i class="fas fa-check-circle"></i> Hadir
                                    </span>
                                @elseif ($r->status_cek_in === 'terlambat')
                                    <span class="pulse-badge inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-full border bg-amber-100 text-amber-700 border-amber-200 uppercase badge-status">
                                        <i class="fas fa-clock"></i> Terlambat
                                    </span>
                                @else
                                    <span class="pulse-badge inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-full border bg-cyan-100 text-cyan-700 border-cyan-200 uppercase badge-status">
                                        <i class="fas fa-running"></i> Pulang Cepat
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center">
                                <button onclick="downloadPDFAttendance('{{ $r->id_absensi }}', '{{ htmlspecialchars($r->qr->nama_kegiatan ?? 'Kegiatan') }}', '{{ $attendanceDay }}', '{{ $attendanceDate }}', '{{ $checkInTime }}', '{{ $checkOutTime }}', '{{ $r->total_waktu_formatted }}', '{{ $r->total_waktu }}', '{{ $r->status_cek_in }}', '{{ $r->status_cek_out ?? 'hadir' }}')" 
                                        class="btn-pdf">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-50 flex justify-center bg-gray-50/30">
            <div class="pagination-container">
                {{ $riwayat->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<!-- Load html2pdf bundle from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadPDFAttendance(id, kegiatan, hari, tanggal, cekIn, cekOut, durasi, totalSec, statusCekIn, statusCekOut) {
        let statusText = 'Hadir';
        let statusColor = '#00b4d8';
        
        if (statusCekIn === 'terlambat') {
            statusText = 'Terlambat';
            statusColor = '#ffc107';
        } else if (statusCekOut === 'pulang_cepat') {
            statusText = 'Pulang Cepat';
            statusColor = '#dc3545';
        }

        const element = document.createElement('div');
        element.innerHTML = `
            <div style="font-family: 'Inter', 'Segoe UI', sans-serif; padding: 40px; background: #ffffff; color: #1e293b; border: 1px solid #e2e8f0; width: 600px; margin: 0 auto; position: relative;">
                
                <!-- Header Laporan -->
                <div style="text-align: center; border-bottom: 2px solid #cbd5e1; padding-bottom: 20px; margin-bottom: 30px;">
                    <div style="font-size: 0.9rem; font-weight: 700; color: #3b82f6; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 5px;">Sistem Presensi Digital</div>
                    <h2 style="margin: 0; font-size: 1.8rem; font-weight: 800; color: #0f172a;">BUKTI KEHADIRAN</h2>
                    <div style="font-size: 0.9rem; color: #64748b; margin-top: 5px;">Badan Pusat Statistik Provinsi Sulawesi Tenggara</div>
                </div>
                
                <!-- Status Badge -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <span style="background: ${statusText === 'Hadir' ? '#d1fae5' : (statusText === 'Terlambat' ? '#fef3c7' : '#e0f2fe')}; 
                                 color: ${statusText === 'Hadir' ? '#047857' : (statusText === 'Terlambat' ? '#b45309' : '#0369a1')}; 
                                 border: 1px solid ${statusText === 'Hadir' ? '#34d399' : (statusText === 'Terlambat' ? '#fbbf24' : '#38bdf8')};
                                 padding: 8px 24px; border-radius: 9999px; font-weight: 700; font-size: 1rem; letter-spacing: 0.05em; text-transform: uppercase;">
                        Status: ${statusText}
                    </span>
                </div>

                <!-- Info Grid -->
                <div style="margin-bottom: 30px; background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 12px; padding: 20px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <tr>
                            <td style="padding: 10px 0; color: #64748b; font-weight: 600; width: 40%; border-bottom: 1px dashed #e2e8f0;">Nama Pengguna</td>
                            <td style="padding: 10px 0; font-weight: 700; color: #0f172a; text-align: right; border-bottom: 1px dashed #e2e8f0;">{{ $username }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; color: #64748b; font-weight: 600; border-bottom: 1px dashed #e2e8f0;">Kegiatan</td>
                            <td style="padding: 10px 0; font-weight: 700; color: #0f172a; text-align: right; border-bottom: 1px dashed #e2e8f0;">${kegiatan}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; color: #64748b; font-weight: 600; border-bottom: 1px dashed #e2e8f0;">Hari & Tanggal</td>
                            <td style="padding: 10px 0; font-weight: 700; color: #0f172a; text-align: right; border-bottom: 1px dashed #e2e8f0;">${hari}, ${tanggal}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; color: #64748b; font-weight: 600; border-bottom: 1px dashed #e2e8f0;">Waktu Check-in</td>
                            <td style="padding: 10px 0; font-weight: 700; color: #2563eb; text-align: right; border-bottom: 1px dashed #e2e8f0;">${cekIn}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; color: #64748b; font-weight: 600;">Waktu Check-out</td>
                            <td style="padding: 10px 0; font-weight: 700; color: #ea580c; text-align: right;">${cekOut}</td>
                        </tr>
                    </table>
                </div>

                ${cekOut !== '-' ? `
                <div style="background: #fff7ed; border-left: 4px solid #f97316; padding: 16px; margin-bottom: 30px; border-radius: 0 8px 8px 0; display: flex; justify-content: space-between; items-center;">
                    <span style="font-size: 0.9rem; color: #9a3412; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Total Waktu Kerja</span>
                    <div style="font-size: 1.1rem; font-weight: 800; color: #c2410c;">${durasi} <span style="font-size: 0.85rem; font-weight: normal; color: #ea580c;">(${totalSec})</span></div>
                </div>
                ` : ''}

                <!-- Footer / Tanda Tangan placeholder -->
                <div style="margin-top: 50px; text-align: right;">
                    <div style="font-size: 0.9rem; color: #475569; margin-bottom: 50px;">Kendari, ${new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})}</div>
                    <div style="font-size: 0.9rem; font-weight: 700; color: #0f172a; border-top: 1px solid #cbd5e1; display: inline-block; padding-top: 5px; width: 200px; text-align: center;">Tercatat secara digital</div>
                </div>
                
                <!-- Info Dokumen -->
                <div style="text-align: center; margin-top: 40px; font-size: 0.7rem; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 15px;">
                    Dokumen ini dihasilkan secara otomatis oleh Sistem Absensi Digital BPS Sultra.<br>
                    Waktu Cetak: ${new Date().toLocaleString('id-ID')} WITA
                </div>
            </div>
        `;

        const opt = {
            margin:       10,
            filename:     `Bukti_Absensi_${tanggal.replace(/ /g, '_')}_${kegiatan.replace(/ /g, '_')}.pdf`,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, backgroundColor: '#ffffff' },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().from(element).set(opt).save();
    }
</script>
@endsection