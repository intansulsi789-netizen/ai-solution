@extends('admin.layouts.app')
@section('title', 'CMS - Homepage')

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
    .section-title { font-size:16px; font-weight:700; color:var(--cyan); margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 12px; }

    @media (max-width: 768px) {
        .form-wrap { padding: 24px; }
        .form-grid { grid-template-columns: 1fr; gap: 0; }
    }
</style>

<div class="page-header">
    <div class="page-label">CMS / KONTEN WEBSITE</div>
    <h1 class="page-title">Kelola Homepage</h1>
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
    <form action="{{ route('admin.cms.homepage.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="section-title">Hero Section</div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Badge (Teks Kecil di Atas Judul)</label>
                <input type="text" name="hero_badge" class="form-control" value="{{ old('hero_badge', $cms->hero_badge) }}" placeholder="Contoh: ✨ Transformasi Digital Mulai Dari Sini">
            </div>

            <div class="form-group">
                <label class="form-label">Gambar Background Hero (Opsional)</label>
                <input type="file" name="hero_background" id="hero_background" class="form-control" accept="image/*" style="padding: 9px 16px;">
                <small style="color: var(--muted); font-size: 12px; margin-top: 4px; display: block;">Format: JPG, PNG, WEBP. Maks 5MB. Kosongkan jika tidak ingin mengubah.</small>
                @if($cms->hero_background)
                    <img id="preview_hero_bg" src="{{ asset('storage/' . $cms->hero_background) }}" alt="Preview" style="max-height: 100px; object-fit: cover; border-radius: 8px; margin-top: 10px;">
                @else
                    <img id="preview_hero_bg" src="" alt="Preview" style="max-height: 100px; object-fit: cover; border-radius: 8px; margin-top: 10px; display:none;">
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Judul Utama</label>
                <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $cms->hero_title) }}" placeholder="Contoh: Solusi AI Terbaik untuk Bisnis Anda">
            </div>

            <div class="form-group full-width">
                <label class="form-label">Deskripsi</label>
                <textarea name="hero_description" class="form-control" rows="4" placeholder="Tuliskan deskripsi di bawah judul...">{{ old('hero_description', $cms->hero_description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Teks Tombol Utama</label>
                <input type="text" name="hero_btn1_text" class="form-control" value="{{ old('hero_btn1_text', $cms->hero_btn1_text) }}" placeholder="Contoh: Konsultasi Gratis">
            </div>

            <div class="form-group">
                <label class="form-label">Link Tombol Utama</label>
                <input type="text" name="hero_btn1_link" class="form-control" value="{{ old('hero_btn1_link', $cms->hero_btn1_link) }}" placeholder="Contoh: #konsultasi">
            </div>

            <div class="form-group">
                <label class="form-label">Teks Tombol Kedua</label>
                <input type="text" name="hero_btn2_text" class="form-control" value="{{ old('hero_btn2_text', $cms->hero_btn2_text) }}" placeholder="Contoh: Lihat Layanan">
            </div>

            <div class="form-group">
                <label class="form-label">Link Tombol Kedua</label>
                <input type="text" name="hero_btn2_link" class="form-control" value="{{ old('hero_btn2_link', $cms->hero_btn2_link) }}" placeholder="Contoh: #layanan">
            </div>
            <div class="form-group full-width" style="margin-top: 16px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 24px;">
                <label class="form-label">Judul Section Partner</label>
                <input type="text" name="partner_title" class="form-control" value="{{ old('partner_title', $cms->partner_title) }}" placeholder="Contoh: Mitra & Kolaborasi">
            </div>
        </div>

        <div style="margin-top:28px;padding-top:24px;border-top:1px solid var(--border);text-align:right;">
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const heroBgInput = document.getElementById('hero_background');
    const previewHeroBg = document.getElementById('preview_hero_bg');
    heroBgInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            previewHeroBg.src = URL.createObjectURL(file);
            previewHeroBg.style.display = 'block';
        } else {
            @if($cms->hero_background)
                previewHeroBg.src = "{{ asset('storage/' . $cms->hero_background) }}";
                previewHeroBg.style.display = 'block';
            @else
                previewHeroBg.style.display = 'none';
            @endif
        }
    });
</script>
@endpush
@endsection
