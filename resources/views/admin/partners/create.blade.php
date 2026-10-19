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
        color:var(--text); padding:12px 16px; border-radius:10px; font-family:'Inter',sans-serif;
        font-size:14px; transition:border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus { outline:none; border-color:var(--cyan); box-shadow:0 0 0 3px rgba(0,212,255,0.08); }
    .upload-zone { border:2px dashed rgba(0,212,255,0.25);border-radius:12px;padding:32px;text-align:center;cursor:pointer;transition:all 0.2s;position:relative;background:rgba(0,0,0,0.15); }
    .upload-zone:hover { border-color:rgba(0,212,255,0.5);background:rgba(0,212,255,0.04); }
    .upload-zone input[type=file] { position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%; }
    .upload-zone .icon { font-size:32px;margin-bottom:8px; }
    .upload-zone p { font-size:13px;color:var(--muted);margin:0; }

    .preview-card-box {
        height: 68px; width: 140px; min-width: 140px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(10, 20, 55, 0.7);
        border: 1px solid rgba(0, 212, 255, 0.25);
        border-radius: 12px;
        padding: 8px 14px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
    }
    .preview-card-box img {
        max-height: 50px; max-width: 110px;
        object-fit: contain;
        filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
    }

    .btn-submit { background:linear-gradient(135deg,var(--cyan),var(--purple));color:#fff;padding:13px 32px;border-radius:10px;border:none;font-weight:700;font-size:14px;cursor:pointer;transition:opacity 0.2s, transform 0.2s; }
    .btn-submit:hover { opacity:0.95; transform:translateY(-1px); }
    .btn-cancel { color:var(--muted);padding:13px 24px;font-weight:600;font-size:14px;text-decoration:none;border:1px solid var(--border);border-radius:10px;margin-right:12px;display:inline-block;transition:all 0.2s; }
    .btn-cancel:hover { background:rgba(0,212,255,0.08);color:var(--text); }
    .check-wrap { display:flex;align-items:center;gap:12px;padding:12px 16px;background:rgba(0,0,0,0.15);border:1px solid rgba(255,255,255,0.08);border-radius:10px;height:46px; }
    .check-wrap input[type=checkbox] { width:18px;height:18px;cursor:pointer;accent-color:var(--cyan); }
    .error-box { background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:16px;border-radius:10px;margin-bottom:24px;font-size:14px; }
    .tab-row { display:flex;gap:0;margin-bottom:14px;border-radius:10px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);max-width:360px; }
    .tab-btn { flex:1;padding:10px 16px;text-align:center;font-size:13px;font-weight:600;cursor:pointer;background:transparent;border:none;color:var(--muted);transition:all 0.2s; }
    .tab-btn.active { background:rgba(0,212,255,0.12);color:var(--cyan); }
    .tab-pane { display:none; } .tab-pane.active { display:block; }

    .magic-box {
        background: rgba(0, 212, 255, 0.04);
        border: 1px solid rgba(0, 212, 255, 0.2);
        border-radius: 12px;
        padding: 16px 20px;
        margin-top: 16px;
    }
    .brand-chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px; background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12); border-radius: 8px;
        font-size: 11px; font-weight: 600; color: var(--text);
        cursor: pointer; transition: all 0.2s;
    }
    .brand-chip:hover {
        background: rgba(0, 212, 255, 0.15);
        border-color: rgba(0, 212, 255, 0.4);
        color: var(--cyan);
        transform: translateY(-1px);
    }

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

    <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" id="partnerForm">
        @csrf

        {{-- Hidden input for processed transparent PNG Data URL --}}
        <input type="hidden" name="processed_logo_base64" id="processedLogoBase64">

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nama Partner *</label>
                <input type="text" name="nama" id="partnerNama" class="form-control" value="{{ old('nama') }}" required placeholder="Contoh: Telkom Indonesia">
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
                        <label for="is_active" style="font-size:13px;font-weight:500;cursor:pointer;color:var(--text);margin:0;">Aktif (di Frontend)</label>
                    </div>
                </div>
            </div>

            <div class="form-group full-width">
                <label class="form-label">Logo Partner *</label>

                {{-- Feature Banner: Automatic Transparent Background --}}
                <div class="magic-box">
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                        <label style="display:flex;align-items:center;gap:12px;cursor:pointer;margin:0;">
                            <input type="checkbox" name="auto_remove_bg" id="autoRemoveBg" value="1" checked style="width:20px;height:20px;accent-color:var(--cyan);cursor:pointer;">
                            <div>
                                <div style="font-weight:700;font-size:13px;color:var(--text);display:flex;align-items:center;gap:6px;">
                                    ✨ Buat Background Transparan Otomatis (Hapus Kotak Putih / Abu-abu)
                                    <span style="font-size:10px;padding:2px 8px;border-radius:100px;background:rgba(74,222,128,0.15);color:#4ade80;font-weight:700;">AKTIF</span>
                                </div>
                                <div style="font-size:12px;color:var(--muted);margin-top:2px;">
                                    Kotak putih atau abu-abu di sekeliling logo akan otomatis dihapus menjadi transparan murni sehingga menyatu mulus di background website.
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- Tolerance fine-tuning --}}
                    <div style="margin-top:14px;padding-top:12px;border-top:1px dashed rgba(255,255,255,0.1);display:flex;align-items:center;gap:16px;flex-wrap:wrap;font-size:12px;color:var(--muted);">
                        <span>Toleransi Putih:</span>
                        <input type="range" id="toleranceRange" min="10" max="65" value="32" style="flex:1;max-width:220px;accent-color:var(--cyan);">
                        <span id="toleranceVal" style="color:var(--cyan);font-weight:700;">32</span>
                        <span style="font-size:11px;opacity:0.8;">(Geser jika ada sisa bayangan putih di pinggir logo)</span>
                        <button type="button" id="btnReprocess" style="margin-left:auto;padding:4px 12px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:var(--text);border-radius:6px;font-size:11px;cursor:pointer;">
                            🔄 Terapkan Ulang
                        </button>
                    </div>
                </div>

                {{-- Tab selector --}}
                @php
                    $activeTab = old('logo_url') ? 'url' : 'file';
                @endphp
                <div class="tab-row" style="margin-top:20px;margin-bottom:14px;">
                    <button type="button" class="tab-btn {{ $activeTab === 'file' ? 'active' : '' }}" data-tab="tab-file">📁 Upload File</button>
                    <button type="button" class="tab-btn {{ $activeTab === 'url' ? 'active' : '' }}" data-tab="tab-url">🔗 Pakai Link URL</button>
                </div>

                {{-- Tab Upload File --}}
                <div class="tab-pane {{ $activeTab === 'file' ? 'active' : '' }}" id="tab-file">
                    <div class="upload-zone" id="uploadZone">
                        <input type="file" name="logo_file" id="logoFile" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                        <div class="icon">🖼️</div>
                        <p>Klik atau drag gambar logo ke sini (PNG, JPG, WEBP, SVG)<br><span style="font-size:11px;opacity:0.6;">Format: PNG, JPG, WEBP, SVG — maks. 5 MB</span></p>
                    </div>
                </div>

                {{-- Tab Link URL --}}
                <div class="tab-pane {{ $activeTab === 'url' ? 'active' : '' }}" id="tab-url">
                    {{-- Quick Brand Suggestions --}}
                    <div style="margin-bottom:14px;">
                        <div style="font-size:11px;color:var(--muted);font-weight:700;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.5px;">
                            ⚡ PILIHAN LOGO BRAND RESMI (TRANSPARAN VECTOR):
                        </div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            <span class="brand-chip" onclick="applyPresetBrand('Gojek', 'https://cdn.simpleicons.org/gojek')">🟢 Gojek (Transparent SVG)</span>
                            <span class="brand-chip" onclick="applyPresetBrand('Tokopedia', 'https://images.tokopedia.net/assets-tokopedia-lite/v2/zeus/production/e5b8438b.svg')">🦉 Tokopedia</span>
                            <span class="brand-chip" onclick="applyPresetBrand('XL Axiata', 'https://icon.horse/icon/xl.co.id')">📶 XL Axiata</span>
                            <span class="brand-chip" onclick="applyPresetBrand('Indosat', 'https://unavatar.io/indosatooredoo.com')">🟡 Indosat</span>
                            <span class="brand-chip" onclick="applyPresetBrand('Shopee', 'https://cdn.simpleicons.org/shopee')">🟠 Shopee</span>
                            <span class="brand-chip" onclick="applyPresetBrand('Bukalapak', 'https://cdn.simpleicons.org/bukalapak')">🔴 Bukalapak</span>
                            <span class="brand-chip" onclick="applyPresetBrand('Telkom Indonesia', 'https://unavatar.io/telkom.co.id')">🔴 Telkom</span>
                        </div>
                    </div>

                    {{-- Quick Auto-Fetch from domain --}}
                    <div style="background:rgba(0,212,255,0.06);border:1px solid rgba(0,212,255,0.2);border-radius:12px;padding:16px;margin-bottom:16px;">
                        <div style="font-size:12px;font-weight:700;color:var(--cyan);margin-bottom:4px;text-transform:uppercase;letter-spacing:0.5px;">
                            ⚡ Ambil Logo Otomatis dari Website / Domain
                        </div>
                        <div style="font-size:12px;color:var(--muted);margin-bottom:10px;">
                            Cukup ketik domain web partner (contoh: <code>gojek.com</code> atau <code>indosatooredoo.com</code>), sistem otomatis mengambil dan membersihkan background putihnya.
                        </div>
                        <div style="display:flex;gap:8px;">
                            <input type="text" id="domainFetchInput" class="form-control" placeholder="Ketik domain, misal: indosatooredoo.com" style="background:rgba(0,0,0,0.35);">
                            <button type="button" id="btnFetchLogo" style="padding:10px 18px;background:linear-gradient(135deg,var(--cyan),var(--purple));color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap;">
                                ⚡ Ambil Logo
                            </button>
                        </div>
                    </div>

                    <div style="margin-bottom:8px;">
                        <label class="form-label" style="font-size:11px;color:var(--muted);">Link / URL Logo Gambar Langsung</label>
                        <input type="url" name="logo_url" id="logoUrl" class="form-control" 
                               placeholder="https://contoh.com/logo-partner.png" 
                               value="{{ old('logo_url') }}">
                        <div style="font-size:12px;color:var(--muted);margin-top:6px;line-height:1.5;">
                            💡 <strong>Tips link gambar:</strong> Buka gambar logo di browser &rarr; klik kanan &rarr; pilih <em>"Salin Alamat Gambar" (Copy Image Address)</em>, lalu tempel di sini.
                        </div>
                    </div>
                </div>

                {{-- LIVE RESULT PREVIEW --}}
                <div id="livePreviewContainer" style="display:none;margin-top:20px;padding:20px;background:rgba(10,20,50,0.6);border:1px solid rgba(0,212,255,0.3);border-radius:14px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px;">
                        <div>
                            <span style="font-size:11px;color:var(--cyan);font-weight:700;letter-spacing:1px;text-transform:uppercase;">
                                HASIL PREVIEW TAMPILAN DI WEBSITE
                            </span>
                            <div style="font-size:12px;color:var(--muted);margin-top:2px;" id="previewStatusText">
                                Logo siap disimpan dengan background transparan.
                            </div>
                        </div>
                        <div style="display:flex;gap:8px;">
                            <button type="button" id="btnToggleCompare" style="padding:5px 12px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:var(--text);border-radius:6px;font-size:11px;cursor:pointer;">
                                👁️ Bandingkan Gambar Asli
                            </button>
                        </div>
                    </div>

                    <div style="display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
                        <div class="preview-card-box" style="height:76px;width:160px;min-width:160px;">
                            <img id="livePreviewImg" src="" alt="Live Preview">
                        </div>
                        <div style="flex:1;">
                            <div id="badgeStatusClean" style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:#4ade80;background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.3);padding:4px 12px;border-radius:100px;margin-bottom:6px;">
                                ✓ Background Putih Berhasil Dihapus (Transparan)
                            </div>
                            <div style="font-size:13px;color:var(--text);font-weight:600;" id="livePreviewPartnerTitle">
                                Preview Logo
                            </div>
                            <div style="font-size:11px;color:var(--muted);margin-top:2px;">
                                Logo ini akan tampil bersih tanpa kotak putih di atas tampilan dark hero website.
                            </div>
                        </div>
                    </div>
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
// State management
let rawSourceImage = null;
let currentProcessedDataUrl = null;
let isComparingRaw = false;

const autoRemoveBg = document.getElementById('autoRemoveBg');
const toleranceRange = document.getElementById('toleranceRange');
const toleranceVal = document.getElementById('toleranceVal');
const btnReprocess = document.getElementById('btnReprocess');
const processedLogoBase64 = document.getElementById('processedLogoBase64');
const livePreviewContainer = document.getElementById('livePreviewContainer');
const livePreviewImg = document.getElementById('livePreviewImg');
const previewStatusText = document.getElementById('previewStatusText');
const badgeStatusClean = document.getElementById('badgeStatusClean');
const btnToggleCompare = document.getElementById('btnToggleCompare');
const logoFileInput = document.getElementById('logoFile');
const logoUrlInput = document.getElementById('logoUrl');
const domainFetchInput = document.getElementById('domainFetchInput');
const btnFetchLogo = document.getElementById('btnFetchLogo');

if (toleranceRange && toleranceVal) {
    toleranceRange.addEventListener('input', function() {
        toleranceVal.textContent = this.value;
    });
    toleranceRange.addEventListener('change', function() {
        if (rawSourceImage) {
            triggerBackgroundRemoval(rawSourceImage);
        }
    });
}

if (btnReprocess) {
    btnReprocess.addEventListener('click', function() {
        if (rawSourceImage) {
            triggerBackgroundRemoval(rawSourceImage);
        }
    });
}

// Tab Switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        const target = document.getElementById(this.dataset.tab);
        if (target) target.classList.add('active');
    });
});

/**
 * Core Algorithm: Removes outer white / near-white background from image via HTML5 Canvas
 */
function removeWhiteBackground(imgElement, tolerance = 32) {
    try {
        const canvas = document.createElement('canvas');
        const w = imgElement.naturalWidth || imgElement.width;
        const h = imgElement.naturalHeight || imgElement.height;
        if (!w || !h) return null;

        canvas.width = w;
        canvas.height = h;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(imgElement, 0, 0);

        const imgData = ctx.getImageData(0, 0, w, h);
        const data = imgData.data;

        function getPx(x, y) {
            const idx = (y * w + x) * 4;
            return [data[idx], data[idx+1], data[idx+2], data[idx+3]];
        }

        const corners = [
            getPx(0, 0),
            getPx(w - 1, 0),
            getPx(0, h - 1),
            getPx(w - 1, h - 1)
        ];

        let lightCorners = 0;
        let avgR = 0, avgG = 0, avgB = 0;
        corners.forEach(([r, g, b, a]) => {
            if (a > 30 && r > 190 && g > 190 && b > 190) {
                lightCorners++;
                avgR += r; avgG += g; avgB += b;
            }
        });

        if (lightCorners > 0) {
            avgR = Math.round(avgR / lightCorners);
            avgG = Math.round(avgG / lightCorners);
            avgB = Math.round(avgB / lightCorners);
        } else {
            avgR = 255; avgG = 255; avgB = 255;
        }

        function isBg(r, g, b, a) {
            if (a === 0) return true;
            if (r >= (255 - tolerance) && g >= (255 - tolerance) && b >= (255 - tolerance)) return true;
            const dist = Math.sqrt((r - avgR)**2 + (g - avgG)**2 + (b - avgB)**2);
            return dist <= (tolerance * 1.55);
        }

        const visited = new Uint8Array(w * h);
        const queue = [];

        for (let x = 0; x < w; x++) {
            queue.push(0 * w + x);
            queue.push((h - 1) * w + x);
        }
        for (let y = 0; y < h; y++) {
            queue.push(y * w + 0);
            queue.push(y * w + (w - 1));
        }

        while (queue.length > 0) {
            const pos = queue.pop();
            if (visited[pos]) continue;
            visited[pos] = 1;

            const idx = pos * 4;
            const r = data[idx], g = data[idx+1], b = data[idx+2], a = data[idx+3];

            if (isBg(r, g, b, a)) {
                data[idx + 3] = 0;

                const x = pos % w;
                const y = Math.floor(pos / w);

                if (x > 0 && !visited[pos - 1]) queue.push(pos - 1);
                if (x < w - 1 && !visited[pos + 1]) queue.push(pos + 1);
                if (y > 0 && !visited[pos - w]) queue.push(pos - w);
                if (y < h - 1 && !visited[pos + w]) queue.push(pos + w);
            }
        }

        // Edge softening to eliminate white fringes / halos
        for (let y = 1; y < h - 1; y++) {
            for (let x = 1; x < w - 1; x++) {
                const pos = y * w + x;
                const idx = pos * 4;
                if (data[idx + 3] > 0) {
                    const r = data[idx], g = data[idx+1], b = data[idx+2];
                    const hasTransNeighbor = 
                        data[((y-1)*w + x)*4 + 3] === 0 ||
                        data[((y+1)*w + x)*4 + 3] === 0 ||
                        data[(y*w + x-1)*4 + 3] === 0 ||
                        data[(y*w + x+1)*4 + 3] === 0;

                    if (hasTransNeighbor && r > 200 && g > 200 && b > 200) {
                        const whiteness = (r + g + b) / 3;
                        const alphaVal = Math.max(0, Math.min(255, (255 - whiteness) * 3));
                        data[idx + 3] = Math.min(data[idx + 3], Math.round(alphaVal));
                    }
                }
            }
        }

        ctx.putImageData(imgData, 0, 0);
        return canvas.toDataURL('image/png');
    } catch(err) {
        console.warn('Canvas processing error (possibly CORS):', err);
        return null;
    }
}

function triggerBackgroundRemoval(img) {
    rawSourceImage = img;
    const tolerance = parseInt(toleranceRange ? toleranceRange.value : 32, 10);
    
    if (!autoRemoveBg.checked) {
        currentProcessedDataUrl = null;
        processedLogoBase64.value = '';
        livePreviewImg.src = img.src;
        badgeStatusClean.style.display = 'none';
        previewStatusText.textContent = 'Menampilkan logo asli tanpa penghapusan background.';
        livePreviewContainer.style.display = 'block';
        return;
    }

    const transparentPng = removeWhiteBackground(img, tolerance);
    if (transparentPng) {
        currentProcessedDataUrl = transparentPng;
        processedLogoBase64.value = transparentPng;
        livePreviewImg.src = transparentPng;
        badgeStatusClean.style.display = 'inline-flex';
        badgeStatusClean.innerHTML = '✓ Background Putih Dihapus (Transparan Murni)';
        badgeStatusClean.style.color = '#4ade80';
        previewStatusText.textContent = '✓ Logo berhasil dibersihkan! Siap disimpan.';
        livePreviewContainer.style.display = 'block';
    } else {
        livePreviewImg.src = img.src;
        badgeStatusClean.style.display = 'inline-flex';
        badgeStatusClean.innerHTML = '⚡ Server akan menghapus background saat disimpan';
        badgeStatusClean.style.color = 'var(--cyan)';
        previewStatusText.textContent = 'Gambar siap disimpan (pembersihan background dilakukan otomatis di server).';
        livePreviewContainer.style.display = 'block';
    }
}

if (logoFileInput) {
    logoFileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                triggerBackgroundRemoval(img);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
}

function handleUrlChange() {
    let url = logoUrlInput.value.trim();
    if (!url) return;

    if (url.includes('clearbit.com/')) {
        const domain = url.split('clearbit.com/')[1].replace(/^\/+/, '');
        if (domain.includes('gojek')) {
            url = 'https://cdn.simpleicons.org/gojek';
        } else {
            url = 'https://unavatar.io/' + domain;
        }
        logoUrlInput.value = url;
    }

    const img = new Image();
    img.crossOrigin = 'anonymous';
    img.onload = function() {
        triggerBackgroundRemoval(img);
    };
    img.onerror = function() {
        try {
            const u = new URL(url);
            const host = u.hostname;
            if (host && !url.includes('google.com/s2')) {
                const fallback = 'https://www.google.com/s2/favicons?domain=' + encodeURIComponent(host) + '&sz=128';
                const fImg = new Image();
                fImg.crossOrigin = 'anonymous';
                fImg.onload = () => triggerBackgroundRemoval(fImg);
                fImg.src = fallback;
            }
        } catch(e) {}
    };
    img.src = url;
}

if (logoUrlInput) {
    logoUrlInput.addEventListener('change', handleUrlChange);
    logoUrlInput.addEventListener('blur', handleUrlChange);
}

window.applyPresetBrand = function(brandName, url) {
    document.getElementById('partnerNama').value = brandName;
    logoUrlInput.value = url;
    const urlTabBtn = document.querySelector('.tab-btn[data-tab="tab-url"]');
    if (urlTabBtn) urlTabBtn.click();
    handleUrlChange();
};

if (btnFetchLogo && domainFetchInput) {
    btnFetchLogo.addEventListener('click', function() {
        let val = domainFetchInput.value.trim();
        if (!val) {
            alert('Silakan ketik domain website partner (contoh: indosatooredoo.com atau gojek.com)');
            domainFetchInput.focus();
            return;
        }
        val = val.replace(/^https?:\/\//i, '').split('/')[0].split('?')[0].trim();
        if (!val) return;

        if (val.includes('gojek')) {
            logoUrlInput.value = 'https://cdn.simpleicons.org/gojek';
        } else if (val.includes('tokopedia')) {
            logoUrlInput.value = 'https://images.tokopedia.net/assets-tokopedia-lite/v2/zeus/production/e5b8438b.svg';
        } else {
            logoUrlInput.value = 'https://unavatar.io/' + val;
        }
        handleUrlChange();
    });
}

if (btnToggleCompare) {
    btnToggleCompare.addEventListener('click', function() {
        if (!rawSourceImage) return;
        isComparingRaw = !isComparingRaw;
        if (isComparingRaw) {
            livePreviewImg.src = rawSourceImage.src;
            this.textContent = '✨ Lihat Hasil Transparan';
            badgeStatusClean.style.display = 'none';
        } else {
            if (currentProcessedDataUrl) {
                livePreviewImg.src = currentProcessedDataUrl;
                badgeStatusClean.style.display = 'inline-flex';
            }
            this.textContent = '👁️ Bandingkan Gambar Asli';
        }
    });
}

if (autoRemoveBg) {
    autoRemoveBg.addEventListener('change', function() {
        if (rawSourceImage) {
            triggerBackgroundRemoval(rawSourceImage);
        }
    });
}
</script>
@endpush
@endsection
