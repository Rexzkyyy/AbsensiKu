@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('header_title', 'Riwayat Kehadiran')

@section('styles')
<style>
    /* Table Styling */
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
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
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

    .btn-action-print {
        padding: 6px 12px;
        background: rgba(0, 180, 216, 0.08);
        border: 1px solid rgba(0, 180, 216, 0.3);
        color: var(--success);
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-action-print:hover {
        background: var(--success);
        color: white;
        border-color: var(--success);
        box-shadow: 0 4px 12px rgba(0, 180, 216, 0.2);
    }
</style>
@endsection

@section('content')
<div class="card">
    <h3 class="section-title"><i class="fas fa-history"></i> Seluruh Kehadiran Saya</h3>
    <p style="margin-bottom: 25px; color: var(--text-muted); font-size: 0.95rem; line-height: 1.5;">
        Berikut adalah seluruh daftar riwayat kehadiran Anda yang tercatat pada sistem absensi digital BPS Provinsi Sulawesi Tenggara.
    </p>

    <div class="table-container">
        @if ($riwayat->isEmpty())
            <div style="text-align:center; color:#999; padding: 40px;">
                <i class="fas fa-folder-open fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
                <p>Belum ada riwayat kehadiran tercatat.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Hari / Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Durasi Kerja</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($riwayat as $index => $r)
                        @php
                            $rowNum = $riwayat->firstItem() + $index;
                            $checkInTime = $r->absen_cek_in ? Carbon\Carbon::parse($r->absen_cek_in)->format('H:i') . ' WITA' : '-';
                            $checkOutTime = $r->absen_cek_out ? Carbon\Carbon::parse($r->absen_cek_out)->format('H:i') . ' WITA' : '-';
                            $attendanceDate = Carbon\Carbon::parse($r->created_at)->isoFormat('D MMMM Y');
                            $attendanceDay = $r->hari_absen;
                        @endphp
                        <tr>
                            <td>{{ $rowNum }}</td>
                            <td>
                                <strong>{{ $attendanceDay }}</strong>
                                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 3px;">{{ $attendanceDate }}</div>
                            </td>
                            <td>{{ $r->qr->nama_kegiatan ?? 'Kegiatan' }}</td>
                            <td>
                                <span style="font-weight: 500; color: var(--hadir);">{{ $checkInTime }}</span>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">Badge: {{ $r->status_cek_in }}</div>
                            </td>
                            <td>
                                <span style="font-weight: 500; color: var(--total-waktu);">{{ $checkOutTime }}</span>
                                @if ($r->absen_cek_out)
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">Badge: {{ $r->status_cek_out ?? 'hadir' }}</div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $r->total_waktu_formatted }}</strong>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">({{ $r->total_waktu }})</div>
                            </td>
                            <td>
                                @if ($r->status_cek_in === 'hadir' && ($r->status_cek_out === 'hadir' || empty($r->status_cek_out)))
                                    <span class="status-badge status-hadir"><i class="fas fa-check-circle"></i> Hadir</span>
                                @elseif ($r->status_cek_in === 'terlambat')
                                    <span class="status-badge status-terlambat"><i class="fas fa-clock"></i> Terlambat</span>
                                @else
                                    <span class="status-badge status-pulang-cepat"><i class="fas fa-running"></i> Pulang Cepat</span>
                                @endif
                            </td>
                            <td>
                                <button onclick="downloadPDFAttendance('{{ $r->id_absensi }}', '{{ $r->qr->nama_kegiatan ?? 'Kegiatan' }}', '{{ $attendanceDay }}', '{{ $attendanceDate }}', '{{ $checkInTime }}', '{{ $checkOutTime }}', '{{ $r->total_waktu_formatted }}', '{{ $r->total_waktu }}', '{{ $r->status_cek_in }}', '{{ $r->status_cek_out ?? 'hadir' }}')" class="btn-action-print">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination Wrapper -->
            <div class="pagination-wrapper" style="margin-top: 25px; display: flex; justify-content: center;">
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<!-- Load html2pdf bundle from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    // Fungsi download PDF bukti kehadiran magang dengan format modern
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
            <div style="font-family: 'Space Grotesk', 'Segoe UI', sans-serif; padding: 40px; background: #050711; color: white; border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.08); width: 450px; margin: 0 auto; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35); position: relative; overflow: hidden;">
                <!-- Glowing Top Strip -->
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #ff7b00, #7209b7, #00b4d8);"></div>

                <!-- Header -->
                <div style="text-align: center; border-bottom: 2px dashed rgba(255, 255, 255, 0.1); padding-bottom: 20px; margin-bottom: 20px;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #ff7b00; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 5px;">Sistem Presensi Digital</div>
                    <h2 style="margin: 0; font-size: 1.60rem; font-weight: 700; color: white;">Absensi<span style="color: #ff7b00;">Ku</span></h2>
                    <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.5); margin-top: 5px;">BPS Provinsi Sulawesi Tenggara</div>
                </div>
                
                <!-- Status Badge -->
                <div style="background: ${statusColor}; color: ${statusText === 'Terlambat' ? 'black' : 'white'}; padding: 12px; border-radius: 12px; text-align: center; font-weight: bold; margin-bottom: 25px; font-size: 1rem; letter-spacing: 0.05em; text-transform: uppercase;">
                    STATUS: ${statusText}
                </div>

                <!-- Info Grid -->
                <div style="margin-bottom: 25px;">
                    <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); font-size: 0.95rem;">
                        <span style="color: rgba(255, 255, 255, 0.5); font-weight: 500;">Nama Pengguna:</span>
                        <span style="font-weight: 600; color: white;">{{ $username }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); font-size: 0.95rem;">
                        <span style="color: rgba(255, 255, 255, 0.5); font-weight: 500;">Kegiatan:</span>
                        <span style="font-weight: 600; color: white;">${kegiatan}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); font-size: 0.95rem;">
                        <span style="color: rgba(255, 255, 255, 0.5); font-weight: 500;">Hari & Tanggal:</span>
                        <span style="font-weight: 600; color: white;">${hari}, ${tanggal}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); font-size: 0.95rem;">
                        <span style="color: rgba(255, 255, 255, 0.5); font-weight: 500;">Waktu Check-in:</span>
                        <span style="font-weight: 600; color: #00b4d8;">${cekIn}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); font-size: 0.95rem;">
                        <span style="color: rgba(255, 255, 255, 0.5); font-weight: 500;">Waktu Check-out:</span>
                        <span style="font-weight: 600; color: #ff7b00;">${cekOut}</span>
                    </div>
                </div>

                ${cekOut !== '-' ? `
                <div style="background: rgba(255, 123, 0, 0.08); border-left: 4px solid #ff7b00; padding: 14px; margin-bottom: 25px; border-radius: 10px;">
                    <span style="font-size: 0.8rem; color: rgba(255, 123, 0, 0.8); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Total Waktu Kerja</span>
                    <div style="font-size: 1.2rem; font-weight: 700; color: #ffa600; margin-top: 2px;">${durasi} <span style="font-size: 0.85rem; font-weight: normal; color: rgba(255, 255, 255, 0.55);">(${totalSec})</span></div>
                </div>
                ` : ''}

                <!-- Footer -->
                <div style="text-align: center; margin-top: 30px; font-size: 0.75rem; color: rgba(255, 255, 255, 0.4); border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 15px; line-height: 1.4;">
                    Dokumen ini sah diunduh dari Sistem Absensi Digital BPS Sultra.<br>
                    Diunduh pada: ${new Date().toLocaleString('id-ID')} WITA
                </div>
            </div>
        `;

        const opt = {
            margin:       15,
            filename:     `Bukti_Absensi_${tanggal.replace(/ /g, '_')}_${kegiatan.replace(/ /g, '_')}.pdf`,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, backgroundColor: '#050711' },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        // Jalankan proses download PDF
        html2pdf().from(element).set(opt).save();
    }
</script>
@endsection
