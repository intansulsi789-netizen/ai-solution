@extends('admin.layouts.app')
@section('title', 'CMS - Tentang Kami')

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
    .form-group.full-width { grid-column: 1 / -1; }
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
    .section-title { font-size:16px; font-weight:700; color:var(--cyan); margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 12px; margin-top: 10px; }
    
    .photo-preview-wrap {
        display: flex; align-items: center; gap: 20px; padding: 18px;
        background: rgba(0,0,0,0.20); border: 1px solid var(--border); border-radius: 12px;
    }
    .img-preview {
        width: 90px; height: 90px; object-fit: cover; border-radius: 12px;
        border: 2px solid var(--cyan); flex-shrink: 0; box-shadow: 0 4px 14px rgba(0,0,0,0.4);
    }

    @media (max-width: 768px) {
        .form-wrap { padding: 24px; }
        .form-grid { grid-template-columns: 1fr; gap: 0; }
        .photo-preview-wrap { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="page-header">
    <div class="page-label">CMS / KONTEN WEBSITE</div>
    <h1 class="page-title">Kelola Tentang Kami</h1>
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
    <form action="{{ route('admin.cms.about.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="section-title">Informasi Tentang Kami</div>

        <div class="form-grid">
            <div class="form-group full-width">
                <label class="form-label">Judul Utama</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul', $cms->judul) }}" placeholder="Contoh: Mengubah Masa Depan Bisnis dengan Solusi AI Terdepan">
            </div>

            <div class="form-group full-width">
                <label class="form-label">Deskripsi Perusahaan</label>
                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Tuliskan deskripsi profil perusahaan...">{{ old('deskripsi', $cms->deskripsi) }}</textarea>
            </div>
        </div>

        <div class="section-title" style="margin-top: 36px;">Profil CEO / Founder</div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nama CEO</label>
                <input type="text" name="nama_ceo" class="form-control" value="{{ old('nama_ceo', $cms->nama_ceo) }}" placeholder="Contoh: Hero Swaradani">
            </div>

            <div class="form-group">
                <label class="form-label">Jabatan CEO</label>
                <input type="text" name="jabatan_ceo" class="form-control" value="{{ old('jabatan_ceo', $cms->jabatan_ceo) }}" placeholder="Contoh: Chief Executive Officer & AI Architect">
            </div>

            <div class="form-group full-width">
                <label class="form-label">Deskripsi / Biografi CEO</label>
                <textarea name="deskripsi_ceo" class="form-control" rows="3" placeholder="Tuliskan kata sambutan atau biografi singkat CEO...">{{ old('deskripsi_ceo', $cms->deskripsi_ceo) }}</textarea>
            </div>

            <div class="form-group full-width">
                <label class="form-label">Foto CEO</label>
                <div class="photo-preview-wrap">
                    @if($cms->foto_ceo)
                        <img src="{{ str_starts_with($cms->foto_ceo, 'http') ? $cms->foto_ceo : asset('storage/' . $cms->foto_ceo) }}" alt="Foto CEO" class="img-preview">
                    @else
                        <div class="img-preview" style="display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.05);color:var(--muted);font-size:24px;">👤</div>
                    @endif
                    <div style="flex:1;">
                        <input type="file" name="foto_ceo" class="form-control" accept="image/*">
                        <small style="color:var(--muted); font-size:12px; margin-top:6px; display:block;">Format gambar yang didukung: JPG, PNG, WEBP (Maksimal 2 MB)</small>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top:28px;padding-top:24px;border-top:1px solid var(--border);text-align:right;">
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </div>
    </form>
</div>

@endsection
