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

    /* Image Source Tabs */
    .tab-pills { display: flex; gap: 8px; margin-bottom: 12px; }
    .tab-pill {
        padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
        cursor: pointer; background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.7);
        border: 1px solid rgba(255,255,255,0.1); transition: all 0.2s;
    }
    .tab-pill.active {
        background: rgba(0, 212, 255, 0.15); color: var(--cyan);
        border-color: rgba(0, 212, 255, 0.4);
    }
    .preview-box {
        margin-top: 14px; padding: 12px; background: rgba(0,0,0,0.35);
        border: 1px dashed rgba(255,255,255,0.15); border-radius: 10px;
        display: flex; align-items: center; gap: 16px;
    }
    .preview-img { width: 180px; height: 110px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); }

    /* Status Radio Cards */
    .status-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-top: 8px; }
    .status-card {
        border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.25);
        border-radius: 10px; padding: 14px; cursor: pointer; transition: all 0.2s;
        display: flex; align-items: flex-start; gap: 10px;
    }
    .status-card:hover { border-color: rgba(0,212,255,0.3); background: rgba(0,212,255,0.03); }
    .status-card input[type=radio] { margin-top: 3px; accent-color: var(--cyan); }
    .status-card.selected {
        border-color: var(--cyan); background: rgba(0,212,255,0.08); box-shadow: 0 0 12px rgba(0,212,255,0.15);
    }
    .status-card-title { font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 2px; }
    .status-card-desc { font-size: 11.5px; color: var(--muted); }

    .meta-timestamps {
        display: flex; gap: 24px; padding: 14px 18px; background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; font-size: 12.5px; color: var(--muted);
    }

    .ql-toolbar.ql-snow { background: #f8fafc; border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 10px 10px 0 0; border-bottom: none !important; padding: 12px !important; }
    .ql-container.ql-snow { border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 0 0 10px 10px; background: rgba(0,0,0,0.25); color: #fff; font-family: 'Inter', sans-serif; font-size: 15px; }
    .ql-editor { min-height: 300px; line-height: 1.6; }
    .ql-editor.ql-blank::before { color: rgba(255,255,255,0.4); }

    @media (max-width: 768px) {
        .form-wrap { padding: 24px; }
        .form-grid { grid-template-columns: 1fr; gap: 0; }
        .status-cards { grid-template-columns: 1fr; }
        .meta-timestamps { flex-direction: column; gap: 8px; }
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

    <form id="form-article" action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
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
                <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $article->kategori) }}" placeholder="Contoh: AI & Bisnis, Machine Learning">
            </div>

            <div class="form-group">
                <label class="form-label">Nama Penulis (Opsional)</label>
                <input type="text" name="author_name" class="form-control" value="{{ old('author_name', $article->author_name) }}" placeholder="Contoh: Tim Redaksi AI Solution / Dr. Jane Doe" maxlength="100">
            </div>

            <div class="form-group full-width">
                <label class="form-label">Sumber Referensi Artikel (Opsional)</label>
                <input type="text" name="source_name" class="form-control" value="{{ old('source_name', $article->source_name) }}" placeholder="Contoh: Kementerian Perhubungan RI / McKinsey Insight">
            </div>

            <div class="form-group full-width">
                <label class="form-label">URL Tautan Sumber (Opsional)</label>
                <input type="url" name="source_url" class="form-control" value="{{ old('source_url', $article->source_url) }}" placeholder="https://dephub.go.id/berita/...">
                <small style="color: var(--muted); font-size: 11.5px; margin-top: 4px; display: block;">Tautan langsung ke artikel/laporan rujukan resmi.</small>
            </div>

            <!-- Banner Image Section -->
            @php
                $isCurrentUrl = $article->gambar && (str_starts_with($article->gambar, 'http://') || str_starts_with($article->gambar, 'https://'));
                $defaultTab = old('image_type', $isCurrentUrl ? 'url' : ($article->gambar ? 'current' : 'upload'));
                $initialImgSrc = $article->gambar ? ($isCurrentUrl ? $article->gambar : asset('storage/' . $article->gambar)) : '';
            @endphp
            <div class="form-group full-width" style="background: rgba(0,0,0,0.18); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                <label class="form-label" style="color: var(--cyan); margin-bottom: 12px;">Gambar Banner Artikel</label>

                <div class="tab-pills">
                    @if($article->gambar)
                        <button type="button" class="tab-pill {{ $defaultTab === 'current' ? 'active' : '' }}" id="tab-current">🖼️ Pertahankan Gambar Saat Ini</button>
                    @endif
                    <button type="button" class="tab-pill {{ $defaultTab === 'upload' ? 'active' : '' }}" id="tab-upload">📁 Upload dari Komputer</button>
                    <button type="button" class="tab-pill {{ $defaultTab === 'url' ? 'active' : '' }}" id="tab-url">🌐 Tempel Link Gambar / URL</button>
                </div>
                <input type="hidden" name="image_type" id="image_type" value="{{ $defaultTab }}">

                <!-- Current Notice -->
                <div id="section-current" style="{{ $defaultTab === 'current' ? '' : 'display:none;' }}">
                    <p style="font-size: 13px; color: var(--muted); margin-bottom: 8px;">
                        Gambar banner saat ini tetap digunakan tanpa perubahan.
                    </p>
                </div>

                <!-- Upload Input -->
                <div id="section-upload" style="{{ $defaultTab === 'upload' ? '' : 'display:none;' }}">
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/jpeg,image/png,image/webp,image/jpg" style="padding: 9px 16px;">
                    <small style="color: var(--muted); font-size: 12px; margin-top: 5px; display: block;">Format: JPG, PNG, WEBP. Maks 5MB. Memilih file baru akan menggantikan gambar lama.</small>
                </div>

                <!-- URL Input -->
                <div id="section-url" style="{{ $defaultTab === 'url' ? '' : 'display:none;' }}">
                    <div style="display: flex; gap: 8px;">
                        <input type="url" name="gambar_url" id="gambar_url" class="form-control" value="{{ old('gambar_url', $isCurrentUrl ? $article->gambar : '') }}" placeholder="https://... (Link gambar langsung, Google Images, atau Pinterest)" style="flex: 1;">
                        <button type="button" id="btn-resolve-url" style="background: rgba(0, 212, 255, 0.15); border: 1px solid rgba(0, 212, 255, 0.35); color: var(--cyan); padding: 0 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px;">
                            <span id="btn-resolve-spinner" style="display: none;">⏳</span>
                            <span id="btn-resolve-text">Periksa Link</span>
                        </button>
                    </div>
                    <small style="color: var(--muted); font-size: 12px; margin-top: 5px; display: block;">
                        Mendukung tautan gambar langsung (.jpg, .png, .webp, Unsplash), tautan Google Images (otomatis ekstrak gambar asli), dan pin Pinterest. Memasukkan tautan baru akan menggantikan banner lama saat disimpan.
                    </small>

                    <!-- Opsi: Simpan Salinan ke Server -->
                    <div style="margin-top: 12px; background: rgba(255,255,255,0.03); padding: 10px 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.07); display: flex; align-items: flex-start; gap: 10px;">
                        <input type="checkbox" name="simpan_ke_server" id="simpan_ke_server" value="1" {{ old('simpan_ke_server', '1') === '1' ? 'checked' : '' }} style="margin-top: 3px; accent-color: var(--cyan); width: 16px; height: 16px; cursor: pointer;">
                        <label for="simpan_ke_server" style="font-size: 12.5px; color: var(--text); cursor: pointer; line-height: 1.45; margin: 0;">
                            <strong style="color: #fff;">Simpan salinan gambar ke server lokal</strong> <span style="color: var(--cyan); font-size: 11px; font-weight: 700;">(Direkomendasikan)</span><br>
                            <span style="color: var(--muted);">Mengunduh dan menyimpan gambar secara aman ke server lokal AI Solution agar banner tidak hilang jika situs sumber membatasi akses langsung (hotlink protection) atau tautan dihapus.</span>
                        </label>
                    </div>

                    <!-- Status / Feedback Message -->
                    <div id="url-status-msg" style="display: none; margin-top: 10px; padding: 10px 14px; border-radius: 8px; font-size: 12.5px;"></div>
                </div>

                <!-- Preview Area -->
                <div id="preview-box" class="preview-box" style="{{ $initialImgSrc ? 'display:flex;' : 'display:none;' }}; margin-top: 14px;">
                    <img id="preview-img" class="preview-img" src="{{ $initialImgSrc }}" alt="Preview">
                    <div style="flex: 1;">
                        <div style="font-size: 13px; font-weight: 600; color: #fff; margin-bottom: 4px;" id="preview-title">
                            {{ $article->gambar ? 'Gambar Banner Aktif' : 'Preview Banner' }}
                        </div>
                        <div style="font-size: 12px; color: var(--muted); word-break: break-all;" id="preview-info">
                            {{ $isCurrentUrl ? 'URL Eksternal: ' . Str::limit($article->gambar, 60) : ($article->gambar ? 'File tersimpan: ' . $article->gambar : 'Siap digunakan') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group full-width">
                <label class="form-label">Ringkasan Artikel</label>
                <textarea name="ringkasan" class="form-control" rows="3">{{ old('ringkasan', $article->ringkasan) }}</textarea>
            </div>

            @php
                $iaRaw = old('isi_artikel', $article->isi_artikel);
                $ia = json_decode($iaRaw, true);
                if (is_array($ia)) {
                    $content = $ia['content'] ?? '';
                    $takeaway = $ia['takeaway'] ?? '';
                    $readTime = $ia['readTime'] ?? '';
                } else {
                    $content = $iaRaw ?: '';
                    $takeaway = '';
                    $readTime = '';
                }
            @endphp

            <div class="form-group full-width" style="background: rgba(0,0,0,0.15); padding: 24px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 14px; font-weight: 700; color: var(--cyan); margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Konten &amp; Kesimpulan</div>

                <div class="form-group">
                    <label class="form-label" style="color:#fff;">Isi Artikel</label>
                    <div id="quill-editor">{!! $content !!}</div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="color:#fff;">Key Takeaway (Kesimpulan Utama)</label>
                    <input type="text" id="ia_takeaway" class="form-control" value="{{ $takeaway }}" placeholder="Contoh: Implementasi AI mempercepat operasional...">
                </div>
            </div>

            <input type="hidden" name="isi_artikel" id="isi_artikel" value="{{ $iaRaw }}">

            <!-- Status & Penjadwalan -->
            @php
                $currentStatus = old('status', $article->status);
                $initialPubDate = old('published_at', $article->published_at ? $article->published_at->timezone('Asia/Jakarta')->format('Y-m-d\TH:i') : now('Asia/Jakarta')->format('Y-m-d\TH:i'));
            @endphp
            <div class="form-group full-width" style="background: rgba(0,0,0,0.18); padding: 22px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                <label class="form-label" style="color: var(--cyan); margin-bottom: 12px;">Status Publikasi &amp; Jadwal</label>

                <div class="status-cards">
                    <label class="status-card {{ $currentStatus === 'draft' ? 'selected' : '' }}" id="card-draft">
                        <input type="radio" name="status" value="draft" {{ $currentStatus === 'draft' ? 'checked' : '' }}>
                        <div>
                            <div class="status-card-title">Simpan Draft</div>
                            <div class="status-card-desc">Hanya tersimpan di admin, belum tayang di frontend.</div>
                        </div>
                    </label>

                    <label class="status-card {{ $currentStatus === 'published' ? 'selected' : '' }}" id="card-published">
                        <input type="radio" name="status" value="published" {{ $currentStatus === 'published' ? 'checked' : '' }}>
                        <div>
                            <div class="status-card-title">Publish Sekarang</div>
                            <div class="status-card-desc">Terbitkan langsung dan tayang di website saat ini.</div>
                        </div>
                    </label>

                    <label class="status-card {{ $currentStatus === 'scheduled' ? 'selected' : '' }}" id="card-scheduled">
                        <input type="radio" name="status" value="scheduled" {{ $currentStatus === 'scheduled' ? 'checked' : '' }}>
                        <div>
                            <div class="status-card-title">Jadwalkan Publikasi</div>
                            <div class="status-card-desc">Atur tanggal &amp; jam terbit otomatis di masa mendatang.</div>
                        </div>
                    </label>
                </div>

                <!-- Input Tanggal & Jam Penjadwalan (WIB) -->
                <div id="scheduled-date-wrap" style="margin-top: 18px; {{ $currentStatus === 'draft' ? 'display:none;' : '' }}">
                    <label class="form-label" id="date-label">
                        {{ $currentStatus === 'scheduled' ? 'Jadwal Publikasi Otomatis (WIB) *' : 'Tanggal Publikasi (WIB)' }}
                    </label>
                    <input type="datetime-local" name="published_at" id="published_at" class="form-control" style="max-width: 320px;" value="{{ $initialPubDate }}">
                    <small style="color: var(--muted); font-size: 11.5px; margin-top: 5px; display: block;" id="date-hint">
                        Zona Waktu: WIB (Asia/Jakarta). Artikel terjadwal otomatis tayang setelah waktu tersebut tercapai.
                    </small>
                </div>
            </div>

            <div class="form-group check-wrap full-width">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $article->is_active) ? 'checked' : '' }}>
                <label for="is_active" style="font-size: 14px; font-weight: 500; cursor: pointer;">Aktifkan Artikel (Visibilitas Sistem)</label>
            </div>

            <!-- Metadata info (Timestamps WIB) -->
            <div class="form-group full-width">
                <div class="meta-timestamps">
                    <div>
                        <strong style="color: #fff;">Dibuat:</strong>
                        {{ $article->created_at ? $article->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}
                    </div>
                    <div>
                        <strong style="color: #fff;">Terakhir Diperbarui:</strong>
                        {{ $article->updated_at ? $article->updated_at->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}
                    </div>
                    @if($article->published_at)
                    <div>
                        <strong style="color: var(--cyan);">Publikasi:</strong>
                        {{ $article->published_at->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB' }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div style="margin-top: 16px; padding-top: 24px; border-top: 1px solid var(--border); text-align: right;">
            <a href="{{ route('admin.articles.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit" id="btn-submit-text">Simpan Perubahan</button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    // Image Source Tabs & Preview
    const tabCurrent   = document.getElementById('tab-current');
    const tabUpload    = document.getElementById('tab-upload');
    const tabUrl       = document.getElementById('tab-url');
    const imageType    = document.getElementById('image_type');
    const secCurrent   = document.getElementById('section-current');
    const secUpload    = document.getElementById('section-upload');
    const secUrl       = document.getElementById('section-url');
    const inputGambar  = document.getElementById('gambar');
    const inputUrl     = document.getElementById('gambar_url');
    const previewBox   = document.getElementById('preview-box');
    const previewImg   = document.getElementById('preview-img');
    const previewTitle = document.getElementById('preview-title');
    const previewInfo  = document.getElementById('preview-info');
    const btnResolve   = document.getElementById('btn-resolve-url');
    const btnResolveSpinner = document.getElementById('btn-resolve-spinner');
    const btnResolveText    = document.getElementById('btn-resolve-text');
    const urlStatusMsg = document.getElementById('url-status-msg');

    const originalImgSrc = "{{ $initialImgSrc }}";

    function showStatusMessage(type, msg) {
        if (!urlStatusMsg) return;
        urlStatusMsg.style.display = 'block';
        if (type === 'success') {
            urlStatusMsg.style.background = 'rgba(16, 185, 129, 0.15)';
            urlStatusMsg.style.border = '1px solid rgba(16, 185, 129, 0.35)';
            urlStatusMsg.style.color = '#34d399';
        } else if (type === 'warning') {
            urlStatusMsg.style.background = 'rgba(245, 158, 11, 0.15)';
            urlStatusMsg.style.border = '1px solid rgba(245, 158, 11, 0.35)';
            urlStatusMsg.style.color = '#fbbf24';
        } else {
            urlStatusMsg.style.background = 'rgba(239, 68, 68, 0.15)';
            urlStatusMsg.style.border = '1px solid rgba(239, 68, 68, 0.35)';
            urlStatusMsg.style.color = '#f87171';
        }
        urlStatusMsg.innerHTML = msg;
    }

    function hideStatusMessage() {
        if (!urlStatusMsg) return;
        urlStatusMsg.style.display = 'none';
        urlStatusMsg.innerHTML = '';
    }

    function checkAndExtractGoogleUrl(rawUrl) {
        if (!rawUrl) return null;
        try {
            if (rawUrl.includes('google.') && (rawUrl.includes('/imgres') || rawUrl.includes('imgurl='))) {
                const parsed = new URL(rawUrl);
                const imgParam = parsed.searchParams.get('imgurl');
                if (imgParam && (imgParam.startsWith('http://') || imgParam.startsWith('https://'))) {
                    return decodeURIComponent(imgParam);
                }
            }
        } catch (e) {}
        return null;
    }

    function resetPillClasses() {
        if (tabCurrent) tabCurrent.classList.remove('active');
        tabUpload.classList.remove('active');
        tabUrl.classList.remove('active');
        if (secCurrent) secCurrent.style.display = 'none';
        secUpload.style.display = 'none';
        secUrl.style.display = 'none';
        hideStatusMessage();
    }

    if (tabCurrent) {
        tabCurrent.addEventListener('click', function() {
            resetPillClasses();
            tabCurrent.classList.add('active');
            imageType.value = 'current';
            secCurrent.style.display = 'block';
            if (originalImgSrc) {
                previewImg.src = originalImgSrc;
                previewTitle.textContent = 'Gambar Banner Aktif (Dipertahankan)';
                previewInfo.textContent = 'Tidak akan diganti saat artikel disimpan';
                previewBox.style.display = 'flex';
            }
        });
    }

    tabUpload.addEventListener('click', function() {
        resetPillClasses();
        tabUpload.classList.add('active');
        imageType.value = 'upload';
        secUpload.style.display = 'block';
        updateImagePreview(false);
    });

    tabUrl.addEventListener('click', function() {
        resetPillClasses();
        tabUrl.classList.add('active');
        imageType.value = 'url';
        secUrl.style.display = 'block';
        updateImagePreview(false);
    });

    function updateImagePreview(autoResolve = true) {
        if (imageType.value === 'url') {
            let urlVal = inputUrl.value.trim();
            if (!urlVal) {
                if (originalImgSrc) {
                    previewImg.src = originalImgSrc;
                    previewTitle.textContent = 'Gambar Banner Aktif';
                    previewInfo.textContent = 'Masukkan URL baru untuk mengganti gambar';
                    previewBox.style.display = 'flex';
                } else {
                    previewBox.style.display = 'none';
                }
                hideStatusMessage();
                return;
            }

            // Cek Google Images di sisi browser
            const extractedGoogle = checkAndExtractGoogleUrl(urlVal);
            if (extractedGoogle) {
                urlVal = extractedGoogle;
                inputUrl.value = extractedGoogle;
                showStatusMessage('success', '✓ Link gambar asli berhasil diekstrak dari Google Images.');
            }

            // Cek apakah link gambar langsung
            const isDirect = /\.(jpg|jpeg|png|webp|gif|avif)(\?.*)?$/i.test(urlVal) ||
                             urlVal.includes('images.unsplash.com') ||
                             urlVal.includes('i.pinimg.com') ||
                             urlVal.includes('picsum.photos');

            if (isDirect) {
                previewImg.src = urlVal;
                previewTitle.textContent = 'Preview Banner (Link Gambar Baru)';
                previewInfo.textContent = urlVal.length > 60 ? urlVal.substring(0, 60) + '...' : urlVal;
                previewBox.style.display = 'flex';
                if (!extractedGoogle) hideStatusMessage();

                previewImg.onerror = function() {
                    previewInfo.textContent = '⚠️ Gambar tidak dapat dimuat langsung di browser dari tautan ini.';
                    showStatusMessage('warning', '⚠️ Browser dibatasi memuat gambar ini langsung (mungkin hotlink protection). Opsi "Simpan salinan ke server" aktif, sehingga sistem akan mencoba mengunduhnya saat artikel disimpan.');
                };
                previewImg.onload = function() {
                    if (!extractedGoogle) hideStatusMessage();
                };
            } else if (autoResolve && (urlVal.includes('pinterest.com') || urlVal.includes('pin.it') || urlVal.startsWith('http'))) {
                resolveUrlWithServer(urlVal);
            } else {
                if (originalImgSrc) {
                    previewImg.src = originalImgSrc;
                    previewTitle.textContent = 'Gambar Banner Aktif';
                    previewInfo.textContent = 'Klik "Periksa Link" untuk memverifikasi tautan baru.';
                    previewBox.style.display = 'flex';
                } else {
                    previewBox.style.display = 'none';
                }
            }
        } else if (imageType.value === 'upload') {
            hideStatusMessage();
            const file = inputGambar.files[0];
            if (file) {
                previewImg.src = URL.createObjectURL(file);
                previewTitle.textContent = 'Preview Banner (File Baru Terpilih)';
                previewInfo.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                previewBox.style.display = 'flex';
            } else if (originalImgSrc) {
                previewImg.src = originalImgSrc;
                previewTitle.textContent = 'Gambar Banner Aktif';
                previewInfo.textContent = 'Pilih file baru untuk mengganti gambar';
                previewBox.style.display = 'flex';
            } else {
                previewBox.style.display = 'none';
            }
        }
    }

    let resolveTimer = null;
    function resolveUrlWithServer(urlVal) {
        if (!urlVal || !urlVal.startsWith('http')) return;

        if (btnResolveSpinner) btnResolveSpinner.style.display = 'inline-block';
        if (btnResolveText) btnResolveText.textContent = 'Memeriksa...';

        fetch('{{ route("admin.articles.resolveBannerUrl") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ url: urlVal })
        })
        .then(res => res.json())
        .then(data => {
            if (btnResolveSpinner) btnResolveSpinner.style.display = 'none';
            if (btnResolveText) btnResolveText.textContent = 'Periksa Link';

            if (data.success && data.image_url) {
                inputUrl.value = data.image_url;
                previewImg.src = data.image_url;
                previewTitle.textContent = 'Preview Banner (Tautan Baru Terdeteksi)';
                previewInfo.textContent = data.image_url.length > 60 ? data.image_url.substring(0, 60) + '...' : data.image_url;
                previewBox.style.display = 'flex';
                showStatusMessage('success', data.message || '✓ Gambar berhasil ditemukan dari sumber.');

                previewImg.onerror = function() {
                    showStatusMessage('warning', '⚠️ Gambar ditemukan tetapi situs sumber memblokir pratinjau browser. Opsi "Simpan salinan ke server" akan mengunduhnya saat artikel disimpan.');
                };
            } else {
                showStatusMessage('error', data.message || '⚠️ Tidak dapat menemukan gambar dari tautan tersebut.');
            }
        })
        .catch(err => {
            if (btnResolveSpinner) btnResolveSpinner.style.display = 'none';
            if (btnResolveText) btnResolveText.textContent = 'Periksa Link';
            showStatusMessage('error', '⚠️ Terjadi kesalahan saat memeriksa tautan gambar.');
        });
    }

    if (btnResolve) {
        btnResolve.addEventListener('click', function() {
            const urlVal = inputUrl.value.trim();
            if (urlVal) resolveUrlWithServer(urlVal);
        });
    }

    inputGambar.addEventListener('change', function() {
        updateImagePreview(false);
    });

    inputUrl.addEventListener('input', function() {
        clearTimeout(resolveTimer);
        const val = this.value.trim();

        const extractedGoogle = checkAndExtractGoogleUrl(val);
        if (extractedGoogle) {
            this.value = extractedGoogle;
            updateImagePreview(false);
            return;
        }

        if (val.includes('pinterest.com') || val.includes('pin.it')) {
            resolveTimer = setTimeout(() => {
                updateImagePreview(true);
            }, 800);
        } else {
            updateImagePreview(false);
        }
    });

    inputUrl.addEventListener('paste', function() {
        setTimeout(() => {
            const val = this.value.trim();
            const extracted = checkAndExtractGoogleUrl(val);
            if (extracted) {
                this.value = extracted;
            }
            updateImagePreview(true);
        }, 80);
    });

    // Status Cards & Schedule Picker
    const statusCards   = document.querySelectorAll('.status-card');
    const scheduledWrap = document.getElementById('scheduled-date-wrap');
    const dateLabel     = document.getElementById('date-label');
    const btnSubmitText = document.getElementById('btn-submit-text');

    function syncStatusCard() {
        const checked = document.querySelector('input[name=status]:checked');
        statusCards.forEach(c => c.classList.remove('selected'));
        if (checked) {
            checked.closest('.status-card').classList.add('selected');
            if (checked.value === 'scheduled') {
                scheduledWrap.style.display = 'block';
                dateLabel.textContent = 'Jadwal Publikasi Otomatis (WIB) *';
                btnSubmitText.textContent = 'Simpan & Jadwalkan';
            } else if (checked.value === 'published') {
                scheduledWrap.style.display = 'block';
                dateLabel.textContent = 'Tanggal Publikasi (WIB)';
                btnSubmitText.textContent = 'Publish & Simpan';
            } else {
                scheduledWrap.style.display = 'none';
                btnSubmitText.textContent = 'Simpan Draft';
            }
        }
    }

    statusCards.forEach(c => {
        c.addEventListener('click', function() {
            const r = this.querySelector('input[type=radio]');
            if (r) { r.checked = true; syncStatusCard(); }
        });
    });
    syncStatusCard();

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
    const formArticle      = document.getElementById('form-article');
    const takeawayInput    = document.getElementById('ia_takeaway');
    const hiddenIsiArtikel = document.getElementById('isi_artikel');

    function syncIsiArtikel() {
        let contentHtml = quill.root.innerHTML;
        if (contentHtml === '<p><br></p>') contentHtml = '';

        let words = quill.getText().trim().split(/\s+/).filter(Boolean).length;
        let autoReadTime = Math.max(1, Math.ceil(words / 200)) + ' mnt';
        let readTime = "{{ $readTime ?? '' }}" || autoReadTime;

        let data = {
            content: contentHtml,
            takeaway: takeawayInput ? takeawayInput.value.trim() : "",
            readTime: readTime
        };
        hiddenIsiArtikel.value = JSON.stringify(data);
    }

    quill.on('text-change', syncIsiArtikel);
    if (takeawayInput) {
        takeawayInput.addEventListener('input', syncIsiArtikel);
    }

    if (formArticle) {
        formArticle.addEventListener('submit', function(e) {
            syncIsiArtikel();
        });
    }
</script>
@endpush
@endsection
