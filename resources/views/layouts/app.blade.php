<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="@yield('meta_description', 'AbsensiKu - Sistem Presensi Digital Badan Pusat Statistik Provinsi Sulawesi Tenggara (BPS Sultra).')">
    <title>@yield('title') - AbsensiKu</title>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (CDN for instant deployment) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    @yield('styles')
</head>

<body
    class="bg-slate-50 text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-blue-500 selection:text-white flex h-screen relative"
    x-data="{ sidebarOpen: false, pageLoaded: false }"
    x-init="setTimeout(() => pageLoaded = true, 50)">

    <!-- Decorative Background Elements -->
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary-400/10 blur-[100px] pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-emerald-400/10 blur-[100px] pointer-events-none"></div>

    <!-- Alpine.js for interactive UI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Sidebar Overlay (Mobile) -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"
        @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden relative z-10">

        <!-- Header -->
        <header
            class="bg-white/70 backdrop-blur-xl border-b border-white/50 shadow-[0_4px_30px_rgba(0,0,0,0.02)] z-30 py-3 px-6 flex items-center justify-between sticky top-0 transition-all duration-300">
            <div class="flex items-center gap-4">
                <!-- Mobile Menu Button -->
                <button @click="sidebarOpen = true"
                    class="lg:hidden text-gray-500 hover:text-primary-600 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 tracking-tight">@yield('header_title')</h1>
            </div>

            <div class="flex items-center gap-3">
                @php
                    $username = Auth::user()->username ?? 'User';
                    $initials = strtoupper(substr($username, 0, 2));
                @endphp
                @if(Auth::user() && Auth::user()->role !== 'magang')
                    <span class="hidden md:block font-semibold text-gray-700 text-sm">Hai, {{ $username }}</span>
                @endif
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-blue-600 text-white flex items-center justify-center font-bold shadow-md">
                    {{ $initials }}
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 relative transition-all duration-500 ease-out transform"
              x-show="pageLoaded"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 translate-y-4"
              x-transition:enter-end="opacity-100 translate-y-0"
              style="display: none;">

            <!-- Session Messages -->
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-lg shadow-sm flex items-start">
                    <i class="fas fa-check-circle text-emerald-500 mt-1 mr-3 text-lg"></i>
                    <div class="text-emerald-800 font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm flex items-start">
                    <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3 text-lg"></i>
                    <div class="text-red-800 font-medium">{{ session('error') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm flex items-start">
                    <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3 text-lg"></i>
                    <div class="text-red-800 font-medium">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Yield Content -->
            @yield('content')

        </main>
    </div>

    <!-- Script to keep the time updated dynamically -->
    <script>
        function updateTime() {
            const dateElement = document.getElementById('current-date');
            const timeElement = document.getElementById('current-time');
            if (!dateElement || !timeElement) return;

            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const dayName = days[now.getDay()];
            const date = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            dateElement.textContent = `${dayName}, ${date} ${monthName} ${year} - ${hours}:${minutes}:${seconds} WITA`;
            timeElement.textContent = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateTime, 1000);
        document.addEventListener('DOMContentLoaded', updateTime);
    </script>
    @yield('scripts')
</body>

</html>