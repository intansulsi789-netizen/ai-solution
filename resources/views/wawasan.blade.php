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

    $canonicalDomain = 'https://jogjaaisolutions.my.id';
    $canonicalUrl    = $canonicalDomain . '/wawasan';
    $ogImage         = $canonicalDomain . '/images/og-default.jpg';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDesc }}">
    <title>{{ $metaTitle }}</title>

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/favicon.jpg') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:site_name" content="AI Solution">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $canonicalUrl }}">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <!-- Google Fonts: Inter + Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Strictly Dark Mode on public pages --}}
    <script>
        document.documentElement.setAttribute('data-theme', 'dark');
    </script>

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
            background-color: #020818;
            background-attachment: fixed;
            overflow-x: clip;
            position: relative;
        }

        html[data-theme="light"] body {
            background-color: #f8fafc;
        }

        /* ==========================================
           FUTURISTIC AMBIENT SCENE & BACKGROUND
        ========================================== */
        .wawasan-bg-scene {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .wawasan-aurora {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            will-change: transform, opacity;
        }

        .aurora-cyan-top {
            width: 720px;
            height: 720px;
            top: -160px;
            left: -140px;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.20) 0%, rgba(0, 168, 255, 0.08) 45%, transparent 70%);
            filter: blur(85px);
            animation: aurora-float-cyan 14s ease-in-out infinite alternate;
        }

        .aurora-purple-top {
            width: 780px;
            height: 780px;
            top: -140px;
            right: -160px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.22) 0%, rgba(167, 139, 250, 0.10) 45%, transparent 70%);
            filter: blur(90px);
            animation: aurora-float-purple 16s ease-in-out infinite alternate;
        }

        .aurora-pink-mid {
            width: 680px;
            height: 560px;
            top: 48%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: radial-gradient(ellipse, rgba(0, 212, 255, 0.11) 0%, rgba(236, 72, 153, 0.09) 35%, rgba(124, 58, 237, 0.08) 60%, transparent 75%);
            filter: blur(95px);
            animation: aurora-pulse-mid 12s ease-in-out infinite alternate;
        }

        .aurora-blue-bottom {
            width: 950px;
            height: 520px;
            bottom: -160px;
            left: 50%;
            transform: translateX(-50%);
            background: radial-gradient(ellipse at center, rgba(0, 212, 255, 0.15) 0%, rgba(124, 58, 237, 0.14) 45%, transparent 70%);
            filter: blur(90px);
            animation: aurora-float-bottom 18s ease-in-out infinite alternate;
        }

        .wawasan-grid-mesh {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(0, 212, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.05) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(ellipse at 50% 40%, rgba(0, 0, 0, 0.95) 25%, rgba(0, 0, 0, 0.45) 65%, transparent 90%);
            -webkit-mask-image: radial-gradient(ellipse at 50% 40%, rgba(0, 0, 0, 0.95) 25%, rgba(0, 0, 0, 0.45) 65%, transparent 90%);
            opacity: 0.85;
            pointer-events: none;
        }

        .wawasan-radar-scanner {
            position: absolute;
            top: -20%;
            left: -20%;
            width: 140%;
            height: 140%;
            background: radial-gradient(ellipse at center, transparent 40%, rgba(0, 212, 255, 0.035) 58%, transparent 70%);
            pointer-events: none;
            animation: radar-rotate 25s linear infinite;
        }

        /* Lateral tech circuit lines */
        .wawasan-circuit-flank {
            position: absolute;
            top: 80px;
            bottom: 80px;
            width: 150px;
            pointer-events: none;
            opacity: 0.65;
            z-index: 1;
        }
        .wawasan-circuit-left {
            left: 12px;
        }
        .wawasan-circuit-right {
            right: 12px;
        }
        .circuit-svg {
            width: 100%;
            height: 100%;
        }

        /* Light mode overrides for ambient scene */
        html[data-theme="light"] .aurora-cyan-top {
            background: radial-gradient(circle, rgba(2, 132, 199, 0.09) 0%, transparent 65%);
            filter: blur(60px);
        }
        html[data-theme="light"] .aurora-purple-top {
            background: radial-gradient(circle, rgba(124, 58, 237, 0.08) 0%, transparent 65%);
            filter: blur(65px);
        }
        html[data-theme="light"] .aurora-pink-mid {
            background: radial-gradient(ellipse, rgba(2, 132, 199, 0.06) 0%, transparent 65%);
            filter: blur(70px);
        }
        html[data-theme="light"] .aurora-blue-bottom {
            background: radial-gradient(ellipse at center, rgba(124, 58, 237, 0.07) 0%, transparent 65%);
            filter: blur(70px);
        }
        html[data-theme="light"] .wawasan-grid-mesh {
            background-image: 
                linear-gradient(rgba(15, 23, 42, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 23, 42, 0.035) 1px, transparent 1px);
            opacity: 0.6;
        }
        html[data-theme="light"] .wawasan-circuit-flank {
            opacity: 0.3;
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
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25), 0 0 16px rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.6);
            flex-shrink: 0;
            overflow: hidden;
        }

        .nav-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 3px;
        }

        .nav-logo-icon svg {
            width: 22px;
            height: 22px;
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
            position: relative;
            z-index: 2;
            padding: 68px 24px 32px;
            text-align: center;
            max-width: 880px;
            margin: 0 auto;
        }

        .wawasan-hero-halo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 520px;
            height: 260px;
            background: radial-gradient(ellipse, rgba(0, 212, 255, 0.16) 0%, rgba(124, 58, 237, 0.12) 45%, transparent 75%);
            filter: blur(45px);
            pointer-events: none;
            z-index: -1;
            animation: hero-halo-pulse 6s ease-in-out infinite alternate;
        }

        @keyframes hero-halo-pulse {
            0% { transform: translate(-50%, -50%) scale(0.92); opacity: 0.65; }
            50% { transform: translate(-50%, -50%) scale(1.08); opacity: 0.95; }
            100% { transform: translate(-50%, -50%) scale(0.92); opacity: 0.65; }
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
            border: 1px solid rgba(0, 212, 255, 0.25);
            border-radius: 100px;
            margin-bottom: 20px;
            box-shadow: 0 0 16px rgba(0, 212, 255, 0.12);
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
            text-shadow: 0 0 40px rgba(0, 212, 255, 0.20);
        }

        .wawasan-hero-desc {
            font-size: clamp(15px, 2vw, 18px);
            color: var(--text-muted);
            line-height: 1.6;
        }

        .wawasan-tech-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            max-width: 440px;
            margin: 28px auto 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 212, 255, 0.45), transparent);
        }

        .divider-gem {
            width: 9px;
            height: 9px;
            position: relative;
            transform: rotate(45deg);
            border: 1.5px solid var(--cyan-neon);
            background: #020818;
            box-shadow: 0 0 10px var(--cyan-neon);
        }

        .gem-pulse {
            position: absolute;
            inset: 1px;
            background: linear-gradient(135deg, var(--cyan-neon), var(--purple-neon));
            animation: gem-flash 2.5s infinite ease-in-out;
        }

        @keyframes gem-flash {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }

        html[data-theme="light"] .divider-gem {
            background: #ffffff;
            border-color: #0284c7;
            box-shadow: 0 0 8px rgba(2, 132, 199, 0.4);
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
            position: relative;
            z-index: 2;
        }

        .wawasan-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
            margin-top: 30px;
        }

        .article-card {
            background: linear-gradient(160deg, rgba(8, 18, 48, 0.65) 0%, rgba(4, 11, 32, 0.82) 100%);
            border: 1px solid rgba(0, 212, 255, 0.16);
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            cursor: pointer;
            text-decoration: none;
            height: 100%;
            position: relative;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.40), inset 0 1px 0 rgba(255, 255, 255, 0.08);
            box-sizing: border-box;
            min-width: 0;
            width: 100%;
            max-width: 100%;
        }

        html[data-theme="light"] .article-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        .article-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent 5%, var(--cyan-neon) 50%, var(--purple-neon) 80%, transparent 95%);
            opacity: 0.6;
            transition: opacity 0.4s, height 0.4s;
            z-index: 3;
        }

        .article-card:hover {
            transform: translateY(-8px);
            border-color: rgba(0, 212, 255, 0.45);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.55),
                        0 0 35px rgba(0, 212, 255, 0.22),
                        inset 0 1px 0 rgba(255, 255, 255, 0.20);
        }

        html[data-theme="light"] .article-card:hover {
            border-color: rgba(2, 132, 199, 0.4);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.10), 0 0 25px rgba(2, 132, 199, 0.12);
        }

        .article-card:hover::before {
            opacity: 1;
            height: 3px;
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
            padding: 28px 36px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 4px 32px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.06);
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

        html[data-theme="light"] .pagination-wrapper {
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(15, 23, 42, 0.10);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07), inset 0 1px 0 rgba(255,255,255,0.9);
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

        @media (max-width: 1200px) {
            .wawasan-circuit-flank {
                display: block;
                width: 90px;
                opacity: 0.35;
                pointer-events: none;
            }
            .wawasan-circuit-left { left: 0; }
            .wawasan-circuit-right { right: 0; }
        }
        @media (max-width: 768px) {
            .wawasan-circuit-flank {
                width: 60px;
                opacity: 0.20;
            }
        }

        @media (max-width: 1024px) {
            .wawasan-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
            .article-img-wrap {
                height: 200px;
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
            .wawasan-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
            }
            .article-img-wrap {
                height: 170px;
            }
            .article-content {
                padding: 20px 16px;
            }
            .article-title {
                font-size: 16px;
                margin-bottom: 10px;
            }
            .article-desc {
                font-size: 13px;
                margin-bottom: 18px;
            }
        }

        @media (max-width: 640px) {
            .wawasan-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            .article-card {
                border-radius: 18px;
            }
            .article-img-wrap {
                height: 140px;
            }
            .article-content {
                padding: 16px 12px;
            }
            .article-category {
                font-size: 9.5px;
                padding: 4px 10px;
            }
            .article-date {
                font-size: 11px;
            }
            .article-title {
                font-size: 14px;
                line-height: 1.35;
                margin-bottom: 8px;
            }
            .article-desc {
                font-size: 11.5px;
                line-height: 1.45;
                margin-bottom: 14px;
                -webkit-line-clamp: 2;
            }
            .article-btn {
                font-size: 11.5px;
                gap: 5px;
            }
            .article-btn svg {
                width: 13px;
                height: 13px;
            }
            .footer-inner {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .wawasan-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                margin-top: 24px;
            }
            .article-card {
                border-radius: 14px;
            }
            .article-img-wrap {
                height: 115px;
            }
            .article-content {
                padding: 14px 10px;
            }
            .article-category {
                font-size: 8.5px;
                padding: 3px 8px;
                letter-spacing: 0.8px;
            }
            .article-date {
                font-size: 10px;
            }
            .article-title {
                font-size: clamp(11.5px, 3.4vw, 13.5px);
                line-height: 1.3;
                margin-bottom: 6px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .article-desc {
                font-size: clamp(9.5px, 2.7vw, 11px);
                line-height: 1.4;
                margin-bottom: 12px;
                -webkit-line-clamp: 2;
            }
            .article-btn {
                font-size: 10.5px;
                gap: 4px;
            }
            .article-btn svg {
                width: 11px;
                height: 11px;
            }
        }

        @media (max-width: 360px) {
            .wawasan-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
            .article-card {
                border-radius: 12px;
            }
            .article-img-wrap {
                height: 95px;
            }
            .article-content {
                padding: 12px 8px;
            }
            .article-category {
                font-size: 7.5px;
                padding: 2px 6px;
            }
            .article-date {
                font-size: 9px;
            }
            .article-title {
                font-size: 11px;
                line-height: 1.25;
            }
            .article-desc {
                font-size: 9px;
            }
            .article-btn {
                font-size: 9.5px;
            }
        }

        @keyframes aurora-float-cyan {
            0% { transform: translate(0, 0) scale(1); opacity: 0.75; }
            50% { transform: translate(45px, 35px) scale(1.12); opacity: 0.95; }
            100% { transform: translate(-20px, 20px) scale(0.98); opacity: 0.75; }
        }

        @keyframes aurora-float-purple {
            0% { transform: translate(0, 0) scale(1); opacity: 0.8; }
            50% { transform: translate(-40px, 30px) scale(1.15); opacity: 1; }
            100% { transform: translate(25px, -20px) scale(0.95); opacity: 0.8; }
        }

        @keyframes aurora-pulse-mid {
            0% { transform: translate(-50%, -50%) scale(0.9); opacity: 0.6; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.9; }
            100% { transform: translate(-50%, -50%) scale(0.9); opacity: 0.6; }
        }

        @keyframes aurora-float-bottom {
            0% { transform: translateX(-50%) scale(0.95); opacity: 0.7; }
            50% { transform: translateX(-48%) scale(1.1); opacity: 0.95; }
            100% { transform: translateX(-52%) scale(0.95); opacity: 0.7; }
        }

        @keyframes radar-rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <!-- Futuristic Ambient Background Scene -->
    <div class="wawasan-bg-scene" aria-hidden="true">
        <div class="wawasan-aurora aurora-cyan-top"></div>
        <div class="wawasan-aurora aurora-purple-top"></div>
        <div class="wawasan-aurora aurora-pink-mid"></div>
        <div class="wawasan-aurora aurora-blue-bottom"></div>
        <div class="wawasan-grid-mesh"></div>
        <div class="wawasan-radar-scanner"></div>
        
        <!-- Flanking Circuit Lines -->
        <div class="wawasan-circuit-flank wawasan-circuit-left">
            <svg class="circuit-svg" viewBox="0 0 160 800" fill="none">
                <path d="M20 0 V240 L70 290 V450 L30 490 V800" stroke="url(#cyan-grad-l)" stroke-width="1.5" stroke-dasharray="4 8" opacity="0.45" />
                <path d="M60 0 V180 L110 230 V390 L80 420 V800" stroke="url(#purple-grad-l)" stroke-width="1" opacity="0.35" />
                <circle cx="70" cy="290" r="4" fill="#00d4ff" />
                <circle cx="30" cy="490" r="3.5" fill="#7c3aed" />
                <circle cx="110" cy="230" r="3" fill="#ec4899" />
                <defs>
                    <linearGradient id="cyan-grad-l" x1="0" y1="0" x2="0" y2="800" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#00d4ff" stop-opacity="0.8"/>
                        <stop offset="0.5" stop-color="#7c3aed" stop-opacity="0.4"/>
                        <stop offset="1" stop-color="#00d4ff" stop-opacity="0.1"/>
                    </linearGradient>
                    <linearGradient id="purple-grad-l" x1="0" y1="0" x2="0" y2="800" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#7c3aed" stop-opacity="0.7"/>
                        <stop offset="0.6" stop-color="#00d4ff" stop-opacity="0.3"/>
                        <stop offset="1" stop-color="#7c3aed" stop-opacity="0.1"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <div class="wawasan-circuit-flank wawasan-circuit-right">
            <svg class="circuit-svg" viewBox="0 0 160 800" fill="none">
                <path d="M140 0 V220 L90 270 V430 L130 470 V800" stroke="url(#cyan-grad-r)" stroke-width="1.5" stroke-dasharray="4 8" opacity="0.45" />
                <path d="M100 0 V160 L50 210 V370 L80 400 V800" stroke="url(#purple-grad-r)" stroke-width="1" opacity="0.35" />
                <circle cx="90" cy="270" r="4" fill="#00d4ff" />
                <circle cx="130" cy="470" r="3.5" fill="#7c3aed" />
                <circle cx="50" cy="210" r="3" fill="#00d4ff" />
                <defs>
                    <linearGradient id="cyan-grad-r" x1="0" y1="0" x2="0" y2="800" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#00d4ff" stop-opacity="0.8"/>
                        <stop offset="0.5" stop-color="#ec4899" stop-opacity="0.4"/>
                        <stop offset="1" stop-color="#7c3aed" stop-opacity="0.1"/>
                    </linearGradient>
                    <linearGradient id="purple-grad-r" x1="0" y1="0" x2="0" y2="800" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#7c3aed" stop-opacity="0.7"/>
                        <stop offset="0.6" stop-color="#00d4ff" stop-opacity="0.3"/>
                        <stop offset="1" stop-color="#00d4ff" stop-opacity="0.1"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar" role="navigation" aria-label="Main Navigation">
        <div class="navbar-inner">
            <div class="nav-brand-wrap">
                @php
                    $isCustomLogo = (isset($cmsHomepage) && ($cmsHomepage->site_logo_type ?? '') === 'custom' && $cmsHomepage->site_logo_url);
                    $siteHl = isset($cmsHomepage) && $cmsHomepage->site_name_highlight ? $cmsHomepage->site_name_highlight : 'AI';
                    $siteRs = isset($cmsHomepage) && $cmsHomepage->site_name_rest ? $cmsHomepage->site_name_rest : 'Jogja Solutions';
                    $siteFull = trim($siteHl . ' ' . $siteRs);
                @endphp
                <a href="/" class="nav-logo" aria-label="{{ $siteFull }} Beranda">
                    <div class="nav-logo-icon" aria-hidden="true">
                        @if($isCustomLogo)
                            <img src="{{ $cmsHomepage->site_logo_url }}" alt="{{ $siteFull }}" style="width:100%;height:100%;object-fit:contain;padding:3px;">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="3" fill="#0284c7" opacity="0.95"/>
                                <circle cx="5" cy="7" r="1.5" fill="#7c3aed" opacity="0.85"/>
                                <circle cx="19" cy="7" r="1.5" fill="#7c3aed" opacity="0.85"/>
                                <circle cx="5" cy="17" r="1.5" fill="#7c3aed" opacity="0.85"/>
                                <circle cx="19" cy="17" r="1.5" fill="#7c3aed" opacity="0.85"/>
                                <circle cx="12" cy="3" r="1.5" fill="#0284c7" opacity="0.85"/>
                                <circle cx="12" cy="21" r="1.5" fill="#0284c7" opacity="0.85"/>
                                <line x1="9" y1="11" x2="6.12" y2="7.88" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                <line x1="15" y1="11" x2="17.88" y2="7.88" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                <line x1="9" y1="13" x2="6.12" y2="16.12" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                <line x1="15" y1="13" x2="17.88" y2="16.12" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                <line x1="12" y1="9" x2="12" y2="4.5" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                                <line x1="12" y1="15" x2="12" y2="19.5" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                            </svg>
                        @endif
                    </div>
                    <span class="nav-logo-text"><span>{{ $siteHl }}</span> {{ $siteRs }}</span>
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
            </div>
        </div>
    </nav>

    <!-- Hero Header -->
    <header class="wawasan-hero">
        <div class="wawasan-hero-halo" aria-hidden="true"></div>
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

        <div class="wawasan-tech-divider" aria-hidden="true">
            <span class="divider-line"></span>
            <span class="divider-gem">
                <span class="gem-pulse"></span>
            </span>
            <span class="divider-line"></span>
        </div>
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
