@extends('admin.layouts.app')
@section('title', 'Identitas & Logo Website')

@section('content')
<style>
    .form-wrap {
        background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
        padding: 36px; backdrop-filter: blur(12px); max-width: 1200px; width: 100%;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 32px;
    }
    .form-group { margin-bottom: 22px; }
    .form-group.full-width { grid-column: 1 / -1; }
    .form-label { display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:8px;letter-spacing:0.5px;text-transform:uppercase; }
    .form-control {
        width:100%; background:rgba(0,0,0,0.25); border:1px solid rgba(255,255,255,0.1);
        color:var(--text); padding:12px 16px; border-radius:10px; font-family:'Inter',sans-serif;
        font-size:14px; transition:border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus { outline:none; border-color:var(--cyan); box-shadow:0 0 0 3px rgba(0,212,255,0.08); }
    
    /* Option Cards */
    .mode-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 24px; }
    .mode-card {
        border: 2px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 18px;
        background: rgba(0,0,0,0.18); cursor: pointer; transition: all 0.2s;
        display: flex; flex-direction: column; gap: 8px; position: relative;
    }
    .mode-card:hover { border-color: rgba(0,212,255,0.4); background: rgba(0,212,255,0.04); }
    .mode-card.active { border-color: var(--cyan); background: rgba(0,212,255,0.08); }
    .mode-card input[type=radio] { position: absolute; opacity: 0; }
    .mode-card-title { font-size: 14px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 8px; }
    .mode-card-desc { font-size: 12px; color: var(--muted); line-height: 1.5; }

    /* Upload zone */
    .upload-zone { border:2px dashed rgba(0,212,255,0.25);border-radius:12px;padding:26px;text-align:center;cursor:pointer;transition:all 0.2s;position:relative;background:rgba(0,0,0,0.15); }
    .upload-zone:hover { border-color:rgba(0,212,255,0.5);background:rgba(0,212,255,0.04); }
    .upload-zone input[type=file] { position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%; }
    .upload-zone p { font-size:13px;color:var(--muted);margin:0; }

    /* Tabs */
    .tab-row { display:flex;gap:0;margin-bottom:14px;border-radius:10px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);max-width:360px; }
    .tab-btn { flex:1;padding:10px 16px;text-align:center;font-size:13px;font-weight:600;cursor:pointer;background:transparent;border:none;color:var(--muted);transition:all 0.2s; }
    .tab-btn.active { background:rgba(0,212,255,0.12);color:var(--cyan); }
    .tab-pane { display:none; } .tab-pane.active { display:block; }

    /* Live Preview Panel */
    .preview-stage {
        background: rgba(0, 0, 0, 0.35); border: 1px solid var(--border); border-radius: 14px;
        padding: 24px; position: sticky; top: 90px;
    }
    .preview-stage-title {
        font-size: 12px; font-weight: 700; color: var(--cyan); text-transform: uppercase; letter-spacing: 1px;
        margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between;
    }
    .preview-sublabel {
        font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;
    }
    .preview-info-box {
        font-size: 11.5px; color: var(--muted); margin-top: 14px; line-height: 1.5;
    }
    .sim-navbar {
        background: rgba(5, 12, 40, 0.95); border: 1px solid rgba(0,212,255,0.2); border-radius: 12px;
        padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;
    }
    .sim-footer {
        background: radial-gradient(130% 100% at 50% 0%, #0d1a3a 0%, #060d22 50%, #020614 100%);
        border: 1px solid rgba(0,212,255,0.2); border-radius: 12px; padding: 20px; margin-bottom: 16px;
    }
    .sim-logo { display: inline-flex; align-items: center; gap: 10px; text-decoration: none; }
    .sim-logo-icon {
        width: 38px; height: 38px; border-radius: 10px;
        background: #ffffff;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25), 0 0 14px rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.6);
    }
    .sim-logo-icon img { width: 100%; height: 100%; object-fit: contain; padding: 4px; }
    .sim-logo-icon svg { width: 22px; height: 22px; }
    .sim-logo-text {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 18px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
    }
    .sim-logo-highlight {
        color: #00d4ff !important;
        text-shadow: 0 0 12px rgba(0, 212, 255, 0.35);
    }
    .sim-logo-rest {
        color: #ffffff !important;
        margin-left: 4px;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }
    .sim-menu-badge {
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 100px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: rgba(255, 255, 255, 0.85);
    }

    .btn-submit { background:linear-gradient(135deg,var(--cyan),var(--purple));color:#fff;padding:13px 32px;border-radius:10px;border:none;font-weight:700;font-size:14px;cursor:pointer;transition:opacity 0.2s, transform 0.2s; }
    .btn-submit:hover { opacity:0.95; transform:translateY(-1px); }
    .error-box { background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:16px;border-radius:10px;margin-bottom:24px;font-size:14px; }
    .success-box { background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);color:#86efac;padding:16px;border-radius:10px;margin-bottom:24px;font-size:14px; }

    /* =========================================================
       ADMIN LIGHT MODE STYLING & HIGH-CONTRAST PREVIEW
       ========================================================= */
    html[data-theme="light"] .mode-card {
        background: #f8fafc !important;
        border: 2px solid rgba(15, 23, 42, 0.12) !important;
    }
    html[data-theme="light"] .mode-card:hover {
        background: rgba(2, 132, 199, 0.04) !important;
        border-color: rgba(2, 132, 199, 0.4) !important;
    }
    html[data-theme="light"] .mode-card.active {
        background: rgba(2, 132, 199, 0.08) !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 1px #0284c7 !important;
    }
    html[data-theme="light"] .mode-card-title {
        color: #0f172a !important;
    }
    html[data-theme="light"] .mode-card-desc {
        color: #64748b !important;
    }
    html[data-theme="light"] #customLogoSection {
        background: #f8fafc !important;
        border: 1px solid rgba(15, 23, 42, 0.12) !important;
    }
    html[data-theme="light"] .upload-zone {
        background: #ffffff !important;
        border: 2px dashed rgba(2, 132, 199, 0.35) !important;
    }
    html[data-theme="light"] .upload-zone:hover {
        background: rgba(2, 132, 199, 0.04) !important;
        border-color: #0284c7 !important;
    }
    html[data-theme="light"] .upload-zone p {
        color: #475569 !important;
    }
    html[data-theme="light"] .tab-row {
        background: #e2e8f0 !important;
        border: 1px solid rgba(15, 23, 42, 0.12) !important;
    }
    html[data-theme="light"] .tab-btn {
        color: #64748b !important;
    }
    html[data-theme="light"] .tab-btn.active {
        background: #ffffff !important;
        color: #0284c7 !important;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08) !important;
    }
    html[data-theme="light"] .preview-stage {
        background: #f8fafc !important;
        border: 1px solid rgba(15, 23, 42, 0.12) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
    }
    html[data-theme="light"] .preview-stage-title {
        color: #0284c7 !important;
    }
    html[data-theme="light"] .preview-sublabel {
        color: #334155 !important;
    }
    html[data-theme="light"] .preview-info-box {
        color: #475569 !important;
    }

    /* Proteksi Mutlak: Teks Logo dan Tombol Menu di Navbar & Footer Tetap Putih Cerah & Kontras */
    html[data-theme="light"] .sim-logo-rest,
    html[data-theme="light"] #simRestText,
    html[data-theme="light"] #simFootRest,
    html[data-theme="light"] .form-wrap .sim-navbar .sim-logo-rest,
    html[data-theme="light"] .form-wrap .sim-footer .sim-logo-rest,
    html[data-theme="light"] .form-wrap .sim-navbar #simRestText,
    html[data-theme="light"] .form-wrap .sim-footer #simFootRest {
        color: #ffffff !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6) !important;
    }
    html[data-theme="light"] .sim-logo-highlight,
    html[data-theme="light"] #simHighlightText,
    html[data-theme="light"] #simFootHighlight,
    html[data-theme="light"] .form-wrap .sim-navbar .sim-logo-highlight,
    html[data-theme="light"] .form-wrap .sim-footer .sim-logo-highlight,
    html[data-theme="light"] .form-wrap .sim-navbar #simHighlightText,
    html[data-theme="light"] .form-wrap .sim-footer #simFootHighlight {
        color: #00d4ff !important;
        text-shadow: 0 0 12px rgba(0, 212, 255, 0.45) !important;
    }
    html[data-theme="light"] .sim-menu-badge,
    html[data-theme="light"] .form-wrap .sim-navbar .sim-menu-badge {
        background: rgba(255, 255, 255, 0.12) !important;
        border-color: rgba(255, 255, 255, 0.35) !important;
        color: #ffffff !important;
    }

    @media (max-width: 900px) {
        .form-grid { grid-template-columns: 1fr; }
        .mode-cards { grid-template-columns: 1fr; }
    }
</style>

<div class="page-header">
    <div class="page-label">CMS / KONTEN WEBSITE</div>
    <h1 class="page-title">Identitas & Logo Website</h1>
    <p style="color:var(--muted);font-size:14px;margin-top:4px;">Atur logo, ikon grafis, dan nama brand yang tampil di Navbar, Footer, serta seluruh website.</p>
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
    <form action="{{ route('admin.cms.logo.update') }}" method="POST" enctype="multipart/form-data" id="logoForm">
        @csrf @method('PUT')

        <div class="form-grid">
            {{-- Left column: Inputs --}}
            <div>
                {{-- Logo Type Selector --}}
                <div class="form-group">
                    <label class="form-label">Pilihan Gaya Logo *</label>
                    @php
                        $selectedType = old('site_logo_type', $cms->site_logo_type ?? ($cms->site_logo ? 'custom' : 'default'));
                    @endphp
                    <div class="mode-cards">
                        <label class="mode-card {{ $selectedType === 'default' ? 'active' : '' }}" id="cardTypeDefault">
                            <input type="radio" name="site_logo_type" value="default" {{ $selectedType === 'default' ? 'checked' : '' }}>
                            <div class="mode-card-title">
                                <span>✨ Ikon Default Bawaan</span>
                            </div>
                            <div class="mode-card-desc">Gunakan ikon AI neural network bersinar dengan gradien cyan & ungu bawaan.</div>
                        </label>

                        <label class="mode-card {{ $selectedType === 'custom' ? 'active' : '' }}" id="cardTypeCustom">
                            <input type="radio" name="site_logo_type" value="custom" {{ $selectedType === 'custom' ? 'checked' : '' }}>
                            <div class="mode-card-title">
                                <span>🖼️ Upload Logo Sendiri</span>
                            </div>
                            <div class="mode-card-desc">Pakai file gambar logo kustom perusahaan Anda sendiri (PNG transparan, SVG, atau WebP).</div>
                        </label>
                    </div>
                </div>

                {{-- Custom Logo Options (Visible when custom is active) --}}
                <div id="customLogoSection" style="{{ $selectedType === 'custom' ? 'display:block;' : 'display:none;' }}; margin-bottom:24px; padding:20px; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.08); border-radius:12px;">
                    <label class="form-label" style="color:var(--cyan);">Upload Gambar / Link URL Logo</label>

                    @php
                        $isUrl = str_starts_with($cms->site_logo ?? '', 'http');
                        $activeLogoTab = old('site_logo_url') ? 'url' : ($isUrl ? 'url' : 'file');
                    @endphp

                    <div class="tab-row">
                        <button type="button" class="tab-btn {{ $activeLogoTab === 'file' ? 'active' : '' }}" data-tab="tab-file">📁 Upload File</button>
                        <button type="button" class="tab-btn {{ $activeLogoTab === 'url' ? 'active' : '' }}" data-tab="tab-url">🔗 Link URL</button>
                    </div>

                    {{-- Tab Upload --}}
                    <div class="tab-pane {{ $activeLogoTab === 'file' ? 'active' : '' }}" id="tab-file">
                        <div class="upload-zone">
                            <input type="file" name="site_logo_file" id="siteLogoFile" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                            <p>Klik atau drag gambar logo ke sini<br><span style="font-size:11px;opacity:0.6;">Format: PNG transparan, SVG, WEBP — maks. 3 MB</span></p>
                        </div>
                    </div>

                    {{-- Tab URL --}}
                    <div class="tab-pane {{ $activeLogoTab === 'url' ? 'active' : '' }}" id="tab-url">
                        <input type="url" name="site_logo_url" id="siteLogoUrl" class="form-control" 
                               placeholder="https://contoh.com/logo-kami.png" 
                               value="{{ old('site_logo_url', $isUrl ? $cms->site_logo : '') }}">
                        <p style="font-size:11px;color:var(--muted);margin-top:6px;">Tempel link langsung ke file gambar logo (.png, .svg, .webp).</p>
                    </div>
                </div>

                {{-- Brand Name Settings --}}
                <div class="form-group" style="margin-top:20px;">
                    <label class="form-label">Teks Nama Brand Website *</label>
                    <div style="display:grid; grid-template-columns: 1fr 2fr; gap:12px;">
                        <div>
                            <span style="font-size:11px;color:var(--cyan);font-weight:600;display:block;margin-bottom:4px;">Awalan Highlight</span>
                            <input type="text" name="site_name_highlight" id="siteNameHighlight" class="form-control" 
                                   value="{{ old('site_name_highlight', $cms->site_name_highlight ?? 'AI') }}" placeholder="AI">
                        </div>
                        <div>
                            <span style="font-size:11px;color:var(--muted);font-weight:600;display:block;margin-bottom:4px;">Lanjutan Nama Brand</span>
                            <input type="text" name="site_name_rest" id="siteNameRest" class="form-control" 
                                   value="{{ old('site_name_rest', $cms->site_name_rest ?? 'Jogja Solutions') }}" placeholder="Jogja Solutions">
                        </div>
                    </div>
                    <small style="display:block;color:var(--muted);font-size:12px;margin-top:8px;">
                        Kata pertama akan berwarna cyan menyala, dan kata berikutnya berwarna putih/kontras elegan.
                    </small>
                </div>

                <div style="margin-top:32px;padding-top:20px;border-top:1px solid var(--border);">
                    <button type="submit" class="btn-submit">Simpan Perubahan Logo & Brand</button>
                </div>
            </div>

            {{-- Right column: Live Interactive Preview --}}
            <div>
                <div class="preview-stage">
                    <div class="preview-stage-title">
                        <span>👁️ Live Preview Tampilan</span>
                        <span style="font-size:10px;padding:2px 8px;background:rgba(0,212,255,0.15);border-radius:100px;">Real-time</span>
                    </div>

                    {{-- Simulation in Navbar --}}
                    <div class="preview-sublabel">
                        1. Tampilan di Navbar (Header Utama)
                    </div>
                    <div class="sim-navbar">
                        <div class="sim-logo">
                            <div class="sim-logo-icon" id="simNavIcon">
                                @if(($cms->site_logo_type ?? 'default') === 'custom' && $cms->site_logo_url)
                                    <img src="{{ $cms->site_logo_url }}" alt="Logo" id="simNavImg">
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" id="simNavSvg">
                                        <circle cx="12" cy="12" r="3" fill="#0284c7" opacity="0.95"/>
                                        <circle cx="5" cy="7" r="1.5" fill="#7c3aed" opacity="0.85"/>
                                        <circle cx="19" cy="7" r="1.5" fill="#7c3aed" opacity="0.85"/>
                                        <circle cx="5" cy="17" r="1.5" fill="#7c3aed" opacity="0.85"/>
                                        <circle cx="19" cy="17" r="1.5" fill="#7c3aed" opacity="0.85"/>
                                        <line x1="9" y1="11" x2="6.12" y2="7.88" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                        <line x1="15" y1="11" x2="17.88" y2="7.88" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                        <line x1="9" y1="13" x2="6.12" y2="16.12" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                        <line x1="15" y1="13" x2="17.88" y2="16.12" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                    </svg>
                                    <img src="" alt="Logo" id="simNavImg" style="display:none;">
                                @endif
                            </div>
                            <span class="sim-logo-text">
                                <span id="simHighlightText" class="sim-logo-highlight">{{ $cms->site_name_highlight ?? 'AI' }}</span>
                                <span id="simRestText" class="sim-logo-rest">{{ $cms->site_name_rest ?? 'Jogja Solutions' }}</span>
                            </span>
                        </div>
                        <span class="sim-menu-badge">Menu</span>
                    </div>

                    {{-- Simulation in Footer --}}
                    <div class="preview-sublabel" style="margin-top:16px;">
                        2. Tampilan di Footer (Bagian Bawah)
                    </div>
                    <div class="sim-footer">
                        <div class="sim-logo">
                            <div class="sim-logo-icon" id="simFootIcon">
                                @if(($cms->site_logo_type ?? 'default') === 'custom' && $cms->site_logo_url)
                                    <img src="{{ $cms->site_logo_url }}" alt="Logo" id="simFootImg">
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" id="simFootSvg">
                                        <circle cx="12" cy="12" r="3" fill="#0284c7" opacity="0.95"/>
                                        <circle cx="5" cy="7" r="1.5" fill="#7c3aed" opacity="0.85"/>
                                        <circle cx="19" cy="7" r="1.5" fill="#7c3aed" opacity="0.85"/>
                                        <circle cx="5" cy="17" r="1.5" fill="#7c3aed" opacity="0.85"/>
                                        <circle cx="19" cy="17" r="1.5" fill="#7c3aed" opacity="0.85"/>
                                        <line x1="9" y1="11" x2="6.12" y2="7.88" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                        <line x1="15" y1="11" x2="17.88" y2="7.88" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                        <line x1="9" y1="13" x2="6.12" y2="16.12" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                        <line x1="15" y1="13" x2="17.88" y2="16.12" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                    </svg>
                                    <img src="" alt="Logo" id="simFootImg" style="display:none;">
                                @endif
                            </div>
                            <span class="sim-logo-text">
                                <span id="simFootHighlight" class="sim-logo-highlight">{{ $cms->site_name_highlight ?? 'AI' }}</span>
                                <span id="simFootRest" class="sim-logo-rest">{{ $cms->site_name_rest ?? 'Jogja Solutions' }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="preview-info-box">
                        ℹ️ Logo dan nama ini akan otomatis diperbarui di seluruh halaman website, artikel, layanan, serta footer.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Mode selector toggle
const radioDefault = document.querySelector('input[value="default"]');
const radioCustom = document.querySelector('input[value="custom"]');
const cardDefault = document.getElementById('cardTypeDefault');
const cardCustom = document.getElementById('cardTypeCustom');
const customSection = document.getElementById('customLogoSection');

function updateModeView() {
    if (radioCustom.checked) {
        cardCustom.classList.add('active');
        cardDefault.classList.remove('active');
        customSection.style.display = 'block';
    } else {
        cardDefault.classList.add('active');
        cardCustom.classList.remove('active');
        customSection.style.display = 'none';
        // restore default SVG in preview
        showSvgPreview();
    }
}

cardDefault.addEventListener('click', () => { radioDefault.checked = true; updateModeView(); });
cardCustom.addEventListener('click', () => { radioCustom.checked = true; updateModeView(); });
radioDefault.addEventListener('change', updateModeView);
radioCustom.addEventListener('change', updateModeView);

// Tab switching inside custom logo section
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        const target = document.getElementById(this.dataset.tab);
        if (target) target.classList.add('active');
    });
});

// Live Text Preview
const inputHighlight = document.getElementById('siteNameHighlight');
const inputRest = document.getElementById('siteNameRest');
const simHighlightText = document.getElementById('simHighlightText');
const simRestText = document.getElementById('simRestText');
const simFootHighlight = document.getElementById('simFootHighlight');
const simFootRest = document.getElementById('simFootRest');

function updateTextPreview() {
    const hl = inputHighlight.value || 'AI';
    const rest = inputRest.value || 'Jogja Solutions';
    simHighlightText.textContent = hl;
    simFootHighlight.textContent = hl;
    simRestText.textContent = rest;
    simFootRest.textContent = rest;
}

inputHighlight.addEventListener('input', updateTextPreview);
inputRest.addEventListener('input', updateTextPreview);

// Live Image Preview Handlers
const fileInput = document.getElementById('siteLogoFile');
const urlInput = document.getElementById('siteLogoUrl');
const navImg = document.getElementById('simNavImg');
const footImg = document.getElementById('simFootImg');
const navSvg = document.getElementById('simNavSvg');
const footSvg = document.getElementById('simFootSvg');

function showImagePreview(src) {
    if (!src) return;
    if (navSvg) navSvg.style.display = 'none';
    if (footSvg) footSvg.style.display = 'none';
    if (navImg) { navImg.src = src; navImg.style.display = 'block'; }
    if (footImg) { footImg.src = src; footImg.style.display = 'block'; }
}

function showSvgPreview() {
    if (navImg) navImg.style.display = 'none';
    if (footImg) footImg.style.display = 'none';
    if (navSvg) navSvg.style.display = 'block';
    if (footSvg) footSvg.style.display = 'block';
}

if (fileInput) {
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => showImagePreview(e.target.result);
        reader.readAsDataURL(file);
    });
}

if (urlInput) {
    urlInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
            showImagePreview(url);
        } else if (!url && !fileInput.files.length) {
            showSvgPreview();
        }
    });
}
</script>
@endpush
@endsection
