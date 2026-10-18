@php
    $cmsContact = $cmsContact ?? \App\Models\CmsContact::first();
    $waNumber = preg_replace('/[^0-9]/', '', optional($cmsContact)->whatsapp ?? '628XXXXXXXXXX');
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    }
    $waMessage = 'Halo, saya ingin berkonsultasi mengenai solusi AI.';
    $waUrl     = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waMessage);

    $cmsSeo = $cmsSeo ?? \App\Models\CmsSeo::first();
    $metaTitle = ($cmsSeo && $cmsSeo->meta_title) ? 'Wawasan & Artikel - ' . $cmsSeo->meta_title : 'Wawasan & Artikel AI - AI Solution';
    $metaDesc  = ($cmsSeo && $cmsSeo->meta_description) ? $cmsSeo->meta_description : 'Eksplorasi wawasan, berita, dan artikel terkini seputar implementasi kecerdasan buatan untuk akselerasi bisnis dari AI Solution.';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDesc }}">
    <title>{{ $metaTitle }}</title>

    <!-- Google Fonts: Inter + Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Dark & Light Mode Script & Styles --}}
    @include('partials.theme-script')

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-color: #020818;
            --navy-900: #020818;
            --navy-800: #050e27;
            --navy-700: #0a1535;
            --navy-600: #0d1a40;
            --blue-electric: #00a8ff;
            --cyan-neon: #00d4ff;
            --purple-vivid: #7c3aed;
            --purple-neon: #7c3aed;
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.65);
            --text-dim: rgba(255, 255, 255, 0.45);
            --glass-bg: rgba(255, 255, 255, 0.02);
            --glass-border: rgba(255, 255, 255, 0.06);
            --card-hover-border: rgba(0, 168, 255, 0.35);
        }

        /* Light Mode Overrides */
        html[data-theme="light"] {
            --bg-color: #f8fafc;
            --navy-900: #f8fafc;
            --navy-800: #ffffff;
            --navy-700: #f1f5f9;
            --navy-600: #e2e8f0;
            --blue-electric: #0284c7;
            --cyan-neon: #0284c7;
            --purple-vivid: #7c3aed;
            --purple-neon: #6d28d9;
            --text-main: #0f172a;
            --text-muted: #475569;
            --text-dim: #64748b;
            --glass-bg: #ffffff;
            --glass-border: rgba(15, 23, 42, 0.10);
            --card-hover-border: rgba(2, 132, 199, 0.4);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image:
                radial-gradient(circle at 15% 15%, rgba(0, 212, 255, 0.06) 0%, transparent 45%),
                radial-gradient(circle at 85% 25%, rgba(124, 58, 237, 0.06) 0%, transparent 45%);
            background-attachment: fixed;
            overflow-x: clip;
        }

        html[data-theme="light"] body {
            background-image:
                radial-gradient(circle at 15% 15%, rgba(2, 132, 199, 0.04) 0%, transparent 45%),
                radial-gradient(circle at 85% 25%, rgba(124, 58, 237, 0.04) 0%, transparent 45%);
        }

        /* ==========================================
           NAVBAR
        ========================================== */
        .navbar {
            padding: 20px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background: rgba(2, 8, 24, 0.75);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        html[data-theme="light"] .navbar {
            background: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .navbar-inner {
            max-width: 1320px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand-wrap {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-logo-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--cyan-neon) 0%, var(--purple-neon) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 16px rgba(0, 212, 255, 0.35);
        }

        .nav-logo-icon svg {
            width: 20px;
            height: 20px;
        }

        .nav-logo-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
        }

        html[data-theme="light"] .nav-logo-text {
            color: #0f172a;
        }

        .nav-logo-text span {
            color: var(--cyan-neon);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            padding: 8px 16px;
            border-radius: 100px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.25s ease;
        }

        .btn-back:hover {
            color: var(--cyan-neon);
            background: rgba(0, 212, 255, 0.08);
            border-color: rgba(0, 212, 255, 0.25);
            transform: translateX(-3px);
        }

        html[data-theme="light"] .btn-back {
            background: #ffffff;
            border-color: rgba(15, 23, 42, 0.12);
            color: #334155;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        html[data-theme="light"] .btn-back:hover {
            background: #f1f5f9;
            color: #0284c7;
            border-color: #0284c7;
        }

        .nav-links {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .nav-links a {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--cyan-neon);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* ==========================================
           HERO SECTION
        ========================================== */
        .wawasan-hero {
            padding: 60px 24px 30px;
            text-align: center;
            max-width: 860px;
            margin: 0 auto;
        }

        .breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-dim);
            margin-bottom: 20px;
        }

        .breadcrumb a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: var(--cyan-neon);
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--cyan-neon);
            padding: 7px 18px;
            background: rgba(0, 212, 255, 0.08);
            border: 1px solid rgba(0, 212, 255, 0.20);
            border-radius: 100px;
            margin-bottom: 20px;
        }

        .badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--cyan-neon);
            box-shadow: 0 0 8px var(--cyan-neon);
            animation: pulse-dot 2s infinite ease-in-out;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        .wawasan-hero-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(32px, 4.5vw, 48px);
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.2;
            margin-bottom: 16px;
            color: var(--text-main);
        }

        .wawasan-hero-desc {
            font-size: clamp(15px, 2vw, 18px);
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* ==========================================
           ARTICLES MAIN CONTENT
        ========================================== */
        .wawasan-container {
            max-width: 1320px;
            width: 100%;
            margin: 0 auto;
            padding: 20px 24px 80px;
            flex-grow: 1;
        }

        .wawasan-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
            margin-top: 30px;
        }

        .article-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            cursor: pointer;
            text-decoration: none;
            height: 100%;
            position: relative;
        }

        html[data-theme="light"] .article-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .article-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0,168,255,0.06), rgba(124,58,237,0.06));
            opacity: 0;
            transition: opacity 0.4s;
            pointer-events: none;
        }

        .article-card:hover {
            transform: translateY(-8px);
            border-color: var(--card-hover-border);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px rgba(0, 168, 255, 0.12);
        }

        html[data-theme="light"] .article-card:hover {
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08), 0 0 20px rgba(2, 132, 199, 0.08);
        }

        .article-card:hover::before {
            opacity: 1;
        }

        .article-img-wrap {
            width: 100%;
            height: 220px;
            position: relative;
            overflow: hidden;
            background: #050e27;
        }

        html[data-theme="light"] .article-img-wrap {
            background: #f1f5f9;
        }

        .article-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1), filter 0.4s ease;
            filter: brightness(0.9);
        }

        html[data-theme="light"] .article-img {
            filter: brightness(0.98);
        }

        .article-card:hover .article-img {
            transform: scale(1.08);
            filter: brightness(1.05);
        }

        .article-content {
            padding: 28px 24px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 2;
        }

        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            gap: 10px;
        }

        .article-category {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--cyan-neon);
            background: rgba(0, 212, 255, 0.08);
            padding: 6px 14px;
            border-radius: 100px;
            border: 1px solid rgba(0, 212, 255, 0.2);
            white-space: nowrap;
        }

        html[data-theme="light"] .article-category {
            background: rgba(2, 132, 199, 0.08);
            border-color: rgba(2, 132, 199, 0.25);
            color: #0284c7;
        }

        .article-date-row {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-dim);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .article-title {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.4;
            color: var(--text-main);
            margin-bottom: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: color 0.3s ease;
            letter-spacing: -0.3px;
        }

        .article-card:hover .article-title {
            color: var(--cyan-neon);
        }

        .article-desc {
            font-size: 14.5px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 24px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .article-footer {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        html[data-theme="light"] .article-footer {
            border-top: 1px solid rgba(15, 23, 42, 0.06);
        }

        .article-btn {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .article-btn svg {
            width: 18px;
            height: 18px;
            transition: transform 0.3s ease;
            color: var(--cyan-neon);
        }

        .article-card:hover .article-btn {
            color: var(--cyan-neon);
        }

        .article-card:hover .article-btn svg {
            transform: translateX(6px);
        }

        .article-read-time {
            font-size: 12px;
            color: var(--text-dim);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: var(--glass-bg);
            border: 1px dashed var(--glass-border);
            border-radius: 24px;
            margin-top: 40px;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(0, 212, 255, 0.08);
            border: 1px solid rgba(0, 212, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--cyan-neon);
        }

        .empty-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .empty-desc {
            font-size: 15px;
            color: var(--text-muted);
            max-width: 440px;
            margin: 0 auto;
        }

        /* ==========================================
           PAGINATION STYLES
        ========================================== */
        .pagination-wrapper {
            margin-top: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 18px;
        }

        .pagination-info {
            font-size: 13.5px;
            color: var(--text-dim);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .pagination-info span {
            color: var(--text-main);
            font-weight: 600;
        }

        .pagination-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: var(--text-muted);
            transition: all 0.25s ease;
        }

        .pagination-btn:hover:not(.disabled) {
            background: rgba(0, 212, 255, 0.10);
            border-color: rgba(0, 212, 255, 0.35);
            color: var(--cyan-neon);
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(0, 212, 255, 0.15);
        }

        .pagination-btn.disabled {
            opacity: 0.35;
            cursor: not-allowed;
            pointer-events: none;
        }

        html[data-theme="light"] .pagination-btn {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.12);
            color: #475569;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        html[data-theme="light"] .pagination-btn:hover:not(.disabled) {
            background: #f1f5f9;
            border-color: #0284c7;
            color: #0284c7;
        }

        .pagination-numbers {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pagination-number {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-decoration: none;
            color: var(--text-muted);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.25s ease;
        }

        .pagination-number:hover:not(.active) {
            background: rgba(0, 212, 255, 0.08);
            border-color: rgba(0, 212, 255, 0.25);
            color: var(--cyan-neon);
            transform: translateY(-2px);
        }

        .pagination-number.active {
            background: linear-gradient(135deg, var(--cyan-neon) 0%, var(--purple-neon) 100%);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 18px rgba(0, 212, 255, 0.35);
        }

        html[data-theme="light"] .pagination-number {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.12);
            color: #475569;
        }

        html[data-theme="light"] .pagination-number:hover:not(.active) {
            background: #f1f5f9;
            border-color: #0284c7;
            color: #0284c7;
        }

        html[data-theme="light"] .pagination-number.active {
            color: #ffffff;
        }

        .pagination-ellipsis {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            color: var(--text-dim);
            font-weight: 700;
        }

        /* ==========================================
           BOTTOM CTA
        ========================================== */
        .bottom-cta {
            max-width: 1320px;
            margin: 40px auto 80px;
            padding: 0 24px;
            width: 100%;
        }

        .bottom-cta-box {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.08) 0%, rgba(124, 58, 237, 0.12) 100%);
            border: 1px solid rgba(0, 212, 255, 0.25);
            border-radius: 28px;
            padding: 56px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        html[data-theme="light"] .bottom-cta-box {
            background: linear-gradient(135deg, rgba(2, 132, 199, 0.06) 0%, rgba(124, 58, 237, 0.08) 100%);
            border: 1px solid rgba(2, 132, 199, 0.2);
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
        }

        .bottom-cta-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(24px, 3.5vw, 36px);
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 14px;
            letter-spacing: -0.5px;
        }

        .bottom-cta-desc {
            font-size: 16px;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }

        .bottom-cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 36px;
            background: linear-gradient(135deg, var(--cyan-neon) 0%, var(--purple-neon) 100%);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border-radius: 100px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 6px 24px rgba(0, 168, 255, 0.32);
        }

        .bottom-cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0, 168, 255, 0.48);
        }

        /* ==========================================
           FOOTER
        ========================================== */
        .footer {
            background: var(--navy-800);
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding: 50px 24px 30px;
            margin-top: auto;
        }

        html[data-theme="light"] .footer {
            background: #ffffff;
            border-top: 1px solid rgba(15, 23, 42, 0.08);
        }

        .footer-inner {
            max-width: 1320px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-copy {
            font-size: 14px;
            color: var(--text-dim);
        }

        .footer-links {
            display: flex;
            align-items: center;
            gap: 20px;
            list-style: none;
        }

        .footer-links a {
            font-size: 13.5px;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--cyan-neon);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .wawasan-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            .wawasan-hero {
                padding: 40px 20px 20px;
            }
            .bottom-cta-box {
                padding: 40px 20px;
            }
        }

        @media (max-width: 640px) {
            .wawasan-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .article-img-wrap {
                height: 200px;
            }
            .footer-inner {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar" role="navigation" aria-label="Main Navigation">
        <div class="navbar-inner">
            <div class="nav-brand-wrap">
                <a href="/" class="nav-logo" aria-label="AI Solution Beranda">
                    <div class="nav-logo-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="3" fill="white" opacity="0.95"/>
                            <circle cx="5" cy="7" r="1.5" fill="white" opacity="0.80"/>
                            <circle cx="19" cy="7" r="1.5" fill="white" opacity="0.80"/>
                            <circle cx="5" cy="17" r="1.5" fill="white" opacity="0.80"/>
                            <circle cx="19" cy="17" r="1.5" fill="white" opacity="0.80"/>
                            <circle cx="12" cy="3" r="1.5" fill="white" opacity="0.80"/>
                            <circle cx="12" cy="21" r="1.5" fill="white" opacity="0.80"/>
                            <line x1="9" y1="11" x2="6.12" y2="7.88" stroke="white" stroke-width="1.2" opacity="0.70"/>
                            <line x1="15" y1="11" x2="17.88" y2="7.88" stroke="white" stroke-width="1.2" opacity="0.70"/>
                            <line x1="9" y1="13" x2="6.12" y2="16.12" stroke="white" stroke-width="1.2" opacity="0.70"/>
                            <line x1="15" y1="13" x2="17.88" y2="16.12" stroke="white" stroke-width="1.2" opacity="0.70"/>
                            <line x1="12" y1="9" x2="12" y2="4.5" stroke="white" stroke-width="1.2" opacity="0.70"/>
                            <line x1="12" y1="15" x2="12" y2="19.5" stroke="white" stroke-width="1.2" opacity="0.70"/>
                        </svg>
                    </div>
                    <span class="nav-logo-text"><span>AI</span> Solution</span>
                </a>

                <a href="/" class="btn-back">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Beranda</span>
                </a>
            </div>

            <ul class="nav-links">
                <li><a href="/#layanan">Layanan</a></li>
                <li><a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer">Kontak</a></li>
                <li><a href="/#tentang">Tentang Kami</a></li>
                <li><a href="{{ route('articles.index') }}" class="active">Wawasan</a></li>
            </ul>

            <div class="nav-right">
                @include('partials.theme-toggle')
            </div>
        </div>
    </nav>

    <!-- Hero Header -->
    <header class="wawasan-hero">
        <div class="breadcrumb">
            <a href="/">Beranda</a>
            <span>/</span>
            <span>Wawasan</span>
        </div>

        <div class="badge-pill">
            <span class="badge-dot"></span>
            Wawasan &amp; Artikel AI
        </div>

        <h1 class="wawasan-hero-title">Wawasan &amp; Inovasi AI Solution</h1>
        <p class="wawasan-hero-desc">Kumpulan artikel, pemikiran mendalam, dan perkembangan teknologi kecerdasan buatan untuk membantu bisnis berkembang lebih cepat dan efisien.</p>
    </header>

    <!-- Articles Grid Section -->
    <main class="wawasan-container">
        @if($articles->count() > 0)
            <div class="wawasan-grid">
                @foreach($articles as $article)
                    @php
                        $articleImg = $article->image_url ?: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=600';
                        $readTime   = $article->read_time;
                    @endphp
                    <a href="{{ route('article.detail', ['slug' => $article->slug]) }}" class="article-card">
                        <div class="article-img-wrap">
                            <img src="{{ $articleImg }}" alt="{{ $article->judul }}" class="article-img" loading="lazy">
                        </div>

                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-category">{{ $article->kategori ?: 'AI & Bisnis' }}</span>
                                <div class="article-date-row">
                                    <span>{{ $article->formatted_date }}</span>
                                </div>
                            </div>

                            <h2 class="article-title">{{ $article->judul }}</h2>
                            <p class="article-desc">{{ $article->ringkasan }}</p>

                            <div class="article-footer">
                                <div class="article-btn">
                                    Baca Selengkapnya
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </div>

                                @if($readTime)
                                    <span class="article-read-time" title="Estimasi waktu baca">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        {{ $readTime }} baca
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            {{ $articles->links('partials.pagination') }}
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <h3 class="empty-title">Belum Ada Artikel yang Dipublikasikan</h3>
                <p class="empty-desc">Artikel terbaru sedang dipersiapkan oleh tim redaksi. Silakan periksa kembali beberapa saat lagi.</p>
            </div>
        @endif
    </main>

    <!-- Bottom CTA Banner -->
    <section class="bottom-cta">
        <div class="bottom-cta-box">
            <h2 class="bottom-cta-title">Perlu Solusi AI untuk Kebutuhan Bisnis Anda?</h2>
            <p class="bottom-cta-desc">Diskusikan kebutuhan dan tantangan operasional perusahaan Anda bersama tim ahli AI Solution untuk solusi yang tepat sasaran.</p>
            <a href="/#konsultasi" class="bottom-cta-btn">
                <span>Konsultasikan Kebutuhan Anda</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-copy">
                &copy; {{ date('Y') }} AI Solution. Seluruh hak cipta dilindungi.
            </div>

            <ul class="footer-links">
                <li><a href="/">Beranda</a></li>
                <li><a href="/#layanan">Layanan</a></li>
                <li><a href="/#tentang">Tentang Kami</a></li>
                <li><a href="{{ route('articles.index') }}">Wawasan</a></li>
                <li><a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer">Kontak</a></li>
            </ul>
        </div>
    </footer>

    {{-- WhatsApp Chat Widget --}}
    @include('partials.wa-widget')

</body>
</html>
