@extends('admin.layouts.app')
@section('title', 'CMS - CTA / Kontak')

@section('content')
<style>
    .form-wrap {
        background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
        padding: 36px; backdrop-filter: blur(12px); max-width: 1300px; width: 100%;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    .form-group { margin-bottom: 22px; }
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    .form-label { display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:8px;letter-spacing:0.5px;text-transform:uppercase; }
    .form-control {
        width:100%; background:rgba(0,0,0,0.25); border:1px solid rgba(255,255,255,0.1);
        color:#fff; padding:12px 16px; border-radius:10px; font-family:'Inter',sans-serif;
        font-size:14px; transition:border-color 0.2s, box-shadow 0.2s; resize:vertical;
    }
    .form-control:focus { outline:none; border-color:var(--cyan); box-shadow:0 0 0 3px rgba(0,212,255,0.08); }
    .btn-submit { background:linear-gradient(135deg,var(--cyan),var(--purple)); color:#fff; padding:13px 32px; border-radius:10px; border:none; font-weight:700; font-size:14px; cursor:pointer; transition:opacity 0.2s, transform 0.2s; }
    .btn-submit:hover { opacity:0.95; transform:translateY(-1px); }
    .error-box { background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:16px;border-radius:10px;margin-bottom:24px;font-size:14px; }
    .success-box { background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.3);color:#4ade80;padding:12px 16px;border-radius:8px;margin-bottom:24px;font-size:14px; }
    .section-title { font-size:16px; font-weight:700; color:var(--cyan); margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 12px; grid-column: 1 / -1; }

    @media (max-width: 768px) {
        .form-wrap { padding: 24px; }
        .form-grid { grid-template-columns: 1fr; gap: 0; }
    }
</style>

<div class="page-header">
    <div class="page-label">CMS / KONTEN WEBSITE</div>
    <h1 class="page-title">Kelola CTA / Kontak</h1>
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

<div class="form-wrap">
    <form action="{{ route('admin.cms.contact.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="section-title">Section Call to Action (CTA)</div>

            <div class="form-group full-width">
                <label class="form-label">Judul CTA</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul', $cms->judul) }}" placeholder="Contoh: Siap Bertransformasi Bersama Solusi AI Kami?">
            </div>

            <div class="form-group full-width">
                <label class="form-label">Deskripsi CTA</label>
                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Tuliskan deskripsi penawaran atau ajakan bertindak...">{{ old('deskripsi', $cms->deskripsi) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Teks Tombol CTA</label>
                <input type="text" name="teks_tombol" class="form-control" value="{{ old('teks_tombol', $cms->teks_tombol) }}" placeholder="Contoh: Hubungi Kami Sekarang">
            </div>
            <div class="form-group">
                <label class="form-label">Link Tombol CTA</label>
                <input type="text" name="link_tombol" class="form-control" value="{{ old('link_tombol', $cms->link_tombol) }}" placeholder="Contoh: https://wa.me/6281234567890">
            </div>

            <div class="section-title" style="margin-top: 16px;">Informasi Kontak & Sosial Media</div>

            <div class="form-group">
                <label class="form-label">Nomor WhatsApp</label>
                <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $cms->whatsapp) }}" placeholder="Contoh: 6281234567890">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $cms->email) }}" placeholder="Contoh: info@perusahaan.com">
            </div>

            <div class="form-group full-width">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Tuliskan alamat lengkap kantor...">{{ old('alamat', $cms->alamat) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Instagram</label>
                <input type="text" name="instagram" class="form-control" value="{{ old('instagram', $cms->instagram) }}" placeholder="Contoh: https://instagram.com/username">
            </div>
            <div class="form-group">
                <label class="form-label">LinkedIn</label>
                <input type="text" name="linkedin" class="form-control" value="{{ old('linkedin', $cms->linkedin) }}" placeholder="Contoh: https://linkedin.com/in/username">
            </div>

            <div class="form-group">
                <label class="form-label">YouTube</label>
                <input type="text" name="youtube" class="form-control" value="{{ old('youtube', $cms->youtube) }}" placeholder="Contoh: https://youtube.com/@channel">
            </div>
            <div class="form-group">
                <label class="form-label">TikTok</label>
                <input type="text" name="tiktok" class="form-control" value="{{ old('tiktok', $cms->tiktok) }}" placeholder="Contoh: https://tiktok.com/@username">
            </div>
        </div>

        <div style="margin-top:28px;padding-top:24px;border-top:1px solid var(--border);text-align:right;">
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </div>
    </form>
</div>

@endsection
