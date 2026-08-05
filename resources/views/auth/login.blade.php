<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensiku — Login</title>
    
    <!-- Modern Geometric Fonts from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Vector Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #050711;
            --card-bg: rgba(15, 19, 42, 0.6);
            --border-color: rgba(255, 255, 255, 0.06);
            --accent-orange: #ff7b00;
            --accent-orange-light: #ffa600;
            --accent-purple: #7209b7;
            --accent-cyan: #00b4d8;
            --text-bright: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.55);
            
            /* Typography Scale */
            --font-heading: 'Space Grotesk', sans-serif;
            --font-body: 'Outfit', sans-serif;
            --transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background-color: var(--bg-dark);
            font-family: var(--font-body);
            color: var(--text-bright);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 60px 20px;
            perspective: 1000px; /* Required for 3D card tilt */
        }

        /* 🟣 Liquid Glowing Blobs in Background */
        .glow-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(130px);
            opacity: 0.28;
            z-index: 0;
            pointer-events: none;
        }
        
        .blob-1 {
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, var(--accent-orange), rgba(255, 123, 0, 0));
            top: -150px;
            left: 10%;
            animation: float-blob-1 25s infinite alternate;
        }

        .blob-2 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, var(--accent-purple), rgba(114, 9, 183, 0));
            bottom: -200px;
            right: 10%;
            animation: float-blob-2 30s infinite alternate;
        }

        .blob-3 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, var(--accent-cyan), rgba(0, 180, 216, 0));
            top: 30%;
            right: 25%;
            animation: float-blob-3 20s infinite alternate;
        }

        @keyframes float-blob-1 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(80px, 60px) scale(1.15); }
        }

        @keyframes float-blob-2 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-80px, -60px) scale(1.1); }
        }

        @keyframes float-blob-3 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, -40px) scale(1.2); }
        }

        /* 🌐 Blueprint Tech Lines Mask */
        .grid-lines {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.012) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.012) 1px, transparent 1px);
            background-size: 60px 60px;
            z-index: 0;
            pointer-events: none;
            mask-image: radial-gradient(circle at 50% 50%, black, transparent 80%);
            -webkit-mask-image: radial-gradient(circle at 50% 50%, black, transparent 80%);
        }

        /* ✨ Ambient Floating Particles */
        #particles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: white;
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes drift {
            0% {
                transform: translateY(0) translateX(0) scale(1);
            }
            50% {
                transform: translateY(-80px) translateX(30px) scale(1.3);
            }
            100% {
                transform: translateY(-160px) translateX(10px) scale(0.7);
                opacity: 0;
            }
        }

        /* 🚪 Centered Cohesive Portal Layout */
        .portal-wrapper {
            width: 100%;
            max-width: 480px; /* Dibatasi agar kartu terlihat sangat solid dan proporsional */
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 10;
        }

        /* 🏷️ Header Brand Info */
        .header-brand {
            text-align: center;
            margin-bottom: 30px;
            animation: fadeInDown 0.8s cubic-bezier(0.25, 0.8, 0.25, 1) both;
        }

        .brand-badge {
            font-family: var(--font-heading);
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--accent-orange);
            letter-spacing: 0.25em;
            background: rgba(255, 123, 0, 0.06);
            border: 1px solid rgba(255, 123, 0, 0.15);
            padding: 6px 14px;
            border-radius: 30px;
            display: inline-block;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(255, 123, 0, 0.05);
        }

        .header-brand h1 {
            font-family: var(--font-heading);
            font-size: 1.85rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: white;
            margin-bottom: 4px;
            line-height: 1.2;
        }
        
        .header-brand h1 span {
            background: linear-gradient(135deg, var(--accent-orange), var(--accent-orange-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-brand p {
            font-size: 0.88rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* 🎴 3D Tilt Glassmorphism Login Card */
        .login-box {
            width: 100%;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 45px 35px;
            border-radius: 28px;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            transition: transform 0.15s ease-out, box-shadow 0.3s ease;
            animation: scaleIn 0.8s cubic-bezier(0.25, 0.8, 0.25, 1) both;
        }

        .login-box:hover {
            box-shadow: 0 35px 80px rgba(255, 123, 0, 0.06), 0 30px 70px rgba(0, 0, 0, 0.5);
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* Glowing Top Neon Border Line */
        .login-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-orange), var(--accent-purple), var(--accent-cyan));
            z-index: 10;
        }

        /* 🔦 Interactive Mouse-Spotlight Glowing Effect */
        .login-box::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(500px circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(255, 123, 0, 0.09), transparent 45%);
            z-index: 1;
            pointer-events: none;
            transition: opacity 0.5s ease;
        }

        /* Branding row inside login box */
        .brand-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 35px;
            transform: translateZ(30px); /* 3D Depth effect */
        }

        .brand-logo-img {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(255, 123, 0, 0.08);
            border: 1px solid rgba(255, 123, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--accent-orange);
            box-shadow: 0 8px 20px rgba(255, 123, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .brand-logo-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .brand-titles h3 {
            font-family: var(--font-heading);
            font-size: 1.45rem;
            font-weight: 700;
            color: white;
            line-height: 1.1;
        }

        .brand-titles h3 span {
            color: var(--accent-orange);
        }

        .brand-titles span {
            font-size: 0.72rem;
            color: var(--text-muted);
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* ✍️ Form Inputs with Floating Labels and Left Border Accent */
        .input-group {
            position: relative;
            margin-bottom: 30px;
            transform: translateZ(20px); /* 3D Depth */
        }

        .input-wrapper {
            position: relative;
        }

        input {
            width: 100%;
            padding: 18px 16px 18px 50px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-left: 3px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            outline: none;
            background: rgba(255, 255, 255, 0.02);
            color: var(--text-bright);
            font-size: 0.95rem;
            font-family: var(--font-body);
            transition: var(--transition);
        }

        input:focus {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--accent-orange);
            box-shadow: 0 8px 20px rgba(255, 123, 0, 0.08);
        }

        /* 🛡️ Override Chrome/Edge Autofill Background style */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #0f132a inset !important; /* Matches card glass color */
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
            border-left: 3px solid var(--accent-orange) !important;
        }

        /* Unique Floating Label styles */
        .input-label {
            position: absolute;
            left: 50px;
            top: 50%;
            transform: translateY(-50%);
            font-family: var(--font-heading);
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-muted);
            pointer-events: none;
            transition: var(--transition);
        }

        /* Float the label perfectly when input is focused or not empty */
        input:focus ~ .input-label,
        input:not(:placeholder-shown) ~ .input-label {
            top: -10px;
            left: 12px;
            font-size: 0.76rem;
            font-weight: 700;
            color: var(--accent-orange);
            background: #0f132a; /* Matches card inner solid background for readability */
            padding: 2px 8px;
            border-radius: 4px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.15rem;
            transition: var(--transition);
            pointer-events: none;
        }

        input:focus ~ .input-icon {
            color: var(--accent-orange);
            transform: translateY(-50%) scale(1.05);
        }

        /* 🛑 Modern Crimson Error Badge */
        .error-msg {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.88rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: var(--font-body);
            animation: shake 0.5s ease-in-out;
            transform: translateZ(25px);
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0) translateZ(25px); }
            25% { transform: translateX(-5px) translateZ(25px); }
            75% { transform: translateX(5px) translateZ(25px); }
        }

        /* 🎟️ Button with gloss sweep */
        button {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent-orange), var(--accent-orange-light));
            color: white;
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 8px 25px rgba(255, 123, 0, 0.2);
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            position: relative;
            overflow: hidden;
            transform: translateZ(35px); /* Highest 3D depth */
        }

        button::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
            transition: all 0.6s ease;
        }

        button:hover {
            transform: translateY(-2px) translateZ(40px);
            box-shadow: 0 12px 35px rgba(255, 123, 0, 0.4);
        }

        button:hover::after {
            left: 100%;
        }

        /* 📋 Bottom Features Row (Perfect alignment and spaciousness) */
        .features-row {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 35px;
            width: 100%;
            z-index: 10;
            animation: fadeInUp 0.8s cubic-bezier(0.25, 0.8, 0.25, 1) 0.2s both;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: rgba(15, 19, 42, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.03);
            padding: 16px 20px;
            border-radius: 18px;
            flex: 1;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .feature-item:hover {
            background: rgba(15, 19, 42, 0.5);
            border-color: rgba(255, 123, 0, 0.25);
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(255, 123, 0, 0.07);
            border: 1px solid rgba(255, 123, 0, 0.15);
            color: var(--accent-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            transition: var(--transition);
        }

        .feature-item:hover .feature-icon {
            background: rgba(255, 123, 0, 0.15);
            color: var(--accent-orange-light);
            transform: scale(1.05);
        }

        .feature-item h5 {
            font-family: var(--font-heading);
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            margin-bottom: 4px;
        }

        .feature-item p {
            font-size: 0.76rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        /* 🎬 Keyframe Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* 📱 Flawless Fluid Responsiveness */
        @media (max-width: 768px) {
            body {
                padding: 40px 15px;
            }

            .header-brand {
                margin-bottom: 25px;
            }

            .header-brand h1 {
                font-size: 1.6rem;
            }

            .header-brand p {
                font-size: 0.82rem;
            }

            .login-box {
                padding: 35px 22px;
                border-radius: 24px;
            }

            .features-row {
                flex-direction: column;
                gap: 15px;
                margin-top: 25px;
            }

            .feature-item {
                padding: 16px 20px;
                border-radius: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Background glowing atmospheric mesh lights -->
    <div class="glow-blob blob-1"></div>
    <div class="glow-blob blob-2"></div>
    <div class="glow-blob blob-3"></div>

    <!-- Blueprint mesh background -->
    <div class="grid-lines"></div>

    <!-- Cinematic background drift particles -->
    <div id="particles-container"></div>

    <!-- Centered cohesive portal -->
    <div class="portal-wrapper">
        
        <!-- Header Brand Info -->
        <div class="header-brand">
            <span class="brand-badge">Sistem Presensi Digital</span>
            <h1>Badan Pusat Statistik <span>Sultra</span></h1>
            <p>BPS Provinsi Sulawesi Tenggara</p>
        </div>

        <!-- 3D Parallax & Spotlight Glassmorphism Card -->
        <div class="login-box" id="login-card">
            
            <!-- Branding Row -->
            <div class="brand-row">
                <div class="brand-logo-img">
                    <img src="{{ asset('assets/img/logo_login.png') }}" alt="Logo" class="login-logo-img-el">
                </div>
                <div class="brand-titles">
                    <h3>Absensi<span>Ku</span></h3>
                    <span>Presensi Digital Sultra</span>
                </div>
            </div>

            <!-- Laravel validation error handling -->
            @if($errors->has('error'))
                <div class="error-msg">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                
                <!-- Username Field (Floating Label) -->
                <div class="input-group">
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" placeholder=" " value="{{ old('username') }}" required autofocus>
                        <label class="input-label" for="username">Username / Email</label>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>
                
                <!-- Password Field (Floating Label) -->
                <div class="input-group">
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder=" " required>
                        <label class="input-label" for="password">Password</label>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit">Login</button>
            </form>
        </div>

        <!-- Center aligned outlines feature cards row -->
        <div class="features-row">
            <div class="feature-item">
                <span class="feature-icon"><i class="fas fa-chart-pie"></i></span>
                <div>
                    <h5>Laporan Real-time</h5>
                    <p>Log absensi masuk secara instan ke dashboard.</p>
                </div>
            </div>
            
            <div class="feature-item">
                <span class="feature-icon"><i class="fas fa-qrcode"></i></span>
                <div>
                    <h5>Random QR Secure</h5>
                    <p>Enkripsi kode QR acak menjamin keamanan log.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Scripts for Mouse tracking, 3D Tilt, and Cinematic Particles -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.getElementById('login-card');
            const body = document.body;

            // 1. 🔦 Interactive Spotlight Tracking
            card.addEventListener('mousemove', function(e) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            });

            // 2. 🎴 3D Parallax Tilt Effect (Desktop Only)
            if (window.innerWidth > 768) {
                body.addEventListener('mousemove', function(e) {
                    const xAxis = (window.innerWidth / 2 - e.clientX) / 30; // Tilt speed X
                    const yAxis = (window.innerHeight / 2 - e.clientY) / 30; // Tilt speed Y
                    card.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
                });

                // Reset tilt smoothly when mouse leaves screen area
                body.addEventListener('mouseleave', function() {
                    card.style.transition = 'transform 0.8s cubic-bezier(0.25, 0.8, 0.25, 1)';
                    card.style.transform = `rotateY(0deg) rotateX(0deg)`;
                });

                body.addEventListener('mouseenter', function() {
                    card.style.transition = 'none';
                });
            }

            // 3. ✨ Ambient Floating Particle Generator
            const particlesContainer = document.getElementById('particles-container');
            if (particlesContainer) {
                const particleCount = 30;
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    
                    const size = Math.random() * 4 + 1; // 1px to 5px
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;
                    particle.style.left = `${Math.random() * 100}%`;
                    particle.style.top = `${Math.random() * 100}%`;
                    particle.style.opacity = Math.random() * 0.4 + 0.1;
                    
                    const duration = Math.random() * 20 + 10; // 10s to 30s
                    particle.style.animation = `drift ${duration}s linear infinite`;
                    particle.style.animationDelay = `-${Math.random() * duration}s`;
                    
                    particlesContainer.appendChild(particle);
                }
            }

            // 4. 🖼️ Fallback Logo Handler to avoid HTML parsing errors
            const logoImg = document.querySelector('.login-logo-img-el');
            if (logoImg) {
                logoImg.addEventListener('error', function() {
                    this.style.display = 'none';
                    // Replace the container contents safely with FontAwesome icon
                    const logoContainer = document.querySelector('.brand-logo-img');
                    if (logoContainer) {
                        logoContainer.innerHTML = '<i class="fas fa-fingerprint"></i>';
                    }
                });
            }
        });
    </script>
</body>
</html>
