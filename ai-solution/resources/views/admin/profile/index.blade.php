@extends('admin.layouts.app')
@section('title', 'Profil Admin')

@section('content')
<style>
    .profile-wrap { display:flex; gap:32px; align-items:flex-start; margin-top:20px; flex-wrap:wrap; }
    .profile-card {
        background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
        padding: 32px; backdrop-filter: blur(12px); width: 280px; text-align: center; flex-shrink:0;
    }
    .profile-avatar-large {
        width: 120px; height: 120px; border-radius: 50%; object-fit: cover;
        border: 4px solid rgba(0,212,255,0.25); margin: 0 auto 16px; display: flex;
        align-items: center; justify-content: center; overflow:hidden;
        background: linear-gradient(135deg, rgba(0,212,255,0.15), rgba(124,58,237,0.15));
        font-size: 40px; color: rgba(255,255,255,0.5); font-weight: 700;
    }
    .profile-avatar-large img { width: 100%; height: 100%; object-fit: cover; }
    .profile-name { font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 4px; }
    .profile-email { font-size: 13px; color: var(--muted); margin-bottom: 12px; }
    .profile-badge {
        display: inline-block; padding: 4px 12px; border-radius: 100px;
        background: rgba(0,212,255,0.1); border: 1px solid rgba(0,212,255,0.2);
        font-size: 11px; font-weight: 700; color: var(--cyan); letter-spacing: 1px; text-transform: uppercase;
    }
    .cms-link-box {
        margin-top: 20px; padding: 14px; border-radius: 12px;
        background: rgba(124,58,237,0.08); border: 1px solid rgba(124,58,237,0.2);
        font-size: 12px; color: rgba(255,255,255,0.6); line-height: 1.5; text-align: left;
    }
    .cms-link-box a { color: #a78bfa; font-weight: 600; text-decoration: none; }
    .cms-link-box a:hover { text-decoration: underline; }

    .form-wrap {
        background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
        padding: 32px; backdrop-filter: blur(12px); flex: 1; min-width: 320px;
    }
    .section-label {
        font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
        color: var(--cyan); margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--border);
    }
    .form-group { margin-bottom:20px; }
    .form-label { display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:7px;letter-spacing:0.5px;text-transform:uppercase; }
    .form-control {
        width:100%; background:rgba(0,0,0,0.25); border:1px solid rgba(255,255,255,0.1);
        color:#fff; padding:12px 14px; border-radius:10px; font-family:'Inter',sans-serif;
        font-size:14px; transition:border-color 0.2s; resize:vertical; box-sizing: border-box;
    }
    .form-control:focus { outline:none; border-color:var(--cyan); box-shadow:0 0 0 3px rgba(0,212,255,0.08); }
    .form-hint { font-size: 11px; color: var(--muted); margin-top: 5px; }
    .btn-submit { background:linear-gradient(135deg,var(--cyan),var(--purple)); color:#fff; padding:12px 28px; border-radius:10px; border:none; font-weight:700; font-size:14px; cursor:pointer; transition:opacity 0.2s; }
    .btn-submit:hover { opacity:0.9; }
    .error-box { background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:16px;border-radius:10px;margin-bottom:24px;font-size:14px; }
    .success-box { background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.3);color:#4ade80;padding:12px 16px;border-radius:8px;margin-bottom:24px;font-size:14px; }
    .upload-btn {
        display: inline-block; background: rgba(0,212,255,0.1); color: var(--cyan);
        border: 1px solid rgba(0,212,255,0.25); padding: 8px 16px; border-radius: 100px;
        font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;
    }
    .upload-btn:hover { background: rgba(0,212,255,0.2); }
</style>

<div class="page-header">
    <div class="page-label">AKUN</div>
    <h1 class="page-title">Profil Admin</h1>
</div>

@if(session('success'))
<div class="success-box">✓ {{ session('success') }}</div>
@endif

@if($errors->any())
<div class="error-box">
    <ul style="margin-left:18px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="profile-wrap">
    {{-- Profil Info Card --}}
    <div class="profile-card">
        <div class="profile-avatar-large" id="avatarPreviewBox">
            @if($user->foto)
                <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto" id="avatarPreviewImg">
            @else
                <span id="avatarPreviewInitials">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            @endif
        </div>
        <div class="profile-name">{{ $user->name }}</div>
        <div class="profile-email">{{ $user->email }}</div>
        <span class="profile-badge">{{ $user->role ?? 'Admin' }}</span>

        <div class="cms-link-box">
            <strong style="color:rgba(255,255,255,0.8);">Profil CEO di Homepage?</strong><br>
            Edit melalui menu
            <a href="{{ route('admin.cms.about.index') }}">CMS → Tentang Kami</a>
        </div>
    </div>

    {{-- Edit Profil Form --}}
    <div class="form-wrap">
        <div class="section-label">Informasi Akun Admin</div>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Foto Profil</label>
                <label class="upload-btn">
                    Pilih Foto Baru
                    <input type="file" name="foto" id="fotoInput" accept="image/*" style="display:none;">
                </label>
                <span id="fotoFileName" style="margin-left:12px;font-size:12px;color:var(--muted);"></span>
                <div class="form-hint">Format: JPG, PNG, WEBP — Maks. 2 MB. Foto ini hanya untuk profil akun admin, bukan foto CEO di Homepage.</div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            {{-- Ganti Password --}}
            <div style="margin-top:28px; padding-top:24px; border-top:1px solid var(--border);">
                <div class="section-label">Ganti Password</div>
                <div class="form-hint" style="margin-bottom:16px;">Biarkan kosong jika tidak ingin mengubah password.</div>

                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" autocomplete="new-password">
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" autocomplete="new-password">
                </div>
            </div>

            <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--border);text-align:right;">
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('fotoInput').addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
            document.getElementById('fotoFileName').textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                const box = document.getElementById('avatarPreviewBox');
                box.innerHTML = `<img src="${e.target.result}" alt="Preview" style="width:100%;height:100%;object-fit:cover;">`;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
