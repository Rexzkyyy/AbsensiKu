<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="description" content="Login AbsensiKu - Sistem Presensi Digital BPS Sulawesi Tenggara">
    <meta name="theme-color" content="#2563eb">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="AbsensiKu">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/assets/icons/icon-192.png">
    <link rel="preload" as="image" href="/assets/img/logo_login.webp" type="image/webp">
    <title>AbsensiKu - Login</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --blue: #2563eb;
            --cyan: #0891b2;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            background: #f8fafc;
            color: var(--ink);
            overflow: hidden;
        }

        .login-shell {
            display: grid;
            grid-template-columns: minmax(0, 1.04fr) minmax(360px, 0.96fr);
            height: 100dvh;
            min-height: 560px;
            width: 100%;
        }

        .brand-panel {
            background: #0f172a;
            isolation: isolate;
            overflow: hidden;
            padding: clamp(32px, 6vw, 72px);
            position: relative;
        }

        .brand-panel picture,
        .brand-panel img {
            height: 100%;
            inset: 0;
            position: absolute;
            width: 100%;
            z-index: -2;
        }

        .brand-panel img {
            object-fit: cover;
        }

        .brand-panel::after {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.2), rgba(15, 23, 42, 0.92));
            content: "";
            inset: 0;
            position: absolute;
            z-index: -1;
        }

        .brand-content {
            align-content: end;
            color: #ffffff;
            display: grid;
            height: 100%;
            max-width: 620px;
        }

        .brand-kicker,
        .mobile-brand {
            align-items: center;
            display: flex;
            font-weight: 800;
            gap: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .brand-mark {
            align-items: center;
            background: linear-gradient(135deg, #22d3ee, #2563eb);
            border-radius: 14px;
            color: #ffffff;
            display: flex;
            font-size: 1.05rem;
            font-weight: 900;
            height: 46px;
            justify-content: center;
            width: 46px;
        }

        h1 {
            font-size: clamp(3rem, 7vw, 6.6rem);
            letter-spacing: -0.04em;
            line-height: 0.92;
            margin: 26px 0 14px;
        }

        .brand-content p {
            color: #cbd5e1;
            font-size: clamp(1rem, 1.8vw, 1.18rem);
            line-height: 1.7;
            margin: 0;
            max-width: 520px;
        }

        .form-panel {
            align-items: center;
            background:
                radial-gradient(circle at 85% 15%, rgba(34, 211, 238, 0.14), transparent 30%),
                radial-gradient(circle at 15% 80%, rgba(37, 99, 235, 0.12), transparent 34%),
                #ffffff;
            display: flex;
            justify-content: center;
            min-width: 0;
            padding: clamp(20px, 4vw, 56px);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(226, 232, 240, 0.92);
            border-radius: 28px;
            box-shadow: 0 20px 70px rgba(15, 23, 42, 0.08);
            max-width: 430px;
            padding: clamp(24px, 4vw, 42px);
            width: 100%;
        }

        .mobile-brand {
            display: none;
            letter-spacing: 0.04em;
            margin-bottom: 18px;
        }

        .mobile-thumb {
            display: none;
        }

        .welcome {
            color: var(--cyan);
            display: block;
            font-size: 0.82rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            margin-bottom: 8px;
            text-align: center;
            text-transform: uppercase;
        }

        h2 {
            font-size: clamp(1.65rem, 4vw, 2.15rem);
            letter-spacing: -0.03em;
            line-height: 1.08;
            margin: 0;
            text-align: center;
        }

        .subtitle {
            color: var(--muted);
            font-size: 0.94rem;
            font-weight: 600;
            line-height: 1.5;
            margin: 10px 0 26px;
            text-align: center;
        }

        .alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 4px solid #ef4444;
            border-radius: 14px;
            color: #991b1b;
            font-size: 0.86rem;
            font-weight: 700;
            margin-bottom: 16px;
            padding: 12px 14px;
        }

        form {
            display: grid;
            gap: 16px;
        }

        label {
            color: #334155;
            display: block;
            font-size: 0.84rem;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .field {
            position: relative;
        }

        input {
            background: #f8fafc;
            border: 1px solid var(--line);
            border-radius: 16px;
            color: var(--ink);
            font: inherit;
            font-size: 0.95rem;
            font-weight: 650;
            outline: 0;
            padding: 14px 16px;
            transition: border-color 0.16s ease, box-shadow 0.16s ease, background 0.16s ease;
            width: 100%;
        }

        input:focus {
            background: #ffffff;
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .password-field input {
            padding-right: 74px;
        }

        .toggle-password {
            background: transparent;
            border: 0;
            color: var(--blue);
            cursor: pointer;
            font: inherit;
            font-size: 0.8rem;
            font-weight: 900;
            padding: 8px;
            position: absolute;
            right: 8px;
            top: 34px;
        }

        .submit-btn {
            align-items: center;
            background: linear-gradient(135deg, #2563eb, #0891b2);
            border: 0;
            border-radius: 16px;
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.24);
            color: #ffffff;
            cursor: pointer;
            display: flex;
            font: inherit;
            font-weight: 900;
            justify-content: center;
            min-height: 50px;
            padding: 13px 18px;
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }

        .submit-btn:hover {
            box-shadow: 0 18px 34px rgba(37, 99, 235, 0.28);
            transform: translateY(-1px);
        }

        .secure-note {
            align-items: center;
            color: var(--muted);
            display: flex;
            font-size: 0.76rem;
            font-weight: 800;
            gap: 8px;
            justify-content: center;
            margin-top: 20px;
            text-align: center;
        }

        @media (max-width: 780px) {
            body {
                background: #ffffff;
            }

            .login-shell {
                display: block;
                height: 100dvh;
                min-height: 0;
            }

            .brand-panel {
                display: none;
            }

            .form-panel {
                height: 100dvh;
                padding: max(12px, env(safe-area-inset-top)) 14px max(12px, env(safe-area-inset-bottom));
            }

            .login-card {
                border-radius: 22px;
                max-height: calc(100dvh - 24px);
                padding: clamp(16px, 4.5vh, 24px);
            }

            .mobile-brand {
                display: flex;
                font-size: 0.78rem;
            }

            .brand-mark {
                border-radius: 12px;
                height: 40px;
                width: 40px;
            }

            .mobile-thumb {
                border-radius: 18px;
                display: block;
                height: clamp(76px, 17vh, 118px);
                margin-bottom: 16px;
                overflow: hidden;
                width: 100%;
            }

            .mobile-thumb img {
                height: 100%;
                object-fit: cover;
                width: 100%;
            }

            .welcome {
                font-size: 0.72rem;
                margin-bottom: 6px;
            }

            h2 {
                font-size: clamp(1.35rem, 5.4vw, 1.7rem);
            }

            .subtitle {
                font-size: 0.82rem;
                margin: 7px 0 16px;
            }

            form {
                gap: 12px;
            }

            label {
                font-size: 0.78rem;
                margin-bottom: 5px;
            }

            input {
                border-radius: 14px;
                font-size: 0.9rem;
                padding: 12px 14px;
            }

            .toggle-password {
                top: 28px;
            }

            .submit-btn {
                min-height: 46px;
            }

            .secure-note {
                font-size: 0.7rem;
                margin-top: 14px;
            }
        }

        @media (max-height: 620px) and (max-width: 780px) {
            .mobile-thumb {
                height: 64px;
                margin-bottom: 10px;
            }

            .mobile-brand {
                margin-bottom: 10px;
            }

            .subtitle {
                margin-bottom: 12px;
            }

            form {
                gap: 9px;
            }

            input {
                padding-bottom: 10px;
                padding-top: 10px;
            }

            .secure-note {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="login-shell">
        <section class="brand-panel" aria-label="AbsensiKu">
            <picture>
                <source srcset="/assets/img/logo_login.webp" type="image/webp">
                <img src="/assets/img/logo_login.png" alt="Gedung BPS Sultra" width="1146" height="729">
            </picture>
            <div class="brand-content">
                <div class="brand-kicker">
                    <span class="brand-mark">AK</span>
                    <span>BPS Sultra</span>
                </div>
                <h1>AbsensiKu</h1>
                <p>Sistem Presensi Digital Badan Pusat Statistik Provinsi Sulawesi Tenggara.</p>
            </div>
        </section>

        <main class="form-panel">
            <section class="login-card" aria-label="Form login">
                <div class="mobile-brand">
                    <span class="brand-mark">AK</span>
                    <span>BPS Sultra</span>
                </div>

                <picture class="mobile-thumb">
                    <source srcset="/assets/img/logo_login.webp" type="image/webp">
                    <img src="/assets/img/logo_login.png" alt="Gedung BPS Sultra" width="1146" height="729">
                </picture>

                <span class="welcome">Selamat Datang</span>
                <h2>Masuk ke Akun Anda</h2>
                <p class="subtitle">Gunakan username atau email untuk membuka dashboard.</p>

                @if($errors->has('error'))
                    <div class="alert">{{ $errors->first('error') }}</div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div>
                        <label for="username">Username / Email</label>
                        <input type="text" id="username" name="username" placeholder="Masukkan username atau email"
                            value="{{ old('username') }}" required autofocus autocomplete="username">
                    </div>

                    <div class="field password-field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required
                            autocomplete="current-password">
                        <button type="button" onclick="togglePassword()" class="toggle-password" id="toggle-password">Lihat</button>
                    </div>

                    <button type="submit" class="submit-btn">Masuk</button>
                </form>

                <div class="secure-note">
                    <span>Keamanan sistem AbsensiKu aktif</span>
                </div>
            </section>
        </main>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const toggle = document.getElementById('toggle-password');
            const isHidden = password.type === 'password';

            password.type = isHidden ? 'text' : 'password';
            toggle.textContent = isHidden ? 'Tutup' : 'Lihat';
        }

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>

</html>
