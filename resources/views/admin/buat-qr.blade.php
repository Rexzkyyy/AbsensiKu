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
        background: rgba(114, 9, 183, 0.04);
        border: 1px solid rgba(114, 9, 183, 0.12);
        border-radius: 12px;
        padding: 16px;
        margin-top: 10px;
    }

    .jumat-label {
        color: var(--secondary);
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
        padding: 16px;
        border: 1px solid var(--border-light);
        border-radius: 16px;
        background: var(--glass-bg);
        box-shadow: var(--card-shadow);
    }

    .qr-image-wrapper img {
        max-width: 230px;
        height: auto;
    }

    .qr-info {
        text-align: center;
        color: var(--text-muted);
        margin-bottom: 20px;
        width: 100%;
    }

    /* List */
    .qr-item {
        display: flex;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid var(--border-light);
    }

    .qr-item:last-child { border-bottom: none; }

    .qr-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(67, 97, 238, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
        color: var(--primary);
        flex-shrink: 0;
    }

    .qr-details { flex: 1; }
    .qr-actions { display: flex; gap: 8px; }

    .qr-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        margin-top: 5px;
        letter-spacing: 0.03em;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.08);
        color: var(--success);
    }

    .status-expired {
        background: rgba(239, 68, 68, 0.08);
        color: var(--danger);
    }

    .btn-action-small {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: white;
        transition: var(--transition);
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
    }

    .btn-show { background: var(--info); }
    .btn-show:hover { background: #0891b2; transform: scale(1.08); }
    .btn-edit { background: var(--primary); }
    .btn-edit:hover { background: var(--primary-dark); transform: scale(1.08); }
    .btn-delete { background: var(--danger); }
    .btn-delete:hover { background: #dc2626; transform: scale(1.08); }

    @media (max-width: 1100px) {
        .grid-container { grid-template-columns: 1fr; }
    }

    /* Custom Modal Glassmorphism */
    .custom-modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .custom-modal.show { display: flex; opacity: 1; }

    .custom-modal-content {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 32px;
        width: 90%;
        max-width: 440px;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
        transform: scale(0.9) translateY(20px);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
    }

    .custom-modal.show .custom-modal-content {
        transform: scale(1) translateY(0);
    }

    .close-modal-btn {
        position: absolute;
        top: 18px; right: 18px;
        font-size: 1.3rem;
        color: var(--text-muted);
        cursor: pointer;
        transition: var(--transition);
        background: rgba(0, 0, 0, 0.04);
        border: none;
        width: 34px; height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        outline: none;
    }

    .close-modal-btn:hover {
        color: var(--danger);
        background: rgba(239, 68, 68, 0.08);
        transform: rotate(90deg);
    }

    .modal-qr-img-wrapper {
        margin: 20px auto;
        padding: 16px;
        border: 1px solid var(--border-light);
        border-radius: 16px;
        background: var(--glass-bg);
        display: inline-block;
        box-shadow: var(--card-shadow);
    }

    .modal-qr-img-wrapper img {
        width: 180px; height: 180px; display: block;
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
        <div class="card" style="border: 2px solid var(--success);">
            <h3 class="section-title" style="color: var(--success);"><i class="fas fa-check-circle"></i> Hasil QR Code</h3>
            <div class="qr-container">
                <div class="qr-image-wrapper">
                    <img src="{{ $generatedData['url_img'] }}" alt="QR Code" id="qr-img">
                </div>
                
                <div class="qr-info">
                    <h3 style="font-size: 1.4rem; color: var(--text-dark); margin-bottom: 5px;">{{ $generatedData['kode'] }}</h3>
                    <p style="font-weight: 600; color: var(--primary); margin-bottom: 15px;">{{ htmlspecialchars($generatedData['nama_kegiatan']) }}</p>
                    
                    <div style="background: rgba(67,97,238,0.03); padding: 15px; border-radius: 12px; text-align: left; font-size: 0.9rem; border: 1px solid var(--border-light);">
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; border-bottom:1px solid var(--border-light); padding-bottom:5px;">
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
            <p style="margin-bottom: 20px; color: var(--text-muted); font-size: 0.88rem; line-height: 1.6;">
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
            <p style="margin-bottom: 20px; color: var(--text-muted); font-size: 0.88rem;">
                Daftar QR Code yang telah dibuat oleh sistem. Anda dapat mengedit batas jam presensi atau menghapus barcode log.
            </p>

            <div id="recent-qr">
                @if ($recentQrs->isEmpty())
                    <div style="text-align:center; color:var(--text-muted); padding: 30px;">
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
                                    <strong style="color:var(--text-dark); font-size:1rem; font-weight:700;">{{ $qr->kode_qr }}</strong>
                                    <span class="qr-status {{ $isExpired ? 'status-expired' : 'status-active' }}">
                                        {{ $isExpired ? 'expired' : 'aktif' }}
                                    </span>
                                </div>
                                <div style="font-weight: 600; color: var(--primary); margin: 3px 0; font-size: 0.9rem;">{{ htmlspecialchars($qr->nama_kegiatan ?? 'Tanpa Nama') }}</div>
                                <div style="font-size:0.82rem; color:var(--text-muted);">
                                    <i class="fas fa-clock"></i> 
                                    Masuk: <strong>{{ Carbon\Carbon::parse($qr->cek_in)->format('H:i') }}</strong> | 
                                    Pulang: <strong>{{ Carbon\Carbon::parse($qr->cek_out)->format('H:i') }}</strong> WITA
                                </div>
                                <div style="font-size:0.78rem; color:var(--text-muted); margin-top:2px;">
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
        
        <h3 style="font-size: 1.3rem; color: var(--text-dark); font-weight:700; margin-bottom: 5px;">Detail QR Code</h3>
        <p id="modal-qr-title" style="font-weight: 600; color: var(--primary); font-size:1rem; margin-bottom:15px;"></p>
        
        <div class="modal-qr-img-wrapper">
            <img id="modal-qr-img" src="" alt="QR Code">
        </div>
        
        <div style="margin-bottom: 20px;">
            <span id="modal-qr-code" style="background: rgba(67,97,238,0.08); color: var(--primary); padding: 8px 20px; border-radius: 20px; font-weight: 700; font-size: 0.9rem; display: inline-block;"></span>
        </div>
        
        <div style="background: rgba(67,97,238,0.03); padding: 15px; border-radius: 12px; text-align: left; font-size: 0.88rem; margin-bottom: 20px; border: 1px solid var(--border-light);">
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; border-bottom:1px solid var(--border-light); padding-bottom:5px;">
                <span style="color:var(--text-muted);">Check-in:</span>
                <strong id="modal-qr-cekin"></strong>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; border-bottom:1px solid var(--border-light); padding-bottom:5px;">
                <span style="color:var(--text-muted);">Check-out:</span>
                <strong id="modal-qr-cekout"></strong>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span style="color:var(--text-muted);">Batas Expired:</span>
                <strong id="modal-qr-expired" style="color: var(--danger);"></strong>
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
        win.document.write('<p style="font-size:0.8rem; color:var(--text-muted); margin-top:20px;">Berlaku s/d: ' + expired + '</p>');
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
