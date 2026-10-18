@php
    $cmsContact = \App\Models\CmsContact::first();
    $waNumber = preg_replace('/[^0-9]/', '', optional($cmsContact)->whatsapp ?? '628XXXXXXXXXX');
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    }
    $waMessage = 'Halo, saya ingin berkonsultasi mengenai layanan ' . $service['title'] . '.';
    $waUrl     = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waMessage);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service['title'] }} - AI Jogja Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Dark & Light Mode Script & Styles --}}
    @include('partials.theme-script')

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

        html[data-theme="light"] .detail-title {
            color: #0f172a;
        }

        html[data-theme="light"] .detail-desc {
            color: #475569;
        }

        html[data-theme="light"] .badge-layanan {
            background: rgba(2, 132, 199, 0.08);
            border-color: rgba(2, 132, 199, 0.25);
            color: #0284c7;
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

        html[data-theme="light"] .solutions-title {
            color: #0f172a;
        }

        html[data-theme="light"] .solutions-subtitle {
            color: #475569;
        }

        html[data-theme="light"] .solution-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.12);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        }

        html[data-theme="light"] .solution-card:hover {
            border-color: #0284c7;
            box-shadow: 0 12px 32px rgba(2, 132, 199, 0.12);
        }

        html[data-theme="light"] .solution-card-title {
            color: #0f172a;
        }

        html[data-theme="light"] .solution-card-desc {
            color: #475569;
        }

        html[data-theme="light"] .solution-icon {
            background: rgba(2, 132, 199, 0.08);
            border-color: rgba(2, 132, 199, 0.2);
            color: #0284c7;
        }

        html[data-theme="light"] .solution-number {
            background: linear-gradient(135deg, rgba(2, 132, 199, 0.35), rgba(124, 58, 237, 0.25));
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
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--cyan-neon), var(--purple-neon));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.4);
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

        /* Detail Container */
        .detail-container {
            max-width: 1200px;
            margin: 40px auto 60px;
            padding: 0 5%;
            position: relative;
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
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 100px;
            margin-bottom: 24px;
        }
        .detail-icon-wrap {
            width: 80px;
            height: 80px;
            margin: 0 0 24px 0;
            background: linear-gradient(135deg, rgba(0,212,255,0.1), rgba(124,58,237,0.1));
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--cyan-neon);
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.15);
            position: relative;
        }
        .detail-icon-wrap::after {
            content: ''; position: absolute; inset: -1px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--cyan-neon), var(--purple-neon));
            opacity: 0.3; z-index: -1; filter: blur(10px);
        }
        .detail-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(32px, 4vw, 48px);
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .detail-desc {
            font-size: clamp(16px, 2vw, 20px);
            color: var(--text-muted);
            line-height: 1.6;
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
            border: 1px solid rgba(0, 212, 255, 0.15);
            box-shadow: 0 12px 40px rgba(0, 212, 255, 0.1);
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
            box-shadow: inset 0 0 40px rgba(0,0,0,0.5);
            pointer-events: none;
            border-radius: 24px;
        }

        /* Solutions Section */
        .solutions-wrapper {
            max-width: 1280px;
            margin: 0 auto 48px;
            padding: 0 5%;
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
        .solutions-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }
        .solutions-subtitle {
            font-size: 15px;
            color: var(--text-muted);
            max-width: 560px;
            margin: 0 auto;
            line-height: 1.6;
        }
        .solutions-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .solution-card {
            background: rgba(10, 20, 50, 0.5);
            border: 1px solid rgba(0, 212, 255, 0.12);
            border-radius: 18px;
            padding: 28px 24px 24px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 220px;
        }
        /* Subtle radial glow behind card on hover */
        .solution-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at top right, rgba(124, 58, 237, 0.12), transparent 65%);
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
            border-top: 2px solid rgba(0, 212, 255, 0.15);
            border-right: 2px solid rgba(0, 212, 255, 0.15);
            border-radius: 0 18px 0 0;
            pointer-events: none;
            transition: border-color 0.35s ease;
        }
        .solution-card:hover {
            transform: translateY(-4px);
            border-color: rgba(0, 212, 255, 0.35);
            box-shadow:
                0 8px 32px rgba(0, 212, 255, 0.12),
                0 0 0 1px rgba(0, 212, 255, 0.08);
        }
        .solution-card:hover::before {
            opacity: 1;
        }
        .solution-card:hover::after {
            border-top-color: rgba(0, 212, 255, 0.4);
            border-right-color: rgba(124, 58, 237, 0.4);
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
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.12), rgba(124, 58, 237, 0.10));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--cyan-neon);
            flex-shrink: 0;
            border: 1px solid rgba(0, 212, 255, 0.18);
            box-shadow: 0 0 12px rgba(0, 212, 255, 0.08);
        }
        .solution-icon svg {
            width: 20px;
            height: 20px;
        }
        .solution-number {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.25), rgba(124, 58, 237, 0.18));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
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
            color: rgba(255, 255, 255, 0.6);
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
            opacity: 0.25;
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
            .tech-circuit-left, .tech-circuit-right { display: none; }
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
            .solutions-grid { grid-template-columns: repeat(2, 1fr); }
            .solution-card { min-height: 200px; }
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
            .detail-container {
                gap: 32px;
                margin-top: 20px;
            }
            .solutions-grid { grid-template-columns: 1fr; gap: 16px; }
            .solution-card { min-height: auto; padding: 24px 20px 20px; }
            .solutions-title { font-size: 22px; }
            .content-card { padding: 24px; }
            .cta-box { padding: 32px 20px; }
            .benefit-item { padding: 16px; flex-direction: column; gap: 12px; }
            .benefit-icon { width: 40px; height: 40px; }
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
        @include('partials.theme-toggle')
    </nav>

    <!-- Detail Container -->
    <div class="detail-container">
        
        <!-- Header -->
        <div class="detail-header">
            <div class="detail-icon-wrap">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="{{ $service['icon'] }}"></path>
                </svg>
            </div>
            <div class="badge-layanan">Layanan AI</div>
            <h1 class="detail-title">{{ $service['title'] }}</h1>
            <p class="detail-desc">{{ $service['desc'] }}</p>
        </div>
        <!-- Dynamic Image -->
        <div class="detail-image-wrap">
            @php
                $imageSrc = asset('images/services/' . $service['slug'] . '.jpg');
                if (!empty($service['image'])) {
                    $imageSrc = Storage::url($service['image']);
                }
            @endphp
            <img src="{{ $imageSrc }}" alt="Ilustrasi Layanan {{ $service['title'] }}">
            <div class="image-glow"></div>
        </div>
    </div>{{-- close detail-container for hero --}}

    <!-- Solutions Section (wider container) -->
    @if(isset($service['solutions']) && count($service['solutions']) > 0)
    <div class="solutions-wrapper">
        <div class="solutions-section">
            <div class="solutions-header">
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
