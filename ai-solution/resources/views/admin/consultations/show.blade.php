@extends('admin.layouts.app')
@section('title', 'Detail Pesan')

@section('content')
<style>
    .detail-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 32px;
        backdrop-filter: blur(12px);
        max-width: 800px;
    }
    .meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .meta-label { font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .meta-value { font-size: 15px; font-weight: 500; color: #fff; }
    .msg-box {
        background: rgba(0,0,0,0.25);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 24px;
        font-size: 15px;
        line-height: 1.6;
        color: rgba(255,255,255,0.9);
        white-space: pre-wrap;
    }
    .btn-back { color:rgba(255,255,255,0.5); padding:10px 20px; font-weight:600; font-size:14px; text-decoration:none; border:1px solid rgba(255,255,255,0.1); border-radius:8px; display:inline-block; transition:all 0.2s; }
    .btn-back:hover { background:rgba(255,255,255,0.05); color:#fff; }
    .btn-wa { background:#25D366; color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600; font-size:14px; display:inline-block; transition:opacity 0.2s; }
    .btn-wa:hover { opacity:0.9; }
</style>

<div class="page-header">
    <div class="page-label">KOMUNIKASI / PESAN KONSULTASI</div>
    <h1 class="page-title">Detail Pesan</h1>
</div>

<div class="detail-card">
    <div class="meta-grid">
        <div>
            <div class="meta-label">Nama Pengirim</div>
            <div class="meta-value">{{ $consultation->nama }}</div>
        </div>
        <div>
            <div class="meta-label">Tanggal Masuk</div>
            <div class="meta-value">{{ $consultation->created_at->timezone('Asia/Jakarta')->format('d F Y, H:i') }} WIB</div>
        </div>
        <div>
            <div class="meta-label">Alamat Email</div>
            <div class="meta-value">{{ $consultation->email }}</div>
        </div>
        <div>
            <div class="meta-label">Nomor WhatsApp</div>
            <div class="meta-value">{{ $consultation->whatsapp ?: '-' }}</div>
        </div>
    </div>

    <div class="meta-label" style="margin-bottom:12px;">Detail Kebutuhan / Pesan</div>
    <div class="msg-box">{{ $consultation->kebutuhan }}</div>

    <div style="margin-top:32px;display:flex;gap:12px;align-items:center;">
        <a href="{{ route('admin.consultations.index') }}" class="btn-back">← Kembali ke Daftar</a>
        @if($consultation->whatsapp)
            @php
                $wa = preg_replace('/[^0-9]/', '', $consultation->whatsapp);
                if(str_starts_with($wa, '0')) $wa = '62' . substr($wa, 1);
            @endphp
            <a href="https://wa.me/{{ $wa }}" target="_blank" class="btn-wa">Balas via WhatsApp</a>
        @endif
        <a href="mailto:{{ $consultation->email }}" class="btn-back" style="border-color:var(--cyan);color:var(--cyan);">Balas via Email</a>
    </div>
</div>
@endsection
