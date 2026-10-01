@extends('admin.layouts.app')
@section('title', 'Pesan Konsultasi')

@section('content')
<style>
    .table-container {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        backdrop-filter: blur(12px);
    }
    .table-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;
    }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th, td { padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }
    th { font-size: 12px; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
    td { font-size: 13.5px; color: rgba(255,255,255,0.8); }
    tr.unread td { color: #fff; font-weight: 600; background: rgba(0,212,255,0.03); }
    tr:last-child td { border-bottom: none; }
    .action-btn { color: var(--cyan); text-decoration: none; font-weight: 600; font-size: 13px; margin-right:12px; }
    .action-btn.del { color: #fca5a5; background: none; border: none; font-weight: 600; font-size: 13px; cursor: pointer; font-family: 'Inter', sans-serif; padding: 0; }
    .status-badge { padding: 4px 10px; border-radius: 100px; font-size: 11px; font-weight: 700; }
    .status-read   { background: rgba(255,255,255,0.1); color: var(--muted); border: 1px solid rgba(255,255,255,0.15); }
    .status-unread { background: rgba(0,212,255,0.15); color: var(--cyan); border: 1px solid rgba(0,212,255,0.3); }
</style>

<div class="page-header">
    <div class="page-label">KOMUNIKASI</div>
    <h1 class="page-title">Pesan Konsultasi</h1>
    <p class="page-desc">Kelola pesan masuk dari calon klien.</p>
</div>

@if(session('success'))
<div style="background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.3);color:#4ade80;padding:12px 16px;border-radius:8px;margin-bottom:24px;font-size:14px;">
    ✓ {{ session('success') }}
</div>
@endif

<div class="table-container">
    <div class="table-header">
        <h3 style="font-size:16px;font-weight:700;">Daftar Pesan <span style="font-size:13px;color:var(--muted);font-weight:400;">({{ $consultations->count() }} pesan)</span></h3>
    </div>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Nama & Email</th>
                    <th>WhatsApp</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($consultations as $c)
                <tr class="{{ $c->is_read ? '' : 'unread' }}">
                    <td>
                        <div style="margin-bottom:2px;">{{ $c->nama }}</div>
                        <div style="font-size:12px;color:var(--muted);font-weight:400;">{{ $c->email }}</div>
                    </td>
                    <td>{{ $c->whatsapp ?: '-' }}</td>
                    <td style="font-size:12px;">{{ $c->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</td>
                    <td>
                        @if($c->is_read)
                            <span class="status-badge status-read">Dibaca</span>
                        @else
                            <span class="status-badge status-unread">Baru</span>
                        @endif
                    </td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="{{ route('admin.consultations.show', $c->id) }}" class="action-btn">Detail</a>
                        <form action="{{ route('admin.consultations.toggleRead', $c->id) }}" method="POST" style="display:inline;">
                            @csrf @method('PUT')
                            <button type="submit" class="action-btn" style="background:none;border:none;cursor:pointer;padding:0;">
                                {{ $c->is_read ? 'Tandai Baru' : 'Tandai Dibaca' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.consultations.destroy', $c->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus pesan ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn del">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:48px;color:var(--muted);">Belum ada pesan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
