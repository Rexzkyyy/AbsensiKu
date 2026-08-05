@extends('layouts.app')

@section('title', 'Data Peserta')
@section('header_title', 'Biodata Magang')

@section('content')

@if ($magang)
<div class="bg-emerald-50 border border-emerald-200 p-5 rounded-3xl mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
            <i class="fas fa-check-circle text-lg"></i>
        </div>
        <div>
            <div class="text-sm font-semibold text-emerald-800">Status Profil Magang</div>
            <div class="font-bold text-emerald-900">Tercatat Aktif</div>
        </div>
    </div>
    <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide bg-emerald-200/50 text-emerald-700 border border-emerald-200">
        {{ $magang->status }}
    </span>
</div>
@else
<div class="bg-rose-50 border border-rose-200 p-5 rounded-2xl mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
            <i class="fas fa-exclamation-triangle text-lg"></i>
        </div>
        <div>
            <div class="text-sm font-semibold text-rose-800">Status Profil Magang</div>
            <div class="font-bold text-rose-900">Belum Dilengkapi</div>
        </div>
    </div>
    <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide bg-rose-200/50 text-rose-700 border border-rose-200">
        Belum Ada
    </span>
</div>
@endif

<div class="bg-white/70 backdrop-blur-2xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-white/80 overflow-hidden mb-24">
    <div class="bg-gradient-to-r from-blue-600 to-cyan-500 px-6 py-5">
        <h3 class="font-extrabold text-white text-xl flex items-center gap-2">
            <i class="fas fa-user-edit text-cyan-200"></i> Lengkapi Biodata Magang
        </h3>
        <p class="text-blue-100 text-sm mt-1 font-medium">Harap isi data berikut dengan lengkap dan benar sesuai SK Magang Anda.</p>
    </div>

    <form method="POST" action="{{ route('magang.peserta.update') }}" class="p-6 md:p-8">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="nama_lengkap">
                    <i class="fas fa-user text-gray-400 mr-1.5 w-4 text-center"></i> Nama Lengkap
                </label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" 
                       value="{{ old('nama_lengkap', $magang->nama_lengkap ?? '') }}"
                       placeholder="Masukkan nama lengkap Anda" required
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none text-gray-800">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="instansi">
                    <i class="fas fa-university text-gray-400 mr-1.5 w-4 text-center"></i> Asal Instansi / Universitas
                </label>
                <input type="text" id="instansi" name="instansi" 
                       value="{{ old('instansi', $magang->instansi ?? '') }}"
                       placeholder="Contoh: Universitas Halu Oleo" required
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none text-gray-800">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="posisi_magang">
                    <i class="fas fa-briefcase text-gray-400 mr-1.5 w-4 text-center"></i> Posisi Magang
                </label>
                <input type="text" id="posisi_magang" name="posisi_magang" 
                       value="{{ old('posisi_magang', $magang->posisi_magang ?? '') }}"
                       placeholder="Contoh: Statistisi / Pranata Komputer" required
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none text-gray-800">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="pembimbing">
                    <i class="fas fa-user-tie text-gray-400 mr-1.5 w-4 text-center"></i> Nama Pembimbing Lapangan
                </label>
                <input type="text" id="pembimbing" name="pembimbing" 
                       value="{{ old('pembimbing', $magang->pembimbing ?? '') }}"
                       placeholder="Contoh: La Ode Haerul Saleh"
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none text-gray-800">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="tanggal_mulai">
                    <i class="fas fa-calendar-alt text-gray-400 mr-1.5 w-4 text-center"></i> Tanggal Mulai Magang
                </label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                       value="{{ old('tanggal_mulai', $magang->tanggal_mulai ?? '') }}" required
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none text-gray-800">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="tanggal_selesai">
                    <i class="fas fa-calendar-check text-gray-400 mr-1.5 w-4 text-center"></i> Tanggal Selesai Magang
                </label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                       value="{{ old('tanggal_selesai', $magang->tanggal_selesai ?? '') }}" required
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none text-gray-800">
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-sm font-semibold text-gray-700 mb-2" for="catatan">
                <i class="fas fa-sticky-note text-gray-400 mr-1.5 w-4 text-center"></i> Catatan Tambahan (Opsional)
            </label>
            <textarea id="catatan" name="catatan" rows="4" 
                      placeholder="Tuliskan catatan tambahan (misal: Batch Magang, No. Surat, dll)"
                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none text-gray-800 resize-y">{{ old('catatan', $magang->catatan ?? '') }}</textarea>
        </div>

        <div>
            <button type="submit" class="w-full md:w-auto md:min-w-[250px] bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-bold py-3.5 px-6 rounded-2xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-1 flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Simpan Data Biodata
            </button>
        </div>
    </form>
</div>
@endsection
