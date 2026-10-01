@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
{{-- Page header --}}
<div class="page-header">
    <div class="page-label">ADMIN PANEL</div>
    <h1 class="page-title">Selamat datang, {{ Auth::user()->name }} 👋</h1>
    <p class="page-desc">Ringkasan data dan statistik AI Solution saat ini.</p>
</div>

{{-- Stat cards --}}
<div class="stat-grid">

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                <polyline points="2 17 12 22 22 17"></polyline>
                <polyline points="2 12 12 17 22 12"></polyline>
            </svg>
        </div>
        <div class="stat-value">{{ $stats['total_layanan'] }}</div>
        <div class="stat-label">Total Layanan AI</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
        </div>
        <div class="stat-value">{{ $stats['total_artikel'] }}</div>
        <div class="stat-label">Total Artikel</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div class="stat-value">{{ $stats['total_partner'] }}</div>
        <div class="stat-label">Total Partner</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
        </div>
        <div class="stat-value">{{ $stats['total_konsultasi'] }}</div>
        <div class="stat-label">Pesan Konsultasi</div>
    </div>

</div>

{{-- Info panels --}}
<div class="info-grid">

    {{-- Quick access --}}
    <div class="info-card">
        <div class="info-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 8 12 12 14 14"></polyline>
            </svg>
            Menu Manajemen
        </div>
        <div class="menu-link-list">
            <a href="{{ route('admin.services.index') }}" class="menu-link-item">
                Manajemen Layanan AI
                <span style="font-size:10px;padding:2px 8px;background:rgba(0,212,255,0.12);border:1px solid rgba(0,212,255,0.25);border-radius:100px;color:#00d4ff;font-weight:600;">Aktif</span>
            </a>
            <a href="{{ route('admin.articles.index') }}" class="menu-link-item">
                Manajemen Artikel
                <span style="font-size:10px;padding:2px 8px;background:rgba(0,212,255,0.12);border:1px solid rgba(0,212,255,0.25);border-radius:100px;color:#00d4ff;font-weight:600;">Aktif</span>
            </a>
            <a href="{{ route('admin.partners.index') }}" class="menu-link-item">
                Manajemen Partner / Logo
                <span style="font-size:10px;padding:2px 8px;background:rgba(0,212,255,0.12);border:1px solid rgba(0,212,255,0.25);border-radius:100px;color:#00d4ff;font-weight:600;">Aktif</span>
            </a>
            <a href="{{ route('admin.consultations.index') }}" class="menu-link-item">
                Pesan Konsultasi
                @php $unreadCount = \App\Models\Consultation::where('is_read', false)->count(); @endphp
                @if($unreadCount > 0)
                    <span style="font-size:10px;padding:2px 8px;background:var(--cyan);color:#000;border-radius:100px;font-weight:700;">{{ $unreadCount }} Baru</span>
                @else
                    <span style="font-size:10px;padding:2px 8px;background:rgba(0,212,255,0.12);border:1px solid rgba(0,212,255,0.25);border-radius:100px;color:#00d4ff;font-weight:600;">Aktif</span>
                @endif
            </a>
        </div>
    </div>

    {{-- Recent activity placeholder --}}
    <div class="info-card">
        <div class="info-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
            Aktivitas Terbaru
        </div>
        <div class="menu-link-list">
            @php
                $activities = collect();

                foreach(\App\Models\Service::latest()->take(5)->get() as $item) {
                    $activities->push([
                        'type' => 'Layanan',
                        'title' => $item->title ?? $item->name,
                        'time' => $item->updated_at ?? $item->created_at,
                        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>',
                        'color' => '#00d4ff'
                    ]);
                }

                foreach(\App\Models\Article::latest()->take(5)->get() as $item) {
                    $activities->push([
                        'type' => 'Artikel',
                        'title' => $item->title,
                        'time' => $item->updated_at ?? $item->created_at,
                        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>',
                        'color' => '#a78bfa'
                    ]);
                }

                foreach(\App\Models\Partner::latest()->take(5)->get() as $item) {
                    $activities->push([
                        'type' => 'Partner',
                        'title' => $item->name,
                        'time' => $item->updated_at ?? $item->created_at,
                        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
                        'color' => '#f472b6'
                    ]);
                }

                foreach(\App\Models\Consultation::latest()->take(5)->get() as $item) {
                    $activities->push([
                        'type' => 'Konsultasi',
                        'title' => $item->name,
                        'time' => $item->created_at,
                        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
                        'color' => '#4ade80'
                    ]);
                }

                $activities = $activities->sortByDesc('time')->take(5);
            @endphp
            
            @forelse($activities as $act)
            <div class="menu-link-item" style="justify-content: flex-start; gap: 12px; align-items: flex-start;">
                <div style="color: {{ $act['color'] }}; width: 16px; height: 16px; margin-top: 2px;">
                    {!! $act['icon'] !!}
                </div>
                <div style="flex: 1; min-width: 0; text-align: left;">
                    <div style="font-weight: 600; font-size: 13px; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $act['title'] }}
                    </div>
                    <div style="font-size: 11px; color: rgba(255,255,255,0.45); margin-top: 3px;">
                        {{ $act['type'] }} &bull; {{ $act['time'] ? $act['time']->diffForHumans() : '-' }}
                    </div>
                </div>
            </div>
            @empty
            <div class="activity-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <p>Belum ada aktivitas tercatat.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
