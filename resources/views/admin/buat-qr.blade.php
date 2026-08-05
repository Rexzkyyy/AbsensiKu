@extends('layouts.app')

@section('title', $editMode ? 'Edit QR Code' : 'Buat QR Code')

@section('header_title', $editMode ? 'Edit QR Code' : 'Generator QR Code')

@section('styles')
<style>
    .grid-container {
        display: grid;
        grid-template-columns: 1.2fr 1.8fr;
        gap: 25px;
    }
    
    .jumat-section {
        background: rgba(156, 39, 176, 0.05);
        border: 1px solid rgba(156, 39, 176, 0.2);
        border-radius: 10px;
        padding: 15px;
        margin-top: 10px;
    }
    
    .jumat-label {
        color: var(--jumat);
        font-weight: 600;
    }
    
    /* QR Result */
    .qr-container { 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        padding: 20px 0; 
    }
    
    .qr-image-wrapper { 
        margin-bottom: 20px; 
        padding: 15px; 
        border: 1px solid #eee; 
        border-radius: 12px; 
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .qr-image-wrapper img { 
        max-width: 230px; 
        height: auto; 
    }
    
    .qr-info { 
        text-align: center; 
        color: var(--gray); 
        margin-bottom: 20px; 
        width: 100%;
    }
    
    /* List */
    .qr-item { 
        display: flex; 
        align-items: center; 
        padding: 15px 0; 
        border-bottom: 1px solid var(--light-gray); 
    }
    
    .qr-item:last-child {
        border-bottom: none;
    }
    
    .qr-icon { 
        width: 40px; 
        height: 40px; 
        border-radius: 50%; 
        background: var(--light-gray); 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        margin-right: 15px; 
        color: var(--primary); 
    }
    
    .qr-details { 
        flex: 1; 
    }
    
    .qr-actions { 
        display: flex; 
        gap: 8px; 
    }
    
    .qr-status { 
        padding: 5px 10px; 
        border-radius: 20px; 
        font-size: 0.8rem; 
        font-weight: 500; 
        text-transform: capitalize;
        display: inline-block;
        margin-top: 5px;
    }
    
    .status-active { 
        background: rgba(76,201,240,0.15); 
        color: var(--primary-dark); 
    }
    
    .status-expired { 
        background: rgba(247,37,133,0.15); 
        color: var(--warning); 
    }

    .btn-action-small {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: white;
        transition: var(--transition);
        border: none;
        cursor: pointer;
    }

    .btn-show {
        background: #00b4d8;
    }
    .btn-show:hover {
        background: #0077b6;
        transform: scale(1.1);
    }
    .btn-edit {
        background: var(--primary);
    }
    .btn-edit:hover {
        background: var(--primary-dark);
        transform: scale(1.1);
    }
    .btn-delete {
        background: var(--tidak-hadir);
    }
    .btn-delete:hover {
        background: #bd2130;
        transform: scale(1.1);
    }
    
    @media (max-width: 1100px) {
        .grid-container {
            grid-template-columns: 1fr;
        }
    }

    /* Custom Modal Glassmorphism and animations */
    .custom-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .custom-modal.show {
        display: flex;
        opacity: 1;
    }
    
    .custom-modal-content {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 24px;
        padding: 30px;
        width: 90%;
        max-width: 440px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        transform: scale(0.9) translateY(20px);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
    }
    
    .custom-modal.show .custom-modal-content {
        transform: scale(1) translateY(0);
    }
    
    .close-modal-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 1.5rem;
        color: var(--gray);
        cursor: pointer;
        transition: var(--transition);
        background: rgba(0, 0, 0, 0.05);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        outline: none;
    }
    
    .close-modal-btn:hover {
        color: var(--tidak-hadir);
        background: rgba(220, 53, 69, 0.1);
        transform: rotate(90deg);
    }
    
    .modal-qr-img-wrapper {
        margin: 20px auto;
        padding: 15px;
        border: 1px solid var(--light-gray);
        border-radius: 16px;
        background: white;
        display: inline-block;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    
    .modal-qr-img-wrapper img {
        width: 180px;
        height: 180px;
        display: block;
    }
</style>
@endsection

@section('content')
<!-- Time Display -->
<div class="time-display">
    <div class="date" id="current-date">{{ Carbon\Carbon::now('Asia/Makassar')->isoFormat('dddd, D MMMM Y') }} - {{ Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') }} WITA</div>
    <div class="time" id="current-time">{{ Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') }}</div>
    <div class="location">
        <i class="fas fa-map-marker-alt"></i>
        <span>Kendari, Sulawesi Tenggara - WITA</span>
    </div>
</div>

<div class="grid-container">
    
    <!-- LEFT PANEL: SETTINGS / GENERATED CODE -->
    <div>
        <!-- Menampilkan Hasil QR Code yang Baru Dibuat -->
        @if ($generatedData)
        <div class="card" style="border: 2px solid var(--hadir);">
            <h3 class="section-title" style="color: var(--hadir);"><i class="fas fa-check-circle"></i> Hasil QR Code</h3>
            <div class="qr-container">
                <div class="qr-image-wrapper">
                    <img src="{{ $generatedData['url_img'] }}" alt="QR Code" id="qr-img">
                </div>
                
                <div class="qr-info">
                    <h3 style="font-size: 1.4rem; color: var(--dark); margin-bottom: 5px;">{{ $generatedData['kode'] }}</h3>
                    <p style="font-weight: 600; color: var(--primary-dark); margin-bottom: 15px;">{{ htmlspecialchars($generatedData['nama_kegiatan']) }}</p>
                    
                    <div style="background: var(--light); padding: 15px; border-radius: 12px; text-align: left; font-size: 0.9rem;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; border-bottom:1px solid #ddd; padding-bottom:5px;">
                            <span>Check-in:</span>
                            <strong>{{ Carbon\Carbon::parse($generatedData['cek_in'])->format('H:i') }} WITA</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; border-bottom:1px solid #ddd; padding-bottom:5px;">
                            <span>Check-out:</span>
                            <strong>{{ Carbon\Carbon::parse($generatedData['cek_out'])->format('H:i') }} WITA</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; border-bottom:1px solid #ddd; padding-bottom:5px;">
                            <span>Batas Expired:</span>
                            <strong>{{ Carbon\Carbon::parse($generatedData['expired'])->isoFormat('D MMM Y - H:i') }} WITA</strong>
                        </div>
                    </div>
                </div>
                
                <div style="display:flex; gap:10px; width: 100%;">
                    <button onclick="printGeneratedQR('{{ $generatedData['url_img'] }}', '{{ $generatedData['kode'] }}', '{{ htmlspecialchars($generatedData['nama_kegiatan']) }}', '{{ Carbon\Carbon::parse($generatedData['expired'])->isoFormat('D MMM Y - H:i') }} WITA')" class="btn btn-outline" style="flex:1; justify-content:center;">
                        <i class="fas fa-print"></i> Cetak QR
                    </button>
                    <a href="{{ route('admin.buat_qr') }}" class="btn btn-primary" style="flex:1; justify-content:center;">
                        <i class="fas fa-redo"></i> Selesai
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Pembuatan QR Code -->
        <div class="card">
            <h3 class="section-title">
                <i class="fas {{ $editMode ? 'fa-edit' : 'fa-cogs' }}"></i> 
                {{ $editMode ? 'Edit QR Code' : 'Pengaturan QR Code Baru' }}
            </h3>
            <p style="margin-bottom: 20px; color: #666; font-size: 0.9rem; line-height: 1.5;">
                @if ($editMode)
                    Edit data QR Code. <strong>Kode QR baru akan dibuat otomatis</strong> untuk menjaga validitas log.
                @else
                    Tentukan nama kegiatan dan jam batas absensi. Kode unik QR akan dibuat secara <strong>otomatis & acak</strong> oleh sistem demi keamanan.
                @endif
            </p>
            
            <form method="POST" action="{{ $editMode ? route('admin.buat_qr.update', $editData->id_qr) : route('admin.buat_qr.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="nama_kegiatan">
                        <i class="fas fa-tag"></i> Nama Kegiatan
                    </label>
                    <input type="text" class="form-input" id="nama_kegiatan" name="nama_kegiatan" 
                           value="{{ old('nama_kegiatan', $editData->nama_kegiatan ?? '') }}"
                           placeholder="Contoh: Absen Magang Batch 3 2026" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="cek_in">
                        <i class="fas fa-sign-in-alt"></i> Waktu Batas Check-in
                    </label>
                    <input type="time" class="form-input" id="cek_in" name="cek_in" 
                           value="{{ old('cek_in', $editData->cek_in ?? '08:00') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="cek_out">
                        <i class="fas fa-sign-out-alt"></i> Waktu Batas Check-out (Senin-Kamis)
                    </label>
                    <input type="time" class="form-input" id="cek_out" name="cek_out" 
                           value="{{ old('cek_out', $editData->cek_out ?? '17:00') }}" required>
                </div>
                
                <div class="form-group">
                    <div class="jumat-section">
                        <label class="form-label jumat-label" for="cek_out_jumat">
                            <i class="fas fa-star"></i> Waktu Check-out Khusus Jumat
                        </label>
                        <input type="time" class="form-input" id="cek_out_jumat" name="cek_out_jumat" 
                               value="{{ old('cek_out_jumat', $editData->cek_out_jumat ?? '16:00') }}">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="expired_at">
                        <i class="fas fa-calendar-times"></i> Waktu Kadaluarsa QR
                    </label>
                    <input type="datetime-local" class="form-input" id="expired_at" name="expired_at" 
                           value="{{ old('expired_at', isset($editData->expired_at) ? date('Y-m-d\TH:i', strtotime($editData->expired_at)) : '') }}" required>
                </div>
                
                <div style="display: flex; gap: 12px; margin-top: 15px;">
                    @if ($editMode)
                        <button type="submit" class="btn btn-success" style="flex: 1; justify-content: center; padding: 15px;">
                            <i class="fas fa-sync-alt"></i> Update & Generate
                        </button>
                        <a href="{{ route('admin.buat_qr') }}" class="btn btn-outline" style="padding: 15px 20px;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    @else
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 15px;">
                            <i class="fas fa-magic"></i> Generate Random QR Code
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- RIGHT PANEL: BARCODE LOG HISTORY -->
    <div>
        <div class="card">
            <h3 class="section-title"><i class="fas fa-history"></i> Riwayat QR Code</h3>
            <p style="margin-bottom: 20px; color: #666; font-size: 0.9rem;">
                Daftar QR Code yang telah dibuat oleh sistem. Anda dapat mengedit batas jam presensi atau menghapus barcode log.
            </p>

            <div id="recent-qr">
                @if ($recentQrs->isEmpty())
                    <div style="text-align:center; color:#999; padding: 30px;">
                        <i class="fas fa-qrcode fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
                        <p>Belum ada QR Code yang dibuat.</p>
                    </div>
                @else
                    @foreach ($recentQrs as $qr)
                        @php
                            $isExpired = Carbon\Carbon::parse($qr->expired_at)->isPast();
                        @endphp
                        <div class="qr-item">
                            <div class="qr-icon">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div class="qr-details">
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <strong style="color:var(--dark); font-size:1.05rem;">{{ $qr->kode_qr }}</strong>
                                    <span class="qr-status {{ $isExpired ? 'status-expired' : 'status-active' }}">
                                        {{ $isExpired ? 'expired' : 'aktif' }}
                                    </span>
                                </div>
                                <div style="font-weight: 600; color: var(--primary-dark); margin: 3px 0;">{{ htmlspecialchars($qr->nama_kegiatan ?? 'Tanpa Nama') }}</div>
                                <div style="font-size:0.85rem; color:#666;">
                                    <i class="fas fa-clock"></i> 
                                    Masuk: <strong>{{ Carbon\Carbon::parse($qr->cek_in)->format('H:i') }}</strong> | 
                                    Pulang: <strong>{{ Carbon\Carbon::parse($qr->cek_out)->format('H:i') }}</strong> WITA
                                </div>
                                <div style="font-size:0.8rem; color:#888; margin-top:2px;">
                                    Batas Expired: {{ Carbon\Carbon::parse($qr->expired_at)->isoFormat('D MMM Y - H:i') }} WITA
                                </div>
                            </div>
                            
                            <div class="qr-actions" style="margin-left:15px;">
                                <button type="button" class="btn-action-small btn-show" 
                                        onclick="openQrModal('{{ $qr->kode_qr }}', '{{ htmlspecialchars($qr->nama_kegiatan ?? 'Tanpa Nama') }}', '{{ Carbon\Carbon::parse($qr->expired_at)->isoFormat('D MMM Y - H:i') }} WITA', '{{ Carbon\Carbon::parse($qr->cek_in)->format('H:i') }} WITA', '{{ Carbon\Carbon::parse($qr->cek_out)->format('H:i') }} WITA')" 
                                        title="Tampilkan QR">
                                    <i class="fas fa-qrcode"></i>
                                </button>
                                <a href="{{ route('admin.buat_qr.edit', $qr->id_qr) }}" class="btn-action-small btn-edit" title="Edit QR">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.buat_qr.delete', $qr->id_qr) }}" class="btn-action-small btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus QR Code ini? Seluruh data absensi magang terkait QR ini juga akan terhapus secara permanen.')" title="Hapus QR">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Pagination Wrapper -->
                    <div class="pagination-wrapper" style="margin-top: 20px; display: flex; justify-content: center;">
                        {{ $recentQrs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Custom Modal for QR Display -->
<div id="qrModal" class="custom-modal">
    <div class="custom-modal-content">
        <button class="close-modal-btn" onclick="closeQrModal()">&times;</button>
        
        <h3 style="font-size: 1.4rem; color: var(--dark); font-weight:600; margin-bottom: 5px;">Detail QR Code</h3>
        <p id="modal-qr-title" style="font-weight: 600; color: var(--primary-dark); font-size:1.1rem; margin-bottom:15px;"></p>
        
        <div class="modal-qr-img-wrapper">
            <img id="modal-qr-img" src="" alt="QR Code">
        </div>
        
        <div style="margin-bottom: 20px;">
            <span id="modal-qr-code" style="background: rgba(76,201,240,0.15); color: var(--primary-dark); padding: 8px 20px; border-radius: 20px; font-weight: 600; font-size: 1rem; display: inline-block;"></span>
        </div>
        
        <div style="background: var(--light); padding: 15px; border-radius: 12px; text-align: left; font-size: 0.9rem; margin-bottom: 20px; border: 1px solid var(--light-gray);">
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; border-bottom:1px solid #eee; padding-bottom:5px;">
                <span style="color:var(--gray);">Check-in:</span>
                <strong id="modal-qr-cekin"></strong>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; border-bottom:1px solid #eee; padding-bottom:5px;">
                <span style="color:var(--gray);">Check-out:</span>
                <strong id="modal-qr-cekout"></strong>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span style="color:var(--gray);">Batas Expired:</span>
                <strong id="modal-qr-expired" style="color: var(--warning);"></strong>
            </div>
        </div>
        
        <div style="display:flex; gap:12px; justify-content:center;">
            <button id="modal-print-btn" class="btn btn-outline" style="flex:1; justify-content:center;">
                <i class="fas fa-print"></i> Cetak QR
            </button>
            <button onclick="closeQrModal()" class="btn btn-primary" style="flex:1; justify-content:center;">
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
        win.document.write('<p style="font-size:0.8rem; color:#666; margin-top:20px;">Berlaku s/d: ' + expired + '</p>');
        win.document.write('</div>');
        win.document.write('</body></html>');
        win.document.close();
        win.print();
    }

    // Modal Control Functions
    function openQrModal(kode, nama, expired, cekIn, cekOut) {
        const modal = document.getElementById('qrModal');
        const modalImg = document.getElementById('modal-qr-img');
        const modalTitle = document.getElementById('modal-qr-title');
        const modalCode = document.getElementById('modal-qr-code');
        const modalCekin = document.getElementById('modal-qr-cekin');
        const modalCekout = document.getElementById('modal-qr-cekout');
        const modalExpired = document.getElementById('modal-qr-expired');
        const printBtn = document.getElementById('modal-print-btn');
        
        const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + kode;
        
        modalImg.src = qrUrl;
        modalTitle.textContent = nama;
        modalCode.textContent = 'KODE: ' + kode;
        modalCekin.textContent = cekIn;
        modalCekout.textContent = cekOut;
        modalExpired.textContent = expired;
        
        printBtn.onclick = function() {
            printGeneratedQR(qrUrl, kode, nama, expired);
        };
        
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
    }
    
    function closeQrModal() {
        const modal = document.getElementById('qrModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
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
