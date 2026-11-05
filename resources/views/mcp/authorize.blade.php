<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Izinkan Akses Connector - AI Solution</title>
    <style>
        :root {
            --bg: #030712; --card: #0b1530; --border: rgba(0, 212, 255, 0.18);
            --text: #ffffff; --muted: rgba(255, 255, 255, 0.65); --accent: #00d4ff; --accent-2: #7c3aed;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 16px; background: radial-gradient(ellipse at top, #0a1940 0%, var(--bg) 65%);
            color: var(--text); font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .card {
            width: 100%; max-width: 440px; background: var(--card); border: 1px solid var(--border);
            border-radius: 20px; padding: 32px 28px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
        }
        .badge {
            display: inline-flex; align-items: center; gap: 8px; font-size: 11px; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase; color: var(--accent);
            padding: 6px 14px; border: 1px solid var(--border); border-radius: 100px; background: rgba(0, 212, 255, 0.08);
        }
        h1 { font-size: 22px; line-height: 1.3; margin: 18px 0 8px; }
        p { color: var(--muted); font-size: 14px; line-height: 1.6; margin: 0 0 16px; }
        .box { border: 1px solid rgba(255, 255, 255, 0.08); background: rgba(255, 255, 255, 0.03); border-radius: 12px; padding: 14px 16px; margin-bottom: 14px; }
        .label { font-size: 12px; color: var(--muted); margin-bottom: 4px; }
        .value { font-weight: 600; font-size: 14px; word-break: break-word; }
        ul { margin: 6px 0 0; padding-left: 18px; color: var(--muted); font-size: 14px; line-height: 1.7; }
        .redirect { font-size: 12px; color: var(--muted); word-break: break-all; margin-bottom: 20px; }
        .actions { display: flex; gap: 12px; }
        .actions form { flex: 1; margin: 0; }
        button {
            width: 100%; height: 44px; border-radius: 10px; font-size: 14px; font-weight: 700;
            cursor: pointer; font-family: inherit; border: 1px solid transparent;
        }
        .btn-deny { background: transparent; color: var(--text); border-color: rgba(255, 255, 255, 0.2); }
        .btn-approve { background: linear-gradient(135deg, var(--accent), var(--accent-2)); color: #fff; }
        button:disabled { opacity: 0.6; cursor: wait; }
        .switch { margin: 18px 0 0; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
<main class="card">
    <span class="badge">Connector Claude</span>
    <h1>Izinkan <strong>{{ $client->name }}</strong> mengakses akun Anda?</h1>
    <p>Aplikasi ini akan terhubung ke website AI Solution atas nama akun di bawah ini.</p>

    <div class="box">
        <div class="label">Masuk sebagai</div>
        <div class="value">{{ $user->name }} &middot; {{ $user->email }}</div>
    </div>

    <div class="box">
        <div class="label">Aplikasi ini akan bisa:</div>
        <ul>
            <li>Melihat daftar judul &amp; kategori artikel</li>
            <li>Membuat artikel baru berstatus <strong>draft</strong> atas nama Anda</li>
        </ul>
        <div class="label" style="margin-top:10px;">Tidak bisa menerbitkan, mengubah, atau menghapus artikel.</div>
    </div>

    @php($redirectUri = request()->query('redirect_uri', $client->redirect_uris[0] ?? null))
    @if ($redirectUri)
        <div class="redirect">Setelah diizinkan, Anda akan diarahkan ke: {{ $redirectUri }}</div>
    @endif

    <div class="actions">
        <form method="POST" action="{{ route('passport.authorizations.deny') }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="state" value="">
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit" class="btn-deny">Batal</button>
        </form>
        <form method="POST" action="{{ route('passport.authorizations.approve') }}" id="approve-form">
            @csrf
            <input type="hidden" name="state" value="">
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit" class="btn-approve" id="approve-btn">Izinkan</button>
        </form>
    </div>
    <p class="switch">Bukan akun Anda? Klik Batal, keluar dari admin website, lalu hubungkan ulang dari Claude.</p>
</main>
<script>
    document.getElementById('approve-form').addEventListener('submit', function () {
        var btn = document.getElementById('approve-btn');
        btn.disabled = true;
        btn.textContent = 'Menghubungkan...';
    });
</script>
</body>
</html>
