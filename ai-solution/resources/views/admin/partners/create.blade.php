@extends('admin.layouts.app')
@section('title', 'Tambah Partner')

@section('content')
<style>
    .form-wrap {
        background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
        padding: 36px; backdrop-filter: blur(12px); max-width: 1200px; width: 100%;
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
        font-size:14px; transition:border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus { outline:none; border-color:var(--cyan); box-shadow:0 0 0 3px rgba(0,212,255,0.08); }
    .upload-zone { border:2px dashed rgba(0,212,255,0.25);border-radius:12px;padding:32px;text-align:center;cursor:pointer;transition:all 0.2s;position:relative;background:rgba(0,0,0,0.15); }
    .upload-zone:hover { border-color:rgba(0,212,255,0.5);background:rgba(0,212,255,0.04); }
    .upload-zone input[type=file] { position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%; }
    .upload-zone .icon { font-size:32px;margin-bottom:8px; }
    .upload-zone p { font-size:13px;color:var(--muted);margin:0; }
    .preview-box { margin-top:16px;padding:16px;background:rgba(0,0,0,0.25);border:1px solid var(--border);border-radius:10px;display:none;align-items:center;gap:16px; }
    .preview-box img { max-height:60px;max-width:160px;object-fit:contain;border-radius:6px;filter:brightness(0) invert(1);opacity:0.85; }
    .preview-box .preview-name { font-size:13px;color:rgba(255,255,255,0.7); }
    .btn-submit { background:linear-gradient(135deg,var(--cyan),var(--purple));color:#fff;padding:13px 32px;border-radius:10px;border:none;font-weight:700;font-size:14px;cursor:pointer;transition:opacity 0.2s, transform 0.2s; }
    .btn-submit:hover { opacity:0.95; transform:translateY(-1px); }
    .btn-cancel { color:rgba(255,255,255,0.6);padding:13px 24px;font-weight:600;font-size:14px;text-decoration:none;border:1px solid rgba(255,255,255,0.1);border-radius:10px;margin-right:12px;display:inline-block;transition:all 0.2s; }
    .btn-cancel:hover { background:rgba(255,255,255,0.05);color:#fff; }
    .check-wrap { display:flex;align-items:center;gap:12px;padding:12px 16px;background:rgba(0,0,0,0.15);border:1px solid rgba(255,255,255,0.08);border-radius:10px;height:46px; }
    .check-wrap input[type=checkbox] { width:18px;height:18px;cursor:pointer;accent-color:var(--cyan); }
    .error-box { background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:16px;border-radius:10px;margin-bottom:24px;font-size:14px; }
    .tab-row { display:flex;gap:0;margin-bottom:14px;border-radius:10px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);max-width:360px; }
    .tab-btn { flex:1;padding:10px 16px;text-align:center;font-size:13px;font-weight:600;cursor:pointer;background:transparent;border:none;color:var(--muted);transition:all 0.2s; }
    .tab-btn.active { background:rgba(0,212,255,0.12);color:var(--cyan); }
    .tab-pane { display:none; } .tab-pane.active { display:block; }

    @media (max-width: 768px) {
        .form-wrap { padding: 24px; }
        .form-grid { grid-template-columns: 1fr; gap: 0; }
    }
</style>

<div class="page-header">
    <div class="page-label">MANAJEMEN / PARTNER</div>
    <h1 class="page-title">Tambah Partner</h1>
</div>

<div class="form-wrap">
    @if($errors->any())
    <div class="error-box"><ul style="margin-left:18px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nama Partner *</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required placeholder="Contoh: Telkom Indonesia">
            </div>

            <div style="display:flex; gap:16px;">
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', 0) }}" min="0">
                </div>

                <div class="form-group" style="flex:1.2;">
                    <label class="form-label">Status</label>
                    <div class="check-wrap">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" style="font-size:13px;font-weight:500;cursor:pointer;color:#fff;margin:0;">Aktif (di Frontend)</label>
                    </div>
                </div>
            </div>

            <div class="form-group full-width">
                <label class="form-label">Upload Logo Partner</label>

                <div class="upload-zone" id="uploadZone">
                    <input type="file" name="logo_file" id="logoFile" accept="image/png,image/jpeg,image/webp,image/svg+xml" required>
                    <div class="icon">🖼️</div>
                    <p>Klik atau drag gambar logo ke sini<br><span style="font-size:11px;opacity:0.6;">Format: PNG, JPG, WEBP, SVG — maks. 2 MB</span></p>
                </div>
                <div class="preview-box" id="filePreview">
                    <img id="filePreviewImg" src="" alt="Preview" style="display:none;">
                    <div class="preview-name" id="filePreviewName">—</div>
                </div>
            </div>
        </div>

        <div style="margin-top:28px;padding-top:24px;border-top:1px solid var(--border);text-align:right;">
            <a href="{{ route('admin.partners.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan Partner</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// File upload preview
document.getElementById('logoFile').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('filePreviewImg');
        img.src = e.target.result;
        img.style.display = 'block';
        document.getElementById('filePreviewName').textContent = file.name;
        document.getElementById('filePreview').style.display = 'flex';
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
@endsection
