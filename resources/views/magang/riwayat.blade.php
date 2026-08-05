@extends('layouts.app')

@section('title', 'Riwayat Absensi')
@section('header_title', 'Riwayat Kehadiran')

@section('content')
<div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 overflow-hidden mb-8">
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
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-[13px]">
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">No</th>
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">Hari / Tanggal</th>
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">Kegiatan</th>
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">Check-in</th>
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">Check-out</th>
                        <th class="py-4 px-5 font-semibold text-gray-600 whitespace-nowrap">Durasi Kerja</th>
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
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
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
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-full border bg-emerald-100 text-emerald-700 border-emerald-200 uppercase">
                                        <i class="fas fa-check-circle"></i> Hadir
                                    </span>
                                @elseif ($r->status_cek_in === 'terlambat')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-full border bg-amber-100 text-amber-700 border-amber-200 uppercase">
                                        <i class="fas fa-clock"></i> Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold rounded-full border bg-cyan-100 text-cyan-700 border-cyan-200 uppercase">
                                        <i class="fas fa-running"></i> Pulang Cepat
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center">
                                <button onclick="downloadPDFAttendance('{{ $r->id_absensi }}', '{{ htmlspecialchars($r->qr->nama_kegiatan ?? 'Kegiatan') }}', '{{ $attendanceDay }}', '{{ $attendanceDate }}', '{{ $checkInTime }}', '{{ $checkOutTime }}', '{{ $r->total_waktu_formatted }}', '{{ $r->total_waktu }}', '{{ $r->status_cek_in }}', '{{ $r->status_cek_out ?? 'hadir' }}')" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg bg-cyan-50 text-cyan-600 border border-cyan-100 hover:bg-cyan-500 hover:text-white hover:border-cyan-500 transition-all shadow-sm">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Wrapper -->
        <div class="px-6 py-4 border-t border-gray-50 flex justify-center bg-gray-50/30">
            {{ $riwayat->links() }}
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
            <div style="font-family: 'Inter', 'Space Grotesk', 'Segoe UI', sans-serif; padding: 40px; background: #050711; color: white; border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.08); width: 450px; margin: 0 auto; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35); position: relative; overflow: hidden;">
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

        html2pdf().from(element).set(opt).save();
    }
</script>
@endsection
