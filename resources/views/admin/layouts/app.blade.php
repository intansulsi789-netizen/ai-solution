<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — AI Solution</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:         #020818;
            --sidebar-bg: rgba(5, 12, 40, 0.95);
            --surface:    rgba(10, 20, 60, 0.55);
            --border:     rgba(0, 212, 255, 0.14);
            --border-h:   rgba(0, 212, 255, 0.35);
            --cyan:       #00d4ff;
            --purple:     #7c3aed;
            --text:       #f0f4ff;
            --muted:      rgba(255,255,255,0.45);
            --sidebar-w:  240px;
        }

        body {
            background: var(--bg);
            font-family: 'Inter', sans-serif;
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ===========================
           SIDEBAR
        =========================== */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .sidebar-logo {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
        }
        .sidebar-logo-icon {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--cyan), var(--purple));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-logo-icon svg { width: 18px; height: 18px; }
        .sidebar-logo-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px; font-weight: 800;
            color: #fff;
        }
        .sidebar-logo-text span { color: var(--cyan); }

        /* admin badge */
        .admin-badge {
            margin: 16px 20px;
            padding: 10px 14px;
            background: rgba(0,212,255,0.07);
            border: 1px solid rgba(0,212,255,0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, rgba(0,212,255,0.3), rgba(124,58,237,0.3));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700;
            color: var(--cyan);
            flex-shrink: 0;
        }
        .admin-info { overflow: hidden; }
        .admin-name {
            font-size: 13px; font-weight: 600;
            color: #fff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .admin-role {
            font-size: 11px;
            color: var(--muted);
            margin-top: 1px;
        }

        /* nav */
        .sidebar-nav {
            flex: 1;
            padding: 8px 12px;
            overflow-y: auto;
        }
        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            padding: 10px 8px 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--muted);
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }
        .nav-item svg {
            width: 16px; height: 16px;
            flex-shrink: 0;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        .nav-item:hover {
            background: rgba(0,212,255,0.08);
            color: #fff;
        }
        .nav-item:hover svg { opacity: 1; }
        .nav-item.active {
            background: rgba(0,212,255,0.12);
            color: var(--cyan);
            font-weight: 600;
        }
        .nav-item.active svg { opacity: 1; color: var(--cyan); }

        /* logout */
        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--border);
        }
        .btn-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            background: transparent;
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 10px;
            color: rgba(252,165,165,0.70);
            font-size: 13.5px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            text-align: left;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-logout svg { width: 16px; height: 16px; opacity: 0.7; }
        .btn-logout:hover {
            background: rgba(239,68,68,0.10);
            border-color: rgba(239,68,68,0.45);
            color: #fca5a5;
        }

        /* ===========================
           MAIN CONTENT
        =========================== */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* topbar */
        .topbar {
            height: 64px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            background: rgba(2,8,24,0.60);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 17px;
            font-weight: 700;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar-date {
            font-size: 12px;
            color: var(--muted);
        }
        .status-dot {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: rgba(74,222,128,0.90);
        }
        .status-dot::before {
            content: '';
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #4ade80;
            box-shadow: 0 0 6px #4ade80;
        }

        /* Quick Search */
        .quick-search-wrap {
            position: relative;
            max-width: 320px;
            flex: 1;
            margin: 0 20px;
        }
        .quick-search-input {
            width: 100%;
            padding: 8px 14px 8px 36px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            outline: none;
            transition: all 0.2s ease;
        }
        .quick-search-input::placeholder {
            color: var(--muted);
            font-size: 12.5px;
        }
        .quick-search-input:focus {
            border-color: var(--border-h);
            background: rgba(255,255,255,0.08);
            box-shadow: 0 0 0 3px rgba(0,212,255,0.08);
        }
        .quick-search-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            width: 15px; height: 15px;
            color: var(--muted);
            pointer-events: none;
        }
        .quick-search-results {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0; right: 0;
            background: rgba(5, 12, 40, 0.97);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 6px;
            max-height: 320px;
            overflow-y: auto;
            z-index: 200;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.5);
        }
        .quick-search-results.visible { display: block; }
        .qs-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: rgba(255,255,255,0.75);
            font-size: 13px;
            font-weight: 500;
            transition: all 0.15s ease;
        }
        .qs-item:hover {
            background: rgba(0,212,255,0.10);
            color: #fff;
        }
        .qs-item svg {
            width: 15px; height: 15px;
            flex-shrink: 0;
            opacity: 0.6;
        }
        .qs-item:hover svg { opacity: 1; color: var(--cyan); }
        .qs-item-label { flex: 1; }
        .qs-item-badge {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 100px;
            background: rgba(0,212,255,0.10);
            color: var(--cyan);
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .qs-empty {
            padding: 16px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }
        @media (max-width: 640px) {
            .quick-search-wrap { margin: 0 10px; max-width: 200px; }
        }

        /* page content */
        .content {
            padding: 32px;
            flex: 1;
        }

        /* page header */
        .page-header {
            margin-bottom: 32px;
        }
        .page-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--cyan);
            margin-bottom: 6px;
        }
        .page-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .page-desc {
            font-size: 14px;
            color: var(--muted);
            margin-top: 4px;
        }

        /* ===========================
           STAT CARDS
        =========================== */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 36px;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px 24px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            position: relative;
            overflow: hidden;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 2px;
        }
        .stat-card:nth-child(1)::before { background: linear-gradient(90deg, var(--cyan), transparent); }
        .stat-card:nth-child(2)::before { background: linear-gradient(90deg, var(--purple), transparent); }
        .stat-card:nth-child(3)::before { background: linear-gradient(90deg, #ec4899, transparent); }
        .stat-card:nth-child(4)::before { background: linear-gradient(90deg, #4ade80, transparent); }
        .stat-card:hover {
            border-color: var(--border-h);
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        }
        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
        }
        .stat-card:nth-child(1) .stat-icon { background: rgba(0,212,255,0.12); color: var(--cyan); }
        .stat-card:nth-child(2) .stat-icon { background: rgba(124,58,237,0.12); color: #a78bfa; }
        .stat-card:nth-child(3) .stat-icon { background: rgba(236,72,153,0.12); color: #f472b6; }
        .stat-card:nth-child(4) .stat-icon { background: rgba(74,222,128,0.10); color: #4ade80; }
        .stat-icon svg { width: 20px; height: 20px; }
        .stat-value {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 32px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 6px;
        }
        .stat-label {
            font-size: 13px;
            color: var(--muted);
            font-weight: 500;
        }

        /* ===========================
           INFO PANEL
        =========================== */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .info-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .info-card-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-card-title svg { width: 16px; height: 16px; color: var(--cyan); }
        .menu-link-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .menu-link-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 10px;
            text-decoration: none;
            color: rgba(255,255,255,0.70);
            font-size: 13px;
            transition: all 0.2s;
        }
        .menu-link-item:hover {
            background: rgba(0,212,255,0.07);
            border-color: rgba(0,212,255,0.20);
            color: #fff;
        }
        .menu-link-item .soon {
            font-size: 10px;
            padding: 2px 8px;
            background: rgba(124,58,237,0.20);
            border: 1px solid rgba(124,58,237,0.30);
            border-radius: 100px;
            color: #a78bfa;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* activity placeholder */
        .activity-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px;
            gap: 10px;
            color: rgba(255,255,255,0.20);
        }
        .activity-placeholder svg { width: 36px; height: 36px; opacity: 0.4; }
        .activity-placeholder p { font-size: 13px; }

        /* CMS Accordion */
        .cms-accordion-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            color: var(--muted);
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }
        .cms-accordion-toggle:hover {
            background: rgba(0,212,255,0.08);
            color: #fff;
        }
        .cms-accordion-toggle.open {
            background: rgba(0,212,255,0.10);
            color: var(--cyan);
            font-weight: 600;
        }
        .cms-accordion-toggle svg.icon-main {
            width: 16px; height: 16px;
            flex-shrink: 0;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        .cms-accordion-toggle:hover svg.icon-main,
        .cms-accordion-toggle.open svg.icon-main { opacity: 1; }
        .cms-chevron {
            width: 12px; height: 12px;
            margin-left: auto;
            flex-shrink: 0;
            opacity: 0.5;
            transition: transform 0.25s ease, opacity 0.2s;
        }
        .cms-accordion-toggle.open .cms-chevron {
            transform: rotate(180deg);
            opacity: 1;
        }
        .cms-submenu {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease;
            padding-left: 14px;
        }
        .cms-submenu.open { max-height: 400px; }
        .cms-sub-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: rgba(255,255,255,0.45);
            font-size: 12.5px;
            font-weight: 500;
            transition: all 0.18s ease;
            margin-bottom: 1px;
            position: relative;
        }
        .cms-sub-item::before {
            content: '';
            width: 5px; height: 5px;
            border-radius: 50%;
            background: rgba(0,212,255,0.35);
            flex-shrink: 0;
            transition: background 0.2s;
        }
        .cms-sub-item:hover {
            background: rgba(0,212,255,0.07);
            color: #fff;
        }
        .cms-sub-item:hover::before { background: var(--cyan); }
        .cms-sub-item.active {
            background: rgba(0,212,255,0.10);
            color: var(--cyan);
            font-weight: 600;
        }
        .cms-sub-item.active::before { background: var(--cyan); }


        /* ===========================
           SIDEBAR CLOSE BUTTON (mobile)
        =========================== */
        .sidebar-close-btn {
            display: none;
            position: absolute;
            top: 14px;
            right: 14px;
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: rgba(255,255,255,0.55);
            transition: all 0.2s;
            z-index: 101;
            flex-shrink: 0;
        }
        .sidebar-close-btn:hover {
            background: rgba(239,68,68,0.15);
            border-color: rgba(239,68,68,0.30);
            color: #fca5a5;
        }
        .sidebar-close-btn svg { width: 16px; height: 16px; }

        /* ===========================
           HAMBURGER BUTTON (mobile)
        =========================== */
        .hamburger-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(0,212,255,0.18);
            border-radius: 9px;
            cursor: pointer;
            color: rgba(255,255,255,0.75);
            transition: all 0.2s;
            flex-shrink: 0;
            margin-right: 10px;
        }
        .hamburger-btn:hover {
            background: rgba(0,212,255,0.12);
            border-color: rgba(0,212,255,0.35);
            color: var(--cyan);
        }
        .hamburger-btn svg { width: 20px; height: 20px; }

        /* ===========================
           OVERLAY
        =========================== */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(2, 8, 24, 0.65);
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            z-index: 99;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        #sidebar-overlay.visible {
            opacity: 1;
        }

        /* ===========================
           RESPONSIVE
        =========================== */
        @media (max-width: 1200px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 900px) {
            .info-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            /* Reset sidebar width variable — main no longer offsets */
            :root { --sidebar-w: 240px; }

            /* Sidebar jadi off-canvas drawer */
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: none;
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 40px rgba(0,0,0,0.5);
            }

            /* Tombol close muncul di dalam sidebar */
            .sidebar-close-btn { display: flex; }

            /* Main content mulai dari kiri (tanpa offset sidebar) */
            .main { margin-left: 0; }

            /* Hamburger muncul di topbar */
            .hamburger-btn { display: flex; }

            /* Overlay aktif saat drawer terbuka */
            #sidebar-overlay { display: block; }

            /* Stat & content padding */
            .stat-grid { grid-template-columns: 1fr 1fr; }
            .content { padding: 20px; }

            /* Kunci scroll body saat drawer terbuka */
            body.sidebar-open { overflow: hidden; }
        }
    </style>
</head>
<body>

{{-- ======================== SIDEBAR OVERLAY ======================== --}}
<div id="sidebar-overlay" aria-hidden="true"></div>

{{-- ======================== SIDEBAR ======================== --}}
<aside class="sidebar" id="admin-sidebar" aria-label="Navigasi Admin">

    {{-- Tombol Close (mobile only) --}}
    <button class="sidebar-close-btn" id="sidebar-close-btn" aria-label="Tutup menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </button>
    {{-- Logo --}}
    <a href="/" class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <svg viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="3" fill="white" opacity="0.95"/>
                <circle cx="5" cy="7" r="1.5" fill="white" opacity="0.75"/>
                <circle cx="19" cy="7" r="1.5" fill="white" opacity="0.75"/>
                <circle cx="5" cy="17" r="1.5" fill="white" opacity="0.75"/>
                <circle cx="19" cy="17" r="1.5" fill="white" opacity="0.75"/>
                <line x1="9" y1="11" x2="6.12" y2="7.88" stroke="white" stroke-width="1.2" opacity="0.65"/>
                <line x1="15" y1="11" x2="17.88" y2="7.88" stroke="white" stroke-width="1.2" opacity="0.65"/>
                <line x1="9" y1="13" x2="6.12" y2="16.12" stroke="white" stroke-width="1.2" opacity="0.65"/>
                <line x1="15" y1="13" x2="17.88" y2="16.12" stroke="white" stroke-width="1.2" opacity="0.65"/>
            </svg>
        </div>
        <span class="sidebar-logo-text"><span>AI</span> Solution</span>
    </a>

    {{-- Admin info --}}
    <div class="admin-badge">
        <div class="admin-avatar">
            @if(Auth::user()->foto)
                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
            @else
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            @endif
        </div>
        <div class="admin-info">
            <div class="admin-name">{{ Auth::user()->name }}</div>
            <div class="admin-role">{{ Auth::user()->isAdminWebsite() ? 'Admin Website' : 'Admin Marketing/SEO' }}</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu Utama</div>

        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" id="nav-dashboard">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            Dashboard
        </a>

        @if(auth()->user()->hasRole('admin_website'))
        <a href="{{ route('admin.services.index') }}" class="nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" id="nav-layanan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                <polyline points="2 17 12 22 22 17"></polyline>
                <polyline points="2 12 12 17 22 12"></polyline>
            </svg>
            Layanan AI
        </a>
        @endif

        <a href="{{ route('admin.articles.index') }}" class="nav-item {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}" id="nav-artikel">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            Artikel
        </a>

        @if(auth()->user()->hasRole('admin_website'))
        <a href="{{ route('admin.partners.index') }}" class="nav-item {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}" id="nav-partner">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            Partner / Logo
        </a>
        @endif

        @if(auth()->user()->hasRole('admin_marketing'))
        {{-- ===== SEO (Admin Marketing only) ===== --}}
        <a href="{{ route('admin.seo.index') }}" class="nav-item {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}" id="nav-seo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            SEO
        </a>
        @endif

        @if(auth()->user()->hasRole('admin_website'))
        {{-- ===== CMS / KONTEN WEBSITE ===== --}}
        <div class="nav-section-label">CMS / Konten Website</div>

        {{-- Accordion toggle --}}
        <button class="cms-accordion-toggle {{ request()->routeIs('admin.cms.*') ? 'open' : '' }}"
            id="cms-toggle" aria-expanded="false" aria-controls="cms-submenu">
            <svg class="icon-main" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <path d="M3 9h18M9 21V9"/>
            </svg>
            Kelola Konten
            <svg class="cms-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>

        <div class="cms-submenu {{ request()->routeIs('admin.cms.*') ? 'open' : '' }}"
             id="cms-submenu">

            {{-- Homepage --}}
            <a href="{{ route('admin.cms.homepage.index') }}" class="cms-sub-item {{ request()->routeIs('admin.cms.homepage.*') ? 'active' : '' }}" id="cms-homepage">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-left:-2px;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Homepage
            </a>

            {{-- Tentang Kami --}}
            <a href="{{ route('admin.cms.about.index') }}" class="cms-sub-item {{ request()->routeIs('admin.cms.about.*') ? 'active' : '' }}" id="cms-tentang">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-left:-2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Tentang Kami
            </a>



            {{-- CTA / Kontak --}}
            <a href="{{ route('admin.cms.contact.index') }}" class="cms-sub-item {{ request()->routeIs('admin.cms.contact.*') ? 'active' : '' }}" id="cms-cta">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-left:-2px;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.26h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.18 6.18l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                CTA / Kontak
            </a>

        </div>
        @endif

        <div class="nav-section-label">Komunikasi</div>

        <a href="{{ route('admin.consultations.index') }}" class="nav-item {{ request()->routeIs('admin.consultations.*') ? 'active' : '' }}" id="nav-konsultasi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            Pesan Konsultasi
            @php $unreadCount = \App\Models\Consultation::where('is_read', false)->count(); @endphp
            @if($unreadCount > 0)
                <span style="background:var(--cyan);color:#000;font-size:10px;font-weight:800;padding:2px 6px;border-radius:100px;margin-left:auto;">{{ $unreadCount }}</span>
            @endif
        </a>

        <div class="nav-section-label">Akun</div>

        <a href="{{ route('admin.profile.index') }}" class="nav-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" id="nav-profil">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            Profil
        </a>
    </nav>

    {{-- Logout --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout" id="btn-logout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

{{-- ======================== MAIN ======================== --}}
<main class="main">

    {{-- Topbar --}}
    <div class="topbar">
        <div style="display:flex;align-items:center;flex:1;min-width:0;">
            {{-- Hamburger (mobile/tablet only) --}}
            <button class="hamburger-btn" id="hamburger-btn" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="admin-sidebar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
        </div>
        <div class="quick-search-wrap">
            <svg class="quick-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" class="quick-search-input" id="quick-search-input" placeholder="Cari menu... (Ctrl+K)" autocomplete="off">
            <div class="quick-search-results" id="quick-search-results"></div>
        </div>
        <div class="topbar-right">
            <span class="status-dot">System Online</span>
            <span class="topbar-date" id="topbar-date"></span>
        </div>
    </div>

    {{-- Content --}}
    <div class="content">
        @yield('content')

    </div>
</main>

<script>
    // ─── Clock ───
    function updateClock() {
        const el = document.getElementById('topbar-date');
        if (!el) return;
        const now = new Date();
        const opts = { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric',
                       hour: '2-digit', minute: '2-digit' };
        el.textContent = now.toLocaleDateString('id-ID', opts);
    }
    updateClock();
    setInterval(updateClock, 60000);

    // ─── CMS Accordion ───
    (function () {
        const toggle = document.getElementById('cms-toggle');
        const menu   = document.getElementById('cms-submenu');
        if (!toggle || !menu) return;

        const isActive = menu.classList.contains('open');
        toggle.setAttribute('aria-expanded', isActive ? 'true' : 'false');

        toggle.addEventListener('click', function () {
            const open = menu.classList.toggle('open');
            toggle.classList.toggle('open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    })();

    // ─── Responsive Sidebar Drawer ───
    (function () {
        const sidebar    = document.getElementById('admin-sidebar');
        const overlay    = document.getElementById('sidebar-overlay');
        const hamburger  = document.getElementById('hamburger-btn');
        const closeBtn   = document.getElementById('sidebar-close-btn');

        if (!sidebar || !overlay || !hamburger) return;

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('visible');
            document.body.classList.add('sidebar-open');
            hamburger.setAttribute('aria-expanded', 'true');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('visible');
            document.body.classList.remove('sidebar-open');
            hamburger.setAttribute('aria-expanded', 'false');
        }

        hamburger.addEventListener('click', function () {
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', closeSidebar);
        }

        overlay.addEventListener('click', closeSidebar);

        // Tutup drawer saat link/nav diklik pada mobile
        sidebar.querySelectorAll('a, button[type="submit"]').forEach(function (el) {
            el.addEventListener('click', function () {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });

        // Tutup dengan ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });
    })();

    // ─── Quick Navigation Search ───
    (function () {
        const input   = document.getElementById('quick-search-input');
        const results = document.getElementById('quick-search-results');
        if (!input || !results) return;

        const userRole = @json(auth()->user()->role);
        const isWebsite   = (userRole === 'admin_website');
        const isMarketing = (userRole === 'admin_marketing');

        // Define all menu items with role access, keywords, and routes
        const allMenus = [
            {
                label: 'Dashboard',
                url: '{{ route("admin.dashboard") }}',
                category: 'Menu Utama',
                roles: ['admin_website', 'admin_marketing'],
                keywords: ['dashboard', 'beranda', 'home', 'utama'],
                icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>'
            },
            {
                label: 'Layanan AI',
                url: '{{ route("admin.services.index") }}',
                category: 'Menu Utama',
                roles: ['admin_website'],
                keywords: ['layanan', 'service', 'jasa', 'ai'],
                icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>'
            },
            {
                label: 'Artikel',
                url: '{{ route("admin.articles.index") }}',
                category: 'Menu Utama',
                roles: ['admin_website', 'admin_marketing'],
                keywords: ['artikel', 'article', 'blog', 'tulisan', 'konten'],
                icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>'
            },
            {
                label: 'Partner / Logo',
                url: '{{ route("admin.partners.index") }}',
                category: 'Menu Utama',
                roles: ['admin_website'],
                keywords: ['partner', 'logo', 'mitra', 'kolaborasi', 'perusahaan'],
                icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>'
            },
            {
                label: 'SEO',
                url: '{{ route("admin.seo.index") }}',
                category: 'Menu Utama',
                roles: ['admin_marketing'],
                keywords: ['seo', 'meta', 'search engine', 'optimasi', 'marketing'],
                icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>'
            },
            {
                label: 'CMS Homepage',
                url: '{{ route("admin.cms.homepage.index") }}',
                category: 'CMS / Konten',
                roles: ['admin_website'],
                keywords: ['homepage', 'cms', 'konten', 'beranda', 'hero', 'halaman utama'],
                icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>'
            },
            {
                label: 'CMS Tentang Kami',
                url: '{{ route("admin.cms.about.index") }}',
                category: 'CMS / Konten',
                roles: ['admin_website'],
                keywords: ['tentang', 'about', 'profil perusahaan', 'ceo', 'cms'],
                icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>'
            },
            {
                label: 'CMS CTA / Kontak',
                url: '{{ route("admin.cms.contact.index") }}',
                category: 'CMS / Konten',
                roles: ['admin_website'],
                keywords: ['kontak', 'contact', 'cta', 'whatsapp', 'wa', 'telepon', 'email', 'cms'],
                icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.26h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.18 6.18l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>'
            },
            {
                label: 'Pesan Konsultasi',
                url: '{{ route("admin.consultations.index") }}',
                category: 'Komunikasi',
                roles: ['admin_website', 'admin_marketing'],
                keywords: ['konsultasi', 'pesan', 'chat', 'inbox', 'komunikasi', 'message'],
                icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>'
            },
            {
                label: 'Profil',
                url: '{{ route("admin.profile.index") }}',
                category: 'Akun',
                roles: ['admin_website', 'admin_marketing'],
                keywords: ['profil', 'profile', 'akun', 'pengaturan', 'foto', 'password'],
                icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>'
            }
        ];

        // Filter menus by user role
        const userMenus = allMenus.filter(m => m.roles.includes(userRole));

        function renderResults(query) {
            const q = query.trim().toLowerCase();
            if (!q) {
                results.classList.remove('visible');
                return;
            }

            const matched = userMenus.filter(function (m) {
                if (m.label.toLowerCase().includes(q)) return true;
                if (m.category.toLowerCase().includes(q)) return true;
                return m.keywords.some(k => k.includes(q));
            });

            if (matched.length === 0) {
                results.innerHTML = '<div class="qs-empty">Tidak ada hasil ditemukan</div>';
            } else {
                results.innerHTML = matched.map(function (m) {
                    return '<a href="' + m.url + '" class="qs-item">' +
                        m.icon +
                        '<span class="qs-item-label">' + m.label + '</span>' +
                        '<span class="qs-item-badge">' + m.category + '</span>' +
                        '</a>';
                }).join('');
            }
            results.classList.add('visible');
        }

        input.addEventListener('input', function () {
            renderResults(this.value);
        });

        input.addEventListener('focus', function () {
            if (this.value.trim()) renderResults(this.value);
        });

        // Close on click outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.quick-search-wrap')) {
                results.classList.remove('visible');
            }
        });

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                results.classList.remove('visible');
                input.blur();
            }
            // Ctrl+K to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                input.focus();
                input.select();
            }
        });

        // Keyboard navigation in results
        input.addEventListener('keydown', function (e) {
            const items = results.querySelectorAll('.qs-item');
            if (!items.length) return;

            let focused = results.querySelector('.qs-item:focus');
            let idx = Array.from(items).indexOf(focused);

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                idx = (idx + 1) % items.length;
                items[idx].focus();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                idx = idx <= 0 ? items.length - 1 : idx - 1;
                items[idx].focus();
            } else if (e.key === 'Enter' && focused) {
                e.preventDefault();
                focused.click();
            }
        });
    })();
</script>
@stack('scripts')
</body>
</html>