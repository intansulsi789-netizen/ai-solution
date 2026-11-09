@extends('admin.layouts.app')
@section('title', 'Tambah Layanan AI')

@section('content')
<style>
    .form-container {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 36px;
        backdrop-filter: blur(12px);
        max-width: 1300px;
        width: 100%;
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
        width: 100%;
        background: rgba(0,0,0,0.25);
        border: 1px solid rgba(255,255,255,0.1);
        color: var(--text);
        padding: 12px 16px;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
        resize: vertical;
    }
    .form-control:focus { outline: none; border-color: var(--cyan); box-shadow: 0 0 0 3px rgba(0,212,255,0.08); }
    .btn-submit {
        background: linear-gradient(135deg, var(--cyan), var(--purple));
        color: #fff; padding: 13px 32px; border-radius: 10px; border: none; font-weight: 700; font-size: 14px; cursor: pointer; transition: opacity 0.2s, transform 0.2s;
    }
    .btn-submit:hover { opacity: 0.95; transform: translateY(-1px); }
    .btn-cancel {
        background: transparent; color: var(--muted); padding: 12px 24px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-block; border: 1px solid var(--border); border-radius: 10px; margin-right: 12px;
    }
    .checkbox-wrap { display: flex; align-items: center; gap: 10px; }
    .checkbox-wrap input { width: 18px; height: 18px; cursor: pointer; }

    /* ICON PICKER */
    .icon-picker-box {
        background: rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 20px;
    }
    .icon-preview-card {
        display: flex;
        align-items: center;
        gap: 16px;
        background: rgba(0, 212, 255, 0.05);
        border: 1px solid rgba(0, 212, 255, 0.25);
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 16px;
    }
    .icon-preview-display {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.15), rgba(124, 58, 237, 0.2));
        border: 1px solid rgba(0, 212, 255, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--cyan);
        box-shadow: 0 0 16px rgba(0, 212, 255, 0.25);
        flex-shrink: 0;
    }
    .icon-preview-display svg {
        width: 26px;
        height: 26px;
    }
    .icon-preview-info {
        flex-grow: 1;
        min-width: 0;
    }
    .icon-preview-title {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 3px;
    }
    .icon-preview-sub {
        font-size: 12px;
        color: var(--muted);
    }
    .icon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 10px;
        max-height: 260px;
        overflow-y: auto;
        padding: 4px;
        margin-top: 12px;
    }
    .icon-grid::-webkit-scrollbar {
        width: 6px;
    }
    .icon-grid::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.15);
        border-radius: 4px;
    }
    .icon-item {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px;
        padding: 12px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }
    .icon-item:hover {
        background: rgba(0, 212, 255, 0.08);
        border-color: rgba(0, 212, 255, 0.35);
        transform: translateY(-2px);
    }
    .icon-item.active {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.18), rgba(124, 58, 237, 0.18));
        border-color: var(--cyan);
        box-shadow: 0 0 14px rgba(0, 212, 255, 0.35);
    }
    .icon-item svg {
        width: 22px;
        height: 22px;
        margin-bottom: 6px;
        color: var(--cyan);
    }
    .icon-item span {
        font-size: 11px;
        font-weight: 600;
        color: var(--text);
        line-height: 1.25;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* IMAGE PREVIEW */
    .image-live-preview {
        margin-top: 12px;
        display: none;
        align-items: center;
        gap: 16px;
        padding: 12px 16px;
        background: rgba(0,0,0,0.25);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        max-width: 450px;
    }
    .image-live-preview img {
        width: 90px;
        height: 56px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid rgba(0, 212, 255, 0.3);
    }

    @media (max-width: 768px) {
        .form-container { padding: 24px; }
        .form-grid { grid-template-columns: 1fr; gap: 0; }
        .icon-grid { grid-template-columns: repeat(auto-fill, minmax(105px, 1fr)); }
    }
</style>

<div class="page-header">
    <div class="page-label">MANAJEMEN / LAYANAN AI</div>
    <h1 class="page-title">Tambah Layanan</h1>
</div>

<div class="form-container">
    @if($errors->any())
        <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
            <ul style="margin-left: 20px;">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="form-service" action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nama Layanan *</label>
                <input type="text" name="nama_layanan" id="nama_layanan" class="form-control" value="{{ old('nama_layanan') }}" placeholder="Contoh: AI Chatbot Enterprise" required>
            </div>

            <div class="form-group">
                <label class="form-label">Slug (URL) *</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" placeholder="ai-chatbot-enterprise" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Urut (Opsional)</label>
                <input type="number" name="nomor" id="nomor" class="form-control" value="{{ old('nomor') }}" placeholder="Otomatis jika dikosongkan">
            </div>

            {{-- PILIHAN IKON LAYANAN AI --}}
            <div class="form-group full-width">
                <label class="form-label">Pilihan Ikon Layanan AI *</label>
                <div class="icon-picker-box">
                    @php
                        $defaultIconPath = old('icon', $availableIcons[0]['path'] ?? '');
                        $defaultIconName = 'AI Chatbot / Asisten';
                        foreach($availableIcons as $ic) {
                            if ($ic['path'] === $defaultIconPath) {
                                $defaultIconName = $ic['name'];
                                break;
                            }
                        }
                    @endphp

                    {{-- Live Icon Preview Box --}}
                    <div class="icon-preview-card" id="icon-preview-card">
                        <div class="icon-preview-display" id="icon-preview-display">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path id="preview-path" d="{{ $defaultIconPath }}"></path>
                            </svg>
                        </div>
                        <div class="icon-preview-info">
                            <div class="icon-preview-title" id="preview-name">{{ $defaultIconName }}</div>
                            <div class="icon-preview-sub">Ikon terpilih akan ditampilkan pada kartu layanan di beranda dan halaman detail.</div>
                        </div>
                    </div>

                    {{-- Search Filter --}}
                    <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 10px;">
                        <input type="text" id="icon-search-input" class="form-control" placeholder="Cari ikon (misal: Chat, Agent, Vision, Data, Cloud)..." style="font-size: 13px; padding: 10px 14px;">
                        <button type="button" id="toggle-custom-svg" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: var(--muted); border-radius: 8px; padding: 10px 14px; font-size: 12px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: 0.2s;">
                            Kustom SVG Path
                        </button>
                    </div>

                    {{-- Icon Grid --}}
                    <div class="icon-grid" id="icon-grid">
                        @foreach($availableIcons as $ic)
                        <div class="icon-item {{ $defaultIconPath === $ic['path'] ? 'active' : '' }}"
                             data-path="{{ $ic['path'] }}"
                             data-name="{{ $ic['name'] }}"
                             data-search="{{ strtolower($ic['name'] . ' ' . $ic['category'] . ' ' . $ic['id']) }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="{{ $ic['path'] }}"></path>
                            </svg>
                            <span>{{ $ic['name'] }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Hidden/Visible Input --}}
                    <div id="custom-svg-container" style="display: none; margin-top: 14px; padding-top: 14px; border-top: 1px dashed rgba(255,255,255,0.1);">
                        <label class="form-label" style="font-size: 11px;">Path SVG Kustom (Attribut 'd' dari Tag &lt;path&gt;)</label>
                        <input type="text" name="icon" id="icon-input" class="form-control" value="{{ $defaultIconPath }}" placeholder="Contoh: M12 2L2 7l10 5 10-5-10-5z...">
                        <small style="color: var(--muted); font-size: 11px; margin-top: 4px; display: block;">Format SVG 24x24 viewBox. Nilai otomatis terisi saat memilih ikon di atas.</small>
                    </div>
                </div>
            </div>

            {{-- GAMBAR LAYANAN --}}
            <div class="form-group full-width">
                <label class="form-label">Gambar Layanan (Opsional)</label>
                <input type="file" name="image" id="service-image-input" class="form-control" accept="image/jpeg,image/png,image/webp" style="padding: 9px 16px;">
                <small style="color: var(--muted); font-size: 12px; margin-top: 4px; display: block;">Format: JPG, PNG, WEBP. Maks 5MB. Jika diunggah, gambar akan tampil pada kartu layanan di beranda dan halaman detail.</small>

                {{-- Live Image Preview --}}
                <div class="image-live-preview" id="image-live-preview">
                    <img id="image-live-preview-img" src="" alt="Pratinjau Gambar">
                    <div>
                        <div id="image-live-name" style="font-size: 13px; font-weight: 600; color: #fff;"></div>
                        <div id="image-live-size" style="font-size: 11px; color: var(--muted); margin-top: 2px;"></div>
                    </div>
                </div>
            </div>

            <div class="form-group full-width">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="deskripsi_singkat" class="form-control" rows="3">{{ old('deskripsi_singkat') }}</textarea>
            </div>

            @php
                $dlRaw = old('deskripsi_lengkap');
                $dl = json_decode($dlRaw, true) ?: [];
                $solutions = $dl['solutions'] ?? [];
                $benefits = $dl['benefits'] ?? [];
                
                for($i = count($solutions); $i < 4; $i++) {
                    $solutions[] = ['title' => '', 'desc' => ''];
                }
                
                for($i = count($benefits); $i < 4; $i++) {
                    $benefits[] = '';
                }
            @endphp

            <div class="form-group full-width" style="background: rgba(0,0,0,0.15); padding: 24px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 14px; font-weight: 700; color: var(--cyan); margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Solusi (4 Item)</div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    @foreach($solutions as $idx => $sol)
                    @if($idx < 4)
                    <div class="sol-item" style="background: rgba(0,0,0,0.25); padding: 16px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.05);">
                        <label class="form-label">Item {{ $idx + 1 }} - Judul</label>
                        <input type="text" class="form-control sol-title" value="{{ $sol['title'] ?? '' }}" style="margin-bottom: 12px;" placeholder="Contoh: Otomatisasi 24/7">
                        <label class="form-label">Item {{ $idx + 1 }} - Deskripsi</label>
                        <textarea class="form-control sol-desc" rows="2" placeholder="Deskripsi solusi...">{{ $sol['desc'] ?? '' }}</textarea>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div style="font-size: 14px; font-weight: 700; color: var(--cyan); margin-top: 32px; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Daftar Benefit (Manfaat)</div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                    @foreach($benefits as $idx => $ben)
                    <div style="display: flex; align-items: center;">
                        <span style="background: rgba(125,125,125,0.15); color: var(--text); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-weight: 600; margin-right: 12px; font-size: 13px;">{{ $idx + 1 }}</span>
                        <input type="text" class="form-control ben-item" value="{{ $ben }}" placeholder="Contoh: Menghemat biaya operasional">
                    </div>
                    @endforeach
                </div>
            </div>
            
            <input type="hidden" name="deskripsi_lengkap" id="deskripsi_lengkap" value="{{ $dlRaw }}">

            <div class="form-group checkbox-wrap full-width">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" style="font-size: 14px; font-weight: 500; cursor: pointer;">Status Aktif</label>
            </div>
        </div>

        <div style="margin-top: 16px; padding-top: 24px; border-top: 1px solid var(--border); text-align: right;">
            <a href="{{ route('admin.services.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan Layanan</button>
        </div>
    </form>
</div>

<script>
// Slug Generator
const namaLayanan = document.getElementById('nama_layanan');
const slugInput = document.getElementById('slug');

if (namaLayanan && slugInput) {
    namaLayanan.addEventListener('input', function() {
        if (!slugInput.dataset.manual) {
            slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
        }
    });
    slugInput.addEventListener('input', function() { this.dataset.manual = true; });
}

// Icon Picker Logic
const iconItems = document.querySelectorAll('.icon-item');
const iconInput = document.getElementById('icon-input');
const previewPath = document.getElementById('preview-path');
const previewName = document.getElementById('preview-name');
const iconSearch = document.getElementById('icon-search-input');
const toggleCustomBtn = document.getElementById('toggle-custom-svg');
const customSvgContainer = document.getElementById('custom-svg-container');

iconItems.forEach(item => {
    item.addEventListener('click', function() {
        iconItems.forEach(i => i.classList.remove('active'));
        this.classList.add('active');

        const path = this.dataset.path;
        const name = this.dataset.name;

        iconInput.value = path;
        previewPath.setAttribute('d', path);
        previewName.textContent = name;
    });
});

if (iconSearch) {
    iconSearch.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        iconItems.forEach(item => {
            const searchKey = item.dataset.search || '';
            if (!query || searchKey.includes(query)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
}

if (toggleCustomBtn && customSvgContainer) {
    toggleCustomBtn.addEventListener('click', function() {
        const isHidden = customSvgContainer.style.display === 'none';
        customSvgContainer.style.display = isHidden ? 'block' : 'none';
        this.style.color = isHidden ? 'var(--cyan)' : 'var(--muted)';
        this.style.borderColor = isHidden ? 'var(--cyan)' : 'rgba(255,255,255,0.12)';
    });
}

if (iconInput) {
    iconInput.addEventListener('input', function() {
        const path = this.value.trim();
        previewPath.setAttribute('d', path);

        let found = false;
        iconItems.forEach(item => {
            if (item.dataset.path === path) {
                item.classList.add('active');
                previewName.textContent = item.dataset.name;
                found = true;
            } else {
                item.classList.remove('active');
            }
        });
        if (!found) {
            previewName.textContent = 'Ikon Kustom (SVG)';
        }
    });
}

// Image Live Preview
const imageInput = document.getElementById('service-image-input');
const imagePreviewBox = document.getElementById('image-live-preview');
const imagePreviewImg = document.getElementById('image-live-preview-img');
const imageLiveName = document.getElementById('image-live-name');
const imageLiveSize = document.getElementById('image-live-size');

if (imageInput && imagePreviewBox) {
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreviewImg.src = e.target.result;
                imagePreviewBox.style.display = 'flex';
                imageLiveName.textContent = file.name;
                imageLiveSize.textContent = (file.size / 1024).toFixed(1) + ' KB';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreviewBox.style.display = 'none';
        }
    });
}

// Sync Solutions and Benefits
function syncDeskripsiLengkap() {
    let solutions = [];
    document.querySelectorAll('.sol-item').forEach(item => {
        let titleInput = item.querySelector('.sol-title');
        let descInput = item.querySelector('.sol-desc');
        let title = titleInput ? titleInput.value.trim() : '';
        let desc = descInput ? descInput.value.trim() : '';
        if(title || desc) {
            solutions.push({ title: title, desc: desc });
        }
    });

    let benefits = [];
    document.querySelectorAll('.ben-item').forEach(item => {
        let val = item.value.trim();
        if(val) benefits.push(val);
    });

    let data = {
        solutions: solutions,
        benefits: benefits
    };

    const hiddenDeskripsiLengkap = document.getElementById('deskripsi_lengkap');
    if (hiddenDeskripsiLengkap) {
        hiddenDeskripsiLengkap.value = JSON.stringify(data);
    }
}

document.querySelectorAll('.sol-title, .sol-desc, .ben-item').forEach(el => {
    el.addEventListener('input', syncDeskripsiLengkap);
});

const formService = document.getElementById('form-service');
if (formService) {
    formService.addEventListener('submit', function() {
        syncDeskripsiLengkap();
    });
}
</script>
@endsection
