@extends('admin.layouts.app')
@section('title', 'Artikel')

@section('content')
<style>
    .table-container {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 32px;
        backdrop-filter: blur(12px);
        max-width: 1300px;
        width: 100%;
    }
    .table-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--cyan), var(--purple));
        color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;
        display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer;
    }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th, td { padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }
    th { font-size: 12px; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
    td { font-size: 13.5px; color: rgba(255,255,255,0.8); }
    tr:last-child td { border-bottom: none; }
    .action-btn { color: var(--cyan); text-decoration: none; font-weight: 600; font-size: 13px; }
    .action-btn.del { color: #fca5a5; background: none; border: none; font-weight: 600; font-size: 13px; cursor: pointer; font-family: 'Inter', sans-serif; padding: 0; }
    .status-badge { padding: 4px 10px; border-radius: 100px; font-size: 11px; font-weight: 700; }
    .status-active   { background: rgba(74,222,128,0.15); color: #4ade80; border: 1px solid rgba(74,222,128,0.3); }
    .status-inactive { background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.3); }
    .thumb { width: 56px; height: 40px; object-fit: cover; border-radius: 6px; opacity: 0.85; }
    .thumb-placeholder { width: 56px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.2); font-size: 18px; }
    .cat-pill { display: inline-block; padding: 3px 9px; border-radius: 100px; font-size: 11px; font-weight: 600; background: rgba(124,58,237,0.18); color: #a78bfa; border: 1px solid rgba(124,58,237,0.28); }
</style>

<div class="page-header">
    <div class="page-label">MANAJEMEN</div>
    <h1 class="page-title">Artikel</h1>
    <p class="page-desc">Kelola artikel & wawasan yang ditampilkan di halaman frontend.</p>
</div>

@if(session('success'))
<div style="background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.3);color:#4ade80;padding:12px 16px;border-radius:8px;margin-bottom:24px;font-size:14px;">
    ✓ {{ session('success') }}
</div>
@endif

<div class="table-container">
    <div class="table-header">
        <h3 style="font-size:16px;font-weight:700;">Daftar Artikel <span style="font-size:13px;color:var(--muted);font-weight:400;">({{ $articles->count() }} artikel)</span></h3>
        <a href="{{ route('admin.articles.create') }}" class="btn-primary">
            + Tambah Artikel
        </a>
    </div>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:70px;">Gambar</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $a)
                <tr>
                    <td>
                        @if($a->gambar)
                            <img src="{{ str_starts_with($a->gambar, 'http') ? $a->gambar : asset('storage/' . $a->gambar) }}" alt="{{ $a->judul }}" class="thumb">
                        @else
                            <div class="thumb-placeholder">📄</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;color:#fff;margin-bottom:2px;">{{ Str::limit($a->judul, 55) }}</div>
                        <div style="font-size:12px;color:var(--muted);">{{ $a->slug }}</div>
                    </td>
                    <td><span class="cat-pill">{{ $a->kategori ?: '-' }}</span></td>
                    <td style="font-size:12px;color:var(--muted);">{{ $a->tanggal ?: '-' }}</td>
                    <td>
                        @if($a->is_active)
                            <span class="status-badge status-active" style="margin-bottom: 4px; display: inline-block;">Aktif</span><br>
                        @else
                            <span class="status-badge status-inactive" style="margin-bottom: 4px; display: inline-block;">Nonaktif</span><br>
                        @endif
                        
                        @if($a->status === 'draft')
                            <span class="status-badge" style="background: rgba(234,179,8,0.1); color: #facc15; border: 1px solid rgba(234,179,8,0.3);">Draft</span>
                        @else
                            <span class="status-badge" style="background: rgba(16,185,129,0.1); color: #34d399; border: 1px solid rgba(16,185,129,0.3);">Published</span>
                        @endif
                    </td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="{{ route('admin.articles.edit', $a->id) }}" class="action-btn" style="margin-right:12px;">Edit</a>
                        <form action="{{ route('admin.articles.destroy', $a->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus artikel ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn del">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:48px;color:var(--muted);">Belum ada artikel.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
