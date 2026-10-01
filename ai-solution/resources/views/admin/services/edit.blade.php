@extends('admin.layouts.app')
@section('title', 'Edit Layanan AI')

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
        color: #fff;
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
        background: transparent; color: rgba(255,255,255,0.6); padding: 12px 24px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-block; border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; margin-right: 12px;
    }
    .checkbox-wrap { display: flex; align-items: center; gap: 10px; }
    .checkbox-wrap input { width: 18px; height: 18px; cursor: pointer; }
    @media (max-width: 768px) {
        .form-container { padding: 24px; }
        .form-grid { grid-template-columns: 1fr; gap: 0; }
    }
</style>

<div class="page-header">
    <div class="page-label">MANAJEMEN / LAYANAN AI</div>
    <h1 class="page-title">Edit Layanan</h1>
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

    <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nomor Urut</label>
                <input type="number" name="nomor" class="form-control" value="{{ old('nomor', $service->nomor) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Layanan</label>
                <input type="text" name="nama_layanan" id="nama_layanan" class="form-control" value="{{ old('nama_layanan', $service->nama_layanan) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Slug (URL)</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $service->slug) }}" required readonly style="background: rgba(0,0,0,0.5); color: var(--muted); cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label class="form-label">Pilih Icon Layanan</label>
                @php 
                    $currentIcon = old('icon', $service->icon);
                    $icons = [
                        'Layers / Stack' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>',
                        'Data / Analytics' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>',
                        'Robot / AI' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"></rect><circle cx="12" cy="5" r="2"></circle><path d="M12 7v4"></path><line x1="8" y1="16" x2="8" y2="16"></line><line x1="16" y1="16" x2="16" y2="16"></line></svg>',
                        'Cloud / Network' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path></svg>',
                        'Shield / Security' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
                        'Chat / Message' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
                    ];
                    $isCustom = !in_array($currentIcon, array_values($icons));
                @endphp
                <select name="icon" class="form-control">
                    @foreach($icons as $label => $svg)
                        <option value="{{ $svg }}" {{ $currentIcon == $svg ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                    @if($isCustom && $currentIcon)
                        <option value="{{ $currentIcon }}" selected>Icon Saat Ini (Kustom)</option>
                    @endif
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Gambar Layanan (Opsional)</label>
                @if($service->image)
                    <div style="margin-bottom: 12px; border-radius: 8px; overflow: hidden; max-width: 200px; border: 1px solid rgba(255,255,255,0.1);">
                        <img src="{{ Storage::url($service->image) }}" alt="Current Image" style="width: 100%; display: block;">
                    </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*" style="padding: 9px 16px;">
                <small style="color: var(--muted); font-size: 12px; margin-top: 4px; display: block;">Format: JPG, PNG, WEBP. Maks 5MB. Biarkan kosong jika tidak ingin mengubah gambar. Jika dikosongkan dan belum pernah upload, akan menggunakan gambar bawaan slug.</small>
            </div>

            <div class="form-group full-width">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="deskripsi_singkat" class="form-control" rows="3">{{ old('deskripsi_singkat', $service->deskripsi_singkat) }}</textarea>
            </div>

            @php
                $dlRaw = old('deskripsi_lengkap', $service->deskripsi_lengkap);
                $dl = json_decode($dlRaw, true) ?: [];
                $solutions = $dl['solutions'] ?? [];
                $benefits = $dl['benefits'] ?? [];
                
                // Pastikan minimal 4 solusi
                for($i = count($solutions); $i < 4; $i++) {
                    $solutions[] = ['title' => '', 'desc' => ''];
                }
                
                // Pastikan minimal 4 benefit
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
                        <label class="form-label" style="color: #fff;">Item {{ $idx + 1 }} - Judul</label>
                        <input type="text" class="form-control sol-title" value="{{ $sol['title'] ?? '' }}" style="margin-bottom: 12px;" placeholder="Contoh: Otomatisasi 24/7">
                        <label class="form-label" style="color: #fff;">Item {{ $idx + 1 }} - Deskripsi</label>
                        <textarea class="form-control sol-desc" rows="2" placeholder="Deskripsi solusi...">{{ $sol['desc'] ?? '' }}</textarea>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div style="font-size: 14px; font-weight: 700; color: var(--cyan); margin-top: 32px; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Daftar Benefit (Manfaat)</div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                    @foreach($benefits as $idx => $ben)
                    <div style="display: flex; align-items: center;">
                        <span style="background: rgba(255,255,255,0.1); color: #fff; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-weight: 600; margin-right: 12px; font-size: 13px;">{{ $idx + 1 }}</span>
                        <input type="text" class="form-control ben-item" value="{{ $ben }}" placeholder="Contoh: Menghemat biaya operasional">
                    </div>
                    @endforeach
                </div>
            </div>
            
            <input type="hidden" name="deskripsi_lengkap" id="deskripsi_lengkap" value="{{ $dlRaw }}">

            <div class="form-group checkbox-wrap full-width">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                <label for="is_active" style="font-size: 14px; font-weight: 500; cursor: pointer;">Status Aktif</label>
            </div>
        </div>

        <div style="margin-top: 16px; padding-top: 24px; border-top: 1px solid var(--border); text-align: right;">
            <a href="{{ route('admin.services.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
document.getElementById('nama_layanan').addEventListener('input', function() {
    let slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    document.getElementById('slug').value = slug;
});

document.querySelector('form').addEventListener('submit', function(e) {
    let solutions = [];
    document.querySelectorAll('.sol-item').forEach(item => {
        let title = item.querySelector('.sol-title').value.trim();
        let desc = item.querySelector('.sol-desc').value.trim();
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

    document.getElementById('deskripsi_lengkap').value = JSON.stringify(data);
});
</script>
@endsection
