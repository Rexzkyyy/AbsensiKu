@extends('layouts.app')

@section('title', 'Scan QR Code')
@section('header_title', 'Scan QR')

@section('styles')
    <style>
        /* Reset style scanner */
        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        /* Fullscreen scanner overlay */
        #fullscreen-scanner {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #0b1120;
            display: none;
            flex-direction: column;
        }

        #fullscreen-scanner.active {
            display: flex;
        }

        /* Scanner frame - lebih besar & putih terang */
        .scan-frame {
            position: relative;
            width: 340px;
            height: 340px;
            border-radius: 28px;
            box-shadow: 0 0 0 100vmax rgba(0, 0, 0, 0.75),
                0 0 40px rgba(255, 255, 255, 0.15),
                0 0 80px rgba(56, 189, 248, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.03);
        }

        .scan-frame .corner {
            position: absolute;
            width: 34px;
            height: 34px;
            border: 4px solid rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }

        .scan-frame .corner.tl {
            top: -4px;
            left: -4px;
            border-right: none;
            border-bottom: none;
        }

        .scan-frame .corner.tr {
            top: -4px;
            right: -4px;
            border-left: none;
            border-bottom: none;
        }

        .scan-frame .corner.bl {
            bottom: -4px;
            left: -4px;
            border-right: none;
            border-top: none;
        }

        .scan-frame .corner.br {
            bottom: -4px;
            right: -4px;
            border-left: none;
            border-top: none;
        }

        /* Garis scan animasi - putih dengan glow */
        .scan-line {
            position: absolute;
            left: 8%;
            width: 84%;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.9), #38bdf8, rgba(255, 255, 255, 0.9), transparent);
            border-radius: 2px;
            box-shadow: 0 0 30px rgba(56, 189, 248, 0.6), 0 0 60px rgba(255, 255, 255, 0.2);
            animation: scanMove 2.5s ease-in-out infinite;
        }

        @keyframes scanMove {
            0% {
                top: 10%;
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            50% {
                top: 90%;
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                top: 10%;
                opacity: 0;
            }
        }

        /* Tombol kontrol atas */
        .scanner-top-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 20px 24px;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 20;
        }

        .scanner-top-bar .badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 8px 18px;
            border-radius: 40px;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.1em;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .scanner-top-bar .badge i {
            margin-right: 8px;
            color: #38bdf8;
        }

        /* Card hasil scan */
        .result-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 32px;
            padding: 28px 24px;
            max-width: 480px;
            margin: 0 auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .result-card .icon-success {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #34d399, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
            color: white;
            box-shadow: 0 10px 25px -5px rgba(52, 211, 153, 0.4);
        }

        .result-card .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .result-card .info-row:last-child {
            border-bottom: none;
        }

        .result-card .info-label {
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
            font-size: 0.85rem;
        }

        .result-card .info-value {
            color: white;
            font-weight: 700;
            text-align: right;
        }

        .result-card .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-hadir {
            background: rgba(52, 211, 153, 0.25);
            color: #34d399;
        }

        .status-terlambat {
            background: rgba(251, 191, 36, 0.25);
            color: #fbbf24;
        }

        .status-pulang {
            background: rgba(56, 189, 248, 0.25);
            color: #38bdf8;
        }

        /* Tombol aksi */
        .btn-ghost {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            padding: 12px 24px;
            border-radius: 60px;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.16);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border: none;
            color: white;
            padding: 12px 32px;
            border-radius: 60px;
            font-weight: 700;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 20px -6px rgba(59, 130, 246, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -6px rgba(59, 130, 246, 0.5);
        }

        /* Pilihan tipe absensi (toggle) */
        .type-toggle {
            display: flex;
            gap: 12px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 60px;
            padding: 6px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .type-toggle .type-item {
            flex: 1;
            padding: 12px 20px;
            border-radius: 40px;
            text-align: center;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.95rem;
            border: none;
            background: transparent;
        }

        .type-toggle .type-item.active {
            background: rgba(59, 130, 246, 0.25);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .type-toggle .type-item i {
            font-size: 1.2rem;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .scan-frame {
                width: 260px;
                height: 260px;
            }

            .result-card {
                padding: 20px;
            }

            .type-toggle .type-item {
                font-size: 0.8rem;
                padding: 10px 14px;
            }
        }

        /* Animasi fade-in */
        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Confetti */
        .confetti-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 10000;
            overflow: hidden;
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 2px;
            animation: confettiFall linear forwards;
        }

        @keyframes confettiFall {
            0% {
                transform: translateY(-10vh) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(110vh) rotate(720deg);
                opacity: 0;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Fullscreen Scanner (aktif otomatis) -->
    <div id="fullscreen-scanner" class="active">
        <!-- Top Bar -->
        <div class="scanner-top-bar">
            <button onclick="stopScanner()" class="text-white/70 hover:text-white text-2xl transition">
                <i class="fas fa-times"></i>
            </button>
            <div class="badge">
                <i class="fas fa-qrcode"></i> SCAN <span id="overlay-scan-type">CHECK-IN</span>
            </div>
            <div style="width: 28px;"></div>
        </div>

        <!-- Camera Feed -->
        <div id="reader" class="absolute inset-0 z-0"></div>

        <!-- Scanner Frame Overlay -->
        <div class="absolute inset-0 z-10 flex items-center justify-center pointer-events-none">
            <div class="scan-frame">
                <div class="corner tl"></div>
                <div class="corner tr"></div>
                <div class="corner bl"></div>
                <div class="corner br"></div>
                <div class="scan-line"></div>
            </div>
        </div>

        <!-- Bottom: Pilihan tipe (manual input dihapus) -->
        <div class="absolute bottom-10 left-0 right-0 z-20 px-4 flex flex-col items-center gap-4">
            <!-- Type Toggle -->
            <div class="type-toggle w-full max-w-sm">
                <button class="type-item active" id="type-checkin-btn" onclick="selectAbsensiType('check_in')">
                    <i class="fas fa-sign-in-alt"></i> Check-in
                </button>
                <button class="type-item" id="type-checkout-btn" onclick="selectAbsensiType('check_out')">
                    <i class="fas fa-sign-out-alt"></i> Check-out
                </button>
            </div>

            <p class="text-white/50 text-sm font-light tracking-wide">Arahkan kamera ke QR Code kegiatan</p>
        </div>
    </div>

    <!-- Tempat untuk menampilkan hasil scan -->
    <div id="scan-result-container"
        class="fixed inset-0 z-[10000] bg-black/70 backdrop-blur-md flex items-center justify-center p-4 hidden"
        onclick="if(event.target===this) closeResult()">
        <div id="scan-result-content" class="result-card fade-in w-full max-w-lg">
            <!-- Akan diisi oleh JavaScript -->
        </div>
    </div>

    <!-- Konfeti container -->
    <div id="confetti-container" class="confetti-container hidden"></div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let html5QrcodeScanner = null;
        let currentAbsensiType = 'check_in';
        let isProcessing = false;

        // Fungsi pilih tipe
        function selectAbsensiType(type) {
            currentAbsensiType = type;
            document.getElementById('absensi_type').value = type;
            const btnIn = document.getElementById('type-checkin-btn');
            const btnOut = document.getElementById('type-checkout-btn');
            if (type === 'check_in') {
                btnIn.classList.add('active');
                btnOut.classList.remove('active');
                document.getElementById('overlay-scan-type').textContent = 'CHECK-IN';
            } else {
                btnOut.classList.add('active');
                btnIn.classList.remove('active');
                document.getElementById('overlay-scan-type').textContent = 'CHECK-OUT';
            }
        }

        // Inisialisasi scanner saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function () {
            startScanner();
        });

        function startScanner() {
            const scannerEl = document.getElementById('reader');
            if (!scannerEl) return;

            html5QrcodeScanner = new Html5Qrcode("reader");

            // Ukuran qrbox disesuaikan dengan frame (lebih besar)
            const qrboxSize = window.innerWidth < 640 ? 220 : 300;
            const config = {
                fps: 20,
                qrbox: { width: qrboxSize, height: qrboxSize },
                aspectRatio: window.innerWidth / window.innerHeight
            };

            html5QrcodeScanner.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error("Gagal mengakses kamera:", err);
                alert("Tidak dapat mengakses kamera. Pastikan Anda memberikan izin akses kamera.");
                document.getElementById('fullscreen-scanner').classList.remove('active');
            });
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    closeFullscreen();
                }).catch(err => {
                    console.error("Gagal stop scanner", err);
                    closeFullscreen();
                });
            } else {
                closeFullscreen();
            }
        }

        function closeFullscreen() {
            document.getElementById('fullscreen-scanner').classList.remove('active');
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;

            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().catch(() => { });
            }

            showScanResult(decodedText);
        }

        function onScanFailure(error) {
            // Abaikan
        }

        function showScanResult(qrCode) {
            const container = document.getElementById('scan-result-container');
            const content = document.getElementById('scan-result-content');
            const typeText = currentAbsensiType === 'check_in' ? 'Check-in' : 'Check-out';

            content.innerHTML = `
                    <div class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-t-transparent border-white/70"></div>
                        <p class="text-white/70 mt-4">Memproses ${typeText}...</p>
                    </div>
                `;
            container.classList.remove('hidden');

            fetch('{{ route('magang.scan.process') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    qr_code: qrCode,
                    absensi_type: currentAbsensiType
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderSuccessResult(data, qrCode);
                        triggerConfetti();
                    } else {
                        renderErrorResult(data.message || 'Terjadi kesalahan.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    renderErrorResult('Gagal memproses absensi. Silakan coba lagi.');
                })
                .finally(() => {
                    isProcessing = false;
                });
        }

        function renderSuccessResult(data, qrCode) {
            const content = document.getElementById('scan-result-content');
            const typeText = data.type === 'check_in' ? 'Check-in' : 'Check-out';
            const statusClass = data.status_absen === 'hadir' ? 'status-hadir' :
                (data.status_absen === 'terlambat' ? 'status-terlambat' : 'status-pulang');
            const statusLabel = data.status_absen === 'hadir' ? 'Hadir' :
                (data.status_absen === 'terlambat' ? 'Terlambat' : 'Pulang Cepat');

            let totalWaktuHtml = '';
            if (data.type === 'check_out' && data.total_waktu_formatted) {
                totalWaktuHtml = `
                        <div class="info-row">
                            <span class="info-label">Total Waktu Kerja</span>
                            <span class="info-value" style="color:#fbbf24;">${data.total_waktu_formatted}</span>
                        </div>
                    `;
            }

            content.innerHTML = `
                    <div class="icon-success">
                        <i class="fas fa-check"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-center text-white mb-1">${typeText} Berhasil!</h2>
                    <p class="text-white/60 text-center text-sm mb-4">Data absensi telah tercatat</p>

                    <div class="space-y-0">
                        <div class="info-row">
                            <span class="info-label">Kode QR</span>
                            <span class="info-value font-mono tracking-wider">${qrCode}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Kegiatan</span>
                            <span class="info-value">${data.nama_kegiatan}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Hari</span>
                            <span class="info-value">${data.hari_absen}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Waktu ${typeText}</span>
                            <span class="info-value" style="color:#38bdf8;">${data.waktu_absen_formatted}</span>
                        </div>
                        ${totalWaktuHtml}
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value"><span class="status-badge ${statusClass}">${statusLabel}</span></span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 justify-center mt-6">
                        <button onclick="closeResult()" class="btn-ghost">
                            <i class="fas fa-redo"></i> Scan Lagi
                        </button>
                        <button onclick="printAttendanceFromData('${qrCode}')" class="btn-primary">
                            <i class="fas fa-print"></i> Cetak Bukti
                        </button>
                    </div>
                `;
        }

        function renderErrorResult(message) {
            const content = document.getElementById('scan-result-content');
            content.innerHTML = `
                    <div class="text-center py-4">
                        <div class="w-16 h-16 mx-auto rounded-full bg-red-500/20 flex items-center justify-center text-red-400 text-3xl">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mt-4">Gagal</h3>
                        <p class="text-white/60 text-sm mt-1">${message}</p>
                        <button onclick="closeResult()" class="btn-primary mt-6">
                            <i class="fas fa-undo"></i> Coba Lagi
                        </button>
                    </div>
                `;
        }

        function closeResult() {
            document.getElementById('scan-result-container').classList.add('hidden');
            startScanner();
            document.getElementById('confetti-container').classList.add('hidden');
            document.getElementById('confetti-container').innerHTML = '';
        }

        function triggerConfetti() {
            const container = document.getElementById('confetti-container');
            container.classList.remove('hidden');
            container.innerHTML = '';
            const colors = ['#fbbf24', '#34d399', '#60a5fa', '#f472b6', '#a78bfa'];
            for (let i = 0; i < 120; i++) {
                const el = document.createElement('div');
                el.className = 'confetti';
                el.style.left = Math.random() * 100 + '%';
                el.style.background = colors[Math.floor(Math.random() * colors.length)];
                el.style.width = (Math.random() * 8 + 4) + 'px';
                el.style.height = (Math.random() * 8 + 4) + 'px';
                el.style.animationDuration = (Math.random() * 2 + 2) + 's';
                el.style.animationDelay = (Math.random() * 2) + 's';
                el.style.transform = `rotate(${Math.random() * 360}deg)`;
                container.appendChild(el);
            }
            setTimeout(() => {
                container.classList.add('hidden');
                container.innerHTML = '';
            }, 4000);
        }

        function printAttendanceFromData(qrCode) {
            const content = document.getElementById('scan-result-content');
            const infoRows = content.querySelectorAll('.info-row');
            let printData = {};
            infoRows.forEach(row => {
                const label = row.querySelector('.info-label')?.textContent.trim();
                const value = row.querySelector('.info-value')?.textContent.trim();
                if (label && value) printData[label] = value;
            });
            const title = content.querySelector('h2')?.textContent.trim() || 'Bukti Absensi';
            const statusBadge = content.querySelector('.status-badge')?.textContent.trim() || '';

            const win = window.open('', '_blank', 'height=600,width=500');
            win.document.write('<html><head><title>Bukti Absensi</title>');
            win.document.write('<style>');
            win.document.write('body{font-family:"Segoe UI",sans-serif;text-align:center;padding:30px;background:#f8fafc;}');
            win.document.write('.container{max-width:400px;margin:0 auto;background:white;padding:30px;border-radius:24px;box-shadow:0 10px 25px rgba(0,0,0,0.05);border:1px solid #f1f5f9;}');
            win.document.write('h2{color:#4361ee;margin-bottom:5px;font-weight:800;font-size:1.5rem;}');
            win.document.write('h3{color:#334155;margin-bottom:20px;font-size:1.1rem;font-weight:600;}');
            win.document.write('.info-item{text-align:left;margin:8px 0;padding:10px 14px;background:#f8fafc;border-radius:12px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;}');
            win.document.write('.info-label{color:#64748b;font-weight:600;font-size:0.8rem;text-transform:uppercase;}');
            win.document.write('.info-value{color:#1e293b;font-weight:700;}');
            win.document.write('.status{background:#10b981;color:white;padding:8px;border-radius:12px;margin:16px 0;font-weight:700;}');
            win.document.write('</style>');
            win.document.write('</head><body>');
            win.document.write('<div class="container">');
            win.document.write(`<h2>${title}</h2>`);
            win.document.write(`<h3>${printData['Kegiatan'] || ''}</h3>`);
            if (statusBadge) win.document.write(`<div class="status">${statusBadge}</div>`);
            for (const [key, val] of Object.entries(printData)) {
                if (key !== 'Kegiatan' && key !== 'Status') {
                    win.document.write(`<div class="info-item"><span class="info-label">${key}</span><span class="info-value">${val}</span></div>`);
                }
            }
            win.document.write('<div style="margin-top:30px;font-size:0.75rem;color:#94a3b8;">Dokumen sah dari Sistem Presensi Digital</div>');
            win.document.write('</div></body></html>');
            win.document.close();
            win.print();
        }

        // Override selectAbsensiType untuk update badge
        window.selectAbsensiType = function (type) {
            currentAbsensiType = type;
            document.getElementById('absensi_type').value = type;
            const btnIn = document.getElementById('type-checkin-btn');
            const btnOut = document.getElementById('type-checkout-btn');
            if (type === 'check_in') {
                btnIn.classList.add('active');
                btnOut.classList.remove('active');
                document.getElementById('overlay-scan-type').textContent = 'CHECK-IN';
            } else {
                btnOut.classList.add('active');
                btnIn.classList.remove('active');
                document.getElementById('overlay-scan-type').textContent = 'CHECK-OUT';
            }
        };
    </script>
@endsection