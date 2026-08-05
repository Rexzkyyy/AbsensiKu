<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AbsensiKu — Login</title>
    <meta name="description" content="Login AbsensiKu - Sistem Presensi Digital BPS Sulawesi Tenggara">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#8b5cf6">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/logo_login.png') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        cursive: ['Pacifico', 'cursive'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-blue-500 selection:text-white min-h-screen overflow-x-hidden relative">

    <!-- Ambient Glow Backgrounds -->
    <div class="fixed top-[-10%] right-[10%] w-[50vw] h-[50vw] rounded-full bg-blue-500/10 blur-[120px] pointer-events-none"></div>
    <div class="fixed bottom-[-10%] left-[10%] w-[40vw] h-[40vw] rounded-full bg-cyan-500/10 blur-[120px] pointer-events-none"></div>

    <div class="min-h-screen flex flex-col md:flex-row w-full bg-white/40 backdrop-blur-3xl relative z-10">
        
        <!-- Left Side: Branding & Image (Hidden on very small mobile, shown on tablet/desktop) -->
        <div class="hidden md:flex w-full md:w-1/2 relative bg-blue-900 overflow-hidden flex-col justify-end p-8 lg:p-16">
            <!-- Full Bleed Image with Overlay -->
            <img src="/assets/img/logo_login.png" alt="Gedung BPS" class="absolute inset-0 w-full h-full object-cover z-0" style="min-width: 100%; min-height: 100%; margin: 0; padding: 0;">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-blue-900/60 to-transparent z-10"></div>
            
            <!-- Branding Content -->
            <div class="relative z-20 w-full">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center text-white text-xl shadow-lg">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <span class="text-white font-bold tracking-wider uppercase">BPS Sultra</span>
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-extrabold text-white leading-tight mb-2 tracking-tight">
                    Absensi<span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400">Ku</span>
                </h1>
                <h2 class="text-2xl font-bold text-blue-100 mb-4">BPS Sultra</h2>
                <p class="text-slate-300 text-lg max-w-md">
                    Sistem Presensi Digital Badan Pusat Statistik Provinsi <strong class="text-white">Sulawesi Tenggara</strong>
                </p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 min-h-screen flex items-center justify-center p-6 sm:p-12 relative z-20">
            <!-- Mobile Branding (Only visible on small screens) -->
            <div class="md:hidden absolute top-8 left-8 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center text-white shadow-md">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <span class="text-slate-800 font-bold tracking-wide uppercase">BPS Sultra</span>
            </div>

            <div class="w-full max-w-md bg-white/80 backdrop-blur-xl rounded-[2rem] p-8 sm:p-12 shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white">
                <span class="font-cursive text-2xl text-cyan-500 block text-center mb-2 -rotate-3">Selamat Datang!</span>
                <h2 class="text-3xl font-extrabold text-slate-800 text-center mb-2 tracking-tight">Masuk ke Akun Anda</h2>
                <p class="text-center text-slate-500 font-medium mb-8">Silakan login untuk mengakses dashboard</p>

                @if($errors->has('error'))
                    <div class="bg-red-50/80 backdrop-blur-sm border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span class="font-semibold text-sm">{{ $errors->first('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="/login" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="username" class="block text-sm font-bold text-slate-700 mb-2 pl-1">Username / Email</label>
                        <div class="relative group">
                            <input type="text" id="username" name="username" placeholder="Masukkan username atau email" value="{{ old('username') }}" required autofocus
                                class="w-full bg-slate-50 border-2 border-transparent text-slate-800 text-sm rounded-2xl py-4 px-12 transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none shadow-inner">
                            <i class="fas fa-user absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2 pl-1">Password</label>
                        <div class="relative group">
                            <input type="password" id="password" name="password" placeholder="Masukkan password" required
                                class="w-full bg-slate-50 border-2 border-transparent text-slate-800 text-sm rounded-2xl py-4 px-12 pr-12 transition-all duration-300 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none shadow-inner">
                            <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <button type="button" onclick="togglePassword()" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors focus:outline-none p-2 -mr-2">
                                <i class="fas fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 mt-2">
                        Masuk <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="mt-8 flex items-center justify-center gap-2 text-xs font-semibold text-slate-500">
                    <i class="fas fa-shield-check text-emerald-500"></i>
                    <span>Dilindungi oleh keamanan sistem AbsensiKu</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }).catch(err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>
</html>
