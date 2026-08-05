@extends('layouts.app')

@section('title', 'Prestasi Magang')
@section('header_title', 'Prestasi Peserta Magang')

@section('content')
<div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-blue-600 to-cyan-500 px-6 py-5">
        <h3 class="font-extrabold text-white text-xl flex items-center gap-2">
            <i class="fas fa-filter text-cyan-200"></i> Filter Prestasi
        </h3>
    </div>
    
    <div class="p-6 md:p-8">
        <form id="filterForm" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-semibold text-gray-700 mb-1" for="kegiatan">Pilih Kegiatan</label>
                <select name="kegiatan" id="kegiatan" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800 text-sm">
                    <option value="all">Semua Kegiatan</option>
                    @foreach ($kegiatanList as $kegiatan)
                        <option value="{{ $kegiatan->id_qr }}" {{ $selectedKegiatan == $kegiatan->id_qr ? 'selected' : '' }}>
                            {{ htmlspecialchars($kegiatan->nama_kegiatan) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-1/4">
                <label class="block text-sm font-semibold text-gray-700 mb-1" for="start_date">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800 text-sm" value="{{ htmlspecialchars($startDate ?? '') }}">
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-sm font-semibold text-gray-700 mb-1" for="end_date">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all outline-none text-gray-800 text-sm" value="{{ htmlspecialchars($endDate ?? '') }}">
            </div>
            
            <div class="w-full md:w-auto flex gap-3">
                <button type="submit" class="flex-1 md:flex-none bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-bold py-3 px-6 rounded-2xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-1 flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Terapkan
                </button>
                <a href="{{ route('admin.prestasi') }}" class="flex-1 md:flex-none bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 font-bold py-3 px-5 rounded-2xl transition-all shadow-sm hover:shadow text-center flex items-center justify-center gap-2">
                    <i class="fas fa-redo text-slate-400"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Loading Spinner -->
<div id="loadingSpinner" class="hidden py-12 flex-col items-center justify-center text-primary-500">
    <i class="fas fa-circle-notch fa-spin text-4xl mb-3"></i>
    <p class="text-sm font-medium text-gray-500">Memuat data prestasi...</p>
</div>

<!-- Tabs Container -->
<div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 overflow-hidden mb-8" id="tabsContainer">
    <div class="flex flex-col md:flex-row bg-white/40 border-b border-white/60 divide-y md:divide-y-0 md:divide-x divide-slate-200/50 overflow-x-auto custom-scrollbar">
        <button class="tab-btn active flex-1 px-6 py-5 text-center font-bold text-sm text-slate-500 hover:bg-white/60 hover:text-blue-600 transition flex items-center justify-center gap-2 border-b-[3px] border-transparent" data-tab="hadir">
            <i class="fas fa-calendar-check"></i>
            Top Kehadiran
        </button>
        <button class="tab-btn flex-1 px-6 py-5 text-center font-bold text-sm text-slate-500 hover:bg-white/60 hover:text-blue-600 transition flex items-center justify-center gap-2 border-b-[3px] border-transparent" data-tab="jam_kerja">
            <i class="fas fa-clock"></i>
            Top Jam Kerja
        </button>
        <button class="tab-btn flex-1 px-6 py-5 text-center font-bold text-sm text-slate-500 hover:bg-white/60 hover:text-amber-500 transition flex items-center justify-center gap-2 border-b-[3px] border-transparent" data-tab="terlambat">
            <i class="fas fa-exclamation-triangle"></i>
            Top Keterlambatan
        </button>
        <button class="tab-btn flex-1 px-6 py-5 text-center font-bold text-sm text-slate-500 hover:bg-white/60 hover:text-red-500 transition flex items-center justify-center gap-2 border-b-[3px] border-transparent" data-tab="pulang_cepat">
            <i class="fas fa-running"></i>
            Top Pulang Cepat
        </button>
        <button class="tab-btn flex-1 px-6 py-5 text-center font-bold text-sm text-slate-500 hover:bg-white/60 hover:text-teal-500 transition flex items-center justify-center gap-2 border-b-[3px] border-transparent" data-tab="datang_cepat">
            <i class="fas fa-bolt"></i>
            Top Datang Cepat
        </button>
    </div>

    <div class="p-6 md:p-8">
        <!-- Tab Content - Kehadiran -->
        <div class="tab-content block" id="hadirTab">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="hadirRanking">
                @include('admin.prestasi-cards', ['ranking' => $rankingHadir, 'kategori' => 'hadir'])
            </div>
        </div>

        <!-- Tab Content - Jam Kerja -->
        <div class="tab-content hidden" id="jam_kerjaTab">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="jamKerjaRanking">
                @include('admin.prestasi-cards', ['ranking' => $rankingJamKerja, 'kategori' => 'jam_kerja'])
            </div>
        </div>

        <!-- Tab Content - Terlambat -->
        <div class="tab-content hidden" id="terlambatTab">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="terlambatRanking">
                @include('admin.prestasi-cards', ['ranking' => $rankingTerlambat, 'kategori' => 'terlambat'])
            </div>
        </div>

        <!-- Tab Content - Pulang Cepat -->
        <div class="tab-content hidden" id="pulang_cepatTab">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="pulangCepatRanking">
                @include('admin.prestasi-cards', ['ranking' => $rankingPulangCepat, 'kategori' => 'pulang_cepat'])
            </div>
        </div>

        <!-- Tab Content - Datang Cepat -->
        <div class="tab-content hidden" id="datang_cepatTab">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="datangCepatRanking">
                @include('admin.prestasi-cards', ['ranking' => $rankingDatangCepat, 'kategori' => 'datang_cepat'])
            </div>
        </div>
    </div>
</div>

<style>
    /* Add specific border colors when active for each tab */
    .tab-btn.active[data-tab="hadir"], .tab-btn.active[data-tab="jam_kerja"] { border-bottom-color: #3b82f6; color: #2563eb; background: rgba(255,255,255,0.7); }
    .tab-btn.active[data-tab="terlambat"] { border-bottom-color: #f59e0b; color: #d97706; background: rgba(255,255,255,0.7); }
    .tab-btn.active[data-tab="pulang_cepat"] { border-bottom-color: #ef4444; color: #dc2626; background: rgba(255,255,255,0.7); }
    .tab-btn.active[data-tab="datang_cepat"] { border-bottom-color: #14b8a6; color: #0d9488; background: rgba(255,255,255,0.7); }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-btn');
        const contents = document.querySelectorAll('.tab-content');
        
        // Tab switching
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const target = this.getAttribute('data-tab');
                
                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(c => {
                    c.classList.remove('block');
                    c.classList.add('hidden');
                });
                
                this.classList.add('active');
                const targetContent = document.getElementById(target + 'Tab');
                targetContent.classList.remove('hidden');
                targetContent.classList.add('block');
            });
        });

        // AJAX Form Filter Submission
        const filterForm = document.getElementById('filterForm');
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const loadingSpinner = document.getElementById('loadingSpinner');
            const tabsContainer = document.getElementById('tabsContainer');
            
            loadingSpinner.classList.remove('hidden');
            loadingSpinner.classList.add('flex');
            tabsContainer.style.opacity = '0.5';
            
            const formData = new FormData(this);
            formData.append('ajax_request', '1');
            
            const searchParams = new URLSearchParams();
            for (const pair of formData.entries()) {
                searchParams.append(pair[0], pair[1]);
            }
            
            const ajaxUrl = "{{ route('admin.prestasi.ajax', [], false) }}";
            fetch(ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: searchParams.toString()
            })
            .then(async response => {
                if (!response.ok) {
                    const data = await response.json().catch(() => ({}));
                    throw new Error(data.message || `HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                loadingSpinner.classList.add('hidden');
                loadingSpinner.classList.remove('flex');
                tabsContainer.style.opacity = '1';
                
                renderRanking('hadirRanking', data.hadir, 'hadir');
                renderRanking('jamKerjaRanking', data.jam_kerja, 'jam_kerja');
                renderRanking('terlambatRanking', data.terlambat, 'terlambat');
                renderRanking('pulangCepatRanking', data.pulang_cepat, 'pulang_cepat');
                renderRanking('datangCepatRanking', data.datang_cepat, 'datang_cepat');
            })
            .catch(err => {
                console.error("Gagal memfilter prestasi magang:", err);
                loadingSpinner.classList.add('hidden');
                loadingSpinner.classList.remove('flex');
                tabsContainer.style.opacity = '1';
                alert("Kesalahan: " + err.message);
            });
        });
        
        function renderRanking(containerId, data, kategori) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            
            if (!data || data.length === 0) {
                let iconClass = 'fa-calendar-times text-gray-400';
                let msg = 'Tidak ada data kehadiran';
                if (kategori === 'jam_kerja') { iconClass = 'fa-clock text-gray-400'; msg = 'Tidak ada data jam kerja'; }
                else if (kategori === 'terlambat') { iconClass = 'fa-exclamation-triangle text-amber-500'; msg = 'Tidak ada data keterlambatan'; }
                else if (kategori === 'pulang_cepat') { iconClass = 'fa-running text-cyan-500'; msg = 'Tidak ada data pulang cepat'; }
                else if (kategori === 'datang_cepat') { iconClass = 'fa-bolt text-amber-400'; msg = 'Tidak ada data datang cepat'; }
                
                container.innerHTML = `
                    <div class="col-span-full flex flex-col items-center justify-center py-16 text-gray-400">
                        <i class="fas ${iconClass} text-5xl mb-4 opacity-50"></i>
                        <p class="text-sm font-medium">${msg}</p>
                    </div>
                `;
                return;
            }
            
            data.forEach((user, index) => {
                const rank = index + 1;
                const initials = user.nama_lengkap ? user.nama_lengkap.substring(0, 1).toUpperCase() : '?';
                
                let badgeBg = '';
                if (kategori === 'terlambat' || kategori === 'pulang_cepat') {
                    badgeBg = 'bg-gradient-to-br from-red-500 to-rose-600 shadow-red-500/30';
                } else if (kategori === 'datang_cepat') {
                    badgeBg = 'bg-gradient-to-br from-teal-400 to-emerald-500 shadow-emerald-500/30';
                } else {
                    if (rank == 1) badgeBg = 'bg-gradient-to-br from-yellow-400 to-amber-500 shadow-yellow-500/30';
                    else if (rank == 2) badgeBg = 'bg-gradient-to-br from-gray-300 to-gray-400 shadow-gray-400/30';
                    else if (rank == 3) badgeBg = 'bg-gradient-to-br from-orange-400 to-orange-600 shadow-orange-500/30';
                    else badgeBg = 'bg-gradient-to-br from-primary-500 to-blue-600 shadow-primary-500/30';
                }
                
                let cardBorder = '';
                if (rank == 1) cardBorder = 'border-yellow-200 ring-2 ring-yellow-500/20';
                else if (rank == 2) cardBorder = 'border-gray-200';
                else if (rank == 3) cardBorder = 'border-orange-200';
                else cardBorder = 'border-gray-100';
                
                let statValueHtml = '';
                if (kategori === 'hadir') {
                    statValueHtml = `
                        <div class="flex flex-col items-center">
                            <div class="text-3xl font-black text-emerald-500 mb-1 leading-none">${user.total_hadir || 0}</div>
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Hari Hadir</div>
                        </div>
                    `;
                } else if (kategori === 'jam_kerja') {
                    statValueHtml = `
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-black text-primary-600 mb-1 leading-none">${user.total_jam_kerja_formatted || '-'}</div>
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Jam Kerja</div>
                        </div>
                    `;
                } else if (kategori === 'terlambat') {
                    statValueHtml = `
                        <div class="flex flex-col items-center">
                            <div class="text-3xl font-black text-amber-500 mb-1 leading-none">${user.total_terlambat || 0}</div>
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Terlambat</div>
                        </div>
                    `;
                } else if (kategori === 'pulang_cepat') {
                    statValueHtml = `
                        <div class="flex flex-col items-center">
                            <div class="text-3xl font-black text-red-500 mb-1 leading-none">${user.total_pulang_cepat || 0}</div>
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pulang Cepat</div>
                        </div>
                    `;
                } else if (kategori === 'datang_cepat') {
                    statValueHtml = `
                        <div class="flex flex-col items-center">
                            <div class="text-xl font-black text-teal-500 mb-1 leading-none">${user.rata_rata_datang_formatted || '--:--'}</div>
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Rata-rata Cek In</div>
                        </div>
                    `;
                }
                
                const card = document.createElement('div');
                card.className = `bg-white rounded-2xl p-6 shadow-sm border ${cardBorder} hover:-translate-y-1.5 hover:shadow-md transition-all relative overflow-hidden flex flex-col items-center text-center`;
                card.innerHTML = `
                    <div class="absolute -top-3 -right-3 w-14 h-14 rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg ${badgeBg}">
                        ${rank}
                    </div>
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 border-4 border-white shadow-sm flex items-center justify-center text-3xl font-black text-gray-500 mb-4 mt-2">
                        ${initials}
                    </div>
                    <div class="font-bold text-gray-800 text-lg mb-1 truncate w-full">${escapeHtml(user.nama_lengkap)}</div>
                    <div class="text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full mb-5">${escapeHtml(user.posisi_magang)}</div>
                    <div class="w-full bg-gray-50 rounded-xl p-4 mt-auto">
                        ${statValueHtml}
                    </div>
                `;
                container.appendChild(card);
            });
        }
        
        function escapeHtml(text) {
            if (!text) return '';
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    });
</script>
@endsection
