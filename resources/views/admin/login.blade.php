<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — AI Solution</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    @include('partials.theme-script')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #020818;
            --surface:   rgba(10, 20, 60, 0.70);
            --border:    rgba(0, 212, 255, 0.20);
            --cyan:      #00d4ff;
            --purple:    #7c3aed;
            --text:      #f0f4ff;
            --muted:     rgba(255,255,255,0.45);
        }

        html[data-theme="light"] {
            --bg:        #f1f5f9;
            --surface:   rgba(255, 255, 255, 0.95);
            --border:    rgba(148, 163, 184, 0.4);
            --text:      #0f172a;
            --muted:     #64748b;
        }

        html[data-theme="light"] .grid-bg {
            background-image:
                linear-gradient(rgba(0, 0, 0, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 0, 0, 0.04) 1px, transparent 1px);
        }
        html[data-theme="light"] .logo-text {
            color: #0f172a;
        }
        html[data-theme="light"] .login-card {
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08), inset 0 1px 0 rgba(255, 255, 255, 1);
        }
        html[data-theme="light"] label {
            color: #334155;
        }
        html[data-theme="light"] input[type="password"] {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #0f172a;
        }
        html[data-theme="light"] input[type="password"]::placeholder {
            color: #94a3b8;
        }
        html[data-theme="light"] .card-title {
            color: #0f172a;
        }
        html[data-theme="light"] .card-sub {
            color: #64748b;
        }
        html[data-theme="light"] .remember-row label {
            color: #64748b;
        }
        html[data-theme="light"] .back-link {
            color: #64748b;
        }
        html[data-theme="light"] .back-link:hover {
            color: #0284c7;
        }

        body {
            background: var(--bg);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* subtle ambient blobs */
        body::before {
            content: '';
            position: fixed;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(124,58,237,0.12) 0%, transparent 70%);
            top: -100px; left: -150px;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(0,212,255,0.10) 0%, transparent 70%);
            bottom: -80px; right: -100px;
            pointer-events: none;
        }

        /* grid overlay */
        .grid-bg {
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 0;
        }

        .login-wrap {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 0 20px;
        }

        /* logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            margin-bottom: 32px;
            text-decoration: none;
        }
        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--cyan), var(--purple));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-icon svg { width: 20px; height: 20px; }
        .logo-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 18px; font-weight: 800;
            color: #fff;
            letter-spacing: -0.3px;
        }
        .logo-text span { color: var(--cyan); }

        /* card */
        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px 36px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 24px 60px rgba(0,0,0,0.45), inset 0 1px 0 rgba(255,255,255,0.07);
            position: relative;
            overflow: hidden;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan), var(--purple), transparent);
        }

        .card-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px; font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }
        .card-sub {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 28px;
        }

        /* alert */
        .alert-error {
            background: rgba(239,68,68,0.10);
            border: 1px solid rgba(239,68,68,0.30);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            color: #fca5a5;
            margin-bottom: 20px;
        }

        /* form */
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: rgba(255,255,255,0.70);
            margin-bottom: 8px;
            letter-spacing: 0.2px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 14px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s;
        }
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 0 3px rgba(0,212,255,0.12);
        }
        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: rgba(255,255,255,0.25);
        }
        .field-error {
            font-size: 12px;
            color: #fca5a5;
            margin-top: 6px;
        }

        /* remember */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        .remember-row input[type="checkbox"] {
            accent-color: var(--cyan);
            width: 15px; height: 15px;
            cursor: pointer;
        }
        .remember-row label {
            margin: 0;
            font-size: 13px;
            font-weight: 400;
            cursor: pointer;
            color: var(--muted);
        }

        /* submit */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #00a8ff 0%, #7c3aed 100%);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0,168,255,0.25);
            letter-spacing: 0.2px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(0,168,255,0.38);
        }
        .btn-login:active { transform: translateY(0); }

        /* back link */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 22px;
            font-size: 13px;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--cyan); }
    </style>
</head>
<body>
<div class="grid-bg"></div>

<div style="position: fixed; top: 20px; right: 20px; z-index: 50;">
    @include('partials.theme-toggle')
</div>

<div class="login-wrap">

    <a href="/" class="logo">
        <div class="logo-icon">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="3" fill="white" opacity="0.95"/>
                <circle cx="5" cy="7" r="1.5" fill="white" opacity="0.75"/>
                <circle cx="19" cy="7" r="1.5" fill="white" opacity="0.75"/>
                <circle cx="5" cy="17" r="1.5" fill="white" opacity="0.75"/>
                <circle cx="19" cy="17" r="1.5" fill="white" opacity="0.75"/>
                <line x1="9" y1="11" x2="6.12" y2="7.88" stroke="white" stroke-width="1.2" opacity="0.65"/>
                <line x1="15" y1="11" x2="17.88" y2="7.88" stroke="white" stroke-width="1.2" opacity="0.65"/>
                <line x1="9" y1="13" x2="6.12" y2="16.12" stroke="white" stroke-width="1.2" opacity="0.65"/>
                <line x1="15" y1="13" x2="17.88" y2="16.12" stroke="white" stroke-width="1.2" opacity="0.65"/>
            </svg>
        </div>
        <span class="logo-text"><span>AI</span> Solution</span>
    </a>

    <div class="login-card">
        <div class="card-title">Admin Login</div>
        <div class="card-sub">Masukkan sandi untuk mengakses panel manajemen AI Solution</div>

        {{-- Error messages --}}
        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" id="login-form">
            @csrf

            <div class="form-group">
                <label for="password">Password Admin</label>
                <div style="position: relative;">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Masukkan sandi admin..."
                        required
                        autofocus
                        autocomplete="current-password"
                        style="padding-right: 42px;"
                    >
                    <button
                        type="button"
                        id="toggle-pwd"
                        onclick="togglePasswordVisibility()"
                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--muted); cursor: pointer; padding: 4px; display: flex; align-items: center;"
                        title="Tampilkan / Sembunyikan Password"
                    >
                        <svg id="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Ingat sesi saya</label>
            </div>

            <button type="submit" class="btn-login" id="btn-admin-login">
                Masuk ke Dashboard
            </button>
        </form>
    </div>

    <a href="/" class="back-link">← Kembali ke halaman utama</a>

</div>

<script>
    function togglePasswordVisibility() {
        const pwd = document.getElementById('password');
        const eye = document.getElementById('eye-icon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            eye.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            pwd.type = 'password';
            eye.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    }
</script>

</body>
</html>
