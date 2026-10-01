@extends('admin.layouts.app')
@section('title', 'Layanan AI')

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
    th, td { padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,0.05); }
    th { font-size: 13px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
    td { font-size: 14px; color: rgba(255,255,255,0.8); }
    .action-links a { color: var(--cyan); text-decoration: none; margin-right: 12px; font-weight: 600; font-size: 13px; }
    .action-links a.text-danger { color: #fca5a5; }
    .status-badge { padding: 4px 10px; border-radius: 100px; font-size: 11px; font-weight: 700; }
    .status-active { background: rgba(74,222,128,0.15); color: #4ade80; border: 1px solid rgba(74,222,128,0.3); }
    .status-inactive { background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.3); }
</style>

<div class="page-header">
    <div class="page-label">MANAJEMEN</div>
    <h1 class="page-title">Layanan AI</h1>
    <p class="page-desc">Kelola daftar layanan AI yang ditampilkan di halaman depan.</p>
</div>

@if(session('success'))
<div style="background: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.3); color: #4ade80; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 14px;">
    {{ session('success') }}
</div>
@endif

<div class="table-container">
    <div class="table-header">
        <h3 style="font-size: 16px; font-weight: 700;">Daftar Layanan</h3>
        <a href="{{ route('admin.services.create') }}" class="btn-primary">
            + Tambah Layanan
        </a>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">No.</th>
                    <th>Nama Layanan</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $s)
                <tr>
                    <td>{{ $s->nomor }}</td>
                    <td style="font-weight: 600; color: #fff;">{{ $s->nama_layanan }}</td>
                    <td><span style="background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 6px; font-size: 12px;">{{ $s->slug }}</span></td>
                    <td>
                        @if($s->is_active)
                            <span class="status-badge status-active">Aktif</span>
                        @else
                            <span class="status-badge status-inactive">Nonaktif</span>
                        @endif
                    </td>
                    <td style="text-align: right;" class="action-links">
                        <a href="{{ route('admin.services.edit', $s->id) }}">Edit</a>
                        <form action="{{ route('admin.services.destroy', $s->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus layanan ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#fca5a5;font-weight:600;font-size:13px;cursor:pointer;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if($services->isEmpty())
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: var(--muted);">Belum ada data layanan.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
