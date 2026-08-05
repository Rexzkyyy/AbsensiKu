@extends('layouts.app')

@section('title', 'Scan QR Code')

@section('header_title', 'Scan QR')

@section('styles')
    <style>
        /* Scanner Styles */
        /* Time Display Custom Compact Override for Scanner */
        .time-display {
            padding: 12px 18px !important;
            margin-bottom: 20px !important;
            text-align: center !important;
            background: rgba(15, 19, 42, 0.6) !important;
            backdrop-filter: blur(25px) !important;
            border: 1px solid var(--border-light) !important;
            box-shadow: var(--card-shadow) !important;
            border-radius: 14px !important;
            color: white !important;
            position: relative;
        }
        
        .time-display::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--success));
            border-radius: 14px 14px 0 0;
        }

        .time-display .date {
            font-family: var(--font-heading) !important;
            font-size: 0.95rem !important;
            margin-bottom: 0 !important;
            font-weight: 600 !important;
            display: inline-block !important;
            letter-spacing: 0.5px !important;
            color: white !important;
        }

        .time-display .time {
            display: none !important; /* Sembunyikan jam besar karena jam detik sudah ada di string tanggal */
        }

        .time-display .location {
            display: none !important; /* Sembunyikan lokasi agar minimalis */
        }

        /* Scanner Styles */
        .scanner-container {
            text-align: center;
            padding: 10px 0;
        }

        .scanner-placeholder {
            width: 100%;
            max-width: 500px; /* Increased from 400px */
            height: 380px;    /* Increased from 300px */
            background: rgba(255, 255, 255, 0.02);
            border-radius: 20px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border: 2px dashed var(--border-light);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        /* Smooth Video Feed Scaling to Cover full screen scanner box */
        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 18px;
        }

        .scanner-placeholder:hover {
            border-color: var(--primary);
            background: rgba(255, 123, 0, 0.04);
        }

        .scanner-placeholder i {
            font-size: 4rem;
            color: var(--primary);
            margin-bottom: 15px;
            opacity: 0.85;
        }

        .scanner-active {
            background: rgba(15, 19, 42, 0.95);
            border: 2px solid var(--primary);
        }

        .manual-input {
            max-width: 400px;
            margin: 30px auto 0;
            padding-top: 20px;
            border-top: 1px solid var(--border-light);
        }

        /* Result Styles */
        .result-container {
            text-align: center;
            padding: 30px 20px;
        }

        .result-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        }

        .result-success {
            background: linear-gradient(135deg, var(--success), #3dcad8);
            color: white;
        }

        /* Attendance Info */
        .attendance-info {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-light);
            padding: 20px;
            border-radius: 16px;
            margin: 20px 0;
            text-align: left;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-light);
        }

        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 500;
            color: var(--text-muted);
        }

        .info-value {
            font-weight: 600;
            color: var(--primary-light);
        }

        /* Status Badge */
        .status-badge {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            font-family: var(--font-heading);
        }

        .status-hadir {
            background: rgba(0, 180, 216, 0.08);
            color: var(--success);
            border: 1px solid rgba(0, 180, 216, 0.15);
        }

        .status-terlambat {
            background: rgba(255, 193, 7, 0.08);
            color: var(--warning);
            border: 1px solid rgba(255, 193, 7, 0.15);
        }

        .status-pulang-cepat {
            background: rgba(23, 162, 184, 0.08);
            color: var(--early);
            border: 1px solid rgba(23, 162, 184, 0.15);
        }

        /* Total Waktu Badge */
        .total-waktu-badge {
            background: linear-gradient(135deg, rgba(253, 126, 20, 0.08), rgba(253, 126, 20, 0.04));
            border: 1px solid rgba(253, 126, 20, 0.2);
            color: var(--total-waktu);
            padding: 12px 18px;
            border-radius: 12px;
            margin: 15px 0;
            text-align: center;
            font-weight: 600;
        }

        /* Hari Khusus Badge */
        .hari-khusus-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 8px;
        }

        .badge-jumat {
            background: rgba(156, 39, 176, 0.1);
            color: #d896ff;
            border: 1px solid rgba(156, 39, 176, 0.2);
        }

        .badge-minggu {
            background: rgba(255, 107, 107, 0.1);
            color: var(--minggu);
            border: 1px solid rgba(255, 107, 107, 0.2);
        }

        /* Absensi Type Selector */
        .absensi-type-selector {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            background: var(--glass-bg);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--border-light);
            padding: 16px;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .type-option {
            flex: 1;
            text-align: center;
            padding: 14px;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition);
            color: var(--text-muted);
        }

        .type-option i {
            font-size: 1.5rem;
            margin-bottom: 6px;
            display: block;
        }

        .type-checkin.active {
            border-color: var(--success);
            background: rgba(0, 180, 216, 0.08);
            color: white;
            box-shadow: 0 8px 20px rgba(0, 180, 216, 0.15);
        }

        .type-checkout.active {
            border-color: var(--total-waktu);
            background: rgba(253, 126, 20, 0.08);
            color: white;
            box-shadow: 0 8px 20px rgba(253, 126, 20, 0.15);
        }

        /* Stop Scanner Button inside camera container */
        .stop-btn {
            margin-top: 15px;
            z-index: 10;
        }
    </style>
@endsection

@section('content')
    <!-- Time Display -->
    <div class="time-display">
        <div class="date" id="current-date">{{ Carbon\Carbon::now('Asia/Makassar')->isoFormat('dddd, D MMMM Y') }} -
            {{ Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') }} WITA</div>
        <div class="time" id="current-time">{{ Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') }}</div>
        <div class="location">
            <i class="fas fa-map-marker-alt"></i>
            <span>Kendari, Sulawesi Tenggara - WITA</span>
        </div>
    </div>

    <!-- Absensi Type Selector -->
    <div class="absensi-type-selector">
        <div class="type-option type-checkin active" onclick="selectAbsensiType('check_in')">
            <i class="fas fa-sign-in-alt" style="color: var(--success);"></i>
            <div>Check-in</div>
            <small>Absen Masuk</small>
        </div>
        <div class="type-option type-checkout" onclick="selectAbsensiType('check_out')">
            <i class="fas fa-sign-out-alt" style="color: var(--total-waktu);"></i>
            <div>Check-out</div>
            <small>Absen Keluar</small>
        </div>
    </div>

    <div class="card">
        <h3 class="section-title"><i class="fas fa-camera"></i> Scanner QR Code</h3>

        <div class="scanner-container">
            <!-- Scanner placeholder/webcam camera reader -->
            <div id="reader" class="scanner-placeholder">
                <i class="fas fa-camera"></i>
                <p style="font-size: 1.1rem; margin-bottom: 15px; color: var(--text-muted);">Kamera siap untuk scan QR Code</p>
                <button onclick="startScanner()" class="btn btn-primary">
                    <i class="fas fa-play"></i> Mulai Scan
                </button>
            </div>

            <div id="scanner-result" style="display: none; max-width: 400px; margin: 0 auto 20px;">
                <!-- Hasil scan sementara sebelum diposting akan muncul di sini -->
            </div>

            <div class="manual-input">
                <p style="margin-bottom: 15px; color: var(--text-muted); text-align: center;">
                    <i class="fas fa-keyboard"></i> Atau masukkan kode QR manual:
                </p>
                <form method="POST" action="{{ route('magang.scan.process') }}" id="manual-form">
                    @csrf
                    <input type="hidden" name="absensi_type" id="absensi_type" value="check_in">
                    <div class="form-group">
                        <input type="text" class="form-input" name="qr_code"
                            placeholder="Masukkan kode QR (contoh: QR-ABC123)" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-check"></i> Validasi Kode
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Hasil Absensi (Jika Baru Selesai Absen & Data Result Tersimpan di Session) -->
    @if ($scanResult)
        <div class="card" id="bukti-absensi-card">
            <h3 class="section-title"><i class="fas fa-clipboard-check"></i> Hasil Absensi</h3>
            <div class="result-container">
                <div class="result-icon result-success">
                    <i class="fas fa-check"></i>
                </div>
                <h2 style="color: var(--success); margin-bottom: 10px; font-size: 1.8rem;">
                    {{ $scanResult['type'] === 'check_in' ? 'Check-in Berhasil!' : 'Check-out Berhasil!' }}
                </h2>
                <p style="color: var(--text-muted); margin-bottom: 25px;">Data absensi Anda telah tercatat dengan baik</p>

                <div class="attendance-info">
                    <div class="info-item">
                        <span class="info-label">Kode QR:</span>
                        <span class="info-value">{{ $scanResult['kode_qr'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Kegiatan:</span>
                        <span class="info-value">{{ $scanResult['nama_kegiatan'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Hari Absen:</span>
                        <span class="info-value">
                            {{ $scanResult['hari_absen'] }}
                            @if (isset($scanResult['waktu_khusus_hari']))
                                @if ($scanResult['waktu_khusus_hari'] === 'Jumat')
                                    <span class="hari-khusus-badge badge-jumat">
                                        <i class="fas fa-star"></i> Khusus Jumat
                                    </span>
                                @elseif ($scanResult['waktu_khusus_hari'] === 'Minggu')
                                    <span class="hari-khusus-badge badge-minggu">
                                        <i class="fas fa-sun"></i> Khusus Minggu
                                    </span>
                                @endif
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Waktu
                            {{ $scanResult['type'] === 'check_in' ? 'Check-in' : 'Check-out' }}:</span>
                        <span class="info-value">{{ $scanResult['waktu_absen_formatted'] }}</span>
                    </div>
                    @if ($scanResult['type'] === 'check_out' && isset($scanResult['waktu_check_in']))
                        <div class="info-item">
                            <span class="info-label">Waktu Check-in:</span>
                            <span class="info-value">{{ Carbon\Carbon::parse($scanResult['waktu_check_in'])->format('H:i') }}
                                WITA</span>
                        </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Waktu Batas:</span>
                        <span class="info-value">
                            {{ Carbon\Carbon::parse($scanResult['waktu_batas'])->format('H:i') }} WITA
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="status-badge status-{{ $scanResult['status_absen'] }}">
                                <i
                                    class="fas @if($scanResult['status_absen'] === 'hadir') fa-check-circle @elseif($scanResult['status_absen'] === 'terlambat') fa-clock @else fa-running @endif"></i>
                                {{ $scanResult['status_absen'] }}
                            </span>
                        </span>
                    </div>
                    @if ($scanResult['type'] === 'check_out' && isset($scanResult['total_waktu']))
                        <div class="total-waktu-badge">
                            <i class="fas fa-clock"></i>
                            <strong>Total Waktu Kerja: {{ $scanResult['total_waktu_formatted'] }}</strong>
                            <br>
                            <small style="font-weight: normal;">({{ $scanResult['total_waktu'] }})</small>
                        </div>
                    @endif
                </div>

                <div style="margin-top: 25px; display: flex; justify-content: center; gap: 15px;">
                    <button onclick="printAttendance()" class="btn btn-outline">
                        <i class="fas fa-print"></i> Cetak Bukti
                    </button>
                    <button onclick="newScan()" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Selesai
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <!-- Include HTML5 QR Code Scanner Library dari CDN -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let html5QrcodeScanner = null;
        let currentAbsensiType = 'check_in';

        // Fungsi untuk memilih jenis absensi
        function selectAbsensiType(type) {
            currentAbsensiType = type;

            // Update UI selector
            document.querySelectorAll('.type-option').forEach(option => {
                option.classList.remove('active');
            });

            if (type === 'check_in') {
                document.querySelector('.type-checkin').classList.add('active');
            } else {
                document.querySelector('.type-checkout').classList.add('active');
            }

            // Update input tipe form manual
            document.getElementById('absensi_type').value = type;
        }

        function startScanner() {
            const readerElement = document.getElementById('reader');

            // Bersihkan placeholder awal
            readerElement.innerHTML = '';
            readerElement.style.background = 'rgba(15, 19, 42, 0.95)';
            readerElement.classList.add('scanner-active');

            // Inisialisasi scanner
            html5QrcodeScanner = new Html5Qrcode("reader");

            const config = {
                fps: 15,
                qrbox: { width: 300, height: 300 }
            };

            // Mulai scanner menggunakan kamera belakang (environment)
            html5QrcodeScanner.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanFailure
            ).then(() => {
                // Berhasil start, tambahkan tombol stop di bawah video reader
                const stopBtn = document.createElement('button');
                stopBtn.className = 'btn btn-danger stop-btn';
                stopBtn.innerHTML = '<i class="fas fa-stop"></i> Berhenti Scan';
                stopBtn.onclick = stopScanner;
                readerElement.appendChild(stopBtn);
            }).catch(err => {
                console.error("Gagal memulai kamera:", err);
                alert("Tidak dapat mengakses kamera. Pastikan Anda memberikan izin akses kamera.");
                resetScanner();
            });
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    resetScanner();
                }).catch(err => {
                    console.error("Gagal menghentikan kamera:", err);
                    resetScanner();
                });
            }
        }

        function resetScanner() {
            const readerElement = document.getElementById('reader');
            readerElement.innerHTML = `
                <i class="fas fa-camera"></i>
                <p style="font-size: 1.1rem; margin-bottom: 15px; color: var(--text-muted);">Kamera siap untuk scan QR Code</p>
                <button onclick="startScanner()" class="btn btn-primary">
                    <i class="fas fa-play"></i> Mulai Scan
                </button>
            `;
            readerElement.style.background = 'rgba(255, 255, 255, 0.02)';
            readerElement.classList.remove('scanner-active');
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Hentikan scanner segera setelah terdeteksi
            stopScanner();

            const typeText = currentAbsensiType === 'check_in' ? 'Check-in' : 'Check-out';
            const resultElement = document.getElementById('scanner-result');

            // Render loading state & form otomatis
            resultElement.innerHTML = `
                <div class="alert alert-success" style="margin-bottom:15px;">
                    <i class="fas fa-check-circle"></i> QR Code berhasil di-scan!
                </div>
                <div style="background: rgba(255, 123, 0, 0.08); border: 1px solid rgba(255, 123, 0, 0.2); padding: 15px; border-radius: 10px; margin-bottom: 15px; text-align: left;">
                    <p style="margin: 0; font-weight: 600; color: var(--primary-light);">
                        <i class="fas fa-qrcode"></i> Kode: <strong>${decodedText}</strong>
                    </p>
                    <p style="margin: 5px 0 0 0; color: var(--text-muted);">
                        <i class="fas fa-${currentAbsensiType === 'check_in' ? 'sign-in-alt' : 'sign-out-alt'}"></i> 
                        Tipe: <strong>${typeText}</strong>
                    </p>
                </div>
                <form method="POST" action="{{ route('magang.scan.process') }}" id="auto-submit-form">
                    @csrf
                    <input type="hidden" name="qr_code" value="${decodedText}">
                    <input type="hidden" name="absensi_type" value="${currentAbsensiType}">
                    <button type="submit" class="btn btn-success" style="width: 100%;">
                        <i class="fas fa-spinner fa-spin"></i> Memproses ${typeText}...
                    </button>
                </form>
            `;
            resultElement.style.display = 'block';

            // Auto-submit setelah 1 detik untuk pengalaman nirkabel
            setTimeout(() => {
                document.getElementById('auto-submit-form').submit();
            }, 1000);
        }

        function onScanFailure(error) {
            // Abaikan kegagalan frame scanning biasa (riple noise)
        }

        function newScan() {
            // Scroll ke scanner atau hilangkan card hasil
            const resultCard = document.getElementById('bukti-absensi-card');
            if (resultCard) {
                resultCard.style.display = 'none';
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Fungsi print tanda bukti absensi presisi
        function printAttendance() {
            @if ($scanResult)
                const typeText = "{{ $scanResult['type'] === 'check_in' ? 'Check-in' : 'Check-out' }}";
                const typeIcon = "{{ $scanResult['type'] === 'check_in' ? 'sign-in-alt' : 'sign-out-alt' }}";
                const statusText = "{{ $scanResult['status_absen'] }}";
                const statusColor = "@if($scanResult['status_absen'] === 'hadir') #28a745 @elseif($scanResult['status_absen'] === 'terlambat') #ffc107 @else #17a2b8 @endif";
                const totalWaktu = "{{ $scanResult['total_waktu_formatted'] ?? '' }}";
                const totalWaktuStr = "{{ $scanResult['total_waktu'] ?? '' }}";
                const waktuCheckIn = "{{ ($scanResult['waktu_check_in'] ?? null) ? Carbon\Carbon::parse($scanResult['waktu_check_in'])->format('H:i') . ' WITA' : '' }}";

                const win = window.open('', '', 'height=600,width=500');
                win.document.write('<html><head><title>Bukti Absensi - {{ $scanResult['nama_kegiatan'] }}</title>');
                win.document.write('<style>');
                win.document.write('body{font-family:"Segoe UI",sans-serif; text-align:center; padding:30px; background:#f5f7fb;}');
                win.document.write('.print-container{max-width:400px; margin:0 auto; background:white; padding:30px; border-radius:15px; box-shadow:0 10px 25px rgba(0,0,0,0.1); border: 1px solid #ddd;}');
                win.document.write('h2{color:#4361ee; margin-bottom:5px;}');
                win.document.write('h3{color:#333; margin-bottom:20px; font-size:1.1rem; font-weight:600;}');
                win.document.write('.info-item{text-align:left; margin:10px 0; padding:10px; background:#f8f9fa; border-radius:8px; border-bottom: 1px solid var(--border-light);}');
                win.document.write('.total-waktu{background:#fff3cd; border-left:4px solid #fd7e14; padding:10px; margin:15px 0; border-radius:8px;}');
                win.document.write('</style>');
                win.document.write('</head><body>');
                win.document.write('<div class="print-container">');
                win.document.write('<h2>Bukti Absensi</h2>');
                win.document.write('<h3>{{ $scanResult['nama_kegiatan'] }}</h3>');
                win.document.write('<div style="background:' + statusColor + '; color:white; padding:10px; border-radius:8px; margin:15px 0; font-weight:bold;">');
                win.document.write(typeText.toUpperCase() + ' - ' + statusText.toUpperCase());
                win.document.write('</div>');
                win.document.write('<div class="info-item">');
                win.document.write('<strong>Nama Pengguna:</strong><br>{{ $username }}');
                win.document.write('</div>');
                win.document.write('<div class="info-item">');
                win.document.write('<strong>Kode QR:</strong><br>{{ $scanResult['kode_qr'] }}');
                win.document.write('</div>');
                win.document.write('<div class="info-item">');
                win.document.write('<strong>Hari Absen:</strong><br>{{ $scanResult['hari_absen'] }}');
                win.document.write('</div>');
                if (typeText === 'Check-out' && waktuCheckIn) {
                    win.document.write('<div class="info-item">');
                    win.document.write('<strong>Waktu Check-in:</strong><br>' + waktuCheckIn);
                    win.document.write('</div>');
                }
                win.document.write('<div class="info-item">');
                win.document.write('<strong>Waktu ' + typeText + ':</strong><br>{{ $scanResult['waktu_absen_formatted'] }}');
                win.document.write('</div>');
                win.document.write('<div class="info-item">');
                win.document.write('<strong>Waktu Batas:</strong><br>{{ Carbon\Carbon::parse($scanResult['waktu_batas'])->format('H:i') }} WITA');
                win.document.write('</div>');
                if (typeText === 'Check-out' && totalWaktu) {
                    win.document.write('<div class="total-waktu">');
                    win.document.write('<strong><i class="fas fa-clock"></i> Total Waktu Kerja:</strong><br>' + totalWaktu + ' (' + totalWaktuStr + ')');
                    win.document.write('</div>');
                }
                win.document.write('<div style="margin-top:25px; font-size:0.85rem; color:var(--text-muted);">');
                win.document.write('<p>Badan Pusat Statistik Provinsi Sulawesi Tenggara</p>');
                win.document.write('</div>');
                win.document.write('</div>');
                win.document.write('</body></html>');
                win.document.close();
                win.print();
            @endif
        }
    </script>
@endsection