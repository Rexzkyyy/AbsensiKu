@extends('layouts.app')

@section('title', 'Data Peserta')

@section('header_title', 'Biodata Magang')

@section('styles')
<style>
    .profile-card {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .status-panel {
        background: rgba(67, 97, 238, 0.05);
        border: 1px solid rgba(67, 97, 238, 0.2);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .status-text {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--primary-dark);
    }
    
    .status-pill {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        background: rgba(40, 167, 69, 0.2);
        color: var(--hadir);
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        .status-panel {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }
</style>
@endsection

@section('content')

@if ($magang)
<div class="status-panel">
    <div class="status-text">
        <i class="fas fa-check-circle" style="color: var(--hadir); margin-right: 8px;"></i>
        Status Profil Magang: <strong>Tercatat Aktif</strong>
    </div>
    <span class="status-pill">{{ $magang->status }}</span>
</div>
@else
<div class="status-panel" style="background: rgba(247, 37, 133, 0.05); border-color: rgba(247, 37, 133, 0.2);">
    <div class="status-text" style="color: var(--warning);">
        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
        Status Profil Magang: <strong>Belum Dilengkapi</strong>
    </div>
    <span class="status-pill" style="background: rgba(247, 37, 133, 0.2); color: var(--warning);">Belum Ada</span>
</div>
@endif

<div class="card">
    <h3 class="section-title"><i class="fas fa-user-edit"></i> Lengkapi Biodata Magang</h3>
    <p style="margin-bottom: 25px; color: #666; font-size: 0.95rem; line-height: 1.5;">
        Harap isi data berikut dengan lengkap dan benar sesuai dengan surat keputusan (SK) Magang Anda dari BPS Provinsi Sulawesi Tenggara.
    </p>

    <form method="POST" action="{{ route('magang.peserta.update') }}">
        @csrf
        <div class="profile-card">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="nama_lengkap">
                        <i class="fas fa-user"></i> Nama Lengkap
                    </label>
                    <input type="text" class="form-input" id="nama_lengkap" name="nama_lengkap" 
                           value="{{ old('nama_lengkap', $magang->nama_lengkap ?? '') }}"
                           placeholder="Masukkan nama lengkap Anda" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="instansi">
                        <i class="fas fa-university"></i> Asal Instansi / Universitas
                    </label>
                    <input type="text" class="form-input" id="instansi" name="instansi" 
                           value="{{ old('instansi', $magang->instansi ?? '') }}"
                           placeholder="Contoh: Universitas Halu Oleo" required>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="posisi_magang">
                        <i class="fas fa-briefcase"></i> Posisi Magang
                    </label>
                    <input type="text" class="form-input" id="posisi_magang" name="posisi_magang" 
                           value="{{ old('posisi_magang', $magang->posisi_magang ?? '') }}"
                           placeholder="Contoh: Statistisi / Pranata Komputer" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="pembimbing">
                        <i class="fas fa-user-tie"></i> Nama Pembimbing Lapangan
                    </label>
                    <input type="text" class="form-input" id="pembimbing" name="pembimbing" 
                           value="{{ old('pembimbing', $magang->pembimbing ?? '') }}"
                           placeholder="Contoh: La Ode Haerul Saleh">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="tanggal_mulai">
                        <i class="fas fa-calendar-alt"></i> Tanggal Mulai Magang
                    </label>
                    <input type="date" class="form-input" id="tanggal_mulai" name="tanggal_mulai" 
                           value="{{ old('tanggal_mulai', $magang->tanggal_mulai ?? '') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="tanggal_selesai">
                        <i class="fas fa-calendar-check"></i> Tanggal Selesai Magang
                    </label>
                    <input type="date" class="form-input" id="tanggal_selesai" name="tanggal_selesai" 
                           value="{{ old('tanggal_selesai', $magang->tanggal_selesai ?? '') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="catatan">
                    <i class="fas fa-sticky-note"></i> Catatan Tambahan (Opsional)
                </label>
                <textarea class="form-input" id="catatan" name="catatan" rows="4" 
                          placeholder="Tuliskan catatan tambahan (misal: Batch Magang, No. Surat, dll)">{{ old('catatan', $magang->catatan ?? '') }}</textarea>
            </div>

            <div style="margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 15px;">
                    <i class="fas fa-save"></i> Simpan Data Biodata Magang
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
