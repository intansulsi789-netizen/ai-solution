@extends('admin.layouts.app')
@section('title', 'Edit Artikel')

@section('content')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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
    .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-control {
        width: 100%; background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.1);
        color: #fff; padding: 12px 16px; border-radius: 10px; font-family: 'Inter', sans-serif;
        font-size: 14px; transition: border-color 0.2s, box-shadow 0.2s; resize: vertical;
    }
    .form-control:focus { outline: none; border-color: var(--cyan); box-shadow: 0 0 0 3px rgba(0,212,255,0.08); }
    .btn-submit { background: linear-gradient(135deg, var(--cyan), var(--purple)); color: #fff; padding: 13px 32px; border-radius: 10px; border: none; font-weight: 700; font-size: 14px; cursor: pointer; transition: opacity 0.2s, transform 0.2s; }
    .btn-submit:hover { opacity: 0.95; transform: translateY(-1px); }
    .btn-cancel { background: transparent; color: rgba(255,255,255,0.6); padding: 12px 24px; font-weight: 600; font-size: 14px; text-decoration: none; border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; margin-right: 12px; display: inline-block; transition: all 0.2s; }
    .btn-cancel:hover { background: rgba(255,255,255,0.05); color: #fff; }
    .check-wrap { display: flex; align-items: center; gap: 10px; }
    .check-wrap input[type=checkbox] { width: 18px; height: 18px; cursor: pointer; accent-color: var(--cyan); }
    .error-box { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 16px; border-radius: 10px; margin-bottom: 24px; font-size: 14px; }
    .preview-img { max-height: 140px; object-fit: cover; border-radius: 8px; margin-top: 10px; }
    .ql-toolbar.ql-snow { background: #f8fafc; border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 10px 10px 0 0; border-bottom: none !important; padding: 12px !important; }
    .ql-container.ql-snow { border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 0 0 10px 10px; background: rgba(0,0,0,0.25); color: #fff; font-family: 'Inter', sans-serif; font-size: 15px; }
    .ql-editor { min-height: 300px; line-height: 1.6; }
    .ql-editor.ql-blank::before { color: rgba(255,255,255,0.4); }
    @media (max-width: 768px) {
        .form-wrap { padding: 24px; }
        .form-grid { grid-template-columns: 1fr; gap: 0; }
    }
</style>

<div class="page-header">
    <div class="page-label">MANAJEMEN / ARTIKEL</div>
    <h1 class="page-title">Edit Artikel</h1>
    <p class="page-desc">{{ Str::limit($article->judul, 70) }}</p>
</div>

<div class="form-wrap">
    @if($errors->any())
    <div class="error-box">
        <ul style="margin-left: 18px;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Judul Artikel *</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul', $article->judul) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Slug (URL) *</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $article->slug) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $article->kategori) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal</label>
                <input type="text" name="tanggal" class="form-control" value="{{ old('tanggal', $article->tanggal) }}">
            </div>

            <div class="form-group full-width">
                <label class="form-label">Gambar Banner Artikel (Opsional)</label>
                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" style="padding: 9px 16px;">
                <small style="color: var(--muted); font-size: 12px; margin-top: 4px; display: block;">Format: JPG, PNG, WEBP. Maks 5MB. Kosongkan jika tidak ingin mengubah gambar.</small>
                @if($article->gambar)
                    <img id="preview" class="preview-img" src="{{ str_starts_with($article->gambar, 'http') ? $article->gambar : asset('storage/' . $article->gambar) }}" alt="Preview">
                @else
                    <img id="preview" class="preview-img" src="" alt="Preview" style="display:none;">
                @endif
            </div>

            <div class="form-group full-width">
                <label class="form-label">Ringkasan</label>
                <textarea name="ringkasan" class="form-control" rows="3">{{ old('ringkasan', $article->ringkasan) }}</textarea>
            </div>

            @php
                $iaRaw = old('isi_artikel', $article->isi_artikel);
                $ia = json_decode($iaRaw, true) ?: [];
                $content = $ia['content'] ?? '';
                $takeaway = $ia['takeaway'] ?? '';
                $readTime = $ia['readTime'] ?? '';
            @endphp

            <div class="form-group full-width" style="background: rgba(0,0,0,0.15); padding: 24px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 14px; font-weight: 700; color: var(--cyan); margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Konten Artikel</div>
                
                <div class="form-group">
                    <label class="form-label" style="color:#fff;">Isi Artikel</label>
                    <div id="quill-editor">{!! $content !!}</div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="color:#fff;">Key Takeaway (Kesimpulan)</label>
                    <input type="text" id="ia_takeaway" class="form-control" value="{{ $takeaway }}" placeholder="Contoh: Implementasi AI mempercepat operasional...">
                </div>
            </div>
            
            <input type="hidden" name="isi_artikel" id="isi_artikel" value="{{ $iaRaw }}">

            <div class="form-group full-width">
                <label class="form-label">Status Artikel (Draft/Published)</label>
                <select name="status" class="form-control" style="appearance: auto;">
                    <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft (Simpan Sementara)</option>
                    <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published (Terbitkan)</option>
                </select>
            </div>

            <div class="form-group check-wrap full-width" style="margin-top: -10px;">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $article->is_active) ? 'checked' : '' }}>
                <label for="is_active" style="font-size: 14px; font-weight: 500; cursor: pointer;">Aktifkan Artikel</label>
            </div>
        </div>

        <div style="margin-top: 16px; padding-top: 24px; border-top: 1px solid var(--border); text-align: right;">
            <a href="{{ route('admin.articles.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    const gambar  = document.getElementById('gambar');
    const preview = document.getElementById('preview');
    gambar.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        } else {
            @if($article->gambar)
                preview.src = "{{ str_starts_with($article->gambar, 'http') ? $article->gambar : asset('storage/' . $article->gambar) }}";
            @else
                preview.style.display = 'none';
            @endif
        }
    });
    // Quill Initialization
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Tulis isi artikel di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'clean']
            ]
        }
    });

    // Structured Editor for isi_artikel
    document.querySelector('form').addEventListener('submit', function(e) {
        let contentHtml = quill.root.innerHTML;
        if(contentHtml === '<p><br></p>') contentHtml = '';

        let data = {
            content: contentHtml,
            takeaway: document.getElementById('ia_takeaway') ? document.getElementById('ia_takeaway').value.trim() : "",
            readTime: "{{ $readTime ?? '' }}"
        };
        document.getElementById('isi_artikel').value = JSON.stringify(data);
    });
</script>
@endpush
@endsection
