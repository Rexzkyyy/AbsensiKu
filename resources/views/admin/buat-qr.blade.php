@extends('layouts.app')

@section('title', 'Buat QR Code')
@section('header_title', 'Kelola QR Code')

@section('content')
<!-- Time Display -->
<div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col sm:flex-row justify-between items-center mb-6">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-blue-600 flex items-center justify-center text-white shadow-md">
            <i class="far fa-clock text-xl"></i>
        </div>
        <div>
            <div class="text-sm font-medium text-gray-500" id="current-date">{{ Carbon\Carbon::now('Asia/Makassar')->isoFormat('dddd, D MMMM Y') }}</div>
            <div class="text-xl font-black text-gray-800 tracking-tight"><span id="current-time">{{ Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') }}</span> WITA</div>
        </div>
    </div>
    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50/80 rounded-xl text-sm font-semibold text-gray-600 border border-gray-200 mt-4 sm:mt-0 shadow-inner">
        <i class="fas fa-map-marker-alt text-primary-500"></i>
        <span>Kendari, Sulawesi Tenggara</span>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-12 gap-6 xl:gap-8">
    
    <!-- LEFT PANEL: SETTINGS / GENERATED CODE -->
    <div class="xl:col-span-5 space-y-6">
        
        <!-- Menampilkan Hasil QR Code yang Baru Dibuat -->
        @if ($generatedData)
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl overflow-hidden shadow-xl shadow-emerald-500/20 relative border border-emerald-400/50">
            <!-- Decorative circle -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-black/10 rounded-full blur-xl"></div>
            
            <div class="px-6 py-5 border-b border-white/20 flex items-center gap-3 relative z-10">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-check text-white text-sm"></i>
                </div>
                <h3 class="font-bold text-white text-lg tracking-wide">QR Code Berhasil Dibuat</h3>
            </div>
            
            <div class="p-6 flex flex-col items-center relative z-10">
                <div class="bg-white p-4 rounded-2xl shadow-2xl mb-6 transform hover:scale-105 transition-transform duration-300">
                    <img src="{{ $generatedData['url_img'] }}" alt="QR Code" class="w-48 h-48 object-contain">
                </div>
                
                <div class="w-full text-center mb-6">
                    <h3 class="text-3xl font-black text-white font-mono tracking-widest bg-black/20 backdrop-blur-sm inline-block px-5 py-2 rounded-xl mb-3 border border-white/10 shadow-inner">{{ $generatedData['kode'] }}</h3>
                    <p class="font-bold text-emerald-50 text-base mb-5">{{ htmlspecialchars($generatedData['nama_kegiatan']) }}</p>
                    
                    <div class="bg-black/10 backdrop-blur-md rounded-2xl p-4 text-left border border-white/10 text-sm space-y-3 text-white">
                        <div class="flex justify-between items-center pb-2 border-b border-white/10">
                            <span class="text-emerald-100 font-medium">Check-in:</span>
                            <strong class="font-bold">{{ Carbon\Carbon::parse($generatedData['cek_in'])->format('H:i') }} WITA</strong>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-white/10">
                            <span class="text-emerald-100 font-medium">Check-out:</span>
                            <strong class="font-bold">{{ Carbon\Carbon::parse($generatedData['cek_out'])->format('H:i') }} WITA</strong>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-emerald-100 font-medium">Expired:</span>
                            <strong class="font-bold text-amber-300">{{ Carbon\Carbon::parse($generatedData['expired'])->isoFormat('D MMM Y - H:i') }}</strong>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row w-full gap-3">
                    <button onclick="printGeneratedQR('{{ $generatedData['url_img'] }}', '{{ $generatedData['kode'] }}', '{{ htmlspecialchars($generatedData['nama_kegiatan']) }}', '{{ Carbon\Carbon::parse($generatedData['expired'])->isoFormat('D MMM Y - H:i') }} WITA')" class="flex-1 bg-white hover:bg-gray-50 text-emerald-700 font-bold py-3 px-4 rounded-xl transition-all duration-200 flex justify-center items-center gap-2 shadow-lg">
                        <i class="fas fa-print"></i> Cetak QR
                    </button>
                    <a href="{{ route('admin.buat_qr') }}" class="flex-1 bg-emerald-800/40 hover:bg-emerald-800/60 backdrop-blur-md border border-white/20 text-white font-bold py-3 px-4 rounded-xl transition-all duration-200 flex justify-center items-center gap-2">
                        <i class="fas fa-redo"></i> Selesai
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Pembuatan QR Code -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
            <!-- Header section -->
            <div class="px-7 py-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-gray-800 text-xl flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg {{ $editMode ? 'bg-amber-100 text-amber-600' : 'bg-primary-100 text-primary-600' }} flex items-center justify-center">
                            <i class="fas {{ $editMode ? 'fa-edit' : 'fa-qrcode' }}"></i> 
                        </div>
                        {{ $editMode ? 'Edit Pengaturan QR' : 'Buat QR Baru' }}
                    </h3>
                </div>
            </div>
            
            <div class="p-7">
                <p class="text-sm text-gray-500 mb-6 bg-blue-50/50 p-4 rounded-xl border border-blue-100/50 flex gap-3 items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    <span>
                    @if ($editMode)
                        Edit data QR Code. <strong>Kode QR baru akan dibuat otomatis</strong> untuk menjaga validitas log absensi.
                    @else
                        Tentukan nama kegiatan dan batas waktu presensi. Kode unik akan di-generate <strong>otomatis & acak</strong> demi keamanan tingkat tinggi.
                    @endif
                    </span>
                </p>
                
                <form method="POST" action="{{ $editMode ? route('admin.buat_qr.update', $editData->id_qr) : route('admin.buat_qr.store') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5" for="nama_kegiatan">
                            Nama Kegiatan
                        </label>
                        <input type="text" id="nama_kegiatan" name="nama_kegiatan" 
                               value="{{ old('nama_kegiatan', $editData->nama_kegiatan ?? '') }}"
                               placeholder="Contoh: Absen Magang Batch 3 2026" required
                               class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none text-gray-800 font-medium placeholder:font-normal">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5" for="cek_in">
                                Batas Check-in
                            </label>
                            <input type="time" id="cek_in" name="cek_in" 
                                   value="{{ old('cek_in', $editData->cek_in ?? '08:00') }}" required
                                   class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none text-gray-800 font-bold">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5" for="cek_out">
                                Batas Check-out
                            </label>
                            <input type="time" id="cek_out" name="cek_out" 
                                   value="{{ old('cek_out', $editData->cek_out ?? '17:00') }}" required
                                   class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none text-gray-800 font-bold">
                        </div>
                    </div>
                    
                    <div class="bg-purple-50/30 border border-purple-100 rounded-2xl p-4">
                        <label class="block text-sm font-bold text-purple-800 mb-1.5 flex items-center gap-2" for="cek_out_jumat">
                            <i class="fas fa-star text-amber-400"></i> Check-out Khusus Jumat
                        </label>
                        <input type="time" id="cek_out_jumat" name="cek_out_jumat" 
                               value="{{ old('cek_out_jumat', $editData->cek_out_jumat ?? '16:00') }}"
                               class="w-full px-4 py-3 bg-white border border-purple-200 rounded-xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-400 outline-none text-gray-800 font-bold transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5" for="expired_at">
                            Waktu Kadaluarsa QR (Expired)
                        </label>
                        <input type="datetime-local" id="expired_at" name="expired_at" 
                               value="{{ old('expired_at', isset($editData->expired_at) ? date('Y-m-d\TH:i', strtotime($editData->expired_at)) : '') }}" required
                               class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-400 transition-all outline-none text-gray-800 font-bold">
                    </div>
                    
                    <div class="pt-5 flex flex-col sm:flex-row gap-3">
                        @if ($editMode)
                            <button type="submit" class="flex-1 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold py-3.5 px-4 rounded-xl transition-all shadow-lg shadow-amber-500/30 flex items-center justify-center gap-2">
                                <i class="fas fa-sync-alt"></i> Update & Generate
                            </button>
                            <a href="{{ route('admin.buat_qr') }}" class="sm:w-1/3 bg-white hover:bg-gray-50 border border-gray-200 text-gray-600 font-bold py-3.5 px-4 rounded-xl transition flex items-center justify-center gap-2 text-center">
                                Batal
                            </a>
                        @else
                            <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-blue-600 hover:from-primary-700 hover:to-blue-700 text-white font-bold py-4 px-4 rounded-xl transition-all shadow-lg shadow-primary-500/30 flex items-center justify-center gap-2 text-lg">
                                <i class="fas fa-magic"></i> Generate Random QR Code
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL: BARCODE LOG HISTORY -->
    <div class="xl:col-span-7">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
            <div class="px-7 py-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <div>
                    <h3 class="font-extrabold text-gray-800 text-xl flex items-center gap-2">
                        <i class="fas fa-history text-primary-500"></i> Riwayat QR Code
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Daftar lengkap kode QR yang telah diterbitkan.</p>
                </div>
                <div class="hidden sm:flex w-10 h-10 rounded-full bg-primary-50 text-primary-600 items-center justify-center font-bold">
                    {{ $recentQrs->total() }}
                </div>
            </div>

            <div class="p-5 md:p-7 flex-1 flex flex-col bg-gray-50/30">
                @if ($recentQrs->isEmpty())
                    <div class="text-center py-20 flex-1 flex flex-col justify-center items-center">
                        <div class="w-24 h-24 bg-white rounded-full shadow-sm flex items-center justify-center text-gray-300 mb-5 border border-gray-100">
                            <i class="fas fa-qrcode text-4xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-700">Belum ada data QR Code</h4>
                        <p class="text-gray-500 mt-1">Silakan buat QR code pertama Anda menggunakan form di sebelah kiri.</p>
                    </div>
                @else
                    <div class="space-y-4 flex-1">
                        @foreach ($recentQrs as $qr)
                            @php 
                                $isExpired = Carbon\Carbon::parse($qr->expired_at)->isPast(); 
                            @endphp
                            <div class="group bg-white flex flex-col sm:flex-row justify-between items-start sm:items-center p-5 rounded-2xl border border-gray-200 hover:border-primary-200 shadow-sm hover:shadow-md transition-all duration-300 gap-4">
                                
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-14 h-14 rounded-2xl {{ $isExpired ? 'bg-red-50 text-red-500' : 'bg-gradient-to-br from-primary-50 to-blue-50 text-primary-600' }} flex items-center justify-center shrink-0 border border-gray-100 group-hover:scale-105 transition-transform duration-300">
                                        <i class="fas fa-qrcode text-2xl"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-3 mb-1">
                                            <h4 class="font-black text-gray-800 text-lg font-mono tracking-wider">{{ $qr->kode_qr }}</h4>
                                            <span class="px-2.5 py-1 text-[10px] font-black rounded-md {{ $isExpired ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-700' }} uppercase tracking-wider">
                                                {{ $isExpired ? 'Expired' : 'Aktif' }}
                                            </span>
                                        </div>
                                        <div class="text-sm font-bold text-gray-600 truncate mb-2">
                                            {{ htmlspecialchars($qr->nama_kegiatan ?? 'Tanpa Nama') }}
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs font-medium text-gray-500">
                                            <div class="flex items-center gap-1.5 bg-gray-50 px-2 py-1 rounded-md border border-gray-100"><i class="fas fa-sign-in-alt text-emerald-500"></i> {{ Carbon\Carbon::parse($qr->cek_in)->format('H:i') }} - {{ Carbon\Carbon::parse($qr->cek_out)->format('H:i') }}</div>
                                            <div class="flex items-center gap-1.5 bg-gray-50 px-2 py-1 rounded-md border border-gray-100"><i class="fas fa-calendar-times text-red-400"></i> Exp: {{ Carbon\Carbon::parse($qr->expired_at)->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex gap-2 w-full sm:w-auto shrink-0 mt-3 sm:mt-0 opacity-100 sm:opacity-50 group-hover:opacity-100 transition-opacity">
                                    <button type="button" class="flex-1 sm:flex-none w-11 h-11 rounded-xl bg-gray-50 text-gray-600 hover:bg-primary-50 hover:text-primary-600 border border-gray-200 hover:border-primary-200 transition-all flex items-center justify-center" 
                                            onclick="openQrModal('{{ $qr->kode_qr }}', '{{ htmlspecialchars($qr->nama_kegiatan ?? 'Tanpa Nama') }}', '{{ Carbon\Carbon::parse($qr->expired_at)->isoFormat('D MMM Y - H:i') }} WITA', '{{ Carbon\Carbon::parse($qr->cek_in)->format('H:i') }} WITA', '{{ Carbon\Carbon::parse($qr->cek_out)->format('H:i') }} WITA')" 
                                            title="Tampilkan QR">
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                    <a href="{{ route('admin.buat_qr.edit', $qr->id_qr) }}" class="flex-1 sm:flex-none w-11 h-11 rounded-xl bg-gray-50 text-gray-600 hover:bg-amber-50 hover:text-amber-600 border border-gray-200 hover:border-amber-200 transition-all flex items-center justify-center" title="Edit QR">
                                        <i class="fas fa-edit text-lg"></i>
                                    </a>
                                    <a href="{{ route('admin.buat_qr.delete', $qr->id_qr) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus QR Code ini? Seluruh data absensi magang terkait QR ini juga akan terhapus.')" class="flex-1 sm:flex-none w-11 h-11 rounded-xl bg-gray-50 text-gray-600 hover:bg-red-50 hover:text-red-600 border border-gray-200 hover:border-red-200 transition-all flex items-center justify-center" title="Hapus QR">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                    </a>
                                </div>
                                
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination Tailwind -->
                    <div class="mt-8">
                        {{ $recentQrs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Custom Modal for QR Display -->
<div id="qrModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" aria-hidden="true">
    <div class="bg-white rounded-3xl p-8 w-[90%] max-w-md shadow-2xl transform scale-95 translate-y-4 transition-all duration-300 relative border border-gray-100" id="qrModalContent">
        <button onclick="closeQrModal()" class="absolute top-5 right-5 w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-red-100 hover:text-red-500 hover:rotate-90 transition-all outline-none">
            <i class="fas fa-times text-lg"></i>
        </button>
        
        <h3 class="text-2xl font-black text-gray-800 mb-1">Detail QR Code</h3>
        <p id="modal-qr-title" class="font-bold text-primary-600 mb-6"></p>
        
        <div class="bg-white p-5 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-gray-100 flex justify-center items-center mb-6 max-w-[240px] mx-auto transform hover:scale-105 transition-transform">
            <img id="modal-qr-img" src="" alt="QR Code" class="w-full h-auto object-contain">
        </div>
        
        <div class="text-center mb-7">
            <span id="modal-qr-code" class="inline-block bg-gray-900 text-white font-mono font-black tracking-widest px-6 py-2.5 rounded-xl text-xl shadow-md"></span>
        </div>
        
        <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100 text-sm mb-7 space-y-3">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <span class="text-gray-500 font-semibold">Check-in:</span>
                <strong id="modal-qr-cekin" class="text-gray-800 font-bold"></strong>
            </div>
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <span class="text-gray-500 font-semibold">Check-out:</span>
                <strong id="modal-qr-cekout" class="text-gray-800 font-bold"></strong>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-500 font-semibold">Batas Expired:</span>
                <strong id="modal-qr-expired" class="text-red-500 font-bold"></strong>
            </div>
        </div>
        
        <div class="flex gap-4">
            <button id="modal-print-btn" class="flex-1 bg-white hover:bg-gray-50 text-gray-800 font-bold py-3.5 px-4 rounded-xl border-2 border-gray-200 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-print"></i> Cetak
            </button>
            <button onclick="closeQrModal()" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 px-4 rounded-xl transition-all shadow-md flex items-center justify-center">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Fungsi print QR Code presisi cetak mandiri
    function printGeneratedQR(url, kode, nama, expired) {
        const win = window.open('', '', 'height=500,width=450');
        win.document.write('<html><head><title>Print QR Code - ' + kode + '</title>');
        win.document.write('<style>');
        win.document.write('body{font-family:"Segoe UI",sans-serif; text-align:center; padding:30px;}');
        win.document.write('.container{max-width:350px; margin:0 auto; padding:20px; border:2px dashed #4361ee; border-radius:15px;}');
        win.document.write('h2{color:#4361ee; margin-bottom:5px; font-size:1.8rem;}');
        win.document.write('h3{color:#333; margin:5px 0 20px 0;}');
        win.document.write('img{width:220px; height:220px; margin:15px 0;}');
        win.document.write('.badge{background:#e9ecef; padding:8px 15px; border-radius:10px; font-size:0.9rem; font-weight:600;}');
        win.document.write('</style>');
        win.document.write('</head><body>');
        win.document.write('<div class="container">');
        win.document.write('<h2>AbsensiKu</h2>');
        win.document.write('<h3>' + nama + '</h3>');
        win.document.write('<img src="' + url + '" alt="QR Code">');
        win.document.write('<br><br>');
        win.document.write('<span class="badge">KODE: ' + kode + '</span>');
        win.document.write('<p style="font-size:0.8rem; color:#6b7280; margin-top:20px;">Berlaku s/d: ' + expired + '</p>');
        win.document.write('</div>');
        win.document.write('</body></html>');
        win.document.close();
        win.print();
    }

    // Modal Control Functions
    function openQrModal(kode, nama, expired, cekIn, cekOut) {
        const modal = document.getElementById('qrModal');
        const modalContent = document.getElementById('qrModalContent');
        
        document.getElementById('modal-qr-img').src = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + kode;
        document.getElementById('modal-qr-title').textContent = nama;
        document.getElementById('modal-qr-code').textContent = kode;
        document.getElementById('modal-qr-cekin').textContent = cekIn;
        document.getElementById('modal-qr-cekout').textContent = cekOut;
        document.getElementById('modal-qr-expired').textContent = expired;
        
        document.getElementById('modal-print-btn').onclick = function() {
            printGeneratedQR('https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + kode, kode, nama, expired);
        };
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Trigger reflow
        void modal.offsetWidth;
        
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95', 'translate-y-4');
        modalContent.classList.add('scale-100', 'translate-y-0');
    }
    
    function closeQrModal() {
        const modal = document.getElementById('qrModal');
        const modalContent = document.getElementById('qrModalContent');
        
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100', 'translate-y-0');
        modalContent.classList.add('scale-95', 'translate-y-4');
        
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }
    
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('qrModal');
        if (event.target === modal) {
            closeQrModal();
        }
    });
</script>
@endsection
