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
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        },
                        secondary: {
                            500: '#ec4899',
                            600: '#db2777',
                        }
                    },
                    animation: {
                        'gradient-x': 'gradient-x 15s ease infinite',
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        'gradient-x': {
                            '0%, 100%': {
                                'background-size': '200% 200%',
                                'background-position': 'left center'
                            },
                            '50%': {
                                'background-size': '200% 200%',
                                'background-position': 'right center'
                            },
                        },
                        'blob': {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Glassmorphism yang lebih bersih - tanpa blur berlebihan */
        .glass-panel {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.04);
        }

        /* Animated background - lembut */
        .animated-bg {
            background: linear-gradient(-45deg, #f5f3ff, #e0e7ff, #fae8ff, #f0fdf4);
            background-size: 400% 400%;
            animation: gradient-x 15s ease infinite;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.25);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.5);
        }

        /* Reset beberapa style bawaan */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    @yield('styles')
</head>

<body
    class="animated-bg text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-primary-500 selection:text-white flex h-screen relative"
    x-data="{ pageLoaded: false }" x-init="setTimeout(() => pageLoaded = true, 80)">

    <!-- Decorative Background Elements (Animated Blobs) -->
    <div
        class="fixed top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-primary-400/20 blur-[100px] pointer-events-none animate-blob">
    </div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-secondary-500/10 blur-[100px] pointer-events-none animate-blob"
        style="animation-delay: 2s"></div>
    <div class="fixed top-[40%] left-[20%] w-[30%] h-[30%] rounded-full bg-emerald-400/10 blur-[80px] pointer-events-none animate-blob"
        style="animation-delay: 4s"></div>

    <!-- Alpine.js for interactive UI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden relative z-10">

        <!-- Header -->
        <header
            class="glass-panel z-30 py-3 px-6 flex items-center justify-between sticky top-0 transition-all duration-300">
            <div class="flex items-center gap-4">
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

        <!-- Main Content Area - dengan padding bawah agar tidak tertutup bottom nav -->
        <main
            class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 relative transition-all duration-500 ease-out transform pb-20 lg:pb-8"
            x-show="pageLoaded" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
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

    <!-- Script to update time on dashboard (if elements exist) -->
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