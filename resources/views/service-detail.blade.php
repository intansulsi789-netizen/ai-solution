@php
    $cmsContact = \App\Models\CmsContact::first();
    $waNumber = preg_replace('/[^0-9]/', '', optional($cmsContact)->whatsapp ?? '628XXXXXXXXXX');
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    }
    $waMessage = 'Halo, saya ingin berkonsultasi mengenai layanan ' . $service['title'] . '.';
    $waUrl     = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waMessage);

    $canonicalDomain = 'https://jogjaaisolutions.my.id';
    $canonicalUrl    = $canonicalDomain . '/layanan/' . $service['slug'];
    $serviceImage    = !empty($service['image']) ? (str_starts_with($service['image'], 'http') ? $service['image'] : $canonicalDomain . '/' . ltrim($service['image'], '/')) : $canonicalDomain . '/images/og-default.jpg';
    $serviceMetaDesc = \Illuminate\Support\Str::limit(strip_tags($service['desc'] ?? ''), 160);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service['title'] }} - AI Solution</title>
    <meta name="description" content="{{ $serviceMetaDesc }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/favicon.jpg') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $service['title'] }} - AI Solution">
    <meta property="og:description" content="{{ $serviceMetaDesc }}">
    <meta property="og:image" content="{{ $serviceImage }}">
    <meta property="og:site_name" content="AI Solution">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $canonicalUrl }}">
    <meta name="twitter:title" content="{{ $service['title'] }} - AI Solution">
    <meta name="twitter:description" content="{{ $serviceMetaDesc }}">
    <meta name="twitter:image" content="{{ $serviceImage }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Strictly Dark Mode on public pages --}}
    <script>
        document.documentElement.setAttribute('data-theme', 'dark');
    </script>

    <style>
        :root {
            --bg-color: #020818;
            --cyan-neon: #00d4ff;
            --purple-neon: #7c3aed;
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.65);
        }

        /* ==========================================
           LIGHT MODE OVERRIDES
        ========================================== */
        html[data-theme="light"] {
            --bg-color: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #475569;
            --cyan-neon: #0284c7;
            --purple-neon: #7c3aed;
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

        html[data-theme="light"] .nav-logo-text {
            color: #0f172a !important;
        }

        html[data-theme="light"] .detail-title {
            color: #091224 !important;
            background: linear-gradient(135deg, #091224 0%, #0f172a 45%, #0284c7 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
            text-shadow: none !important;
            filter: drop-shadow(0 2px 8px rgba(2, 132, 199, 0.12)) !important;
        }

        html[data-theme="light"] .detail-desc {
            color: #334155 !important;
            font-weight: 500 !important;
        }

        html[data-theme="light"] .badge-layanan {
            background: rgba(2, 132, 199, 0.10) !important;
            border-color: rgba(2, 132, 199, 0.35) !important;
            color: #0369a1 !important;
            font-weight: 700 !important;
            box-shadow: 0 2px 10px rgba(2, 132, 199, 0.10) !important;
        }

        html[data-theme="light"] .badge-pulse-dot {
            background: #0284c7 !important;
            box-shadow: 0 0 8px #0284c7 !important;
        }

        html[data-theme="light"] .detail-icon-wrap {
            background: linear-gradient(135deg, rgba(2, 132, 199, 0.12), rgba(124, 58, 237, 0.12)) !important;
            border: 1px solid rgba(2, 132, 199, 0.35) !important;
            color: #0284c7 !important;
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.12) !important;
        }

        html[data-theme="light"] .detail-image-wrap {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.10);
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.07), 0 2px 8px rgba(15, 23, 42, 0.04);
        }

        html[data-theme="light"] .detail-image-wrap img {
            border-radius: 24px;
            object-fit: cover;
        }

        html[data-theme="light"] .image-glow {
            box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.04);
        }

        html[data-theme="light"] .service-hero-solutions-wrapper {
            background: transparent;
        }

        html[data-theme="light"] .hero-aurora-glow.aurora-cyan {
            background: radial-gradient(circle, rgba(2, 132, 199, 0.08) 0%, transparent 70%);
        }

        html[data-theme="light"] .hero-aurora-glow.aurora-purple {
            background: radial-gradient(circle, rgba(124, 58, 237, 0.06) 0%, transparent 70%);
        }

        html[data-theme="light"] .hero-aurora-glow.aurora-indigo {
            background: radial-gradient(ellipse, rgba(2, 132, 199, 0.05) 0%, transparent 75%);
        }

        html[data-theme="light"] .constellation-dot-matrix {
            background-image: radial-gradient(rgba(2, 132, 199, 0.12) 1.2px, transparent 1.2px);
        }

        html[data-theme="light"] .hero-image-status-pill {
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(2, 132, 199, 0.25);
            color: #1e293b;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
        }

        html[data-theme="light"] .tech-corner {
            border-color: #0284c7;
        }

        html[data-theme="light"] .horizon-beam-line {
            background: linear-gradient(90deg, transparent 0%, rgba(2, 132, 199, 0.15) 20%, rgba(2, 132, 199, 0.4) 50%, rgba(124, 58, 237, 0.15) 80%, transparent 100%);
            box-shadow: 0 0 8px rgba(2, 132, 199, 0.25);
        }

        html[data-theme="light"] .horizon-beam-core {
            background: linear-gradient(90deg, #0284c7, #38bdf8, #7c3aed);
            box-shadow: 0 0 10px rgba(2, 132, 199, 0.4);
        }

        html[data-theme="light"] .solutions-pill {
            background: rgba(2, 132, 199, 0.08);
            border-color: rgba(2, 132, 199, 0.25);
            color: #0284c7;
            box-shadow: 0 0 12px rgba(2, 132, 199, 0.1);
        }

        html[data-theme="light"] .solutions-title {
            color: #091224 !important;
            font-weight: 800 !important;
        }

        html[data-theme="light"] .solutions-subtitle {
            color: #475569 !important;
            font-weight: 500 !important;
        }

        html[data-theme="light"] .solution-card {
            background: #ffffff !important;
            border: 1px solid rgba(15, 23, 42, 0.12) !important;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05) !important;
        }

        html[data-theme="light"] .solution-card:hover {
            border-color: #0284c7 !important;
            box-shadow: 0 14px 35px rgba(2, 132, 199, 0.14) !important;
        }

        html[data-theme="light"] .solution-card-title {
            color: #0f172a !important;
            font-weight: 700 !important;
        }

        html[data-theme="light"] .solution-card-desc {
            color: #475569 !important;
            font-weight: 500 !important;
        }

        html[data-theme="light"] .solution-icon {
            background: rgba(2, 132, 199, 0.08);
            border-color: rgba(2, 132, 199, 0.2);
            color: #0284c7;
        }

        html[data-theme="light"] .solution-number {
            background: linear-gradient(135deg, #0284c7 30%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        html[data-theme="light"] .benefits-section-wrap {
            background: linear-gradient(180deg, rgba(241, 245, 249, 0.4) 0%, rgba(226, 232, 240, 0.6) 50%, rgba(241, 245, 249, 0.6) 100%);
        }
        html[data-theme="light"] .benefits-bg-glow.glow-left {
            background: radial-gradient(circle, rgba(2, 132, 199, 0.12) 0%, transparent 70%);
        }
        html[data-theme="light"] .benefits-bg-glow.glow-right {
            background: radial-gradient(circle, rgba(124, 58, 237, 0.10) 0%, transparent 70%);
        }
        html[data-theme="light"] .benefits-cyber-grid {
            background-image: 
                linear-gradient(to right, rgba(15, 23, 42, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15, 23, 42, 0.04) 1px, transparent 1px);
        }
        html[data-theme="light"] .content-card {
            background: #ffffff !important;
            border: 1px solid rgba(15, 23, 42, 0.12) !important;
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.05), 0 0 25px rgba(2, 132, 199, 0.06) !important;
        }
        html[data-theme="light"] .content-card::before {
            background: linear-gradient(90deg, transparent, #0284c7, #7c3aed, transparent) !important;
            box-shadow: 0 0 8px #0284c7 !important;
        }
        html[data-theme="light"] .content-section-title {
            color: #0f172a;
        }
        html[data-theme="light"] .content-title-icon {
            background: rgba(2, 132, 199, 0.08);
            border-color: rgba(2, 132, 199, 0.25);
            color: #0284c7;
            box-shadow: 0 0 15px rgba(2, 132, 199, 0.15);
        }
        html[data-theme="light"] .benefit-item {
            background: #f8fafc !important;
            border-color: rgba(15, 23, 42, 0.09) !important;
        }
        html[data-theme="light"] .benefit-item:hover {
            border-color: #0284c7 !important;
            background: rgba(2, 132, 199, 0.04) !important;
            box-shadow: 0 8px 24px rgba(2, 132, 199, 0.12) !important;
        }
        html[data-theme="light"] .benefit-icon {
            background: rgba(2, 132, 199, 0.08);
            border-color: rgba(2, 132, 199, 0.25);
            color: #0284c7;
            box-shadow: 0 0 12px rgba(2, 132, 199, 0.15);
        }
        html[data-theme="light"] .benefit-text {
            color: #1e293b;
        }
        html[data-theme="light"] .cta-box {
            background: linear-gradient(135deg, rgba(2, 132, 199, 0.06), rgba(124, 58, 237, 0.06)), #ffffff !important;
            border: 1px solid rgba(2, 132, 199, 0.25) !important;
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.06) !important;
        }
        html[data-theme="light"] .cta-title {
            color: #0f172a;
        }
        html[data-theme="light"] .cta-desc {
            color: #475569;
        }
        html[data-theme="light"] .btn-cta {
            background: linear-gradient(135deg, #0284c7 0%, #7c3aed 100%);
            color: #ffffff;
            box-shadow: 0 4px 20px rgba(2, 132, 199, 0.3);
        }
        html[data-theme="light"] .btn-cta:hover {
            color: #ffffff;
            box-shadow: 0 8px 25px rgba(2, 132, 199, 0.45);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(0, 212, 255, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(124, 58, 237, 0.08) 0%, transparent 50%);
            background-attachment: fixed;
        }

        /* Navbar Simple */
        .navbar {
            padding: 24px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 50;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .nav-logo-icon {
            width: 34px; height: 34px;
            background: #ffffff;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25), 0 0 16px rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.6);
            overflow: hidden;
            flex-shrink: 0;
        }
        .nav-logo-icon img {
            width: 100%; height: 100%; object-fit: contain; padding: 3px;
        }
        .nav-logo-icon svg { width: 20px; height: 20px; }
        .nav-logo-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px; font-weight: 800; color: #fff;
            letter-spacing: -0.5px;
        }
        .nav-logo-text span { color: var(--cyan-neon); }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            padding: 8px 16px;
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

        /* ============================================================
           HERO & SOLUTIONS SECTION (NEURAL AURORA & CONSTELLATION MATRIX)
           ============================================================ */
        .service-hero-solutions-wrapper {
            position: relative;
            overflow: hidden;
            width: 100%;
            padding-top: 10px;
            padding-bottom: 20px;
        }

        /* Ambient Fluid Aurora Glows */
        .hero-aurora-glow {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.9;
        }

        .hero-aurora-glow.aurora-cyan {
            top: -60px;
            left: 5%;
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.22) 0%, rgba(0, 150, 255, 0.08) 45%, transparent 70%);
            filter: blur(85px);
            animation: floatAurora1 11s ease-in-out infinite alternate;
        }

        .hero-aurora-glow.aurora-purple {
            top: 40px;
            right: 2%;
            width: 560px;
            height: 560px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.25) 0%, rgba(99, 102, 241, 0.09) 45%, transparent 70%);
            filter: blur(95px);
            animation: floatAurora2 13s ease-in-out infinite alternate;
        }

        .hero-aurora-glow.aurora-indigo {
            bottom: 60px;
            left: 30%;
            width: 650px;
            height: 400px;
            background: radial-gradient(ellipse, rgba(30, 58, 138, 0.28) 0%, rgba(0, 212, 255, 0.06) 50%, transparent 75%);
            filter: blur(90px);
            animation: pulseAurora 9s ease-in-out infinite alternate;
        }

        @keyframes floatAurora1 {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(35px, 30px) scale(1.12); }
        }

        @keyframes floatAurora2 {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-40px, -25px) scale(1.08); }
        }

        @keyframes pulseAurora {
            0%, 100% { opacity: 0.7; transform: scale(1); }
            50%      { opacity: 1; transform: scale(1.1); }
        }

        /* Constellation Tech Dot Matrix */
        .constellation-dot-matrix {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(0, 212, 255, 0.2) 1.2px, transparent 1.2px);
            background-size: 32px 32px;
            mask-image: radial-gradient(ellipse 85% 75% at 50% 40%, black 25%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse 85% 75% at 50% 40%, black 25%, transparent 75%);
            pointer-events: none;
            z-index: 0;
        }

        /* Side Floating Geometric Telemetry */
        .hero-tech-floating {
            position: absolute;
            top: 120px;
            width: 90px;
            height: 260px;
            pointer-events: none;
            z-index: 1;
            opacity: 0.75;
        }
        .hero-tech-floating.left-side { left: 24px; }
        .hero-tech-floating.right-side { right: 24px; }

        /* Detail Container */
        .detail-container {
            max-width: 1200px;
            margin: 20px auto 48px;
            padding: 0 5%;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 48px;
            justify-content: space-between;
        }

        /* Header Section */
        .detail-header {
            text-align: left;
            margin-bottom: 0;
            flex: 1;
            animation: fadeUp 0.8s ease forwards;
        }
        .badge-layanan {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--cyan-neon);
            padding: 6px 16px;
            background: rgba(0, 212, 255, 0.08);
            border: 1px solid rgba(0, 212, 255, 0.25);
            border-radius: 100px;
            margin-bottom: 24px;
            box-shadow: 0 0 16px rgba(0, 212, 255, 0.12);
        }
        .badge-pulse-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--cyan-neon);
            box-shadow: 0 0 8px var(--cyan-neon);
            animation: statusPulse 1.8s infinite;
        }
        @keyframes statusPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.35; transform: scale(0.75); }
        }

        .detail-icon-wrap {
            width: 80px;
            height: 80px;
            margin: 0 0 24px 0;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.14), rgba(124, 58, 237, 0.14));
            border: 1px solid rgba(0, 212, 255, 0.25);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--cyan-neon);
            box-shadow: 0 0 35px rgba(0, 212, 255, 0.2);
            position: relative;
            backdrop-filter: blur(12px);
        }
        .detail-icon-wrap::after {
            content: ''; position: absolute; inset: -1px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--cyan-neon), var(--purple-neon));
            opacity: 0.35; z-index: -1; filter: blur(10px);
        }
        .detail-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(34px, 4.2vw, 50px);
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.18;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #ffffff 45%, #67e8f9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(0, 212, 255, 0.15);
        }
        .detail-desc {
            font-size: clamp(16px, 2vw, 19px);
            color: var(--text-muted);
            line-height: 1.65;
            max-width: 520px;
            margin: 0;
        }

        /* Image Wrap */
        .detail-image-wrap {
            flex: 1;
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            animation: fadeUp 0.8s ease forwards;
            animation-delay: 0.2s;
            border: 1px solid rgba(0, 212, 255, 0.28);
            box-shadow: 
                0 16px 50px rgba(0, 0, 0, 0.55),
                0 0 35px rgba(0, 212, 255, 0.16),
                inset 0 0 20px rgba(0, 212, 255, 0.06);
            background: rgba(10, 20, 50, 0.4);
            backdrop-filter: blur(12px);
        }
        .detail-image-wrap img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
            border-radius: 24px;
        }
        .image-glow {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            box-shadow: inset 0 0 40px rgba(0,0,0,0.45);
            pointer-events: none;
            border-radius: 24px;
        }

        /* Tech Corner Brackets */
        .tech-corner {
            position: absolute;
            width: 18px;
            height: 18px;
            pointer-events: none;
            z-index: 3;
            border-color: var(--cyan-neon);
            opacity: 0.85;
        }
        .corner-tl { top: 12px; left: 12px; border-top: 2px solid var(--cyan-neon); border-left: 2px solid var(--cyan-neon); border-top-left-radius: 6px; }
        .corner-tr { top: 12px; right: 12px; border-top: 2px solid var(--cyan-neon); border-right: 2px solid var(--cyan-neon); border-top-right-radius: 6px; }
        .corner-bl { bottom: 12px; left: 12px; border-bottom: 2px solid var(--cyan-neon); border-left: 2px solid var(--cyan-neon); border-bottom-left-radius: 6px; }
        .corner-br { bottom: 12px; right: 12px; border-bottom: 2px solid var(--cyan-neon); border-right: 2px solid var(--cyan-neon); border-bottom-right-radius: 6px; }

        .hero-image-status-pill {
            position: absolute;
            bottom: 16px;
            left: 16px;
            z-index: 3;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 15px;
            background: rgba(2, 8, 24, 0.78);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 212, 255, 0.35);
            border-radius: 100px;
            font-size: 11px;
            font-weight: 600;
            color: #e2e8f0;
            letter-spacing: 0.4px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
        }
        .status-indicator-ping {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--cyan-neon);
            box-shadow: 0 0 8px var(--cyan-neon);
            animation: statusPulse 2s ease-in-out infinite;
        }

        /* Neural Horizon Beam (Divider between Hero and Solutions) */
        .neural-horizon-beam {
            position: relative;
            max-width: 960px;
            margin: 10px auto 44px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            pointer-events: none;
            padding: 0 5%;
        }
        .horizon-beam-line {
            position: absolute;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, rgba(0, 212, 255, 0.12) 15%, rgba(0, 212, 255, 0.6) 50%, rgba(124, 58, 237, 0.12) 85%, transparent 100%);
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.35);
        }
        .horizon-beam-core {
            width: 68px;
            height: 3px;
            background: linear-gradient(90deg, var(--cyan-neon), #ffffff, var(--purple-neon));
            border-radius: 10px;
            filter: blur(0.5px);
            box-shadow: 0 0 14px var(--cyan-neon), 0 0 24px rgba(124, 58, 237, 0.6);
        }

        /* Solutions Section */
        .solutions-wrapper {
            max-width: 1280px;
            margin: 0 auto 36px;
            padding: 0 5%;
            position: relative;
            z-index: 2;
        }
        .solutions-section {
            animation: fadeUp 1s ease forwards;
            animation-delay: 0.1s;
            opacity: 0;
        }
        .solutions-header {
            text-align: center;
            margin-bottom: 36px;
        }
        .solutions-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--cyan-neon);
            padding: 5px 14px;
            background: rgba(0, 212, 255, 0.08);
            border: 1px solid rgba(0, 212, 255, 0.22);
            border-radius: 100px;
            margin-bottom: 14px;
            box-shadow: 0 0 14px rgba(0, 212, 255, 0.12);
        }
        .pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--cyan-neon);
            box-shadow: 0 0 6px var(--cyan-neon);
        }
        .solutions-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
            letter-spacing: -0.3px;
        }
        .solutions-subtitle {
            font-size: 15px;
            color: var(--text-muted);
            max-width: 580px;
            margin: 0 auto;
            line-height: 1.6;
        }
        .solutions-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .solution-card {
            background: linear-gradient(145deg, rgba(12, 26, 68, 0.65) 0%, rgba(7, 16, 46, 0.75) 100%);
            border: 1px solid rgba(0, 212, 255, 0.16);
            border-radius: 18px;
            padding: 28px 24px 24px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 220px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.08);
        }
        /* Top sweeping shimmer line on hover */
        .solution-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at top right, rgba(0, 212, 255, 0.18), rgba(124, 58, 237, 0.12), transparent 70%);
            opacity: 0;
            transition: opacity 0.35s ease;
            pointer-events: none;
        }
        /* Decorative corner accent line */
        .solution-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 60px; height: 60px;
            border-top: 2px solid rgba(0, 212, 255, 0.22);
            border-right: 2px solid rgba(0, 212, 255, 0.22);
            border-radius: 0 18px 0 0;
            pointer-events: none;
            transition: border-color 0.35s ease;
        }
        .solution-card:hover {
            transform: translateY(-6px);
            border-color: rgba(0, 212, 255, 0.45);
            box-shadow:
                0 14px 40px rgba(0, 212, 255, 0.18),
                0 0 25px rgba(124, 58, 237, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }
        .solution-card:hover::before {
            opacity: 1;
        }
        .solution-card:hover::after {
            border-top-color: rgba(0, 212, 255, 0.6);
            border-right-color: rgba(124, 58, 237, 0.6);
        }
        /* Top row: icon + number side by side */
        .solution-top {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        .solution-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.14), rgba(124, 58, 237, 0.12));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--cyan-neon);
            flex-shrink: 0;
            border: 1px solid rgba(0, 212, 255, 0.25);
            box-shadow: 0 0 14px rgba(0, 212, 255, 0.12);
        }
        .solution-icon svg {
            width: 20px;
            height: 20px;
        }
        .solution-number {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #00d4ff 20%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            filter: drop-shadow(0 0 10px rgba(0, 212, 255, 0.25));
            transition: filter 0.3s ease;
        }
        .solution-card:hover .solution-number {
            filter: drop-shadow(0 0 16px rgba(0, 212, 255, 0.55));
        }
        .solution-card-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
            line-height: 1.35;
        }
        .solution-card-desc {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.55;
            position: relative;
            z-index: 1;
        }
        /* Subtle glow line at bottom of card */
        .solution-glow-line {
            margin-top: auto;
            padding-top: 16px;
            position: relative;
            z-index: 1;
        }
        .solution-glow-line span {
            display: block;
            height: 1px;
            background: linear-gradient(90deg, var(--cyan-neon), var(--purple-neon), transparent);
            opacity: 0.3;
            border-radius: 1px;
        }

        /* ============================================================
           BENEFITS & CTA SECTION (ENHANCED AMBIENT TECH BACKGROUND)
           ============================================================ */
        .benefits-section-wrap {
            position: relative;
            padding: 50px 0 90px;
            overflow: hidden;
            background: 
                radial-gradient(ellipse 70% 45% at 50% 0%, rgba(0, 212, 255, 0.14), transparent 70%),
                radial-gradient(ellipse 55% 45% at 15% 55%, rgba(124, 58, 237, 0.16), transparent 60%),
                radial-gradient(ellipse 55% 45% at 85% 65%, rgba(0, 212, 255, 0.13), transparent 60%),
                linear-gradient(180deg, rgba(2, 8, 24, 0.2) 0%, rgba(5, 14, 42, 0.75) 40%, rgba(2, 8, 24, 0.95) 100%);
        }

        /* Ambient floating glow orbs */
        .benefits-bg-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.85;
        }

        .glow-left {
            top: 15%;
            left: -80px;
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.28) 0%, rgba(124, 58, 237, 0.15) 50%, transparent 70%);
            animation: floatGlow1 8s ease-in-out infinite alternate;
        }

        .glow-right {
            bottom: 10%;
            right: -80px;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.3) 0%, rgba(0, 212, 255, 0.15) 50%, transparent 70%);
            animation: floatGlow2 9s ease-in-out infinite alternate;
        }

        .glow-center {
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 350px;
            background: radial-gradient(ellipse, rgba(0, 212, 255, 0.12) 0%, rgba(124, 58, 237, 0.08) 50%, transparent 75%);
            animation: pulseGlow 6s ease-in-out infinite;
        }

        @keyframes floatGlow1 {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(40px, 30px) scale(1.1); }
        }

        @keyframes floatGlow2 {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-35px, -30px) scale(1.08); }
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.6; transform: translate(-50%, -50%) scale(1); }
            50%      { opacity: 0.9; transform: translate(-50%, -50%) scale(1.1); }
        }

        /* Cyber grid overlay */
        .benefits-cyber-grid {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(to right, rgba(0, 212, 255, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 212, 255, 0.04) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(ellipse 80% 65% at 50% 50%, black 30%, transparent 80%);
            -webkit-mask-image: radial-gradient(ellipse 80% 65% at 50% 50%, black 30%, transparent 80%);
            pointer-events: none;
            z-index: 0;
        }

        /* Tech circuit lines on left and right */
        .tech-circuit-left, .tech-circuit-right {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 140px;
            height: 340px;
            pointer-events: none;
            z-index: 1;
            opacity: 0.65;
        }
        .tech-circuit-left {
            left: 2%;
        }
        .tech-circuit-right {
            right: 2%;
        }
        @media (max-width: 1200px) {
            .tech-circuit-left, .tech-circuit-right {
                display: block;
                width: 90px;
                height: 220px;
                opacity: 0.35;
                pointer-events: none;
            }
            .tech-circuit-left { left: 0; }
            .tech-circuit-right { right: 0; }
        }
        @media (max-width: 640px) {
            .tech-circuit-left, .tech-circuit-right {
                width: 60px;
                height: 160px;
                opacity: 0.20;
            }
        }

        /* Content Section */
        .content-card {
            background: 
                radial-gradient(ellipse 80% 50% at 50% 0%, rgba(0, 212, 255, 0.12), transparent 70%),
                radial-gradient(ellipse 60% 40% at 85% 100%, rgba(124, 58, 237, 0.15), transparent 60%),
                rgba(8, 18, 48, 0.85);
            border: 1px solid rgba(0, 212, 255, 0.22);
            border-radius: 28px;
            padding: 48px;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            animation: fadeUp 1s ease forwards;
            animation-delay: 0.2s;
            opacity: 0;
            position: relative;
            z-index: 2;
            box-shadow: 
                0 25px 60px rgba(0, 0, 0, 0.6),
                0 0 45px rgba(0, 212, 255, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            overflow: hidden;
        }

        /* Shimmering top glowing line */
        .content-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 15%;
            right: 15%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan-neon), var(--purple-neon), transparent);
            box-shadow: 0 0 12px var(--cyan-neon);
        }

        .content-section-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 28px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 14px;
            letter-spacing: -0.5px;
        }
        .content-title-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(124, 58, 237, 0.25));
            border: 1px solid rgba(0, 212, 255, 0.35);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--cyan-neon);
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.25);
            flex-shrink: 0;
        }
        
        .benefits-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 44px;
        }
        .benefit-item {
            display: flex;
            align-items: center;
            gap: 18px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.04), rgba(0, 212, 255, 0.02));
            padding: 22px 24px;
            border-radius: 18px;
            border: 1px solid rgba(0, 212, 255, 0.14);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        .benefit-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--cyan-neon), var(--purple-neon));
            opacity: 0.4;
            transition: opacity 0.3s, width 0.3s;
        }
        .benefit-item:hover {
            border-color: rgba(0, 212, 255, 0.45);
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.08), rgba(124, 58, 237, 0.06));
            transform: translateX(8px);
            box-shadow: 0 10px 30px rgba(0, 212, 255, 0.18), 0 0 20px rgba(0, 212, 255, 0.1);
        }
        .benefit-item:hover::before {
            opacity: 1;
            width: 4px;
        }
        .benefit-icon {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.18), rgba(124, 58, 237, 0.18));
            color: var(--cyan-neon);
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 14px rgba(0, 212, 255, 0.2);
            transition: transform 0.3s;
        }
        .benefit-item:hover .benefit-icon {
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.4);
        }
        .benefit-text {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.55;
            align-self: center;
            font-weight: 500;
            flex: 1;
            min-width: 0;
            word-break: break-word;
        }

        /* CTA Section */
        .cta-box {
            margin-top: 40px;
            padding: 44px 36px;
            background: 
                radial-gradient(circle at 50% 0%, rgba(0, 212, 255, 0.24), transparent 75%),
                radial-gradient(circle at 85% 100%, rgba(124, 58, 237, 0.22), transparent 70%),
                linear-gradient(135deg, rgba(8, 20, 56, 0.95), rgba(24, 12, 54, 0.95));
            border: 1px solid rgba(0, 212, 255, 0.38);
            border-radius: 22px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 16px 45px rgba(0, 0, 0, 0.55),
                0 0 35px rgba(0, 212, 255, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }
        .cta-box::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan-neon), var(--purple-neon), transparent);
            box-shadow: 0 0 12px var(--cyan-neon);
        }
        .cta-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 24px; font-weight: 800; margin-bottom: 12px;
            letter-spacing: -0.5px;
            color: #ffffff;
        }
        .cta-desc {
            font-size: 15px; color: var(--text-muted); margin-bottom: 28px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }
        .btn-cta {
            display: inline-flex; align-items: center; gap: 10px;
            background: #ffffff; color: #020818;
            font-weight: 700; font-size: 15px;
            padding: 14px 32px; border-radius: 100px; text-decoration: none;
            box-shadow: 0 4px 20px rgba(0, 212, 255, 0.35);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-cta:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 30px rgba(0, 212, 255, 0.55), 0 0 25px rgba(124, 58, 237, 0.35);
            color: #000;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 1024px) {
            .hero-tech-floating {
                display: block;
                width: 65px;
                height: 190px;
                opacity: 0.40;
                pointer-events: none;
            }
            .hero-tech-floating.left-side { left: 8px; }
            .hero-tech-floating.right-side { right: 8px; }
            .solutions-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .solution-card { min-height: 180px; padding: 22px 18px 18px; }
            .solution-top { gap: 10px; margin-bottom: 14px; }
            .solution-icon { width: 36px; height: 36px; }
            .solution-icon svg { width: 17px; height: 17px; }
            .solution-number { font-size: 26px; }
            .solution-card-title { font-size: 14px; }
            .solution-card-desc { font-size: 12px; }
            .detail-container {
                flex-direction: column-reverse;
                gap: 40px;
                text-align: center;
                margin-top: 30px;
            }
            .detail-header { text-align: center; }
            .detail-icon-wrap { margin: 0 auto 24px; }
            .detail-desc { margin: 0 auto; max-width: 100%; }
        }
        @media (max-width: 640px) {
            .hero-tech-floating {
                width: 45px;
                height: 140px;
                opacity: 0.22;
            }
            .hero-tech-floating.left-side { left: 2px; }
            .hero-tech-floating.right-side { right: 2px; }
            .detail-container {
                gap: 32px;
                margin-top: 20px;
            }
            .solutions-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .solution-card { min-height: auto; padding: 14px 10px 12px; border-radius: 12px; }
            .solution-top { gap: 8px; margin-bottom: 10px; }
            .solution-icon { width: 30px; height: 30px; border-radius: 8px; }
            .solution-icon svg { width: 14px; height: 14px; }
            .solution-number { font-size: 20px; }
            .solution-card-title { font-size: 12px; margin-bottom: 4px; }
            .solution-card-desc { font-size: 10px; line-height: 1.4; }
            .solution-glow-line { padding-top: 8px; }
            .solution-card::after { width: 30px; height: 30px; }
            .solutions-title { font-size: 20px; }
            .solutions-subtitle { font-size: 12px; }
            .solutions-header { margin-bottom: 16px; }
            .content-card { padding: 24px; }
        }
        @media (max-width: 380px) {
            .solutions-grid { gap: 6px; }
            .solution-card { padding: 10px 8px 10px; border-radius: 10px; }
            .solution-top { gap: 6px; margin-bottom: 8px; }
            .solution-icon { width: 24px; height: 24px; border-radius: 6px; }
            .solution-icon svg { width: 12px; height: 12px; }
            .solution-number { font-size: 16px; }
            .solution-card-title { font-size: 10px; margin-bottom: 3px; }
            .solution-card-desc { font-size: 9px; line-height: 1.35; }
            .solution-glow-line { padding-top: 6px; }
            .solution-card::after { width: 22px; height: 22px; }
            .solutions-title { font-size: 18px; }
            .solutions-subtitle { font-size: 11px; }
        }
        /* ==========================================================================
           RESPONSIVE: "Bagaimana Layanan Ini Membantu?" & "Siap Mengimplementasikan Solusi Ini?"
           (Desktop > 1024px untouched. Tablet/iPad & Mobile optimized)
           ========================================================================== */

        /* --- iPad Pro & Tablet Landscape (1024px) --- */
        @media (max-width: 1024px) {
            .benefits-section-wrap {
                padding: 44px 0 85px;
                overflow: hidden;
            }
            .benefits-section-wrap .content-card {
                padding: 38px 32px;
                border-radius: 24px;
            }
            .benefits-section-wrap .content-section-title {
                font-size: 24px;
                gap: 13px;
                margin-bottom: 24px;
                letter-spacing: -0.4px;
            }
            .benefits-section-wrap .content-title-icon {
                width: 38px;
                height: 38px;
                border-radius: 11px;
            }
            .benefits-section-wrap .content-title-icon svg {
                width: 20px;
                height: 20px;
            }
            .benefits-section-wrap .benefits-list {
                gap: 14px;
                margin-bottom: 34px;
            }
            .benefits-section-wrap .benefit-item {
                padding: 18px 22px;
                gap: 16px;
                border-radius: 16px;
                flex-direction: row;
                align-items: center;
                text-align: left;
            }
            .benefits-section-wrap .benefit-icon {
                width: 34px;
                height: 34px;
                border-radius: 9px;
                flex-shrink: 0;
            }
            .benefits-section-wrap .benefit-icon svg {
                width: 17px;
                height: 17px;
            }
            .benefits-section-wrap .benefit-text {
                font-size: 15px;
                line-height: 1.55;
                text-align: left;
            }
            /* CTA Box */
            .benefits-section-wrap .cta-box {
                margin-top: 32px;
                padding: 34px 28px;
                border-radius: 20px;
            }
            .benefits-section-wrap .cta-title {
                font-size: 22px;
                margin-bottom: 10px;
                letter-spacing: -0.4px;
            }
            .benefits-section-wrap .cta-desc {
                font-size: 14.5px;
                margin-bottom: 22px;
                max-width: 560px;
                line-height: 1.55;
            }
            .benefits-section-wrap .btn-cta {
                font-size: 14.5px;
                padding: 13px 28px;
                gap: 9px;
            }
        }

        /* --- iPad Air & Tablet Portrait (820px / 768px) --- */
        @media (max-width: 820px) {
            .benefits-section-wrap {
                padding: 38px 0 90px;
            }
            .benefits-section-wrap .content-card {
                padding: 30px 24px;
                border-radius: 20px;
            }
            .benefits-section-wrap .content-section-title {
                font-size: 22px;
                gap: 12px;
                margin-bottom: 20px;
            }
            .benefits-section-wrap .content-title-icon {
                width: 34px;
                height: 34px;
                border-radius: 10px;
            }
            .benefits-section-wrap .content-title-icon svg {
                width: 18px;
                height: 18px;
            }
            .benefits-section-wrap .benefits-list {
                gap: 12px;
                margin-bottom: 30px;
            }
            .benefits-section-wrap .benefit-item {
                padding: 15px 18px;
                gap: 14px;
                border-radius: 14px;
                flex-direction: row;
                align-items: center;
                text-align: left;
            }
            .benefits-section-wrap .benefit-icon {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                flex-shrink: 0;
            }
            .benefits-section-wrap .benefit-icon svg {
                width: 16px;
                height: 16px;
            }
            .benefits-section-wrap .benefit-text {
                font-size: 14px;
                line-height: 1.52;
                text-align: left;
            }
            /* CTA Box */
            .benefits-section-wrap .cta-box {
                margin-top: 26px;
                padding: 28px 22px;
                border-radius: 18px;
            }
            .benefits-section-wrap .cta-title {
                font-size: 20px;
                margin-bottom: 8px;
            }
            .benefits-section-wrap .cta-desc {
                font-size: 13.5px;
                margin-bottom: 18px;
                max-width: 480px;
                line-height: 1.52;
            }
            .benefits-section-wrap .btn-cta {
                font-size: 13.5px;
                padding: 12px 24px;
                gap: 8px;
            }
            .tech-circuit-left, .tech-circuit-right {
                width: 70px;
                height: 180px;
                opacity: 0.25;
            }
        }

        /* --- Large Mobile & Small Tablet (640px) --- */
        @media (max-width: 640px) {
            .benefits-section-wrap {
                padding: 32px 0 92px;
            }
            .benefits-section-wrap .content-card {
                padding: 24px 18px;
                border-radius: 18px;
            }
            .benefits-section-wrap .content-section-title {
                font-size: 20px;
                gap: 11px;
                margin-bottom: 18px;
            }
            .benefits-section-wrap .content-title-icon {
                width: 32px;
                height: 32px;
                border-radius: 9px;
            }
            .benefits-section-wrap .content-title-icon svg {
                width: 17px;
                height: 17px;
            }
            .benefits-section-wrap .benefits-list {
                gap: 11px;
                margin-bottom: 24px;
            }
            .benefits-section-wrap .benefit-item {
                padding: 13px 15px;
                gap: 13px;
                border-radius: 13px;
                flex-direction: row;
                align-items: center;
                text-align: left;
            }
            .benefits-section-wrap .benefit-icon {
                width: 30px;
                height: 30px;
                border-radius: 8px;
                flex-shrink: 0;
            }
            .benefits-section-wrap .benefit-icon svg {
                width: 15px;
                height: 15px;
            }
            .benefits-section-wrap .benefit-text {
                font-size: 13.5px;
                line-height: 1.5;
                text-align: left;
            }
            /* CTA Box */
            .benefits-section-wrap .cta-box {
                margin-top: 22px;
                padding: 22px 18px;
                border-radius: 16px;
            }
            .benefits-section-wrap .cta-title {
                font-size: 18px;
                margin-bottom: 8px;
            }
            .benefits-section-wrap .cta-desc {
                font-size: 13px;
                margin-bottom: 16px;
                line-height: 1.5;
            }
            .benefits-section-wrap .btn-cta {
                font-size: 13px;
                padding: 11px 22px;
                gap: 8px;
            }
        }

        /* --- Standard Mobile (480px / 430px / 390px) --- */
        @media (max-width: 480px) {
            .benefits-section-wrap {
                padding: 26px 0 95px;
            }
            .benefits-section-wrap .content-card {
                padding: 20px 14px;
                border-radius: 16px;
            }
            .benefits-section-wrap .content-section-title {
                font-size: 18px;
                gap: 10px;
                margin-bottom: 16px;
                letter-spacing: -0.3px;
                text-align: left;
            }
            .benefits-section-wrap .content-title-icon {
                width: 30px;
                height: 30px;
                border-radius: 8px;
                flex-shrink: 0;
            }
            .benefits-section-wrap .content-title-icon svg {
                width: 16px;
                height: 16px;
            }
            .benefits-section-wrap .benefits-list {
                gap: 10px;
                margin-bottom: 22px;
            }
            .benefits-section-wrap .benefit-item {
                padding: 12px 13px;
                gap: 12px;
                border-radius: 12px;
                flex-direction: row;
                align-items: center;
                text-align: left;
            }
            .benefits-section-wrap .benefit-icon {
                width: 28px;
                height: 28px;
                border-radius: 7px;
                flex-shrink: 0;
            }
            .benefits-section-wrap .benefit-icon svg {
                width: 14px;
                height: 14px;
            }
            .benefits-section-wrap .benefit-text {
                font-size: 13px;
                line-height: 1.48;
                text-align: left;
            }
            /* CTA Box: compact, tidy, proportional */
            .benefits-section-wrap .cta-box {
                margin-top: 20px;
                padding: 18px 14px;
                border-radius: 15px;
            }
            .benefits-section-wrap .cta-title {
                font-size: 16.5px;
                margin-bottom: 6px;
                letter-spacing: -0.2px;
            }
            .benefits-section-wrap .cta-desc {
                font-size: 12px;
                margin-bottom: 15px;
                line-height: 1.46;
                padding: 0 4px;
            }
            .benefits-section-wrap .btn-cta {
                font-size: 12.5px;
                padding: 10px 20px;
                gap: 7px;
                width: 100%;
                max-width: 280px;
                margin: 0 auto;
                justify-content: center;
            }
            .benefits-section-wrap .btn-cta svg {
                width: 15px;
                height: 15px;
            }
            .tech-circuit-left, .tech-circuit-right {
                display: none;
            }
        }

        /* --- Smaller Mobile (375px / 360px) --- */
        @media (max-width: 375px) {
            .benefits-section-wrap {
                padding: 22px 0 95px;
            }
            .benefits-section-wrap .content-card {
                padding: 16px 12px;
                border-radius: 14px;
            }
            .benefits-section-wrap .content-section-title {
                font-size: 16.5px;
                gap: 8px;
                margin-bottom: 14px;
            }
            .benefits-section-wrap .content-title-icon {
                width: 26px;
                height: 26px;
                border-radius: 7px;
            }
            .benefits-section-wrap .content-title-icon svg {
                width: 14px;
                height: 14px;
            }
            .benefits-section-wrap .benefits-list {
                gap: 8px;
                margin-bottom: 18px;
            }
            .benefits-section-wrap .benefit-item {
                padding: 10px 11px;
                gap: 10px;
                border-radius: 11px;
                flex-direction: row;
                align-items: center;
                text-align: left;
            }
            .benefits-section-wrap .benefit-icon {
                width: 26px;
                height: 26px;
                border-radius: 6px;
                flex-shrink: 0;
            }
            .benefits-section-wrap .benefit-icon svg {
                width: 13px;
                height: 13px;
            }
            .benefits-section-wrap .benefit-text {
                font-size: 12px;
                line-height: 1.45;
                text-align: left;
            }
            /* CTA Box */
            .benefits-section-wrap .cta-box {
                margin-top: 16px;
                padding: 16px 11px;
                border-radius: 14px;
            }
            .benefits-section-wrap .cta-title {
                font-size: 15px;
                margin-bottom: 6px;
            }
            .benefits-section-wrap .cta-desc {
                font-size: 11.5px;
                margin-bottom: 13px;
                line-height: 1.42;
            }
            .benefits-section-wrap .btn-cta {
                font-size: 11.5px;
                padding: 9px 16px;
                gap: 6px;
                width: 100%;
                max-width: 250px;
                margin: 0 auto;
                justify-content: center;
            }
            .benefits-section-wrap .btn-cta svg {
                width: 14px;
                height: 14px;
            }
        }

        /* --- Ultra-Narrow Mobile (≤ 330px, e.g., Galaxy Fold 320px) --- */
        @media (max-width: 330px) {
            .benefits-section-wrap {
                padding: 18px 0 95px;
            }
            .benefits-section-wrap .content-card {
                padding: 14px 9px;
                border-radius: 12px;
            }
            .benefits-section-wrap .content-section-title {
                font-size: 15px;
                gap: 7px;
                margin-bottom: 12px;
            }
            .benefits-section-wrap .content-title-icon {
                width: 24px;
                height: 24px;
                border-radius: 6px;
            }
            .benefits-section-wrap .content-title-icon svg {
                width: 13px;
                height: 13px;
            }
            .benefits-section-wrap .benefits-list {
                gap: 7px;
                margin-bottom: 16px;
            }
            .benefits-section-wrap .benefit-item {
                padding: 9px 9px;
                gap: 8px;
                border-radius: 9px;
                flex-direction: row;
                align-items: center;
                text-align: left;
            }
            .benefits-section-wrap .benefit-icon {
                width: 24px;
                height: 24px;
                border-radius: 6px;
                flex-shrink: 0;
            }
            .benefits-section-wrap .benefit-icon svg {
                width: 12px;
                height: 12px;
            }
            .benefits-section-wrap .benefit-text {
                font-size: 11px;
                line-height: 1.4;
                text-align: left;
            }
            /* CTA Box */
            .benefits-section-wrap .cta-box {
                margin-top: 14px;
                padding: 13px 9px;
                border-radius: 12px;
            }
            .benefits-section-wrap .cta-title {
                font-size: 14px;
                margin-bottom: 5px;
            }
            .benefits-section-wrap .cta-desc {
                font-size: 10.5px;
                margin-bottom: 11px;
                line-height: 1.38;
            }
            .benefits-section-wrap .btn-cta {
                font-size: 10.5px;
                padding: 8px 12px;
                gap: 5px;
                width: 100%;
                max-width: 220px;
                margin: 0 auto;
                justify-content: center;
            }
            .benefits-section-wrap .btn-cta svg {
                width: 13px;
                height: 13px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="/#layanan" class="btn-back">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Layanan
        </a>

    </nav>

    <!-- Hero & Solutions Super-Wrapper (Neural Aurora & Constellation Tech Matrix) -->
    <section class="service-hero-solutions-wrapper">
        {{-- Ambient Aurora Glow Orbs --}}
        <div class="hero-aurora-glow aurora-cyan" aria-hidden="true"></div>
        <div class="hero-aurora-glow aurora-purple" aria-hidden="true"></div>
        <div class="hero-aurora-glow aurora-indigo" aria-hidden="true"></div>

        {{-- Constellation Dot Matrix Grid --}}
        <div class="constellation-dot-matrix" aria-hidden="true"></div>

        {{-- Floating Tech Geometry Accents on Sides --}}
        <div class="hero-tech-floating left-side" aria-hidden="true">
            <svg viewBox="0 0 90 260" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%;">
                <circle cx="20" cy="30" r="3" fill="var(--cyan-neon)" opacity="0.85"/>
                <circle cx="20" cy="30" r="8" stroke="var(--cyan-neon)" stroke-width="1" stroke-dasharray="2 3" opacity="0.4"/>
                <line x1="20" y1="38" x2="20" y2="100" stroke="rgba(0, 212, 255, 0.25)" stroke-width="1" stroke-dasharray="3 3"/>
                <polygon points="20,110 26,116 20,122 14,116" fill="none" stroke="var(--purple-neon)" stroke-width="1.5" opacity="0.75"/>
                <line x1="20" y1="122" x2="20" y2="185" stroke="rgba(124, 58, 237, 0.2)" stroke-width="1"/>
                <circle cx="20" cy="185" r="2.5" fill="var(--cyan-neon)" opacity="0.7"/>
                <path d="M20 185 L48 213 H72" stroke="rgba(0, 212, 255, 0.35)" stroke-width="1.2"/>
                <circle cx="72" cy="213" r="3" fill="var(--cyan-neon)"/>
            </svg>
        </div>
        <div class="hero-tech-floating right-side" aria-hidden="true">
            <svg viewBox="0 0 90 260" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%;">
                <circle cx="70" cy="30" r="3" fill="var(--purple-neon)" opacity="0.85"/>
                <polygon points="70,68 77,75 70,82 63,75" fill="none" stroke="var(--cyan-neon)" stroke-width="1.5" opacity="0.75"/>
                <line x1="70" y1="82" x2="70" y2="150" stroke="rgba(0, 212, 255, 0.25)" stroke-width="1" stroke-dasharray="3 3"/>
                <circle cx="70" cy="150" r="8" stroke="var(--purple-neon)" stroke-width="1" stroke-dasharray="2 2" opacity="0.45"/>
                <circle cx="70" cy="150" r="2.5" fill="var(--purple-neon)" opacity="0.85"/>
                <path d="M70 150 L42 178 H18" stroke="rgba(124, 58, 237, 0.35)" stroke-width="1.2"/>
                <circle cx="18" cy="178" r="3" fill="var(--purple-neon)"/>
            </svg>
        </div>

        <!-- Detail Container -->
        <div class="detail-container">
            <!-- Header -->
            <div class="detail-header">
                <div class="detail-icon-wrap">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $service['icon'] }}"></path>
                    </svg>
                </div>
                <div class="badge-layanan">
                    <span class="badge-pulse-dot"></span>
                    Layanan AI
                </div>
                <h1 class="detail-title">{{ $service['title'] }}</h1>
                <p class="detail-desc">{{ $service['desc'] }}</p>
            </div>
            <!-- Dynamic Image -->
            <div class="detail-image-wrap">
                <div class="tech-corner corner-tl"></div>
                <div class="tech-corner corner-tr"></div>
                <div class="tech-corner corner-bl"></div>
                <div class="tech-corner corner-br"></div>
                @php
                    $imageSrc = asset('images/services/' . $service['slug'] . '.jpg');
                    if (!empty($service['image'])) {
                        $imageSrc = Storage::url($service['image']);
                    }
                @endphp
                <img src="{{ $imageSrc }}" alt="Ilustrasi Layanan {{ $service['title'] }}">
                <div class="image-glow"></div>
                <div class="hero-image-status-pill">
                    <span class="status-indicator-ping"></span>
                    Solusi AI Terverifikasi
                </div>
            </div>
        </div>{{-- close detail-container for hero --}}

        {{-- Neural Horizon Beam (Divider between Hero and Solutions) --}}
        <div class="neural-horizon-beam" aria-hidden="true">
            <div class="horizon-beam-line"></div>
            <div class="horizon-beam-core"></div>
        </div>

        <!-- Solutions Section (wider container) -->
        @if(isset($service['solutions']) && count($service['solutions']) > 0)
        <div class="solutions-wrapper">
            <div class="solutions-section">
                <div class="solutions-header">
                    <div class="solutions-pill">
                        <span class="pill-dot"></span>
                        KAPABILITAS &amp; FITUR
                    </div>
                    <h2 class="solutions-title">Solusi yang Kami Hadirkan</h2>
                    <p class="solutions-subtitle">Kami membantu mengubah kebutuhan bisnis menjadi solusi AI yang dapat diterapkan sesuai proses kerja Anda.</p>
                </div>
                <div class="solutions-grid">
                    @php
                    $solutionIcons = [
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>',
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>',
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>'
                    ];
                    @endphp
                    @foreach($service['solutions'] as $index => $solution)
                    <div class="solution-card">
                        <div class="solution-top">
                            <div class="solution-icon">
                                {!! $solutionIcons[$index] ?? $solutionIcons[0] !!}
                            </div>
                            <div class="solution-number">0{{ $index + 1 }}</div>
                        </div>
                        <h3 class="solution-card-title">{{ $solution['title'] }}</h3>
                        <p class="solution-card-desc">{{ $solution['desc'] }}</p>
                        <div class="solution-glow-line"><span></span></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </section>

    <!-- Benefits & CTA Section (With Ambient Glowing Tech Background) -->
    <section class="benefits-section-wrap">
        {{-- Ambient Glow Orbs & Cyber Grid --}}
        <div class="benefits-bg-glow glow-left" aria-hidden="true"></div>
        <div class="benefits-bg-glow glow-right" aria-hidden="true"></div>
        <div class="benefits-bg-glow glow-center" aria-hidden="true"></div>
        <div class="benefits-cyber-grid" aria-hidden="true"></div>

        {{-- Tech Circuit SVG Accents on Sides --}}
        <div class="tech-circuit-left" aria-hidden="true">
            <svg viewBox="0 0 140 340" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%;">
                <path d="M10 20 H70 L110 60 V180 L80 210 H20" stroke="url(#c-grad-1)" stroke-width="1.5" stroke-dasharray="4 4" opacity="0.75"/>
                <circle cx="70" cy="20" r="3.5" fill="var(--cyan-neon)"/>
                <circle cx="110" cy="120" r="4" fill="var(--purple-neon)"/>
                <circle cx="80" cy="210" r="3" fill="var(--cyan-neon)"/>
                <path d="M30 100 H85 L120 135 V280" stroke="url(#c-grad-2)" stroke-width="1.2" opacity="0.6"/>
                <circle cx="120" cy="280" r="3.5" fill="var(--cyan-neon)"/>
                <defs>
                    <linearGradient id="c-grad-1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="var(--cyan-neon)"/>
                        <stop offset="100%" stop-color="var(--purple-neon)"/>
                    </linearGradient>
                    <linearGradient id="c-grad-2" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="var(--purple-neon)"/>
                        <stop offset="100%" stop-color="var(--cyan-neon)"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <div class="tech-circuit-right" aria-hidden="true">
            <svg viewBox="0 0 140 340" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%;transform:scaleX(-1);">
                <path d="M10 20 H70 L110 60 V180 L80 210 H20" stroke="url(#c-grad-3)" stroke-width="1.5" stroke-dasharray="4 4" opacity="0.75"/>
                <circle cx="70" cy="20" r="3.5" fill="var(--cyan-neon)"/>
                <circle cx="110" cy="120" r="4" fill="var(--purple-neon)"/>
                <circle cx="80" cy="210" r="3" fill="var(--cyan-neon)"/>
                <path d="M30 100 H85 L120 135 V280" stroke="url(#c-grad-4)" stroke-width="1.2" opacity="0.6"/>
                <circle cx="120" cy="280" r="3.5" fill="var(--cyan-neon)"/>
                <defs>
                    <linearGradient id="c-grad-3" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="var(--purple-neon)"/>
                        <stop offset="100%" stop-color="var(--cyan-neon)"/>
                    </linearGradient>
                    <linearGradient id="c-grad-4" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="var(--cyan-neon)"/>
                        <stop offset="100%" stop-color="var(--purple-neon)"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <div class="detail-container" style="margin-top: 0; position:relative; z-index:2;">
            <!-- Content -->
            <div class="content-card">
                @if(!empty($service['benefits']) && count(array_filter($service['benefits'])) > 0)
                <h2 class="content-section-title">
                    <span class="content-title-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 16v-4"></path>
                            <path d="M12 8h.01"></path>
                        </svg>
                    </span>
                    Bagaimana Layanan Ini Membantu?
                </h2>
                
                <ul class="benefits-list">
                    @foreach($service['benefits'] as $benefit)
                    @if(!empty(trim($benefit)))
                    <li class="benefit-item">
                        <div class="benefit-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div class="benefit-text">
                            {{ $benefit }}
                        </div>
                    </li>
                    @endif
                    @endforeach
                </ul>
                @endif

                <!-- CTA -->
                <div class="cta-box">
                    <h3 class="cta-title">Siap Mengimplementasikan Solusi Ini?</h3>
                    <p class="cta-desc">Hubungi tim konsultan kami untuk mengetahui implementasi teknis dan use-case spesifik untuk industri Anda.</p>
                    <a href="{{ $waUrl }}" class="btn-cta" target="_blank" rel="noopener noreferrer">
                        Konsultasikan Kebutuhan Anda
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

{{-- WhatsApp Chat Widget --}}
@include('partials.wa-widget')

</body>
</html>
