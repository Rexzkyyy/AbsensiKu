@php
    $role = Auth::user()->role ?? 'magang';
@endphp

<!-- Desktop Sidebar -->
<aside class="hidden lg:flex flex-col w-64 bg-white/70 backdrop-blur-xl border-r border-white/50 h-screen shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-20 transition-all duration-300 relative">
    <div class="h-16 flex items-center px-6 border-b border-gray-100">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-fingerprint text-primary-600"></i>
            <span>AbsensiKu</span>
        </h2>
    </div>
    
    <div class="px-6 py-3">
        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700 border border-primary-100">
            <i class="fas fa-circle text-[8px] mr-1.5 text-primary-500"></i>
            {{ $role === 'mentor' ? 'Mentor / Admin' : ucfirst($role) }}
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        @if ($role === 'mentor' || $role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('admin') || Request::is('admin/dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-home w-5 text-center {{ Request::is('admin') || Request::is('admin/dashboard') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.buat_qr') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('admin/buat-qr*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-qrcode w-5 text-center {{ Request::is('admin/buat-qr*') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                Buat QR Code
            </a>
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('admin/users*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-user-cog w-5 text-center {{ Request::is('admin/users*') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                Kelola User
            </a>
            <a href="{{ route('admin.prestasi') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('admin/prestasi*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-chart-line w-5 text-center {{ Request::is('admin/prestasi*') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                Prestasi
            </a>
            <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('admin/laporan*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-file-alt w-5 text-center {{ Request::is('admin/laporan*') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                Laporan
            </a>
        @elseif ($role === 'magang')
            <a href="{{ route('magang.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('magang') || Request::is('magang/dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-home w-5 text-center {{ Request::is('magang') || Request::is('magang/dashboard') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                Dashboard
            </a>
            <a href="{{ route('magang.scan') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('magang/scan*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-camera w-5 text-center {{ Request::is('magang/scan*') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                Scan QR Absensi
            </a>
            <a href="{{ route('magang.riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('magang/riwayat*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-history w-5 text-center {{ Request::is('magang/riwayat*') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                Riwayat Absensi
            </a>
            <a href="{{ route('magang.peserta') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ Request::is('magang/peserta*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-user w-5 text-center {{ Request::is('magang/peserta*') ? 'text-primary-600' : 'text-gray-400' }}"></i>
                Data Peserta
            </a>
        @endif

        <div class="my-4 border-t border-gray-100"></div>

        <a href="{{ route('logout') }}" onclick="return confirmLogout()" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
            <i class="fas fa-sign-out-alt w-5 text-center text-red-500"></i>
            Logout
        </a>
    </nav>
</aside>

<!-- Mobile Bottom Navigation -->
<div class="lg:hidden fixed bottom-4 left-4 right-4 bg-white/70 backdrop-blur-2xl border border-white/60 z-50 px-2 py-2.5 flex justify-around items-center shadow-[0_8px_30px_rgba(0,0,0,0.08)] rounded-3xl">
    @if ($role === 'mentor' || $role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center p-2 text-[10px] sm:text-xs transition-all {{ Request::is('admin') || Request::is('admin/dashboard') ? 'text-blue-600 font-extrabold -translate-y-1' : 'text-slate-400 font-medium hover:text-slate-600' }}">
            <div class="{{ Request::is('admin') || Request::is('admin/dashboard') ? 'bg-blue-100 p-2 rounded-xl' : 'p-2' }}">
                <i class="fas fa-home text-xl"></i>
            </div>
            <span class="mt-1">Beranda</span>
        </a>
        <a href="{{ route('admin.buat_qr') }}" class="flex flex-col items-center p-2 text-[10px] sm:text-xs transition-all {{ Request::is('admin/buat-qr*') ? 'text-blue-600 font-extrabold -translate-y-1' : 'text-slate-400 font-medium hover:text-slate-600' }}">
            <div class="{{ Request::is('admin/buat-qr*') ? 'bg-blue-100 p-2 rounded-xl' : 'p-2' }}">
                <i class="fas fa-qrcode text-xl"></i>
            </div>
            <span class="mt-1">QR Code</span>
        </a>
        <a href="{{ route('admin.users') }}" class="flex flex-col items-center p-2 text-[10px] sm:text-xs transition-all {{ Request::is('admin/users*') ? 'text-blue-600 font-extrabold -translate-y-1' : 'text-slate-400 font-medium hover:text-slate-600' }}">
            <div class="{{ Request::is('admin/users*') ? 'bg-blue-100 p-2 rounded-xl' : 'p-2' }}">
                <i class="fas fa-user-cog text-xl"></i>
            </div>
            <span class="mt-1">Kelola</span>
        </a>
        <a href="{{ route('admin.laporan') }}" class="flex flex-col items-center p-2 text-[10px] sm:text-xs transition-all {{ Request::is('admin/laporan*') ? 'text-blue-600 font-extrabold -translate-y-1' : 'text-slate-400 font-medium hover:text-slate-600' }}">
            <div class="{{ Request::is('admin/laporan*') ? 'bg-blue-100 p-2 rounded-xl' : 'p-2' }}">
                <i class="fas fa-file-alt text-xl"></i>
            </div>
            <span class="mt-1">Laporan</span>
        </a>
        <a href="{{ route('logout') }}" onclick="return confirmLogout()" class="flex flex-col items-center p-2 text-[10px] sm:text-xs transition-all text-red-400 font-medium hover:text-red-600">
            <div class="p-2">
                <i class="fas fa-sign-out-alt text-xl"></i>
            </div>
            <span class="mt-1">Keluar</span>
        </a>
    @elseif ($role === 'magang')
        <a href="{{ route('magang.dashboard') }}" class="flex flex-col items-center p-2 text-[10px] sm:text-xs transition-all {{ Request::is('magang') || Request::is('magang/dashboard') ? 'text-blue-600 font-extrabold -translate-y-1' : 'text-slate-400 font-medium hover:text-slate-600' }}">
            <div class="{{ Request::is('magang') || Request::is('magang/dashboard') ? 'bg-blue-100 p-2 rounded-xl shadow-inner' : 'p-2' }}">
                <i class="fas fa-home text-xl"></i>
            </div>
            <span class="mt-1">Beranda</span>
        </a>
        <a href="{{ route('magang.scan') }}" class="flex flex-col items-center p-2 text-[10px] sm:text-xs transition-all {{ Request::is('magang/scan*') ? 'text-blue-600 font-extrabold -translate-y-1' : 'text-slate-400 font-medium hover:text-slate-600' }}">
            <div class="{{ Request::is('magang/scan*') ? 'bg-blue-100 p-2 rounded-xl shadow-inner' : 'p-2' }}">
                <i class="fas fa-camera text-xl"></i>
            </div>
            <span class="mt-1">Scan</span>
        </a>
        <a href="{{ route('magang.riwayat') }}" class="flex flex-col items-center p-2 text-[10px] sm:text-xs transition-all {{ Request::is('magang/riwayat*') ? 'text-blue-600 font-extrabold -translate-y-1' : 'text-slate-400 font-medium hover:text-slate-600' }}">
            <div class="{{ Request::is('magang/riwayat*') ? 'bg-blue-100 p-2 rounded-xl shadow-inner' : 'p-2' }}">
                <i class="fas fa-history text-xl"></i>
            </div>
            <span class="mt-1">Riwayat</span>
        </a>
        <a href="{{ route('magang.peserta') }}" class="flex flex-col items-center p-2 text-[10px] sm:text-xs transition-all {{ Request::is('magang/peserta*') ? 'text-blue-600 font-extrabold -translate-y-1' : 'text-slate-400 font-medium hover:text-slate-600' }}">
            <div class="{{ Request::is('magang/peserta*') ? 'bg-blue-100 p-2 rounded-xl shadow-inner' : 'p-2' }}">
                <i class="fas fa-user text-xl"></i>
            </div>
            <span class="mt-1">Profil</span>
        </a>
        <a href="{{ route('logout') }}" onclick="return confirmLogout()" class="flex flex-col items-center p-2 text-[10px] sm:text-xs transition-all text-red-400 font-medium hover:text-red-600">
            <div class="p-2">
                <i class="fas fa-sign-out-alt text-xl"></i>
            </div>
            <span class="mt-1">Keluar</span>
        </a>
    @endif
</div>

<script>
    function confirmLogout() {
        if (confirm('Apakah Anda yakin ingin logout?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('logout') }}";
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = "{{ csrf_token() }}";
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
            return false;
        }
        return false;
    }
</script>
