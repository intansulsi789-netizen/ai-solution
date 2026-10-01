@extends('admin.layouts.app')
@section('title', 'Partner / Logo')

@section('content')
<style>
    .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; }
    .btn-primary {
        background: linear-gradient(135deg, var(--cyan), var(--purple));
        color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none;
        font-weight:700; font-size:14px; display:inline-flex; align-items:center; gap:8px; border:none; cursor:pointer;
    }
    /* GRID CARDS */
    .partner-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }
    .partner-card {
        background: rgba(10,20,60,0.6);
        border: 1px solid rgba(0,212,255,0.12);
        border-radius: 14px;
        padding: 20px 16px 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        transition: border-color 0.25s, box-shadow 0.25s;
        cursor: grab;
        position: relative;
    }
    .partner-card:hover {
        border-color: rgba(0,212,255,0.35);
        box-shadow: 0 0 20px rgba(0,212,255,0.08);
    }
    .partner-card.inactive { opacity: 0.45; }
    .partner-logo-wrap {
        width: 100%; height: 70px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,0.04);
        border-radius: 10px;
        overflow: hidden;
        padding: 8px;
    }
    .partner-logo-wrap img {
        max-width: 100%; max-height: 54px;
        object-fit: contain;
        filter: brightness(0) invert(1);
        opacity: 0.75;
        transition: opacity 0.3s;
    }
    .partner-card:hover .partner-logo-wrap img { opacity: 1; }
    .partner-name {
        font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.8);
        text-align: center; line-height: 1.3;
    }
    .card-meta { display:flex; align-items:center; gap:8px; flex-wrap:wrap; justify-content:center; }
    .card-urutan { font-size:11px; color:var(--muted); background:rgba(255,255,255,0.06); padding:2px 8px; border-radius:100px; }
    .badge-active   { font-size:10px; font-weight:700; padding:2px 8px; border-radius:100px; background:rgba(74,222,128,0.12); color:#4ade80; border:1px solid rgba(74,222,128,0.3); }
    .badge-inactive { font-size:10px; font-weight:700; padding:2px 8px; border-radius:100px; background:rgba(239,68,68,0.12); color:#fca5a5; border:1px solid rgba(239,68,68,0.3); }
    .card-actions { display:flex; gap:8px; margin-top:4px; }
    .card-actions a, .card-actions button {
        flex:1; text-align:center; font-size:12px; font-weight:600; padding:6px 0; border-radius:8px; cursor:pointer; text-decoration:none; transition:all 0.2s;
    }
    .btn-edit  { background:rgba(0,212,255,0.12); color:var(--cyan); border:1px solid rgba(0,212,255,0.25); }
    .btn-edit:hover { background:rgba(0,212,255,0.22); }
    .btn-del   { background:rgba(239,68,68,0.1); color:#fca5a5; border:1px solid rgba(239,68,68,0.25); }
    .btn-del:hover { background:rgba(239,68,68,0.2); }
    .drag-handle {
        position:absolute; top:10px; left:10px;
        width:18px; height:18px; opacity:0.35;
        cursor:grab;
        display:flex; flex-direction:column; gap:3px; justify-content:center;
    }
    .drag-handle span { display:block; height:2px; background:rgba(255,255,255,0.6); border-radius:2px; }
    .drag-saving { position:fixed; bottom:24px; right:24px; background:rgba(0,212,255,0.18); border:1px solid rgba(0,212,255,0.4); color:var(--cyan); padding:10px 18px; border-radius:10px; font-size:13px; font-weight:600; display:none; z-index:999; }
</style>

<div class="top-bar">
    <div>
        <div class="page-label">MANAJEMEN</div>
        <h1 class="page-title" style="margin:0;">Partner / Logo</h1>
        <p style="color:var(--muted);font-size:14px;margin-top:4px;">{{ $partners->count() }} partner · drag untuk ubah urutan</p>
    </div>
    <a href="{{ route('admin.partners.create') }}" class="btn-primary">+ Tambah Partner</a>
</div>

@if(session('success'))
<div style="background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.3);color:#4ade80;padding:12px 16px;border-radius:8px;margin-bottom:24px;font-size:14px;">
    ✓ {{ session('success') }}
</div>
@endif

<div class="partner-grid" id="partnerGrid">
    @forelse($partners as $p)
    <div class="partner-card {{ $p->is_active ? '' : 'inactive' }}" data-id="{{ $p->id }}">
        <div class="drag-handle"><span></span><span></span><span></span></div>

        <div class="partner-logo-wrap">
            @if($p->logo)
                @if(str_starts_with($p->logo, 'http'))
                    <img src="{{ $p->logo }}" alt="{{ $p->nama }}"
                         onerror="this.onerror=null;this.src='https://www.google.com/s2/favicons?domain={{ urlencode($p->logo) }}&sz=128';">
                @else
                    <img src="{{ asset('storage/' . $p->logo) }}" alt="{{ $p->nama }}">
                @endif
            @else
                <span style="font-size:28px;">🖼️</span>
            @endif
        </div>

        <div class="partner-name">{{ $p->nama }}</div>

        <div class="card-meta">
            <span class="card-urutan">#{{ $p->urutan }}</span>
            @if($p->is_active)
                <span class="badge-active">Aktif</span>
            @else
                <span class="badge-inactive">Nonaktif</span>
            @endif
        </div>

        <div class="card-actions" style="width:100%;">
            <a href="{{ route('admin.partners.edit', $p->id) }}" class="btn-edit">Edit</a>
            <form action="{{ route('admin.partners.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus partner ini?');" style="flex:1;display:flex;">
                @csrf @method('DELETE')
                <button type="submit" class="btn-del" style="width:100%;">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--muted);">Belum ada partner. <a href="{{ route('admin.partners.create') }}" style="color:var(--cyan);">Tambah sekarang →</a></div>
    @endforelse
</div>

<div class="drag-saving" id="dragSaving">⏳ Menyimpan urutan...</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
const grid    = document.getElementById('partnerGrid');
const saving  = document.getElementById('dragSaving');
let saveTimer = null;

if (grid) {
    Sortable.create(grid, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'drag-ghost',
        onEnd() {
            clearTimeout(saveTimer);
            saving.style.display = 'block';
            const order = [...grid.querySelectorAll('.partner-card')].map(el => el.dataset.id);
            saveTimer = setTimeout(() => saveOrder(order), 600);
        }
    });
}

function saveOrder(order) {
    fetch('{{ route("admin.partners.reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ order })
    })
    .then(r => r.json())
    .then(() => {
        saving.textContent = '✓ Urutan tersimpan';
        setTimeout(() => { saving.style.display = 'none'; saving.textContent = '⏳ Menyimpan urutan...'; }, 2000);
    })
    .catch(() => { saving.textContent = '✗ Gagal menyimpan'; });
}
</script>
<style>
.drag-ghost { opacity: 0.3; border: 2px dashed var(--cyan) !important; }
</style>
@endpush
@endsection
