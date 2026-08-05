<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AbsensiKu — Login</title>
    <meta name="description" content="Login ke AbsensiKu - Sistem Presensi Digital BPS Provinsi Sulawesi Tenggara">

    <!-- Inter Font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #5e7bff;
            --primary-dark: #3651d4;
            --secondary: #7209b7;
            --accent: #f72585;
            --bg-base: #f0f2f8;
            --bg-white: #ffffff;
            --glass-bg: rgba(255, 255, 255, 0.55);
            --glass-border: rgba(255, 255, 255, 0.7);
            --glass-shadow: 0 8px 32px rgba(31, 38, 135, 0.12);
            --text-dark: #1a1d2e;
            --text-body: #4a4d60;
            --text-muted: #8b8fa3;
            --border-light: rgba(0, 0, 0, 0.06);
            --transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: var(--bg-base);
            display: flex;
            overflow-x: hidden;
        }

        /* ═══════════════ SPLIT SCREEN LAYOUT ═══════════════ */
        .login-split {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ── Left Panel: Branding ── */
        .brand-panel {
            flex: 1;
            background: linear-gradient(135deg, #4361ee 0%, #7209b7 50%, #f72585 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 50px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles on brand panel */
        .brand-panel::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            top: -150px;
            right: -100px;
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
            bottom: -100px;
            left: -80px;
        }

        .brand-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            max-width: 420px;
        }

        .brand-logo-circle {
            width: 90px;
            height: 90px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .brand-logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 24px;
        }

        .brand-logo-circle i {
            font-size: 2.2rem;
            color: white;
        }

        .brand-content h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 12px;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .brand-content h1 span {
            opacity: 0.85;
            font-weight: 600;
        }

        .brand-tagline {
            font-size: 1.05rem;
            opacity: 0.85;
            font-weight: 400;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        /* Feature badges on brand panel */
        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 100%;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 14px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 16px 20px;
            border-radius: 16px;
            transition: var(--transition);
        }

        .brand-feature:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateX(5px);
        }

        .brand-feature-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .brand-feature-text h4 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .brand-feature-text p {
            font-size: 0.78rem;
            opacity: 0.75;
            font-weight: 400;
        }

        /* ── Right Panel: Login Form ── */
        .form-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 50px;
            background: var(--bg-base);
            position: relative;
        }

        .form-panel::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(67, 97, 238, 0.08), transparent 70%);
            top: 10%;
            right: 10%;
            pointer-events: none;
        }

        .form-panel::after {
            content: '';
            position: absolute;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(114, 9, 183, 0.06), transparent 70%);
            bottom: 15%;
            left: 5%;
            pointer-events: none;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 48px 40px;
            box-shadow: var(--glass-shadow);
            position: relative;
            z-index: 2;
            animation: cardFadeIn 0.6s ease-out both;
        }

        @keyframes cardFadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Top accent line */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 30px;
            right: 30px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--accent));
            border-radius: 0 0 3px 3px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .form-header .greeting-badge {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            background: rgba(67, 97, 238, 0.08);
            border: 1px solid rgba(67, 97, 238, 0.15);
            padding: 6px 16px;
            border-radius: 30px;
            margin-bottom: 16px;
        }

        .form-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
            letter-spacing: -0.01em;
        }

        .form-header p {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Error message */
        .error-alert {
            background: rgba(239, 68, 68, 0.06);
            border: 1px solid rgba(239, 68, 68, 0.15);
            color: #dc2626;
            padding: 14px 18px;
            border-radius: 14px;
            font-size: 0.88rem;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shakeIn 0.5s ease-in-out;
        }

        @keyframes shakeIn {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        /* Form inputs */
        .input-field {
            position: relative;
            margin-bottom: 24px;
        }

        .input-field input {
            width: 100%;
            padding: 16px 16px 16px 48px;
            border: 1.5px solid var(--border-light);
            border-radius: 14px;
            outline: none;
            background: rgba(255, 255, 255, 0.6);
            color: var(--text-dark);
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            transition: var(--transition);
        }

        .input-field input::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }

        .input-field input:focus {
            background: rgba(255, 255, 255, 0.9);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }

        .input-field .field-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.05rem;
            transition: var(--transition);
            pointer-events: none;
        }

        .input-field input:focus ~ .field-icon {
            color: var(--primary);
        }

        .input-field .float-label {
            position: absolute;
            left: 48px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.95rem;
            font-weight: 400;
            color: var(--text-muted);
            pointer-events: none;
            transition: var(--transition);
            background: transparent;
        }

        .input-field input:focus ~ .float-label,
        .input-field input:not(:placeholder-shown) ~ .float-label {
            top: -8px;
            left: 14px;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--primary);
            background: white;
            padding: 2px 8px;
            border-radius: 4px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.25);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            position: relative;
            overflow: hidden;
            margin-top: 8px;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.6s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.35);
        }

        .btn-login:hover::after {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Footer text */
        .form-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        .form-footer i {
            color: var(--primary);
            margin-right: 4px;
        }

        /* ═══════════════ RESPONSIVE ═══════════════ */
        @media (max-width: 960px) {
            .login-split {
                flex-direction: column;
            }

            .brand-panel {
                padding: 40px 30px;
                min-height: auto;
            }

            .brand-content h1 {
                font-size: 1.7rem;
            }

            .brand-tagline {
                margin-bottom: 25px;
                font-size: 0.95rem;
            }

            .brand-features {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .brand-feature {
                flex: 1;
                min-width: 200px;
            }

            .form-panel {
                padding: 40px 30px;
            }

            .login-card {
                padding: 36px 28px;
                max-width: 100%;
            }
        }

        @media (max-width: 600px) {
            .brand-panel {
                padding: 35px 20px;
            }

            .brand-content h1 {
                font-size: 1.4rem;
            }

            .brand-tagline {
                font-size: 0.88rem;
                margin-bottom: 20px;
            }

            .brand-features {
                flex-direction: column;
            }

            .brand-feature {
                padding: 12px 16px;
            }

            .brand-logo-circle {
                width: 70px;
                height: 70px;
                border-radius: 18px;
                margin-bottom: 20px;
            }

            .form-panel {
                padding: 30px 20px;
            }

            .login-card {
                padding: 32px 22px;
                border-radius: 22px;
            }

            .form-header h2 {
                font-size: 1.35rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-split">
        <!-- ═══ Left: Branding Panel ═══ -->
        <div class="brand-panel">
            <div class="brand-content">
                <div class="brand-logo-circle">
                    <img src="{{ asset('assets/img/logo_login.png') }}" alt="Logo BPS" id="brand-logo-img"
                         onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-fingerprint\'></i>';">
                </div>

                <h1>AbsensiKu <span>— BPS Sultra</span></h1>
                <p class="brand-tagline">Sistem Presensi Digital Badan Pusat Statistik Provinsi Sulawesi Tenggara</p>

                <div class="brand-features">
                    <div class="brand-feature">
                        <div class="brand-feature-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="brand-feature-text">
                            <h4>Laporan Real-time</h4>
                            <p>Log absensi masuk secara instan ke dashboard</p>
                        </div>
                    </div>
                    <div class="brand-feature">
                        <div class="brand-feature-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="brand-feature-text">
                            <h4>QR Code Secure</h4>
                            <p>Enkripsi kode QR acak untuk keamanan log</p>
                        </div>
                    </div>
                    <div class="brand-feature">
                        <div class="brand-feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="brand-feature-text">
                            <h4>Multi-Device</h4>
                            <p>Akses dari perangkat mana saja, kapan saja</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ Right: Login Form Panel ═══ -->
        <div class="form-panel">
            <div class="login-card">
                <div class="form-header">
                    <span class="greeting-badge">Selamat Datang</span>
                    <h2>Masuk ke Akun Anda</h2>
                    <p>Silakan login untuk melanjutkan</p>
                </div>

                @if($errors->has('error'))
                    <div class="error-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <div class="input-field">
                        <input type="text" id="username" name="username" placeholder=" " value="{{ old('username') }}" required autofocus>
                        <i class="fas fa-user field-icon"></i>
                        <label class="float-label" for="username">Username / Email</label>
                    </div>

                    <div class="input-field">
                        <input type="password" id="password" name="password" placeholder=" " required>
                        <i class="fas fa-lock field-icon"></i>
                        <label class="float-label" for="password">Password</label>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> &nbsp;Login
                    </button>
                </form>

                <div class="form-footer">
                    <i class="fas fa-shield-alt"></i> Dilindungi oleh sistem keamanan AbsensiKu
                </div>
            </div>
        </div>
    </div>
</body>
</html>
