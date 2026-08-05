@extends('layouts.app')

@section('title', 'Prestasi Magang')

@section('header_title', 'Prestasi Peserta Magang')

@section('styles')
<style>
    /* Filter Styles */
    .filter-container {
        background: white; border-radius: 12px; padding: 20px;
        margin-bottom: 20px; box-shadow: var(--card-shadow);
        border: 1px solid rgba(0,0,0,0.03);
    }
    .filter-form { display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; }
    .filter-group { flex: 1; min-width: 200px; }
    .filter-label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--dark); font-size: 0.9rem; }
    .filter-select, .filter-input {
        width: 100%; padding: 12px 15px; border: 1px solid var(--light-gray);
        border-radius: 10px; background: white; font-size: 0.95rem; transition: var(--transition);
        color: var(--dark);
    }
    .filter-select:focus, .filter-input:focus {
        outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    .filter-actions { display: flex; gap: 10px; }
    
    .btn-filter {
        padding: 12px 20px; background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white; border: none; border-radius: 10px; cursor: pointer;
        font-weight: 600; transition: var(--transition); display: flex; align-items: center; gap: 8px;
    }
    .btn-reset {
        padding: 12px 20px; background: var(--light-gray); color: var(--gray);
        border: none; border-radius: 10px; cursor: pointer;
        font-weight: 600; transition: var(--transition); display: flex; align-items: center; gap: 8px; text-decoration: none;
    }
    
    /* Tabs Styles */
    .tabs-container {
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        margin-bottom: 30px;
        overflow: hidden;
    }
    
    .tabs-header {
        display: flex;
        background: var(--light);
        border-bottom: 1px solid var(--light-gray);
    }
    
    .tab {
        flex: 1;
        padding: 18px 20px;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        font-weight: 600;
        color: var(--gray);
        border-bottom: 3px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .tab:hover {
        background: rgba(67, 97, 238, 0.05);
        color: var(--primary);
    }
    
    .tab.active {
        background: white;
        color: var(--primary);
        border-bottom: 3px solid var(--primary);
    }
    
    .tab-content {
        display: none;
        padding: 30px;
    }
    
    .tab-content.active {
        display: block;
    }
    
    /* Ranking Cards */
    .ranking-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }
    
    .ranking-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        text-align: center;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.03);
    }
    
    .ranking-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    
    .ranking-badge {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    
    .ranking-1 .ranking-badge {
        background: linear-gradient(135deg, var(--gold, #ffd700), #ffed4e);
    }
    
    .ranking-2 .ranking-badge {
        background: linear-gradient(135deg, var(--silver, #c0c0c0), #e8e8e8);
    }
    
    .ranking-3 .ranking-badge {
        background: linear-gradient(135deg, var(--bronze, #cd7f32), #e6b87e);
    }
    
    .ranking-warning .ranking-badge {
        background: linear-gradient(135deg, var(--tidak-hadir), #e35d6a);
    }
    
    .ranking-datang-cepat .ranking-badge {
        background: linear-gradient(135deg, var(--early), #4dffcc);
    }
    
    .user-avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin: 0 auto 15px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: bold;
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        text-transform: uppercase;
    }
    
    .user-name {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--dark);
    }
    
    .user-position {
        color: var(--gray);
        margin-bottom: 15px;
        font-size: 0.95rem;
    }
    
    .stats-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }
    
    .stat-item {
        text-align: center;
        padding: 15px;
        border-radius: 10px;
        background: var(--light);
        min-width: 120px;
    }
    
    .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: var(--gray);
    }
    
    .stat-hadir { color: var(--hadir); }
    .stat-terlambat { color: var(--terlambat); }
    .stat-tidak-hadir { color: var(--tidak-hadir); }
    .stat-pulang-cepat { color: var(--early); }
    .stat-jam-kerja { color: var(--primary); }
    .stat-datang-cepat { color: #20c997; }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--gray);
        grid-column: 1 / -1;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }
    
    /* Loading Spinner */
    .loading-spinner {
        display: none;
        text-align: center;
        padding: 30px;
    }
    
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(67, 97, 238, 0.2);
        border-radius: 50%;
        border-top-color: var(--primary);
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
        .filter-form { flex-direction: column; align-items: stretch; }
        .filter-group { min-width: 100%; }
        .ranking-container { grid-template-columns: 1fr; }
        .stats-container { flex-direction: column; }
        .tabs-header { flex-direction: column; }
        .tab { padding: 15px; }
    }
</style>
@endsection

@section('content')
<div class="filter-container">
    <h3 class="section-title">
        <i class="fas fa-filter"></i> Filter Prestasi
    </h3>
    
    <form id="filterForm" class="filter-form">
        <div class="filter-group">
            <label class="filter-label">Pilih Kegiatan</label>
            <select name="kegiatan" id="kegiatan" class="filter-select">
                <option value="all">Semua Kegiatan</option>
                @foreach ($kegiatanList as $kegiatan)
                    <option value="{{ $kegiatan->id_qr }}" {{ $selectedKegiatan == $kegiatan->id_qr ? 'selected' : '' }}>
                        {{ htmlspecialchars($kegiatan->nama_kegiatan) }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">Dari Tanggal</label>
            <input type="date" name="start_date" id="start_date" class="filter-input" value="{{ htmlspecialchars($startDate ?? '') }}">
        </div>

        <div class="filter-group">
            <label class="filter-label">Sampai Tanggal</label>
            <input type="date" name="end_date" id="end_date" class="filter-input" value="{{ htmlspecialchars($endDate ?? '') }}">
        </div>
        
        <div class="filter-actions">
            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Terapkan
            </button>
            <a href="{{ route('admin.prestasi') }}" class="btn-reset">
                <i class="fas fa-redo"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Loading Spinner -->
<div class="loading-spinner" id="loadingSpinner">
    <div class="spinner"></div>
    <p>Memuat data...</p>
</div>

<!-- Tabs Container -->
<div class="tabs-container" id="tabsContainer">
    <div class="tabs-header">
        <div class="tab active" data-tab="hadir">
            <i class="fas fa-calendar-check"></i>
            Top Kehadiran
        </div>
        <div class="tab" data-tab="jam_kerja">
            <i class="fas fa-clock"></i>
            Top Jam Kerja
        </div>
        <div class="tab" data-tab="terlambat">
            <i class="fas fa-exclamation-triangle"></i>
            Top Keterlambatan
        </div>
        <div class="tab" data-tab="pulang_cepat">
            <i class="fas fa-running"></i>
            Top Pulang Cepat
        </div>
        <div class="tab" data-tab="datang_cepat">
            <i class="fas fa-bolt"></i>
            Top Datang Cepat
        </div>
    </div>

    <!-- Tab Content - Kehadiran -->
    <div class="tab-content active" id="hadirTab">
        <div class="ranking-container" id="hadirRanking">
            @include('admin.prestasi-cards', ['ranking' => $rankingHadir, 'kategori' => 'hadir'])
        </div>
    </div>

    <!-- Tab Content - Jam Kerja -->
    <div class="tab-content" id="jam_kerjaTab">
        <div class="ranking-container" id="jamKerjaRanking">
            @include('admin.prestasi-cards', ['ranking' => $rankingJamKerja, 'kategori' => 'jam_kerja'])
        </div>
    </div>

    <!-- Tab Content - Terlambat -->
    <div class="tab-content" id="terlambatTab">
        <div class="ranking-container" id="terlambatRanking">
            @include('admin.prestasi-cards', ['ranking' => $rankingTerlambat, 'kategori' => 'terlambat'])
        </div>
    </div>

    <!-- Tab Content - Pulang Cepat -->
    <div class="tab-content" id="pulang_cepatTab">
        <div class="ranking-container" id="pulangCepatRanking">
            @include('admin.prestasi-cards', ['ranking' => $rankingPulangCepat, 'kategori' => 'pulang_cepat'])
        </div>
    </div>

    <!-- Tab Content - Datang Cepat -->
    <div class="tab-content" id="datang_cepatTab">
        <div class="ranking-container" id="datangCepatRanking">
            @include('admin.prestasi-cards', ['ranking' => $rankingDatangCepat, 'kategori' => 'datang_cepat'])
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab');
        const contents = document.querySelectorAll('.tab-content');
        
        // Tab switching
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const target = this.getAttribute('data-tab');
                
                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(c => c.classList.remove('active'));
                
                this.classList.add('active');
                document.getElementById(target + 'Tab').classList.add('active');
            });
        });

        // AJAX Form Filter Submission
        const filterForm = document.getElementById('filterForm');
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const loadingSpinner = document.getElementById('loadingSpinner');
            const tabsContainer = document.getElementById('tabsContainer');
            
            loadingSpinner.style.display = 'block';
            tabsContainer.style.opacity = '0.5';
            
            const formData = new FormData(this);
            formData.append('ajax_request', '1');
            
            // Generate request params
            const searchParams = new URLSearchParams();
            for (const pair of formData.entries()) {
                searchParams.append(pair[0], pair[1]);
            }
            
            fetch("{{ route('admin.prestasi.ajax') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: searchParams.toString()
            })
            .then(response => response.json())
            .then(data => {
                loadingSpinner.style.display = 'none';
                tabsContainer.style.opacity = '1';
                
                // Render hasil di masing-masing tab ranking
                renderRanking('hadirRanking', data.hadir, 'hadir');
                renderRanking('jamKerjaRanking', data.jam_kerja, 'jam_kerja');
                renderRanking('terlambatRanking', data.terlambat, 'terlambat');
                renderRanking('pulangCepatRanking', data.pulang_cepat, 'pulang_cepat');
                renderRanking('datangCepatRanking', data.datang_cepat, 'datang_cepat');
            })
            .catch(err => {
                console.error("Gagal memfilter prestasi magang:", err);
                loadingSpinner.style.display = 'none';
                tabsContainer.style.opacity = '1';
                alert("Terjadi kesalahan saat memfilter data.");
            });
        });
        
        // Render helper untuk update view dengan data AJAX
        function renderRanking(containerId, data, kategori) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            
            if (!data || data.length === 0) {
                let iconClass = 'fa-calendar-times';
                let msg = 'Tidak ada data kehadiran';
                if (kategori === 'jam_kerja') { iconClass = 'fa-clock'; msg = 'Tidak ada data jam kerja'; }
                else if (kategori === 'terlambat') { iconClass = 'fa-exclamation-triangle'; msg = 'Tidak ada data keterlambatan'; }
                else if (kategori === 'pulang_cepat') { iconClass = 'fa-running'; msg = 'Tidak ada data pulang cepat'; }
                else if (kategori === 'datang_cepat') { iconClass = 'fa-bolt'; msg = 'Tidak ada data datang cepat'; }
                
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas ${iconClass}"></i>
                        <p>${msg}</p>
                    </div>
                `;
                return;
            }
            
            data.forEach((user, index) => {
                const rank = index + 1;
                const initials = user.nama_lengkap.substring(0, 1).toUpperCase();
                
                let badgeClass = `ranking-${rank}`;
                if (kategori === 'terlambat' || kategori === 'pulang_cepat') {
                    badgeClass = 'ranking-warning';
                } else if (kategori === 'datang_cepat') {
                    badgeClass = 'ranking-datang-cepat';
                }
                
                let statValueHtml = '';
                if (kategori === 'hadir') {
                    statValueHtml = `
                        <div class="stat-item">
                            <div class="stat-value stat-hadir">${user.total_hadir || 0}</div>
                            <div class="stat-label">Hari Hadir</div>
                        </div>
                    `;
                } else if (kategori === 'jam_kerja') {
                    statValueHtml = `
                        <div class="stat-item">
                            <div class="stat-value stat-jam-kerja">${user.total_jam_kerja_formatted || '-'}</div>
                            <div class="stat-label">Total Jam Kerja</div>
                        </div>
                    `;
                } else if (kategori === 'terlambat') {
                    statValueHtml = `
                        <div class="stat-item">
                            <div class="stat-value stat-terlambat">${user.total_terlambat || 0}</div>
                            <div class="stat-label">Terlambat</div>
                        </div>
                    `;
                } else if (kategori === 'pulang_cepat') {
                    statValueHtml = `
                        <div class="stat-item">
                            <div class="stat-value stat-pulang-cepat">${user.total_pulang_cepat || 0}</div>
                            <div class="stat-label">Pulang Cepat</div>
                        </div>
                    `;
                } else if (kategori === 'datang_cepat') {
                    statValueHtml = `
                        <div class="stat-item">
                            <div class="stat-value stat-datang-cepat">${user.rata_rata_datang_formatted || '--:--'}</div>
                            <div class="stat-label">Rata-rata Cek In</div>
                        </div>
                    `;
                }
                
                const card = document.createElement('div');
                card.className = `ranking-card ranking-${rank}`;
                card.innerHTML = `
                    <div class="ranking-badge ${badgeClass}">${rank}</div>
                    <div class="user-avatar-large">${initials}</div>
                    <div class="user-name">${escapeHtml(user.nama_lengkap)}</div>
                    <div class="user-position">${escapeHtml(user.posisi_magang)}</div>
                    <div class="stats-container">
                        ${statValueHtml}
                    </div>
                `;
                container.appendChild(card);
            });
        }
        
        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    });
</script>
@endsection
