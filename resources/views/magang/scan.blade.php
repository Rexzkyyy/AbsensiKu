@extends('layouts.app')

@section('title', 'Scan QR Code')
@section('header_title', 'Scan QR')

@section('styles')
    <style>
        main:has(#fullscreen-scanner.active) {
            padding: 0 !important;
        }

        #fullscreen-scanner {
            background: #020617;
            color: #ffffff;
            display: none;
            flex-direction: column;
            inset: 0;
            min-height: 100dvh;
            overflow: hidden;
            position: fixed;
            z-index: 9999;
        }

        #fullscreen-scanner.active {
            display: flex;
        }

        #camera-video,
        #reader,
        #reader video {
            background: #020617;
            height: 100% !important;
            inset: 0;
            object-fit: cover !important;
            position: absolute;
            width: 100% !important;
        }

        #reader {
            border: 0 !important;
            overflow: hidden;
        }

        #reader__dashboard,
        #reader__header_message,
        #reader__scan_region img,
        .html5-qrcode-element {
            display: none !important;
        }

        .scanner-shade {
            background:
                linear-gradient(180deg, rgba(2, 6, 23, 0.82) 0%, rgba(2, 6, 23, 0) 26%),
                linear-gradient(0deg, rgba(2, 6, 23, 0.88) 0%, rgba(2, 6, 23, 0) 34%);
            inset: 0;
            pointer-events: none;
            position: absolute;
            z-index: 2;
        }

        .scanner-top-bar {
            align-items: center;
            display: flex;
            gap: 12px;
            justify-content: space-between;
            left: 0;
            padding: max(16px, env(safe-area-inset-top)) 18px 14px;
            position: absolute;
            right: 0;
            top: 0;
            z-index: 5;
        }

        .scanner-icon-btn {
            align-items: center;
            background: rgba(15, 23, 42, 0.58);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            color: #ffffff;
            display: inline-flex;
            height: 44px;
            justify-content: center;
            transition: background 0.18s ease, transform 0.18s ease;
            width: 44px;
        }

        .scanner-icon-btn:hover {
            background: rgba(30, 41, 59, 0.78);
            transform: translateY(-1px);
        }

        .scanner-badge {
            align-items: center;
            background: rgba(15, 23, 42, 0.64);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            display: inline-flex;
            font-size: 0.78rem;
            font-weight: 800;
            gap: 8px;
            letter-spacing: 0.08em;
            padding: 10px 16px;
            text-transform: uppercase;
        }

        .scan-target {
            align-items: center;
            display: flex;
            inset: 0;
            justify-content: center;
            pointer-events: none;
            position: absolute;
            z-index: 3;
        }

        .scan-frame {
            aspect-ratio: 1;
            border: 2px solid rgba(255, 255, 255, 0.72);
            border-radius: 22px;
            box-shadow: 0 0 0 999px rgba(2, 6, 23, 0.32);
            max-width: 390px;
            position: relative;
            width: min(76vw, 42vh);
        }

        .scan-frame::before,
        .scan-frame::after {
            background: linear-gradient(90deg, transparent, #ffffff, #38bdf8, #ffffff, transparent);
            border-radius: 999px;
            content: "";
            height: 3px;
            left: 9%;
            position: absolute;
            width: 82%;
        }

        .scan-frame::before {
            animation: scanLine 1.45s ease-in-out infinite;
            box-shadow: 0 0 22px rgba(56, 189, 248, 0.64);
        }

        .scan-frame::after {
            bottom: 50%;
            opacity: 0.25;
        }

        @keyframes scanLine {
            0% {
                top: 10%;
            }

            50% {
                top: 88%;
            }

            100% {
                top: 10%;
            }
        }

        .corner {
            border-color: #ffffff;
            border-radius: 8px;
            border-style: solid;
            border-width: 0;
            height: 38px;
            position: absolute;
            width: 38px;
        }

        .corner.tl {
            border-left-width: 5px;
            border-top-width: 5px;
            left: -4px;
            top: -4px;
        }

        .corner.tr {
            border-right-width: 5px;
            border-top-width: 5px;
            right: -4px;
            top: -4px;
        }

        .corner.bl {
            border-bottom-width: 5px;
            border-left-width: 5px;
            bottom: -4px;
            left: -4px;
        }

        .corner.br {
            border-bottom-width: 5px;
            border-right-width: 5px;
            bottom: -4px;
            right: -4px;
        }

        .scanner-bottom {
            align-items: center;
            bottom: 0;
            display: flex;
            flex-direction: column;
            gap: 14px;
            left: 0;
            padding: 20px 18px max(22px, env(safe-area-inset-bottom));
            position: absolute;
            right: 0;
            z-index: 5;
        }

        .type-toggle {
            background: rgba(15, 23, 42, 0.68);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 999px;
            display: grid;
            gap: 6px;
            grid-template-columns: 1fr 1fr;
            max-width: 430px;
            padding: 6px;
            width: 100%;
        }

        .type-item {
            align-items: center;
            background: transparent;
            border: 0;
            border-radius: 999px;
            color: rgba(255, 255, 255, 0.7);
            display: inline-flex;
            font-size: 0.9rem;
            font-weight: 800;
            gap: 8px;
            justify-content: center;
            min-height: 46px;
            padding: 10px 12px;
            transition: background 0.18s ease, color 0.18s ease;
        }

        .type-item.active {
            background: #ffffff;
            color: #0f172a;
        }

        .scanner-status {
            background: rgba(15, 23, 42, 0.58);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 999px;
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.82rem;
            font-weight: 600;
            padding: 8px 14px;
        }

        .result-backdrop {
            align-items: center;
            background: rgba(2, 6, 23, 0.76);
            display: flex;
            inset: 0;
            justify-content: center;
            padding: 16px;
            position: fixed;
            z-index: 10000;
        }

        .result-card {
            background: #ffffff;
            border-radius: 22px;
            box-shadow: 0 24px 70px rgba(2, 6, 23, 0.26);
            color: #0f172a;
            max-height: min(720px, calc(100dvh - 32px));
            max-width: 520px;
            overflow-y: auto;
            padding: 24px;
            width: 100%;
        }

        .result-icon {
            align-items: center;
            border-radius: 999px;
            display: flex;
            font-size: 1.8rem;
            height: 68px;
            justify-content: center;
            margin: 0 auto 14px;
            width: 68px;
        }

        .result-icon.success {
            background: #dcfce7;
            color: #16a34a;
        }

        .result-icon.error {
            background: #fee2e2;
            color: #dc2626;
        }

        .info-row {
            align-items: start;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            gap: 16px;
            justify-content: space-between;
            padding: 12px 0;
        }

        .info-label {
            color: #64748b;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .info-value {
            color: #0f172a;
            font-weight: 800;
            text-align: right;
            word-break: break-word;
        }

        .status-badge {
            border-radius: 999px;
            display: inline-flex;
            font-size: 0.74rem;
            font-weight: 900;
            letter-spacing: 0.04em;
            padding: 6px 12px;
            text-transform: uppercase;
        }

        .status-hadir {
            background: #dcfce7;
            color: #15803d;
        }

        .status-terlambat {
            background: #fef3c7;
            color: #b45309;
        }

        .status-pulang {
            background: #e0f2fe;
            color: #0369a1;
        }

        .scan-action {
            align-items: center;
            border: 0;
            border-radius: 999px;
            display: inline-flex;
            font-weight: 800;
            gap: 8px;
            justify-content: center;
            min-height: 44px;
            padding: 11px 18px;
        }

        .scan-action.primary {
            background: #2563eb;
            color: #ffffff;
        }

        .scan-action.secondary {
            background: #e2e8f0;
            color: #0f172a;
        }

        .confetti-container {
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            position: fixed;
            z-index: 10001;
        }

        .confetti {
            animation: confettiFall linear forwards;
            border-radius: 3px;
            height: 9px;
            position: absolute;
            width: 9px;
        }

        @keyframes confettiFall {
            from {
                opacity: 1;
                transform: translateY(-8vh) rotate(0deg);
            }

            to {
                opacity: 0;
                transform: translateY(108vh) rotate(540deg);
            }
        }

        @media (max-width: 640px) {
            .scanner-badge {
                font-size: 0.7rem;
                padding: 9px 12px;
            }

            .scan-frame {
                width: min(78vw, 36vh);
            }

            .result-card {
                border-radius: 18px;
                padding: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <section class="mx-auto flex min-h-[60vh] w-full max-w-2xl flex-col items-center justify-center text-center">
        <div class="rounded-full bg-blue-50 p-5 text-blue-600 shadow-sm">
            <i class="fas fa-qrcode text-4xl"></i>
        </div>
        <h2 class="mt-5 text-2xl font-extrabold text-slate-900">Scanner QR Absensi</h2>
        <p class="mt-2 max-w-md text-sm leading-6 text-slate-500">
            Kamera akan dibuka full layar agar QR lebih mudah terbaca.
        </p>
        <button type="button" onclick="startScanner()" class="scan-action primary mt-6">
            <i class="fas fa-camera"></i>
            Buka Scanner
        </button>
    </section>

    <div id="fullscreen-scanner" class="active" aria-live="polite">
        <video id="camera-video" class="hidden" playsinline muted autoplay></video>
        <div id="reader" class="hidden"></div>
        <div class="scanner-shade"></div>

        <div class="scanner-top-bar">
            <button type="button" onclick="stopScanner()" class="scanner-icon-btn" aria-label="Tutup scanner">
                <i class="fas fa-times"></i>
            </button>
            <div class="scanner-badge">
                <i class="fas fa-qrcode text-sky-300"></i>
                <span>Scan <span id="overlay-scan-type">Check-in</span></span>
            </div>
            <button type="button" onclick="restartScanner()" class="scanner-icon-btn" aria-label="Mulai ulang scanner">
                <i class="fas fa-rotate-right"></i>
            </button>
        </div>

        <div class="scan-target">
            <div class="scan-frame">
                <span class="corner tl"></span>
                <span class="corner tr"></span>
                <span class="corner bl"></span>
                <span class="corner br"></span>
            </div>
        </div>

        <div class="scanner-bottom">
            <div class="type-toggle">
                <button type="button" class="type-item active" id="type-checkin-btn" onclick="selectAbsensiType('check_in')">
                    <i class="fas fa-sign-in-alt"></i>
                    Check-in
                </button>
                <button type="button" class="type-item" id="type-checkout-btn" onclick="selectAbsensiType('check_out')">
                    <i class="fas fa-sign-out-alt"></i>
                    Check-out
                </button>
            </div>
            <div id="scanner-status" class="scanner-status">Menyiapkan kamera...</div>
        </div>
    </div>

    <div id="scan-result-container" class="result-backdrop hidden" onclick="if(event.target===this) closeResult()">
        <div id="scan-result-content" class="result-card"></div>
    </div>

    <div id="confetti-container" class="confetti-container hidden"></div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const scanEndpoint = @json(route('magang.scan.process'));
        const csrfToken = @json(csrf_token());

        let currentAbsensiType = 'check_in';
        let html5QrcodeScanner = null;
        let nativeStream = null;
        let nativeDetector = null;
        let nativeVideo = null;
        let scanLoopId = null;
        let scannerMode = null;
        let isProcessing = false;
        let isScannerRunning = false;
        let detectPending = false;
        let lastDetectTime = 0;

        document.addEventListener('DOMContentLoaded', () => {
            nativeVideo = document.getElementById('camera-video');
            startScanner();
        });

        function selectAbsensiType(type) {
            currentAbsensiType = type;
            document.getElementById('type-checkin-btn').classList.toggle('active', type === 'check_in');
            document.getElementById('type-checkout-btn').classList.toggle('active', type === 'check_out');
            document.getElementById('overlay-scan-type').textContent = type === 'check_in' ? 'Check-in' : 'Check-out';
        }

        async function startScanner() {
            if (isScannerRunning || isProcessing) {
                return;
            }

            document.getElementById('fullscreen-scanner').classList.add('active');
            setScannerStatus('Menyiapkan kamera...');

            try {
                if (await supportsNativeQrScanner()) {
                    await startNativeScanner();
                    return;
                }
            } catch (error) {
                console.warn('Native scanner gagal, memakai fallback.', error);
            }

            await startHtml5Scanner();
        }

        async function supportsNativeQrScanner() {
            if (!('BarcodeDetector' in window) || !navigator.mediaDevices?.getUserMedia) {
                return false;
            }

            if (!BarcodeDetector.getSupportedFormats) {
                return true;
            }

            const formats = await BarcodeDetector.getSupportedFormats();
            return formats.includes('qr_code');
        }

        async function startNativeScanner() {
            scannerMode = 'native';
            nativeDetector = new BarcodeDetector({ formats: ['qr_code'] });
            nativeStream = await navigator.mediaDevices.getUserMedia({
                audio: false,
                video: {
                    facingMode: { ideal: 'environment' },
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    frameRate: { ideal: 30, max: 30 }
                }
            });

            document.getElementById('reader').classList.add('hidden');
            nativeVideo.classList.remove('hidden');
            nativeVideo.srcObject = nativeStream;
            await nativeVideo.play();

            isScannerRunning = true;
            setScannerStatus('Kamera aktif - arahkan ke QR Code');
            detectNativeFrame(performance.now());
        }

        function detectNativeFrame(timestamp) {
            if (!isScannerRunning || scannerMode !== 'native') {
                return;
            }

            if (!detectPending && timestamp - lastDetectTime > 60 && nativeVideo.readyState >= 2) {
                detectPending = true;
                lastDetectTime = timestamp;

                nativeDetector.detect(nativeVideo)
                    .then(codes => {
                        if (codes.length > 0) {
                            onScanSuccess(codes[0].rawValue || codes[0].rawData);
                        }
                    })
                    .catch(error => console.warn('Deteksi QR gagal:', error))
                    .finally(() => {
                        detectPending = false;
                    });
            }

            scanLoopId = requestAnimationFrame(detectNativeFrame);
        }

        async function startHtml5Scanner() {
            if (!window.Html5Qrcode) {
                showCameraError('Library scanner belum termuat. Periksa koneksi lalu coba lagi.');
                return;
            }

            scannerMode = 'html5';
            document.getElementById('reader').classList.remove('hidden');
            nativeVideo.classList.add('hidden');

            html5QrcodeScanner = new Html5Qrcode('reader', false);
            const config = {
                fps: 24,
                disableFlip: true,
                qrbox: (viewfinderWidth, viewfinderHeight) => {
                    const size = Math.floor(Math.min(viewfinderWidth, viewfinderHeight) * 0.72);
                    return {
                        width: Math.min(size, 380),
                        height: Math.min(size, 380)
                    };
                }
            };

            try {
                await html5QrcodeScanner.start({ facingMode: 'environment' }, config, onScanSuccess, () => {});
                isScannerRunning = true;
                setScannerStatus('Kamera aktif - arahkan ke QR Code');
            } catch (error) {
                console.error('Gagal mengakses kamera:', error);
                showCameraError('Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan.');
            }
        }

        async function restartScanner() {
            await stopScanner(false);
            isProcessing = false;
            startScanner();
        }

        async function stopScanner(closeOverlay = true) {
            isScannerRunning = false;
            detectPending = false;

            if (scanLoopId) {
                cancelAnimationFrame(scanLoopId);
                scanLoopId = null;
            }

            if (nativeStream) {
                nativeStream.getTracks().forEach(track => track.stop());
                nativeStream = null;
            }

            if (nativeVideo) {
                nativeVideo.pause();
                nativeVideo.srcObject = null;
                nativeVideo.classList.add('hidden');
            }

            if (html5QrcodeScanner) {
                try {
                    await html5QrcodeScanner.stop();
                    await html5QrcodeScanner.clear();
                } catch (error) {
                    console.warn('Scanner fallback sudah berhenti:', error);
                }
                html5QrcodeScanner = null;
            }

            scannerMode = null;

            if (closeOverlay) {
                document.getElementById('fullscreen-scanner').classList.remove('active');
            }
        }

        async function onScanSuccess(decodedText) {
            if (isProcessing || !decodedText) {
                return;
            }

            isProcessing = true;
            setScannerStatus('QR ditemukan, memproses...');
            await stopScanner(false);
            showScanResult(decodedText);
        }

        function showScanResult(qrCode) {
            const container = document.getElementById('scan-result-container');
            const content = document.getElementById('scan-result-content');
            const typeText = currentAbsensiType === 'check_in' ? 'Check-in' : 'Check-out';

            content.innerHTML = `
                <div class="py-8 text-center">
                    <div class="mx-auto h-12 w-12 animate-spin rounded-full border-4 border-blue-100 border-t-blue-600"></div>
                    <p class="mt-4 font-semibold text-slate-600">Memproses ${typeText}...</p>
                </div>
            `;
            container.classList.remove('hidden');

            fetch(scanEndpoint, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    qr_code: qrCode,
                    absensi_type: currentAbsensiType
                })
            })
                .then(async response => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal memproses absensi.');
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        renderSuccessResult(data, qrCode);
                        triggerConfetti();
                    } else {
                        renderErrorResult(data.message || 'Terjadi kesalahan.');
                    }
                })
                .catch(error => {
                    renderErrorResult(error.message || 'Gagal memproses absensi. Silakan coba lagi.');
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
            const totalWaktuHtml = data.type === 'check_out' && data.total_waktu_formatted ? `
                <div class="info-row">
                    <span class="info-label">Total Waktu Kerja</span>
                    <span class="info-value text-amber-600">${escapeHtml(data.total_waktu_formatted)}</span>
                </div>
            ` : '';

            content.innerHTML = `
                <div class="result-icon success">
                    <i class="fas fa-check"></i>
                </div>
                <h2 class="text-center text-2xl font-black">${typeText} Berhasil</h2>
                <p class="mb-5 mt-1 text-center text-sm font-medium text-slate-500">Data absensi telah tercatat</p>

                <div>
                    <div class="info-row">
                        <span class="info-label">Kode QR</span>
                        <span class="info-value font-mono">${escapeHtml(qrCode)}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kegiatan</span>
                        <span class="info-value">${escapeHtml(data.nama_kegiatan || '-')}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Hari</span>
                        <span class="info-value">${escapeHtml(data.hari_absen || '-')}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Waktu ${typeText}</span>
                        <span class="info-value text-blue-600">${escapeHtml(data.waktu_absen_formatted || '-')}</span>
                    </div>
                    ${totalWaktuHtml}
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value"><span class="status-badge ${statusClass}">${statusLabel}</span></span>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <button type="button" onclick="closeResult()" class="scan-action secondary">
                        <i class="fas fa-redo"></i>
                        Scan Lagi
                    </button>
                    <button type="button" onclick="printAttendanceFromData()" class="scan-action primary">
                        <i class="fas fa-print"></i>
                        Cetak Bukti
                    </button>
                </div>
            `;
        }

        function renderErrorResult(message) {
            const content = document.getElementById('scan-result-content');
            content.innerHTML = `
                <div class="py-4 text-center">
                    <div class="result-icon error">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="text-xl font-black">Scan Gagal</h3>
                    <p class="mx-auto mt-2 max-w-sm text-sm font-medium leading-6 text-slate-500">${escapeHtml(message)}</p>
                    <button type="button" onclick="closeResult()" class="scan-action primary mt-6">
                        <i class="fas fa-rotate-right"></i>
                        Coba Lagi
                    </button>
                </div>
            `;
        }

        function closeResult() {
            document.getElementById('scan-result-container').classList.add('hidden');
            clearConfetti();
            startScanner();
        }

        function setScannerStatus(message) {
            document.getElementById('scanner-status').textContent = message;
        }

        function showCameraError(message) {
            setScannerStatus(message);
            renderErrorResult(message);
            document.getElementById('scan-result-container').classList.remove('hidden');
        }

        function triggerConfetti() {
            const container = document.getElementById('confetti-container');
            const colors = ['#2563eb', '#16a34a', '#f59e0b', '#db2777', '#0ea5e9'];
            clearConfetti();
            container.classList.remove('hidden');

            for (let index = 0; index < 52; index++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = `${Math.random() * 100}%`;
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDuration = `${Math.random() * 1.2 + 1.8}s`;
                confetti.style.animationDelay = `${Math.random() * 0.45}s`;
                container.appendChild(confetti);
            }

            setTimeout(clearConfetti, 2600);
        }

        function clearConfetti() {
            const container = document.getElementById('confetti-container');
            container.classList.add('hidden');
            container.innerHTML = '';
        }

        function printAttendanceFromData() {
            const content = document.getElementById('scan-result-content');
            const rows = content.querySelectorAll('.info-row');
            const printData = {};

            rows.forEach(row => {
                const label = row.querySelector('.info-label')?.textContent.trim();
                const value = row.querySelector('.info-value')?.textContent.trim();
                if (label && value) {
                    printData[label] = value;
                }
            });

            const title = content.querySelector('h2')?.textContent.trim() || 'Bukti Absensi';
            const statusBadge = content.querySelector('.status-badge')?.textContent.trim() || '';
            const printWindow = window.open('', '_blank', 'height=640,width=520');

            if (!printWindow) {
                renderErrorResult('Popup cetak diblokir browser. Izinkan popup lalu coba lagi.');
                return;
            }

            printWindow.document.write('<html><head><title>Bukti Absensi</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body{font-family:Arial,sans-serif;background:#f8fafc;color:#0f172a;margin:0;padding:28px;text-align:center;}');
            printWindow.document.write('.container{background:#fff;border:1px solid #e2e8f0;border-radius:18px;margin:auto;max-width:420px;padding:28px;}');
            printWindow.document.write('h2{color:#2563eb;margin:0 0 6px;font-size:1.45rem;}h3{font-size:1rem;margin:0 0 18px;color:#334155;}');
            printWindow.document.write('.status{background:#16a34a;border-radius:999px;color:#fff;display:inline-block;font-weight:800;margin:0 0 16px;padding:8px 14px;}');
            printWindow.document.write('.info-item{align-items:center;border-bottom:1px solid #e2e8f0;display:flex;gap:16px;justify-content:space-between;padding:10px 0;text-align:left;}');
            printWindow.document.write('.info-label{color:#64748b;font-size:.78rem;font-weight:800;text-transform:uppercase}.info-value{font-weight:800;text-align:right;}');
            printWindow.document.write('</style></head><body><div class="container">');
            printWindow.document.write(`<h2>${escapeHtml(title)}</h2>`);
            printWindow.document.write(`<h3>${escapeHtml(printData.Kegiatan || '')}</h3>`);
            if (statusBadge) {
                printWindow.document.write(`<div class="status">${escapeHtml(statusBadge)}</div>`);
            }
            Object.entries(printData).forEach(([key, value]) => {
                if (key !== 'Kegiatan' && key !== 'Status') {
                    printWindow.document.write(`<div class="info-item"><span class="info-label">${escapeHtml(key)}</span><span class="info-value">${escapeHtml(value)}</span></div>`);
                }
            });
            printWindow.document.write('<p style="color:#94a3b8;font-size:.75rem;margin-top:24px;">Dokumen sah dari Sistem Presensi Digital</p>');
            printWindow.document.write('</div></body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    </script>
@endsection
