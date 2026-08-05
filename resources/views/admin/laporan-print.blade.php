<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Kehadiran Magang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            padding: 20px;
            font-size: 0.9rem;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 1.6rem;
            color: #000;
        }
        
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 1.2rem;
            font-weight: normal;
        }
        
        .header p {
            margin: 5px 0 0 0;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        
        .title {
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        
        .meta-info {
            width: 100%;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 8px 12px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .footer {
            margin-top: 50px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .signature {
            text-align: center;
            width: 250px;
        }

        .signature .date {
            margin-bottom: 60px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: left;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #4361ee; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
            <i class="fas fa-print"></i> Hubungkan Pencetak / Cetak Sekarang
        </button>
    </div>

    <!-- Header BPS -->
    <div class="header">
        <h1>BADAN PUSAT STATISTIK</h1>
        <h2>PROVINSI SULAWESI TENGGARA</h2>
        <p>Jl. Jend. H. A. Nasution No. G-17, Kendari 93231 | Telp: (0401) 3121589 | Email: bps7400@bps.go.id</p>
    </div>

    <div class="title">LAPORAN KEHADIRAN PESERTA MAGANG KEMNAKER</div>

    <!-- Meta Info Laporan -->
    <table class="meta-info" style="border:none; margin-bottom: 10px;">
        <tr style="background:none;">
            <td style="border:none; padding: 2px 0; width: 120px;">Dicetak Tanggal</td>
            <td style="border:none; padding: 2px 0; width: 15px;">:</td>
            <td style="border:none; padding: 2px 0;"><strong>{{ Carbon\Carbon::now('Asia/Makassar')->isoFormat('D MMMM Y - H:i') }} WITA</strong></td>
        </tr>
        @if ($startDate || $endDate)
        <tr style="background:none;">
            <td style="border:none; padding: 2px 0;">Periode Laporan</td>
            <td style="border:none; padding: 2px 0;">:</td>
            <td style="border:none; padding: 2px 0;">
                <strong>
                    @if($startDate) {{ Carbon\Carbon::parse($startDate)->isoFormat('D MMM Y') }} @else Awal @endif
                    s/d
                    @if($endDate) {{ Carbon\Carbon::parse($endDate)->isoFormat('D MMM Y') }} @else Hari ini @endif
                </strong>
            </td>
        </tr>
        @endif
        <tr style="background:none;">
            <td style="border:none; padding: 2px 0;">Jumlah Record</td>
            <td style="border:none; padding: 2px 0;">:</td>
            <td style="border:none; padding: 2px 0;"><strong>{{ $laporan->count() }} Data Kehadiran</strong></td>
        </tr>
    </table>

    <!-- Main Data Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 50px; text-align: center;">No</th>
                <th>Nama Peserta Magang</th>
                <th>Hari, Tanggal</th>
                <th>Kegiatan</th>
                <th>Jam Cek In</th>
                <th>Jam Cek Out</th>
                <th>Durasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if ($laporan->isEmpty())
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted);">Tidak ada data laporan absensi magang.</td>
                </tr>
            @else
                @foreach ($laporan as $index => $log)
                    @php
                        $checkInTime = $log->absen_cek_in ? Carbon\Carbon::parse($log->absen_cek_in)->format('H:i') . ' WITA' : '-';
                        $checkOutTime = $log->absen_cek_out ? Carbon\Carbon::parse($log->absen_cek_out)->format('H:i') . ' WITA' : '-';
                        $attendanceDate = Carbon\Carbon::parse($log->created_at)->isoFormat('D MMMM Y');
                        
                        // Status Text
                        if ($log->status_cek_in === 'hadir' && ($log->status_cek_out === 'hadir' || empty($log->status_cek_out))) {
                            $statusText = 'Hadir';
                        } elseif ($log->status_cek_in === 'terlambat') {
                            $statusText = 'Terlambat';
                        } else {
                            $statusText = 'Pulang Cepat';
                        }
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $log->user->magang->nama_lengkap ?? $log->user->username ?? 'Magang' }}</strong>
                            <div style="font-size: 0.8rem; color: #555; margin-top:2px;">Instansi: {{ $log->user->magang->instansi ?? '-' }}</div>
                        </td>
                        <td>{{ $log->hari_absen }}, {{ $attendanceDate }}</td>
                        <td>{{ $log->qr->nama_kegiatan ?? 'Kegiatan' }}</td>
                        <td>{{ $checkInTime }}</td>
                        <td>{{ $checkOutTime }}</td>
                        <td>{{ $log->total_waktu_formatted }}</td>
                        <td style="font-weight: bold; color: @if($statusText === 'Hadir') green @elseif($statusText === 'Terlambat') orange @else red @endif;">
                            {{ $statusText }}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Signature Footer -->
    <div class="footer">
        <div class="signature">
            <div class="date">Kendari, {{ Carbon\Carbon::now('Asia/Makassar')->isoFormat('D MMMM Y') }}</div>
            <p style="margin-bottom: 50px;">Pembimbing Lapangan,</p>
            <strong>La Ode Haerul Saleh, S.S.T., M.Si.</strong>
            <div style="border-top: 1px solid #000; margin-top: 5px; width: 100%;"></div>
            <span style="font-size: 0.85rem;">NIP. 19850426 200801 1 002</span>
        </div>
    </div>

    <!-- Automatically open browser print dialog on load -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Auto open print dialog after 1 second
            setTimeout(() => {
                window.print();
            }, 1000);
        });
    </script>
</body>
</html>
