@extends('admin.layouts.app')
@section('title', 'Tambah Artikel')

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
        display: none; align-items: center; gap: 16px;
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

    .ql-toolbar.ql-snow { background: #f8fafc; border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 10px 10px 0 0; border-bottom: none !important; padding: 12px !important; }
    .ql-container.ql-snow { border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 0 0 10px 10px; background: rgba(0,0,0,0.25); color: #fff; font-family: 'Inter', sans-serif; font-size: 15px; }
    .ql-editor { min-height: 300px; line-height: 1.6; }
    .ql-editor.ql-blank::before { color: rgba(255,255,255,0.4); }

    @media (max-width: 768px) {
        .form-wrap { padding: 24px; }
        .form-grid { grid-template-columns: 1fr; gap: 0; }
        .status-cards { grid-template-columns: 1fr; }
    }
</style>

<div class="page-header">
    <div class="page-label">MANAJEMEN / ARTIKEL</div>
    <h1 class="page-title">Tambah Artikel</h1>
</div>

<div class="form-wrap">
    @if($errors->any())
    <div class="error-box">
        <ul style="margin-left: 18px;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form id="form-article" action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Judul Artikel *</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" placeholder="Judul artikel informatif..." required>
            </div>
            <div class="form-group">
                <label class="form-label">Slug (URL) *</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" placeholder="url-artikel-anda" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <input type="text" name="kategori" class="form-control" value="{{ old('kategori') }}" placeholder="Contoh: AI & Bisnis, Machine Learning">
            </div>

            <div class="form-group">
                <label class="form-label">Nama Penulis (Opsional)</label>
                <input type="text" name="author_name" class="form-control" value="{{ old('author_name') }}" placeholder="Contoh: Tim Redaksi AI Solution / Dr. Jane Doe" maxlength="100">
            </div>

            <div class="form-group full-width">
                <label class="form-label">Sumber Referensi Artikel (Opsional)</label>
                <input type="text" name="source_name" class="form-control" value="{{ old('source_name') }}" placeholder="Contoh: Kementerian Perhubungan RI / McKinsey Insight">
            </div>

            <div class="form-group full-width">
                <label class="form-label">URL Tautan Sumber (Opsional)</label>
                <input type="url" name="source_url" class="form-control" value="{{ old('source_url') }}" placeholder="https://dephub.go.id/berita/...">
                <small style="color: var(--muted); font-size: 11.5px; margin-top: 4px; display: block;">Tautan langsung ke artikel/laporan rujukan resmi.</small>
            </div>

            <!-- Banner Image Section -->
            <div class="form-group full-width" style="background: rgba(0,0,0,0.18); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                <label class="form-label" style="color: var(--cyan); margin-bottom: 12px;">Gambar Banner Artikel</label>

                <div class="tab-pills">
                    <button type="button" class="tab-pill {{ old('image_type', 'upload') === 'upload' ? 'active' : '' }}" id="tab-upload">📁 Upload dari Komputer</button>
                    <button type="button" class="tab-pill {{ old('image_type') === 'url' ? 'active' : '' }}" id="tab-url">🌐 Tempel Link Gambar / URL</button>
                </div>
                <input type="hidden" name="image_type" id="image_type" value="{{ old('image_type', 'upload') }}">

                <!-- Upload Input -->
                <div id="section-upload" style="{{ old('image_type', 'upload') === 'upload' ? '' : 'display:none;' }}">
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/jpeg,image/png,image/webp,image/jpg" style="padding: 9px 16px;">
                    <small style="color: var(--muted); font-size: 12px; margin-top: 5px; display: block;">Format: JPG, PNG, WEBP. Maks 5MB.</small>
                </div>

                <!-- URL Input -->
                <div id="section-url" style="{{ old('image_type') === 'url' ? '' : 'display:none;' }}">
                    <div style="display: flex; gap: 8px;">
                        <input type="url" name="gambar_url" id="gambar_url" class="form-control" value="{{ old('gambar_url') }}" placeholder="https://... (Link gambar langsung, Google Images, atau Pinterest)" style="flex: 1;">
                        <button type="button" id="btn-resolve-url" style="background: rgba(0, 212, 255, 0.15); border: 1px solid rgba(0, 212, 255, 0.35); color: var(--cyan); padding: 0 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px;">
                            <span id="btn-resolve-spinner" style="display: none;">⏳</span>
                            <span id="btn-resolve-text">Periksa Link</span>
                        </button>
                    </div>
                    <small style="color: var(--muted); font-size: 12px; margin-top: 5px; display: block;">
                        Mendukung link gambar langsung (.jpg, .png, .webp, Unsplash), link Google Images (otomatis mengekstrak gambar asli), dan pin Pinterest.
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
                <div id="preview-box" class="preview-box" style="margin-top: 14px;">
                    <img id="preview-img" class="preview-img" src="" alt="Preview">
                    <div style="flex: 1;">
                        <div style="font-size: 13px; font-weight: 600; color: #fff; margin-bottom: 4px;" id="preview-title">Preview Banner</div>
                        <div style="font-size: 12px; color: var(--muted); word-break: break-all;" id="preview-info">Gambar siap digunakan</div>
                    </div>
                </div>
            </div>

            <div class="form-group full-width">
                <label class="form-label">Ringkasan Artikel</label>
                <textarea name="ringkasan" class="form-control" rows="3" placeholder="Deskripsi singkat yang tampil pada kartu artikel frontend...">{{ old('ringkasan') }}</textarea>
            </div>

            @php
                $iaRaw = old('isi_artikel');
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
                    <input type="text" id="ia_takeaway" class="form-control" value="{{ $takeaway }}" placeholder="Contoh: Kolaborasi AI dan manusia memacu efisiensi bisnis hingga 40%...">
                </div>
            </div>

            <input type="hidden" name="isi_artikel" id="isi_artikel" value="{{ $iaRaw }}">

            @php
                $presetDate = request('scheduled_date') ?? request('date');
                if ($presetDate) {
                    try {
                        $parsedPreset = \Carbon\Carbon::parse($presetDate, 'Asia/Jakarta');
                        if (strlen($presetDate) <= 10) {
                            $parsedPreset->setTime(9, 0);
                        }
                        $defaultPublishedAt = $parsedPreset->format('Y-m-d\TH:i');
                        $defaultStatus = 'scheduled';
                    } catch (\Throwable $e) {
                        $defaultPublishedAt = now('Asia/Jakarta')->format('Y-m-d\TH:i');
                        $defaultStatus = 'published';
                    }
                } else {
                    $defaultPublishedAt = now('Asia/Jakarta')->format('Y-m-d\TH:i');
                    $defaultStatus = 'published';
                }
                $selectedStatus = old('status', $defaultStatus);
                $publishedAtVal = old('published_at', $defaultPublishedAt);
            @endphp

            <!-- Status & Penjadwalan -->
            <div class="form-group full-width" style="background: rgba(0,0,0,0.18); padding: 22px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                <label class="form-label" style="color: var(--cyan); margin-bottom: 12px;">Status Publikasi &amp; Jadwal</label>

                <div class="status-cards">
                    <label class="status-card {{ $selectedStatus === 'draft' ? 'selected' : '' }}" id="card-draft">
                        <input type="radio" name="status" value="draft" {{ $selectedStatus === 'draft' ? 'checked' : '' }}>
                        <div>
                            <div class="status-card-title">Simpan Draft</div>
                            <div class="status-card-desc">Hanya tersimpan di admin, belum tampil di frontend.</div>
                        </div>
                    </label>

                    <label class="status-card {{ $selectedStatus === 'published' ? 'selected' : '' }}" id="card-published">
                        <input type="radio" name="status" value="published" {{ $selectedStatus === 'published' ? 'checked' : '' }}>
                        <div>
                            <div class="status-card-title">Publish Sekarang</div>
                            <div class="status-card-desc">Terbitkan langsung dan tayang di website saat ini juga.</div>
                        </div>
                    </label>

                    <label class="status-card {{ $selectedStatus === 'scheduled' ? 'selected' : '' }}" id="card-scheduled">
                        <input type="radio" name="status" value="scheduled" {{ $selectedStatus === 'scheduled' ? 'checked' : '' }}>
                        <div>
                            <div class="status-card-title">Jadwalkan Publikasi</div>
                            <div class="status-card-desc">Atur tanggal &amp; jam terbit otomatis di masa mendatang.</div>
                        </div>
                    </label>
                </div>

                <!-- Input Tanggal & Jam Penjadwalan (WIB) -->
                <div id="scheduled-date-wrap" style="margin-top: 18px; {{ $selectedStatus === 'draft' ? 'display:none;' : '' }}">
                    <label class="form-label" id="date-label">
                        {{ $selectedStatus === 'scheduled' ? 'Jadwal Publikasi Otomatis (WIB) *' : 'Tanggal Publikasi (WIB)' }}
                    </label>
                    <input type="datetime-local" name="published_at" id="published_at" class="form-control" style="max-width: 320px;" value="{{ $publishedAtVal }}">
                    <small style="color: var(--muted); font-size: 11.5px; margin-top: 5px; display: block;" id="date-hint">
                        Zona Waktu: WIB (Asia/Jakarta). Artikel terjadwal otomatis tayang setelah waktu tersebut tercapai.
                    </small>
                </div>
            </div>

            <div class="form-group check-wrap full-width">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" style="font-size: 14px; font-weight: 500; cursor: pointer;">Aktifkan Artikel (Visibilitas Sistem)</label>
            </div>
        </div>

        <div style="margin-top: 16px; padding-top: 24px; border-top: 1px solid var(--border); text-align: right;">
            <a href="{{ route('admin.articles.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit" id="btn-submit-text">Simpan Artikel</button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    // Auto-generate slug from title
    const judul = document.querySelector('[name=judul]');
    const slug  = document.getElementById('slug');
    judul.addEventListener('input', function() {
        if (!slug.dataset.manual) {
            slug.value = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g,'').trim().replace(/\s+/g,'-');
        }
    });
    slug.addEventListener('input', function() { this.dataset.manual = true; });

    // Image Source Tabs & Preview
    const tabUpload    = document.getElementById('tab-upload');
    const tabUrl       = document.getElementById('tab-url');
    const imageType    = document.getElementById('image_type');
    const secUpload    = document.getElementById('section-upload');
    const secUrl       = document.getElementById('section-url');
    const inputGambar  = document.getElementById('gambar');
    const inputUrl     = document.getElementById('gambar_url');
    const previewBox   = document.getElementById('preview-box');
    const previewImg   = document.getElementById('preview-img');
    const previewTitle = document.getElementById('preview-title');
    const previewInfo  = document.getElementById('preview-info');

    tabUpload.addEventListener('click', function() {
        tabUpload.classList.add('active');
        tabUrl.classList.remove('active');
        imageType.value = 'upload';
        secUpload.style.display = 'block';
        secUrl.style.display = 'none';
        updateImagePreview();
    });

    tabUrl.addEventListener('click', function() {
        tabUrl.classList.add('active');
        tabUpload.classList.remove('active');
        imageType.value = 'url';
        secUrl.style.display = 'block';
        secUpload.style.display = 'none';
        updateImagePreview();
    });

    const statusMsgBox     = document.getElementById('url-status-msg');
    const btnResolve       = document.getElementById('btn-resolve-url');
    const btnResolveSpinner = document.getElementById('btn-resolve-spinner');
    const btnResolveText   = document.getElementById('btn-resolve-text');

    function showStatusMessage(type, text) {
        if (!statusMsgBox) return;
        statusMsgBox.style.display = 'block';
        if (type === 'success') {
            statusMsgBox.style.background = 'rgba(74, 222, 128, 0.12)';
            statusMsgBox.style.border = '1px solid rgba(74, 222, 128, 0.35)';
            statusMsgBox.style.color = '#4ade80';
        } else if (type === 'warning') {
            statusMsgBox.style.background = 'rgba(251, 191, 36, 0.12)';
            statusMsgBox.style.border = '1px solid rgba(251, 191, 36, 0.35)';
            statusMsgBox.style.color = '#fbbf24';
        } else {
            statusMsgBox.style.background = 'rgba(239, 68, 68, 0.12)';
            statusMsgBox.style.border = '1px solid rgba(239, 68, 68, 0.35)';
            statusMsgBox.style.color = '#fca5a5';
        }
        statusMsgBox.textContent = text;
    }

    function hideStatusMessage() {
        if (statusMsgBox) statusMsgBox.style.display = 'none';
    }

    // Ekstraksi otomatis parameter imgurl Google Images di sisi client
    function checkAndExtractGoogleUrl(rawUrl) {
        try {
            const parsed = new URL(rawUrl);
            if (parsed.hostname.includes('google.') && parsed.searchParams.has('imgurl')) {
                const imgUrl = parsed.searchParams.get('imgurl');
                if (imgUrl && (imgUrl.startsWith('http://') || imgUrl.startsWith('https://'))) {
                    return imgUrl;
                }
            }
        } catch (e) {}
        return null;
    }

    function updateImagePreview(autoResolve = true) {
        if (imageType.value === 'url') {
            let urlVal = inputUrl.value.trim();
            if (!urlVal) {
                previewBox.style.display = 'none';
                hideStatusMessage();
                return;
            }

            // Cek Google Images di client terlebih dahulu
            const extractedGoogle = checkAndExtractGoogleUrl(urlVal);
            if (extractedGoogle) {
                urlVal = extractedGoogle;
                inputUrl.value = extractedGoogle;
                showStatusMessage('success', '✓ Link gambar asli berhasil diekstrak dari Google Images.');
            }

            // Jika URL adalah gambar langsung
            const isDirect = /\.(jpg|jpeg|png|webp|gif|avif)(\?.*)?$/i.test(urlVal) ||
                             urlVal.includes('images.unsplash.com') ||
                             urlVal.includes('i.pinimg.com') ||
                             urlVal.includes('picsum.photos');

            if (isDirect) {
                previewImg.src = urlVal;
                previewTitle.textContent = 'Preview Banner (Link Gambar)';
                previewInfo.textContent = urlVal.length > 60 ? urlVal.substring(0, 60) + '...' : urlVal;
                previewBox.style.display = 'flex';
                if (!extractedGoogle) hideStatusMessage();

                previewImg.onerror = function() {
                    previewInfo.textContent = '⚠️ Gambar tidak dapat dimuat langsung di browser dari tautan ini.';
                    showStatusMessage('warning', '⚠️ Browser dibatasi memuat gambar ini langsung (mungkin hotlink protection situs sumber). Opsi "Simpan salinan ke server" aktif, sehingga sistem akan mencoba mengunduhnya saat artikel disimpan.');
                };
                previewImg.onload = function() {
                    if (!extractedGoogle) hideStatusMessage();
                };
            } else if (autoResolve && (urlVal.includes('pinterest.com') || urlVal.includes('pin.it') || urlVal.startsWith('http'))) {
                // Tautan Pinterest atau halaman sumber: jalankan server resolver
                resolveUrlWithServer(urlVal);
            } else {
                previewBox.style.display = 'none';
            }
        } else {
            hideStatusMessage();
            const file = inputGambar.files[0];
            if (file) {
                previewImg.src = URL.createObjectURL(file);
                previewTitle.textContent = 'Preview Banner (File Unggahan)';
                previewInfo.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
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
                previewTitle.textContent = 'Preview Banner (Terdeteksi)';
                previewInfo.textContent = data.image_url.length > 60 ? data.image_url.substring(0, 60) + '...' : data.image_url;
                previewBox.style.display = 'flex';
                showStatusMessage('success', data.message || '✓ Gambar berhasil ditemukan dari sumber.');

                previewImg.onerror = function() {
                    showStatusMessage('warning', '⚠️ Gambar ditemukan tetapi situs sumber memblokir pratinjau browser. Opsi "Simpan salinan ke server" akan mengunduhnya saat form disimpan.');
                };
            } else {
                previewBox.style.display = 'none';
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

    inputGambar.addEventListener('change', updateImagePreview);
    inputUrl.addEventListener('input', function() {
        clearTimeout(resolveTimer);
        const urlVal = this.value.trim();
        // Cek langsung jika Google Images atau link gambar
        if (checkAndExtractGoogleUrl(urlVal)) {
            updateImagePreview(false);
        } else if (/\.(jpg|jpeg|png|webp|gif)(\?.*)?$/i.test(urlVal) || urlVal.includes('i.pinimg.com') || urlVal.includes('unsplash.com')) {
            updateImagePreview(false);
        } else {
            resolveTimer = setTimeout(() => updateImagePreview(true), 700);
        }
    });

    // Initial preview check
    updateImagePreview(false);

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
                btnSubmitText.textContent = 'Publish Sekarang';
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
