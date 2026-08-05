@extends('layouts.app')

@section('title', 'Scan QR Code')
@section('header_title', 'Scan QR')

@section('styles')
    <style>
        /* Smooth Video Feed Scaling */
        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }
        /* Custom scanner overlay frame */
        .scan-overlay-frame {
            box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.6);
            border: 2px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px;
        }
        .scan-corner {
            width: 30px;
            height: 30px;
            border: 4px solid #3b82f6;
            position: absolute;
            border-radius: 4px;
        }
    </style>
@endsection

@section('content')
    <!-- Time Display Overlay -->
    <div class="bg-gray-900/60 backdrop-blur-md border border-gray-700 shadow-lg rounded-2xl p-4 mb-6 text-center text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-500 via-secondary-500 to-emerald-500"></div>
        <div class="font-bold text-lg tracking-wide">{{ Carbon\Carbon::now('Asia/Makassar')->isoFormat('dddd, D MMMM Y') }}</div>
    </div>

    <!-- Pilihan Tipe Absensi -->
    <div class="bg-white/70 backdrop-blur-xl border border-white/60 p-4 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] mb-6 flex gap-3">
        <div class="flex-1 text-center p-4 border-2 border-primary-500 bg-primary-50 text-primary-700 rounded-xl cursor-pointer transition-all hover:bg-primary-50" id="type-checkin-btn" onclick="selectAbsensiType('check_in')">
            <i class="fas fa-sign-in-alt text-2xl mb-2"></i>
            <div class="font-bold text-sm">Check-in</div>
        </div>
        <div class="flex-1 text-center p-4 border-2 border-transparent text-gray-400 rounded-xl cursor-pointer transition-all hover:bg-gray-50" id="type-checkout-btn" onclick="selectAbsensiType('check_out')">
            <i class="fas fa-sign-out-alt text-2xl mb-2"></i>
            <div class="font-bold text-sm">Check-out</div>
        </div>
    </div>

    <!-- Scanner Area -->
    <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 overflow-hidden mb-6 p-6 md:p-8">
        <div class="text-center">
            
            <div id="scanner-result" class="hidden mb-6">
                <!-- Result will be injected here -->
            </div>

            <!-- Scanner Box (Desktop / Initial View) -->
            <div id="scanner-container" class="w-full max-w-lg h-[380px] bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl mx-auto flex flex-col items-center justify-center transition-all relative overflow-hidden group hover:border-blue-400 hover:bg-blue-50/30">
                <i class="fas fa-qrcode text-6xl text-blue-400 mb-4 opacity-80 group-hover:scale-110 transition-transform"></i>
                <p class="text-lg text-slate-500 mb-6 font-medium">Kamera siap untuk scan QR Code</p>
                <button onclick="startScanner()" class="bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-bold py-3 px-8 rounded-2xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 flex items-center justify-center gap-2 hover:-translate-y-1">
                    <i class="fas fa-camera"></i> Mulai Scan Layar Penuh
                </button>
            </div>

            <!-- Full Screen Scanner View (Hidden initially) -->
            <div id="fullscreen-scanner" class="fixed inset-0 z-[60] bg-black hidden flex-col">
                <!-- Top Bar -->
                <div class="absolute top-0 left-0 right-0 p-6 z-20 flex justify-between items-center bg-gradient-to-b from-black/80 to-transparent">
                    <button onclick="stopScanner()" class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-white/30 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                    <div class="px-4 py-2 bg-white/20 backdrop-blur-md rounded-full text-white font-bold text-sm tracking-wide">
                        SCAN <span id="overlay-scan-type" class="uppercase text-cyan-300"></span>
                    </div>
                </div>
                
                <!-- Camera Feed -->
                <div id="reader" class="w-full h-full absolute inset-0 z-0"></div>
                
                <!-- Scanner Frame Overlay -->
                <div class="absolute inset-0 z-10 flex items-center justify-center pointer-events-none">
                    <div class="w-64 h-64 md:w-80 md:h-80 relative scan-overlay-frame">
                        <div class="scan-corner" style="top: -2px; left: -2px; border-right: none; border-bottom: none;"></div>
                        <div class="scan-corner" style="top: -2px; right: -2px; border-left: none; border-bottom: none;"></div>
                        <div class="scan-corner" style="bottom: -2px; left: -2px; border-right: none; border-top: none;"></div>
                        <div class="scan-corner" style="bottom: -2px; right: -2px; border-left: none; border-top: none;"></div>
                        
                        <!-- Scanning Line Animation -->
                        <div class="w-full h-[2px] bg-cyan-400 absolute left-0 shadow-[0_0_8px_2px_rgba(34,211,238,0.8)] animate-[scan_2s_ease-in-out_infinite]"></div>
                    </div>
                </div>
                
                <!-- Bottom Help Text -->
                <div class="absolute bottom-10 left-0 right-0 text-center z-20 px-6">
                    <p class="text-white/80 font-medium text-sm md:text-base drop-shadow-md">Arahkan kamera ke QR Code kegiatan</p>
                </div>
                
                <style>
                    @keyframes scan {
                        0%, 100% { top: 10px; opacity: 0; }
                        10% { opacity: 1; }
                        50% { top: 50%; opacity: 1; }
                        90% { opacity: 1; }
                        100% { top: calc(100% - 10px); opacity: 0; }
                    }
                    /* Hide html5-qrcode extra elements */
                    #reader img, #reader span, #reader select { display: none !important; }
                    #reader__dashboard_section_csr span { color: white !important; }
                </style>
            </div>

            <p class="text-sm text-gray-400 mt-4 mb-8">Arahkan kamera ke QR Code kegiatan yang disediakan oleh Pembimbing atau Mentor.</p>

            <!-- Manual Input -->
            <div class="max-w-md mx-auto pt-6 border-t border-gray-100">
                <p class="text-sm font-semibold text-gray-700 mb-4">Kamera tidak berfungsi? Masukkan kode QR manual:</p>
                <form method="POST" action="{{ route('magang.scan.process') }}" class="flex flex-col gap-3">
                    @csrf
                    <input type="hidden" name="absensi_type" id="absensi_type" value="check_in">
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-keyboard text-gray-400"></i>
                        </div>
                        <input type="text" name="qr_code" 
                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800 text-center font-mono font-bold tracking-widest uppercase placeholder:normal-case placeholder:font-sans placeholder:font-normal placeholder:tracking-normal" 
                               placeholder="Contoh: QR-ABC123" required>
                    </div>
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                        <i class="fas fa-check"></i> Validasi Kode
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Hasil Absensi (Jika Baru Selesai Absen & Data Result Tersimpan di Session) -->
    @if ($scanResult)
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 overflow-hidden mb-8" id="bukti-absensi-card">
            <div class="px-6 py-5 border-b border-white/40 bg-white/30 flex items-center gap-2">
                <i class="fas fa-clipboard-check text-emerald-500 text-xl"></i>
                <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">Hasil Absensi</h3>
            </div>
            
            <div class="p-6 md:p-8 text-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 shadow-lg shadow-emerald-500/30 flex items-center justify-center text-white text-4xl mx-auto mb-6">
                    <i class="fas fa-check"></i>
                </div>
                
                <h2 class="text-2xl font-black text-emerald-600 mb-2">
                    {{ $scanResult['type'] === 'check_in' ? 'Check-in Berhasil!' : 'Check-out Berhasil!' }}
                </h2>
                <p class="text-gray-500 mb-8 font-medium">Data absensi Anda telah tercatat dengan baik.</p>

                <div class="bg-gray-50 rounded-2xl p-6 text-left border border-gray-100 shadow-inner mb-8 max-w-xl mx-auto divide-y divide-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 gap-1">
                        <span class="text-gray-500 font-medium text-sm">Kode QR:</span>
                        <span class="font-bold text-gray-800">{{ $scanResult['kode_qr'] }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 gap-1">
                        <span class="text-gray-500 font-medium text-sm">Kegiatan:</span>
                        <span class="font-bold text-gray-800">{{ $scanResult['nama_kegiatan'] }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 gap-1">
                        <span class="text-gray-500 font-medium text-sm">Hari Absen:</span>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-800">{{ $scanResult['hari_absen'] }}</span>
                            @if (isset($scanResult['waktu_khusus_hari']))
                                @if ($scanResult['waktu_khusus_hari'] === 'Jumat')
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-purple-100 text-purple-600 border border-purple-200">
                                        <i class="fas fa-star mr-1"></i> Jumat
                                    </span>
                                @elseif ($scanResult['waktu_khusus_hari'] === 'Minggu')
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-600 border border-rose-200">
                                        <i class="fas fa-sun mr-1"></i> Minggu
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 gap-1">
                        <span class="text-gray-500 font-medium text-sm">Waktu {{ $scanResult['type'] === 'check_in' ? 'Check-in' : 'Check-out' }}:</span>
                        <span class="font-bold text-primary-600 text-lg">{{ $scanResult['waktu_absen_formatted'] }}</span>
                    </div>
                    @if ($scanResult['type'] === 'check_out' && isset($scanResult['waktu_check_in']))
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 gap-1">
                            <span class="text-gray-500 font-medium text-sm">Waktu Check-in:</span>
                            <span class="font-bold text-gray-600">{{ Carbon\Carbon::parse($scanResult['waktu_check_in'])->format('H:i') }} WITA</span>
                        </div>
                    @endif
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 gap-1">
                        <span class="text-gray-500 font-medium text-sm">Waktu Batas:</span>
                        <span class="font-bold text-gray-600">{{ Carbon\Carbon::parse($scanResult['waktu_batas'])->format('H:i') }} WITA</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 gap-1">
                        <span class="text-gray-500 font-medium text-sm">Status:</span>
                        @if($scanResult['status_absen'] === 'hadir')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200">
                                <i class="fas fa-check-circle"></i> Hadir
                            </span>
                        @elseif($scanResult['status_absen'] === 'terlambat')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200">
                                <i class="fas fa-clock"></i> Terlambat
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-cyan-100 text-cyan-700 border border-cyan-200">
                                <i class="fas fa-running"></i> Pulang Cepat
                            </span>
                        @endif
                    </div>
                    
                    @if ($scanResult['type'] === 'check_out' && isset($scanResult['total_waktu']))
                        <div class="py-4 mt-2">
                            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-4 text-center">
                                <i class="fas fa-clock text-orange-500 text-xl mb-1"></i>
                                <div class="text-xs font-bold text-orange-600 uppercase tracking-wider mb-1">Total Waktu Kerja</div>
                                <div class="text-xl font-black text-orange-700">{{ $scanResult['total_waktu_formatted'] }}</div>
                                <div class="text-xs text-orange-500 mt-0.5">({{ $scanResult['total_waktu'] }})</div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <button onclick="printAttendance()" class="w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-700 font-bold py-3 px-6 border border-gray-200 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                        <i class="fas fa-print"></i> Cetak Bukti
                    </button>
                    <button onclick="newScan()" class="w-full sm:w-auto bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
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
            
            const btnCheckin = document.getElementById('type-checkin-btn');
            const btnCheckout = document.getElementById('type-checkout-btn');

            if (type === 'check_in') {
                btnCheckin.className = 'flex-1 text-center p-4 border-2 border-primary-500 bg-primary-50 text-primary-700 rounded-xl cursor-pointer transition-all hover:bg-primary-50';
                btnCheckout.className = 'flex-1 text-center p-4 border-2 border-transparent text-gray-400 rounded-xl cursor-pointer transition-all hover:bg-gray-50';
            } else {
                btnCheckout.className = 'flex-1 text-center p-4 border-2 border-primary-500 bg-primary-50 text-primary-700 rounded-xl cursor-pointer transition-all hover:bg-primary-50';
                btnCheckin.className = 'flex-1 text-center p-4 border-2 border-transparent text-gray-400 rounded-xl cursor-pointer transition-all hover:bg-gray-50';
            }

            // Update input tipe form manual
            document.getElementById('absensi_type').value = type;
        }

        function startScanner() {
            // Show fullscreen scanner overlay
            document.getElementById('fullscreen-scanner').classList.remove('hidden');
            document.getElementById('fullscreen-scanner').classList.add('flex');
            
            const typeText = currentAbsensiType === 'check_in' ? 'Check-in' : 'Check-out';
            document.getElementById('overlay-scan-type').textContent = typeText;

            // Inisialisasi scanner
            html5QrcodeScanner = new Html5Qrcode("reader");

            const config = {
                fps: 15,
                qrbox: { width: 300, height: 300 },
                aspectRatio: window.innerWidth / window.innerHeight
            };

            html5QrcodeScanner.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error("Gagal memulai kamera:", err);
                alert("Tidak dapat mengakses kamera. Pastikan Anda memberikan izin akses kamera.");
                stopScanner();
            });
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    closeFullscreen();
                }).catch(err => {
                    console.error("Failed to stop scanner", err);
                    closeFullscreen();
                });
            } else {
                closeFullscreen();
            }
        }

        function closeFullscreen() {
            document.getElementById('fullscreen-scanner').classList.add('hidden');
            document.getElementById('fullscreen-scanner').classList.remove('flex');
        }

        function onScanSuccess(decodedText, decodedResult) {
            stopScanner();

            const typeText = currentAbsensiType === 'check_in' ? 'Check-in' : 'Check-out';
            const typeIcon = currentAbsensiType === 'check_in' ? 'sign-in-alt' : 'sign-out-alt';
            const resultElement = document.getElementById('scanner-result');
            
            const containerElement = document.getElementById('scanner-container');
            containerElement.classList.add('hidden');

            resultElement.innerHTML = `
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl mb-6 flex items-center justify-center gap-2 font-bold shadow-sm">
                    <i class="fas fa-check-circle text-xl"></i> QR Code berhasil di-scan!
                </div>
                
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 p-5 rounded-xl mb-6 text-left shadow-sm">
                    <div class="flex items-center gap-2 mb-2 text-orange-800">
                        <i class="fas fa-qrcode text-lg opacity-70"></i> 
                        <span class="text-sm font-medium">Kode:</span> 
                        <strong class="text-lg tracking-wider font-mono">${decodedText}</strong>
                    </div>
                    <div class="flex items-center gap-2 text-orange-700">
                        <i class="fas fa-${typeIcon} text-lg opacity-70"></i> 
                        <span class="text-sm font-medium">Tipe:</span> 
                        <strong class="text-lg">${typeText}</strong>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('magang.scan.process') }}" id="auto-submit-form">
                    @csrf
                    <input type="hidden" name="qr_code" value="${decodedText}">
                    <input type="hidden" name="absensi_type" value="${currentAbsensiType}">
                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-spinner fa-spin text-lg"></i> 
                        <span>Memproses ${typeText}...</span>
                    </button>
                </form>
            `;
            
            resultElement.classList.remove('hidden');
            resultElement.classList.add('block');

            setTimeout(() => {
                document.getElementById('auto-submit-form').submit();
            }, 1000);
        }

        function onScanFailure(error) {
            // Abaikan kegagalan frame scanning biasa
        }

        function newScan() {
            const resultCard = document.getElementById('bukti-absensi-card');
            if (resultCard) {
                resultCard.classList.add('hidden');
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function printAttendance() {
            @if ($scanResult)
                const typeText = "{{ $scanResult['type'] === 'check_in' ? 'Check-in' : 'Check-out' }}";
                const typeIcon = "{{ $scanResult['type'] === 'check_in' ? 'sign-in-alt' : 'sign-out-alt' }}";
                const statusText = "{{ $scanResult['status_absen'] }}";
                const statusColor = "@if($scanResult['status_absen'] === 'hadir') #10b981 @elseif($scanResult['status_absen'] === 'terlambat') #f59e0b @else #0ea5e9 @endif";
                const totalWaktu = "{{ $scanResult['total_waktu_formatted'] ?? '' }}";
                const totalWaktuStr = "{{ $scanResult['total_waktu'] ?? '' }}";
                const waktuCheckIn = "{{ ($scanResult['waktu_check_in'] ?? null) ? Carbon\Carbon::parse($scanResult['waktu_check_in'])->format('H:i') . ' WITA' : '' }}";

                const win = window.open('', '', 'height=600,width=500');
                win.document.write('<html><head><title>Bukti Absensi - {{ $scanResult['nama_kegiatan'] }}</title>');
                win.document.write('<style>');
                win.document.write('body{font-family:"Segoe UI",sans-serif; text-align:center; padding:30px; background:#f8fafc;}');
                win.document.write('.print-container{max-width:400px; margin:0 auto; background:white; padding:30px; border-radius:24px; box-shadow:0 10px 25px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;}');
                win.document.write('h2{color:#4361ee; margin-bottom:5px; font-weight:800; font-size: 1.5rem;}');
                win.document.write('h3{color:#334155; margin-bottom:20px; font-size:1.1rem; font-weight:600;}');
                win.document.write('.info-item{text-align:left; margin:10px 0; padding:12px 15px; background:#f8fafc; border-radius:12px; border-bottom: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 4px;}');
                win.document.write('.info-label{font-size:0.8rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;}');
                win.document.write('.info-value{font-size:1rem; color:#1e293b; font-weight:700;}');
                win.document.write('.total-waktu{background:#fff7ed; border-left:4px solid #f97316; padding:15px; margin:20px 0; border-radius:12px; display:flex; flex-direction:column; gap:4px; text-align:left;}');
                win.document.write('.status-badge{background:' + statusColor + '; color:white; padding:12px; border-radius:12px; margin:20px 0; font-weight:800; font-size:1.1rem; letter-spacing:0.05em; text-transform:uppercase;}');
                win.document.write('</style>');
                win.document.write('</head><body>');
                win.document.write('<div class="print-container">');
                win.document.write('<h2>BUKTI ABSENSI</h2>');
                win.document.write('<h3>{{ $scanResult['nama_kegiatan'] }}</h3>');
                win.document.write('<div class="status-badge">');
                win.document.write(typeText + ' - ' + statusText);
                win.document.write('</div>');
                win.document.write('<div class="info-item">');
                win.document.write('<span class="info-label">Nama Pengguna</span><span class="info-value">{{ $username }}</span>');
                win.document.write('</div>');
                win.document.write('<div class="info-item">');
                win.document.write('<span class="info-label">Kode QR</span><span class="info-value font-mono tracking-wider">{{ $scanResult['kode_qr'] }}</span>');
                win.document.write('</div>');
                win.document.write('<div class="info-item">');
                win.document.write('<span class="info-label">Hari Absen</span><span class="info-value">{{ $scanResult['hari_absen'] }}</span>');
                win.document.write('</div>');
                if (typeText === 'Check-out' && waktuCheckIn) {
                    win.document.write('<div class="info-item">');
                    win.document.write('<span class="info-label">Waktu Check-in</span><span class="info-value">' + waktuCheckIn + '</span>');
                    win.document.write('</div>');
                }
                win.document.write('<div class="info-item">');
                win.document.write('<span class="info-label">Waktu ' + typeText + '</span><span class="info-value" style="color:#4361ee; font-size:1.1rem;">{{ $scanResult['waktu_absen_formatted'] }}</span>');
                win.document.write('</div>');
                if (typeText === 'Check-out' && totalWaktu) {
                    win.document.write('<div class="total-waktu">');
                    win.document.write('<span style="font-size:0.8rem; color:#c2410c; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">Total Waktu Kerja</span><span style="font-size:1.2rem; font-weight:800; color:#ea580c;">' + totalWaktu + '</span><span style="font-size:0.85rem; color:#f97316;">(' + totalWaktuStr + ')</span>');
                    win.document.write('</div>');
                }
                win.document.write('<div style="margin-top:30px; font-size:0.75rem; color:#94a3b8; line-height:1.5;">');
                win.document.write('Dokumen ini sah dari Sistem Presensi Digital<br>Badan Pusat Statistik Provinsi Sulawesi Tenggara');
                win.document.write('</div>');
                win.document.write('</div>');
                win.document.write('</body></html>');
                win.document.close();
                win.print();
            @endif
        }
    </script>
@endsection