@if (empty($ranking))
    @php
        $iconClass = 'fa-calendar-times';
        $msg = 'Tidak ada data kehadiran';
        if ($kategori === 'jam_kerja') { $iconClass = 'fa-clock'; $msg = 'Tidak ada data jam kerja'; }
        elseif ($kategori === 'terlambat') { $iconClass = 'fa-exclamation-triangle text-amber-500'; $msg = 'Tidak ada data keterlambatan'; }
        elseif ($kategori === 'pulang_cepat') { $iconClass = 'fa-running text-cyan-500'; $msg = 'Tidak ada data pulang cepat'; }
        elseif ($kategori === 'datang_cepat') { $iconClass = 'fa-bolt text-amber-400'; $msg = 'Tidak ada data datang cepat'; }
    @endphp
    <div class="col-span-full flex flex-col items-center justify-center py-16 text-gray-400">
        <i class="fas {{ $iconClass }} text-5xl mb-4 opacity-50"></i>
        <p class="text-sm font-medium">{{ $msg }}</p>
    </div>
@else
    @foreach ($ranking as $index => $user)
        @php
            $rank = $index + 1;
            $initials = strtoupper(substr($user['nama_lengkap'], 0, 1));
            
            // Atur badge class kustom
            $badgeBg = '';
            if ($kategori === 'terlambat' || $kategori === 'pulang_cepat') {
                $badgeBg = 'bg-gradient-to-br from-red-500 to-rose-600 shadow-red-500/30';
            } elseif ($kategori === 'datang_cepat') {
                $badgeBg = 'bg-gradient-to-br from-teal-400 to-emerald-500 shadow-emerald-500/30';
            } else {
                if ($rank == 1) $badgeBg = 'bg-gradient-to-br from-yellow-400 to-amber-500 shadow-yellow-500/30';
                elseif ($rank == 2) $badgeBg = 'bg-gradient-to-br from-gray-300 to-gray-400 shadow-gray-400/30';
                elseif ($rank == 3) $badgeBg = 'bg-gradient-to-br from-orange-400 to-orange-600 shadow-orange-500/30';
                else $badgeBg = 'bg-gradient-to-br from-primary-500 to-blue-600 shadow-primary-500/30';
            }
            
            // Highlight card
            $cardBorder = '';
            if ($rank == 1) $cardBorder = 'border-yellow-200 ring-2 ring-yellow-500/20';
            elseif ($rank == 2) $cardBorder = 'border-gray-200';
            elseif ($rank == 3) $cardBorder = 'border-orange-200';
            else $cardBorder = 'border-gray-100';
        @endphp
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border {{ $cardBorder }} hover:-translate-y-1.5 hover:shadow-md transition-all relative overflow-hidden flex flex-col items-center text-center">
            
            <div class="absolute -top-3 -right-3 w-14 h-14 rounded-full flex items-center justify-center text-white font-black text-lg shadow-lg {{ $badgeBg }}">
                {{ $rank }}
            </div>
            
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 border-4 border-white shadow-sm flex items-center justify-center text-3xl font-black text-gray-500 mb-4 mt-2">
                {{ $initials }}
            </div>
            
            <div class="font-bold text-gray-800 text-lg mb-1 truncate w-full">{{ htmlspecialchars($user['nama_lengkap']) }}</div>
            <div class="text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full mb-5">{{ htmlspecialchars($user['posisi_magang']) }}</div>
            
            <div class="w-full bg-gray-50 rounded-xl p-4 mt-auto">
                @if ($kategori === 'hadir')
                    <div class="flex flex-col items-center">
                        <div class="text-3xl font-black text-emerald-500 mb-1 leading-none">{{ $user['total_hadir'] ?? 0 }}</div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Hari Hadir</div>
                    </div>
                @elseif ($kategori === 'jam_kerja')
                    <div class="flex flex-col items-center">
                        <div class="text-xl font-black text-primary-600 mb-1 leading-none">{{ $user['total_jam_kerja_formatted'] ?? '-' }}</div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Jam Kerja</div>
                    </div>
                @elseif ($kategori === 'terlambat')
                    <div class="flex flex-col items-center">
                        <div class="text-3xl font-black text-amber-500 mb-1 leading-none">{{ $user['total_terlambat'] ?? 0 }}</div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Terlambat</div>
                    </div>
                @elseif ($kategori === 'pulang_cepat')
                    <div class="flex flex-col items-center">
                        <div class="text-3xl font-black text-red-500 mb-1 leading-none">{{ $user['total_pulang_cepat'] ?? 0 }}</div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pulang Cepat</div>
                    </div>
                @elseif ($kategori === 'datang_cepat')
                    <div class="flex flex-col items-center">
                        <div class="text-xl font-black text-teal-500 mb-1 leading-none">{{ $user['rata_rata_datang_formatted'] ?? '--:--' }}</div>
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Rata-rata Cek In</div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endif
