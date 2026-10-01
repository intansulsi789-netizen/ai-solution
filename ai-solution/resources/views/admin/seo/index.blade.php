@extends('admin.layouts.app')

@section('title', 'SEO Management')

@section('content')
<style>
    .form-wrap {
        background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
        padding: 36px; backdrop-filter: blur(12px); max-width: 1000px; width: 100%;
    }
    .form-group { margin-bottom: 22px; }
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
</style>

<div class="page-header">
    <div class="page-label">CMS / KONTEN WEBSITE</div>
    <h1 class="page-title">SEO Management</h1>
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
    <form action="{{ route('admin.seo.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="section-title">Meta Tags Homepage</div>

        <div class="form-group">
            <label class="form-label">Meta Title</label>
            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $cms->meta_title) }}" placeholder="Contoh: AI Solution - Platform AI Cerdas untuk Semua Pekerjaan">
            <div style="font-size:11px;color:var(--muted);margin-top:5px;">Kosongkan untuk menggunakan judul bawaan.</div>
        </div>

        <div class="form-group">
            <label class="form-label">Meta Description</label>
            <textarea name="meta_description" class="form-control" rows="3" placeholder="Contoh: AI Solution Engine - Platform AI terdepan untuk semua profesi dan industri.">{{ old('meta_description', $cms->meta_description) }}</textarea>
            <div style="font-size:11px;color:var(--muted);margin-top:5px;">Kosongkan untuk menggunakan deskripsi bawaan.</div>
        </div>

        <div style="margin-top:28px;padding-top:24px;border-top:1px solid var(--border);text-align:right;">
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </div>
    </form>
</div>

<div class="form-wrap" style="margin-top: 24px;">
    <div class="section-title">File SEO Otomatis</div>
    <p style="font-size:14px;color:rgba(255,255,255,0.7);margin-bottom:12px;">Sistem telah menghasilkan file SEO dasar secara otomatis:</p>
    <ul style="list-style-type:disc;margin-left:20px;font-size:14px;color:rgba(255,255,255,0.8);">
        <li style="margin-bottom:8px;"><a href="/sitemap.xml" target="_blank" style="color:var(--cyan);text-decoration:none;font-weight:600;">Sitemap (sitemap.xml)</a> &mdash; Berisi struktur halaman untuk diindeks oleh Google.</li>
        <li><a href="/robots.txt" target="_blank" style="color:var(--cyan);text-decoration:none;font-weight:600;">Robots.txt (/robots.txt)</a> &mdash; Mengatur instruksi crawling dan memblokir akses bot ke area admin.</li>
    </ul>
</div>
@endsection
