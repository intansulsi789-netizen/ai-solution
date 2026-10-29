<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article['title'] }} - AI Solution</title>
    <meta name="description" content="{{ Str::limit(strip_tags($article['summary'] ?? ''), 160) }}">

    @php
        $canonicalDomain = 'https://jogjaaisolutions.my.id';
        $canonicalUrl    = $canonicalDomain . '/wawasan/' . $article['slug'];
        $ogImage         = !empty($article['image']) ? (str_starts_with($article['image'], 'http') ? $article['image'] : $canonicalDomain . '/' . ltrim($article['image'], '/')) : $canonicalDomain . '/images/og-default.jpg';
    @endphp

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/favicon.jpg') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $article['title'] }} - AI Solution">
    <meta property="og:description" content="{{ Str::limit(strip_tags($article['summary'] ?? ''), 200) }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:site_name" content="AI Solution">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $canonicalUrl }}">
    <meta name="twitter:title" content="{{ $article['title'] }} - AI Solution">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($article['summary'] ?? ''), 200) }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Strictly Dark Mode on public pages --}}
    <script>
        document.documentElement.setAttribute('data-theme', 'dark');
    </script>

    <style>
        :root {
            --bg-color: #020818;
            --navy-800: #050e27;
            --cyan-neon: #00d4ff;
            --purple-neon: #7c3aed;
            --purple-light: #a78bfa;
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.65);
            --text-dim: rgba(255, 255, 255, 0.45);
            --glass-bg: rgba(10, 20, 50, 0.45);
            --glass-border: rgba(0, 168, 255, 0.15);
        }

        /* ==========================================
           LIGHT MODE OVERRIDES
        ========================================== */
        html[data-theme="light"] {
            --bg-color: #f8fafc;
            --navy-800: #ffffff;
            --cyan-neon: #0284c7;
            --purple-neon: #7c3aed;
            --purple-light: #6d28d9;
            --text-main: #0f172a;
            --text-muted: #475569;
            --text-dim: #64748b;
            --glass-bg: #ffffff;
            --glass-border: rgba(15, 23, 42, 0.12);
        }

        html[data-theme="light"] body {
            background-color: #f8fafc;
            color: #0f172a;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(2, 132, 199, 0.04) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(124, 58, 237, 0.04) 0%, transparent 50%);
        }

        html[data-theme="light"] .btn-back {
            background: #ffffff;
            border-color: rgba(15, 23, 42, 0.15);
            color: #334155;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        html[data-theme="light"] .btn-back:hover {
            background: #f1f5f9;
            color: #0284c7;
            border-color: #0284c7;
        }

        html[data-theme="light"] .article-hero-title {
            background: linear-gradient(135deg, #0f172a 50%, #0369a1 85%, #6d28d9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: none;
        }
        html[data-theme="light"] .article-hero-halo {
            background: radial-gradient(ellipse 70% 50% at 50% 50%, rgba(2, 132, 199, 0.10) 0%, rgba(124, 58, 237, 0.06) 45%, transparent 75%);
            filter: blur(50px);
        }
        html[data-theme="light"] .article-circuit-flank {
            opacity: 0.35;
        }

        html[data-theme="light"] .article-hero-summary {
            color: #475569;
        }

        html[data-theme="light"] .article-hero-image img {
            border: 1px solid rgba(15, 23, 42, 0.10);
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08), 0 2px 8px rgba(15, 23, 42, 0.04);
            background: #ffffff;
        }

        html[data-theme="light"] .sidebar-related-img {
            border: 1px solid rgba(15, 23, 42, 0.10);
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
            background: #f1f5f9;
        }

        html[data-theme="light"] .related-card-img {
            background: #f8fafc;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }

        html[data-theme="light"] .article-body img {
            border: 1px solid rgba(15, 23, 42, 0.10);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            background: #ffffff;
        }

        html[data-theme="light"] .article-meta-row,
        html[data-theme="light"] .article-meta-text {
            color: #64748b;
        }

        html[data-theme="light"] .article-meta-divider {
            background: rgba(15, 23, 42, 0.2);
        }

        html[data-theme="light"] .article-category-badge {
            background: rgba(2, 132, 199, 0.08);
            border-color: rgba(2, 132, 199, 0.25);
            color: #0284c7;
        }

        html[data-theme="light"] .article-body {
            color: #334155;
        }

        html[data-theme="light"] .article-body h1,
        html[data-theme="light"] .article-body h2,
        html[data-theme="light"] .article-body h3,
        html[data-theme="light"] .article-body h4,
        html[data-theme="light"] .article-body strong {
            color: #0f172a;
        }

        html[data-theme="light"] .article-body p,
        html[data-theme="light"] .article-body ul li,
        html[data-theme="light"] .article-body ol li {
            color: #334155;
        }

        html[data-theme="light"] .article-body blockquote {
            background: #f8fafc;
            border-left-color: #0284c7;
            color: #1e293b;
        }

        html[data-theme="light"] .sidebar-card,
        html[data-theme="light"] .related-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.12);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        html[data-theme="light"] .sidebar-title,
        html[data-theme="light"] .related-section-title {
            color: #0f172a;
        }

        html[data-theme="light"] .sidebar-related-title,
        html[data-theme="light"] .related-card-title {
            color: #0f172a;
        }

        html[data-theme="light"] .sidebar-related-cat,
        html[data-theme="light"] .related-card-cat,
        html[data-theme="light"] .related-card-date {
            color: #64748b;
        }

        html[data-theme="light"] .toc-list a {
            color: #475569;
        }

        html[data-theme="light"] .toc-list a:hover {
            color: #0284c7;
            background: rgba(2, 132, 199, 0.08);
        }

        html[data-theme="light"] .highlight-box {
            background: rgba(2, 132, 199, 0.06);
            border-color: rgba(2, 132, 199, 0.30);
        }

        html[data-theme="light"] .highlight-box-text {
            color: #1e293b;
        }

        html[data-theme="light"] .article-source-container {
            background: #ffffff !important;
            border-color: rgba(15, 23, 42, 0.12) !important;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        html[data-theme="light"] .article-source-container div {
            color: #0f172a !important;
        }

        html[data-theme="light"] .bottom-cta-box {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.12);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
        }

        html[data-theme="light"] .bottom-cta-title {
            color: #0f172a;
        }

        html[data-theme="light"] .bottom-cta-desc {
            color: #475569;
        }

        html[data-theme="light"] .bottom-cta-btn {
            background: linear-gradient(135deg, #0284c7 0%, #7c3aed 100%);
            color: #ffffff;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        html, body {
            overflow-x: clip;
            max-width: 100vw;
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            background-color: #020818;
            background-attachment: fixed;
            position: relative;
        }

        /* ===== FUTURISTIC AMBIENT SCENE ===== */
        .article-bg-scene {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .article-aurora {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            will-change: transform, opacity;
        }

        .art-aurora-cyan-top {
            width: 750px;
            height: 750px;
            top: -60px;
            left: -80px;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.24) 0%, rgba(0, 168, 255, 0.12) 45%, transparent 70%);
            filter: blur(75px);
            animation: aurora-float-cyan 14s ease-in-out infinite alternate;
        }

        .art-aurora-purple-top {
            width: 800px;
            height: 800px;
            top: -50px;
            right: -80px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.24) 0%, rgba(167, 139, 250, 0.12) 45%, transparent 70%);
            filter: blur(80px);
            animation: aurora-float-purple 16s ease-in-out infinite alternate;
        }

        .art-aurora-pink-mid {
            width: 700px;
            height: 550px;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: radial-gradient(ellipse, rgba(0, 212, 255, 0.12) 0%, rgba(236, 72, 153, 0.10) 35%, rgba(124, 58, 237, 0.09) 60%, transparent 75%);
            filter: blur(90px);
            animation: aurora-pulse-mid 12s ease-in-out infinite alternate;
        }

        .art-aurora-blue-bottom {
            width: 900px;
            height: 500px;
            bottom: -150px;
            left: 50%;
            transform: translateX(-50%);
            background: radial-gradient(ellipse at center, rgba(0, 212, 255, 0.16) 0%, rgba(124, 58, 237, 0.14) 45%, transparent 70%);
            filter: blur(90px);
            animation: aurora-float-bottom 18s ease-in-out infinite alternate;
        }

        .article-grid-mesh {
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(rgba(0, 212, 255, 0.18) 1.2px, transparent 1.2px),
                linear-gradient(rgba(0, 212, 255, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.035) 1px, transparent 1px);
            background-size: 32px 32px, 64px 64px, 64px 64px;
            mask-image: radial-gradient(ellipse 90% 80% at 50% 30%, rgba(0, 0, 0, 0.95) 20%, rgba(0, 0, 0, 0.4) 65%, transparent 90%);
            -webkit-mask-image: radial-gradient(ellipse 90% 80% at 50% 30%, rgba(0, 0, 0, 0.95) 20%, rgba(0, 0, 0, 0.4) 65%, transparent 90%);
            pointer-events: none;
        }

        /* Lateral tech circuit lines */
        .article-circuit-flank {
            position: fixed;
            top: 70px;
            bottom: 70px;
            width: 140px;
            pointer-events: none;
            opacity: 0.55;
            z-index: 0;
        }
        .article-circuit-left {
            left: 10px;
        }
        .article-circuit-right {
            right: 10px;
        }
        .circuit-svg {
            width: 100%;
            height: 100%;
        }
        @media (max-width: 1100px) {
            .article-circuit-flank {
                display: block;
                width: 80px;
                opacity: 0.35;
                pointer-events: none;
            }
            .article-circuit-left { left: 0; }
            .article-circuit-right { right: 0; }
        }
        @media (max-width: 640px) {
            .article-circuit-flank {
                width: 50px;
                opacity: 0.20;
            }
        }

        html[data-theme="light"] .art-aurora-cyan-top {
            background: radial-gradient(circle, rgba(2, 132, 199, 0.08) 0%, transparent 65%);
            filter: blur(60px);
        }
        html[data-theme="light"] .art-aurora-purple-top {
            background: radial-gradient(circle, rgba(124, 58, 237, 0.07) 0%, transparent 65%);
            filter: blur(65px);
        }
        html[data-theme="light"] .art-aurora-pink-mid {
            background: radial-gradient(ellipse, rgba(2, 132, 199, 0.05) 0%, transparent 65%);
            filter: blur(70px);
        }
        html[data-theme="light"] .art-aurora-blue-bottom {
            background: radial-gradient(ellipse at center, rgba(124, 58, 237, 0.06) 0%, transparent 65%);
            filter: blur(70px);
        }
        html[data-theme="light"] .article-grid-mesh {
            background-image: 
                radial-gradient(rgba(2, 132, 199, 0.10) 1.2px, transparent 1.2px),
                linear-gradient(rgba(15, 23, 42, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 23, 42, 0.02) 1px, transparent 1px);
            opacity: 0.6;
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

        /* ===== NAVBAR ===== */
        .navbar {
            padding: 20px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 50;
            max-width: 1320px;
            margin: 0 auto;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            padding: 8px 18px;
            border-radius: 100px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateX(-4px);
        }

        /* ===== HERO HEADER ===== */
        .article-hero {
            position: relative;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px 5% 0;
            animation: fadeUp 0.7s ease forwards;
        }
        .article-hero-halo {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 780px;
            max-width: 96vw;
            height: 380px;
            background: radial-gradient(ellipse 70% 50% at 50% 50%, rgba(0, 212, 255, 0.24) 0%, rgba(124, 58, 237, 0.18) 45%, transparent 75%);
            filter: blur(55px);
            pointer-events: none;
            z-index: -1;
            animation: hero-halo-breathe 7s ease-in-out infinite alternate;
        }
        @keyframes hero-halo-breathe {
            0% { transform: translate(-50%, -50%) scale(0.92); opacity: 0.75; }
            100% { transform: translate(-50%, -50%) scale(1.08); opacity: 1; }
        }
        .article-meta-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .article-category-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--cyan-neon);
            padding: 5px 14px;
            background: rgba(0, 212, 255, 0.08);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 100px;
        }
        .article-meta-divider {
            width: 4px; height: 4px;
            border-radius: 50%;
            background: var(--text-dim);
            flex-shrink: 0;
        }
        .article-meta-text {
            font-size: 13px;
            color: var(--text-dim);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .article-meta-text svg {
            width: 14px; height: 14px;
            opacity: 0.6;
        }
        .article-hero-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(28px, 4vw, 44px);
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.2;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #ffffff 45%, #cffafe 80%, #e9d5ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 2px 20px rgba(0, 212, 255, 0.22));
        }
        .article-hero-summary {
            font-size: 17px;
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 700px;
            margin-bottom: 32px;
        }
        .article-hero-image {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto 48px;
            padding: 0 5%;
            animation: fadeUp 0.9s ease forwards;
        }
        .article-hero-image img {
            width: 100%;
            height: auto;
            max-height: 480px;
            object-fit: cover;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        /* ===== 2-COLUMN LAYOUT ===== */
        .article-layout {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 5%;
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 48px;
            align-items: start;
        }

        /* ===== MAIN CONTENT ===== */
        .article-main {
            min-width: 0;
            animation: fadeUp 1s ease forwards;
        }
        .article-body {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.78);
            line-height: 1.85;
        }
        .article-body p {
            margin-bottom: 22px;
        }
        .article-body img {
            max-width: 100%;
            height: auto;
            border-radius: 14px;
            margin: 24px 0;
            display: block;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .article-body h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            margin: 40px 0 16px;
            padding-left: 16px;
            border-left: 3px solid var(--cyan-neon);
        }
        .article-body ul {
            list-style: none;
            margin: 0 0 24px 0;
            padding: 0;
        }
        .article-body ul li {
            position: relative;
            padding-left: 24px;
            margin-bottom: 14px;
            color: rgba(255, 255, 255, 0.78);
        }
        .article-body ul li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10px;
            width: 8px;
            height: 8px;
            border-radius: 2px;
            background: linear-gradient(135deg, var(--cyan-neon), var(--purple-neon));
        }
        .article-body ul li strong {
            color: #fff;
        }

        /* ===== HIGHLIGHT BOX ===== */
        .highlight-box {
            margin: 40px 0;
            padding: 28px 28px 28px 32px;
            background: rgba(0, 212, 255, 0.04);
            border: 1px solid rgba(0, 212, 255, 0.18);
            border-radius: 16px;
            position: relative;
            overflow: hidden;
        }
        .highlight-box::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 4px; height: 100%;
            background: linear-gradient(180deg, var(--cyan-neon), var(--purple-neon));
            border-radius: 4px 0 0 4px;
        }
        .highlight-box-label {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--cyan-neon);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .highlight-box-label svg {
            width: 16px; height: 16px;
        }
        .highlight-box-text {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.7;
            font-style: italic;
        }

        /* ===== SIDEBAR ===== */
        .article-sidebar {
            position: sticky;
            top: 32px;
            animation: fadeUp 1.1s ease forwards;
        }
        .sidebar-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 24px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            margin-bottom: 24px;
        }
        .sidebar-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--cyan-neon);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(0, 212, 255, 0.12);
        }

        /* TOC */
        .toc-list {
            list-style: none;
        }
        .toc-list li {
            margin-bottom: 4px;
        }
        .toc-list a {
            display: block;
            padding: 8px 12px;
            font-size: 13px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            border-left: 2px solid transparent;
        }
        .toc-list a:hover {
            color: var(--cyan-neon);
            background: rgba(0, 212, 255, 0.06);
            border-left-color: var(--cyan-neon);
        }

        /* Sidebar related articles */
        .sidebar-related-item {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .sidebar-related-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .sidebar-related-item:hover .sidebar-related-title {
            color: var(--cyan-neon);
        }
        .sidebar-related-img {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .sidebar-related-info { min-width: 0; }
        .sidebar-related-title {
            font-size: 13px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.4;
            margin-bottom: 4px;
            transition: color 0.2s;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .sidebar-related-cat {
            font-size: 11px;
            color: var(--text-dim);
        }

        /* Sidebar CTA */
        .sidebar-cta-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, #00a8ff 0%, #7c3aed 100%);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 168, 255, 0.3);
        }
        .sidebar-cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 28px rgba(0, 168, 255, 0.45);
        }
        .sidebar-cta-btn svg { width: 16px; height: 16px; }

        /* ===== RELATED ARTICLES BOTTOM ===== */
        .related-section {
            max-width: 1100px;
            margin: 80px auto 0;
            padding: 0 5%;
            animation: fadeUp 1.2s ease forwards;
        }
        .related-section-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 26px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
        }
        .related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        .related-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            overflow: hidden;
            text-decoration: none;
            transition: all 0.35s ease;
            backdrop-filter: blur(12px);
        }
        .related-card:hover {
            transform: translateY(-4px);
            border-color: rgba(0, 212, 255, 0.35);
            box-shadow: 0 12px 40px rgba(0, 212, 255, 0.12);
        }
        .related-card-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .related-card-body {
            padding: 20px;
        }
        .related-card-cat {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--cyan-neon);
            margin-bottom: 10px;
        }
        .related-card-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            line-height: 1.35;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .related-card-date {
            font-size: 12px;
            color: var(--text-dim);
        }
        .btn-more-articles {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            background: rgba(0, 212, 255, 0.08);
            border: 1px solid rgba(0, 212, 255, 0.25);
            border-radius: 100px;
            color: var(--cyan-neon);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .btn-more-articles:hover {
            background: rgba(0, 212, 255, 0.18);
            border-color: var(--cyan-neon);
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 212, 255, 0.25);
        }
        html[data-theme="light"] .btn-more-articles {
            background: rgba(2, 132, 199, 0.08);
            border-color: rgba(2, 132, 199, 0.25);
            color: #0284c7;
        }
        html[data-theme="light"] .btn-more-articles:hover {
            background: rgba(2, 132, 199, 0.15);
            border-color: #0284c7;
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.15);
        }

        /* ===== BOTTOM CTA ===== */
        .bottom-cta {
            max-width: 900px;
            margin: 80px auto 100px;
            padding: 0 5%;
        }
        .bottom-cta-box {
            text-align: center;
            padding: 56px 40px;
            background: linear-gradient(135deg, rgba(0, 168, 255, 0.08), rgba(124, 58, 237, 0.08));
            border: 1px solid rgba(0, 212, 255, 0.25);
            border-radius: 24px;
            position: relative;
            overflow: hidden;
        }
        .bottom-cta-box::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan-neon), var(--purple-neon), transparent);
        }
        .bottom-cta-box::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 100%; height: 2px;
            background: linear-gradient(90deg, transparent, var(--purple-neon), var(--cyan-neon), transparent);
            opacity: 0.5;
        }
        .bottom-cta-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(22px, 3vw, 30px);
            font-weight: 800;
            margin-bottom: 14px;
            letter-spacing: -0.5px;
        }
        .bottom-cta-desc {
            font-size: 16px;
            color: var(--text-muted);
            margin-bottom: 32px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }
        .bottom-cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 36px;
            background: #fff;
            color: #000;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 100px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 24px rgba(255, 255, 255, 0.15);
        }
        .bottom-cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 32px rgba(255, 255, 255, 0.25);
        }
        .bottom-cta-btn svg { width: 18px; height: 18px; }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .article-layout {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .article-sidebar {
                position: static;
            }
            .related-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 640px) {
            .article-hero-title {
                font-size: 26px;
                letter-spacing: -0.5px;
            }
            .article-hero-summary {
                font-size: 15px;
            }
            .article-hero-image img {
                max-height: 280px;
                border-radius: 14px;
            }
            .article-body h3 {
                font-size: 19px;
            }
            .related-grid {
                grid-template-columns: 1fr;
            }
            .bottom-cta-box {
                padding: 40px 24px;
            }
            .highlight-box {
                padding: 20px 20px 20px 24px;
            }
        }
    </style>
</head>
<body>

    <!-- Futuristic Ambient Background Scene -->
    <div class="article-bg-scene" aria-hidden="true">
        <div class="article-aurora art-aurora-cyan-top"></div>
        <div class="article-aurora art-aurora-purple-top"></div>
        <div class="article-aurora art-aurora-pink-mid"></div>
        <div class="article-aurora art-aurora-blue-bottom"></div>
        <div class="article-grid-mesh"></div>
        
        <!-- Flanking Circuit Lines -->
        <div class="article-circuit-flank article-circuit-left">
            <svg class="circuit-svg" viewBox="0 0 160 800" fill="none">
                <path d="M20 0 V240 L70 290 V450 L30 490 V800" stroke="url(#art-cyan-grad-l)" stroke-width="1.5" stroke-dasharray="4 8" opacity="0.45" />
                <path d="M60 0 V180 L110 230 V390 L80 420 V800" stroke="url(#art-purple-grad-l)" stroke-width="1" opacity="0.35" />
                <circle cx="70" cy="290" r="4" fill="#00d4ff" />
                <circle cx="30" cy="490" r="3.5" fill="#7c3aed" />
                <circle cx="110" cy="230" r="3" fill="#ec4899" />
                <defs>
                    <linearGradient id="art-cyan-grad-l" x1="0" y1="0" x2="0" y2="800" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#00d4ff" stop-opacity="0.8"/>
                        <stop offset="0.5" stop-color="#7c3aed" stop-opacity="0.4"/>
                        <stop offset="1" stop-color="#00d4ff" stop-opacity="0.1"/>
                    </linearGradient>
                    <linearGradient id="art-purple-grad-l" x1="0" y1="0" x2="0" y2="800" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#7c3aed" stop-opacity="0.7"/>
                        <stop offset="0.6" stop-color="#00d4ff" stop-opacity="0.3"/>
                        <stop offset="1" stop-color="#7c3aed" stop-opacity="0.1"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <div class="article-circuit-flank article-circuit-right">
            <svg class="circuit-svg" viewBox="0 0 160 800" fill="none">
                <path d="M140 0 V220 L90 270 V430 L130 470 V800" stroke="url(#art-cyan-grad-r)" stroke-width="1.5" stroke-dasharray="4 8" opacity="0.45" />
                <path d="M100 0 V160 L50 210 V370 L80 400 V800" stroke="url(#art-purple-grad-r)" stroke-width="1" opacity="0.35" />
                <circle cx="90" cy="270" r="4" fill="#00d4ff" />
                <circle cx="130" cy="470" r="3.5" fill="#7c3aed" />
                <circle cx="50" cy="210" r="3" fill="#00d4ff" />
                <defs>
                    <linearGradient id="art-cyan-grad-r" x1="0" y1="0" x2="0" y2="800" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#00d4ff" stop-opacity="0.8"/>
                        <stop offset="0.5" stop-color="#ec4899" stop-opacity="0.4"/>
                        <stop offset="1" stop-color="#7c3aed" stop-opacity="0.1"/>
                    </linearGradient>
                    <linearGradient id="art-purple-grad-r" x1="0" y1="0" x2="0" y2="800" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#7c3aed" stop-opacity="0.7"/>
                        <stop offset="0.6" stop-color="#00d4ff" stop-opacity="0.3"/>
                        <stop offset="1" stop-color="#00d4ff" stop-opacity="0.1"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="/#wawasan" class="btn-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Wawasan
        </a>

    </nav>

    <!-- Hero Header -->
    <header class="article-hero">
        <div class="article-hero-halo" aria-hidden="true"></div>
        <div class="article-meta-row">
            <span class="article-category-badge">{{ $article['category'] }}</span>
            <span class="article-meta-divider"></span>
            <span class="article-meta-text">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                {{ $article['date'] }}
            </span>
            <span class="article-meta-divider"></span>
            <span class="article-meta-text">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                {{ $article['readTime'] }} baca
            </span>
            @if(!empty($article['author_name']))
            <span class="article-meta-divider"></span>
            <span class="article-meta-text">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Penulis: {{ $article['author_name'] }}
            </span>
            @endif
            @if(!empty($article['source_name']))
            <span class="article-meta-divider"></span>
            <span class="article-meta-text">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                Sumber:
                @if(!empty($article['source_url']))
                    <a href="{{ $article['source_url'] }}" target="_blank" rel="noopener noreferrer" style="color:var(--cyan-neon); text-decoration:none;">{{ $article['source_name'] }} ↗</a>
                @else
                    {{ $article['source_name'] }}
                @endif
            </span>
            @endif
        </div>
        <h1 class="article-hero-title">{{ $article['title'] }}</h1>
        <p class="article-hero-summary">{{ $article['summary'] }}</p>
    </header>

    <!-- Hero Image -->
    <div class="article-hero-image">
        <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}">
    </div>

    <!-- 2-Column Layout -->
    <div class="article-layout">

        <!-- Main Content -->
        <main class="article-main">
            <div class="article-body">
                {!! $article['content'] !!}
            </div>

            <!-- Source Reference Box -->
            @if(!empty($article['source_name']))
            <div class="article-source-container" style="margin: 32px 0 24px; padding: 18px 22px; background: rgba(0, 212, 255, 0.03); border: 1px solid rgba(0, 212, 255, 0.15); border-radius: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 18px;">📚</span>
                    <div>
                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--cyan-neon); font-weight: 700;">Sumber Referensi</div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text-main);">{{ $article['source_name'] }}</div>
                    </div>
                </div>
                @if(!empty($article['source_url']))
                    <a href="{{ $article['source_url'] }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: var(--cyan-neon); text-decoration: none; padding: 7px 14px; border-radius: 8px; background: rgba(0, 212, 255, 0.08); border: 1px solid rgba(0, 212, 255, 0.2); transition: all 0.2s;">
                        Kunjungi Sumber Resmi
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                @endif
            </div>
            @endif

            <!-- Highlight Box -->
            @if(isset($article['takeaway']))
            <div class="highlight-box">
                <div class="highlight-box-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
                    Intinya
                </div>
                <p class="highlight-box-text">{{ $article['takeaway'] }}</p>
            </div>
            @endif
        </main>

        <!-- Sidebar -->
        <aside class="article-sidebar">
            <!-- Table of Contents -->
            <div class="sidebar-card">
                <div class="sidebar-title">Daftar Isi</div>
                <ul class="toc-list">
                    <li><a href="#pengantar">Pengantar</a></li>
                    <li><a href="#manfaat-ai">Manfaat AI</a></li>
                    <li><a href="#penerapan">Penerapan dalam Bisnis</a></li>
                    <li><a href="#kesimpulan">Kesimpulan</a></li>
                </ul>
            </div>

            <!-- Related Articles Sidebar -->
            <div class="sidebar-card">
                <div class="sidebar-title">Artikel Terkait</div>
                @foreach(array_slice($allArticles, 0, 2) as $related)
                <a href="{{ route('article.detail', ['slug' => $related['slug']]) }}" class="sidebar-related-item">
                    <img src="{{ $related['image'] }}" alt="{{ $related['title'] }}" class="sidebar-related-img" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80';">
                    <div class="sidebar-related-info">
                        <div class="sidebar-related-title">{{ $related['title'] }}</div>
                        <div class="sidebar-related-cat">{{ $related['category'] }}</div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- CTA Button -->
            <a href="/#konsultasi" class="sidebar-cta-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                Konsultasikan Kebutuhan AI
            </a>
        </aside>

    </div>

    <!-- Related Articles Bottom Section -->
    @php
        $relatedArticles = array_slice($allArticles, 0, 3);
    @endphp
    @if(count($relatedArticles) > 0)
    <section class="related-section">
        <h2 class="related-section-title">Artikel Terkait</h2>
        <div class="related-grid">
            @foreach($relatedArticles as $related)
            <a href="{{ route('article.detail', ['slug' => $related['slug']]) }}" class="related-card">
                <img src="{{ $related['image'] }}" alt="{{ $related['title'] }}" class="related-card-img" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80';">
                <div class="related-card-body">
                    <div class="related-card-cat">{{ $related['category'] }}</div>
                    <div class="related-card-title">{{ $related['title'] }}</div>
                    <div class="related-card-date">{{ $related['date'] }}</div>
                </div>
            </a>
            @endforeach
        </div>
        @if(count($allArticles) > 3)
        <div style="text-align: center; margin-top: 36px;">
            <a href="{{ route('articles.index') }}" class="btn-more-articles">
                Lihat Semua Artikel
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
        @endif
    </section>
    @endif

    <!-- Bottom CTA -->
    <div class="bottom-cta">
        <div class="bottom-cta-box">
            <h2 class="bottom-cta-title">Perlu Solusi AI untuk Kebutuhan Bisnis Anda?</h2>
            <p class="bottom-cta-desc">Ceritakan kebutuhan Anda kepada kami dan diskusikan solusi yang sesuai.</p>
            <a href="/#konsultasi" class="bottom-cta-btn">
                Konsultasikan Kebutuhan Anda
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>

{{-- WhatsApp Chat Widget --}}
@include('partials.wa-widget')

</body>
</html>
