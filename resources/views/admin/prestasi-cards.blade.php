@if (empty($ranking))
    @php
        $iconClass = 'fa-calendar-times';
        $msg = 'Tidak ada data kehadiran';
        if ($kategori === 'jam_kerja') { $iconClass = 'fa-clock'; $msg = 'Tidak ada data jam kerja'; }
        elseif ($kategori === 'terlambat') { $iconClass = 'fa-exclamation-triangle'; $msg = 'Tidak ada data keterlambatan'; }
        elseif ($kategori === 'pulang_cepat') { $iconClass = 'fa-running'; $msg = 'Tidak ada data pulang cepat'; }
        elseif ($kategori === 'datang_cepat') { $iconClass = 'fa-bolt'; $msg = 'Tidak ada data datang cepat'; }
    @endphp
    <div class="empty-state">
        <i class="fas {{ $iconClass }}"></i>
        <p>{{ $msg }}</p>
    </div>
@else
    @foreach ($ranking as $index => $user)
        @php
            $rank = $index + 1;
            $initials = strtoupper(substr($user['nama_lengkap'], 0, 1));
            
            // Atur badge class kustom
            $badgeClass = "ranking-{$rank}";
            if ($kategori === 'terlambat' || $kategori === 'pulang_cepat') {
                $badgeClass = 'ranking-warning';
            } elseif ($kategori === 'datang_cepat') {
                $badgeClass = 'ranking-datang-cepat';
            }
        @endphp
        <div class="ranking-card ranking-{{ $rank }}">
            <div class="ranking-badge {{ $badgeClass }}">{{ $rank }}</div>
            <div class="user-avatar-large">{{ $initials }}</div>
            <div class="user-name">{{ htmlspecialchars($user['nama_lengkap']) }}</div>
            <div class="user-position">{{ htmlspecialchars($user['posisi_magang']) }}</div>
            
            <div class="stats-container">
                @if ($kategori === 'hadir')
                    <div class="stat-item">
                        <div class="stat-value stat-hadir">{{ $user['total_hadir'] ?? 0 }}</div>
                        <div class="stat-label">Hari Hadir</div>
                    </div>
                @elseif ($kategori === 'jam_kerja')
                    <div class="stat-item">
                        <div class="stat-value stat-jam-kerja">{{ $user['total_jam_kerja_formatted'] ?? '-' }}</div>
                        <div class="stat-label">Total Jam Kerja</div>
                    </div>
                @elseif ($kategori === 'terlambat')
                    <div class="stat-item">
                        <div class="stat-value stat-terlambat">{{ $user['total_terlambat'] ?? 0 }}</div>
                        <div class="stat-label">Terlambat</div>
                    </div>
                @elseif ($kategori === 'pulang_cepat')
                    <div class="stat-item">
                        <div class="stat-value stat-pulang-cepat">{{ $user['total_pulang_cepat'] ?? 0 }}</div>
                        <div class="stat-label">Pulang Cepat</div>
                    </div>
                @elseif ($kategori === 'datang_cepat')
                    <div class="stat-item">
                        <div class="stat-value stat-datang-cepat">{{ $user['rata_rata_datang_formatted'] ?? '--:--' }}</div>
                        <div class="stat-label">Rata-rata Cek In</div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endif
