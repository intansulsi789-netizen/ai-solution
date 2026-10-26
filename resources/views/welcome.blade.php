@php
    /**
     * ============================================================
     * KONFIGURASI WHATSAPP
     * Nomor diambil otomatis dari database (CMS Kontak).
     * ============================================================
     */
    $cmsContact = \App\Models\CmsContact::first();
    $waNumber = preg_replace('/[^0-9]/', '', optional($cmsContact)->whatsapp ?? '628XXXXXXXXXX');
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    }
    $waMessage = 'Halo, saya ingin berkonsultasi mengenai kebutuhan solusi AI.';
    $waUrl     = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waMessage);

    $cmsSeo = \App\Models\CmsSeo::first();
    $metaTitle = ($cmsSeo && $cmsSeo->meta_title) ? $cmsSeo->meta_title : 'AI Solution - Platform AI Cerdas untuk Semua Pekerjaan';
    $metaDesc  = ($cmsSeo && $cmsSeo->meta_description) ? $cmsSeo->meta_description : 'AI Solution Engine - Platform AI terdepan untuk semua profesi dan industri. Bekerja lebih cepat, lebih cerdas, dan lebih efisien.';

    $canonicalDomain = 'https://aisolution.duckdns.org';
    $canonicalUrl    = $canonicalDomain . '/';
    $ogImage         = $canonicalDomain . '/images/og-default.jpg';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- Homepage strictly Dark Mode --}}
    <script>
        document.documentElement.setAttribute('data-theme', 'dark');
    </script>

    <style>
        /* ==========================================
           RESET & BASE
        ========================================== */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --navy-900: #020818;
            --navy-800: #050e27;
            --navy-700: #0a1535;
            --navy-600: #0d1a40;
            --blue-electric: #00a8ff;
            --blue-bright: #3b82f6;
            --cyan-neon: #00d4ff;
            --purple-vivid: #7c3aed;
            --purple-light: #a78bfa;
            --pink-accent: #ec4899;
            --white: #ffffff;
            --white-80: rgba(255,255,255,0.80);
            --white-60: rgba(255,255,255,0.60);
            --white-40: rgba(255,255,255,0.40);
            --white-20: rgba(255,255,255,0.20);
            --white-10: rgba(255,255,255,0.10);
            --white-05: rgba(255,255,255,0.05);
            --glass-bg: rgba(10, 20, 50, 0.45);
            --glass-border: rgba(0, 168, 255, 0.20);
            --glow-blue: rgba(0, 168, 255, 0.35);
            --glow-purple: rgba(124, 58, 237, 0.35);
        }

        /* ==========================================
           LIGHT MODE OVERRIDES
        ========================================== */
        html[data-theme="light"] {
            --navy-900: #f8fafc;
            --navy-800: #ffffff;
            --navy-700: #f1f5f9;
            --navy-600: #e2e8f0;
            --white: #0f172a;
            --white-80: #334155;
            --white-60: #475569;
            --white-40: #64748b;
            --white-20: rgba(15, 23, 42, 0.15);
            --white-10: rgba(15, 23, 42, 0.08);
            --white-05: rgba(15, 23, 42, 0.04);
            --glass-bg: rgba(255, 255, 255, 0.92);
            --glass-border: rgba(15, 23, 42, 0.12);
            --glow-blue: rgba(2, 132, 199, 0.15);
            --glow-purple: rgba(124, 58, 237, 0.15);
            --cyan-neon: #0284c7;
            --blue-electric: #2563eb;
            --purple-light: #6d28d9;
        }

        html[data-theme="light"] body {
            background-color: #f8fafc;
            color: #0f172a;
        }

        /* Navbar Light Mode */
        html[data-theme="light"] .navbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        html[data-theme="light"] .navbar.scrolled {
            background: rgba(255, 255, 255, 0.97) !important;
            border-bottom: 1px solid rgba(15, 23, 42, 0.12) !important;
            box-shadow: 0 4px 24px rgba(15, 23, 42, 0.08) !important;
        }

        html[data-theme="light"] .nav-logo-text {
            color: #0f172a;
        }

        html[data-theme="light"] .nav-links a {
            color: #334155;
        }

        html[data-theme="light"] .nav-links a:hover {
            color: #0284c7;
            background: rgba(2, 132, 199, 0.08);
        }

        html[data-theme="light"] .nav-search {
            background: rgba(15, 23, 42, 0.04);
            border-color: rgba(15, 23, 42, 0.12);
            color: #334155;
        }

        html[data-theme="light"] .nav-search-text {
            color: #64748b !important;
        }

        html[data-theme="light"] .nav-search-kbd {
            background: rgba(15, 23, 42, 0.08);
            color: #475569;
            border-color: rgba(15, 23, 42, 0.15);
        }

        html[data-theme="light"] .nav-hamburger {
            background: rgba(15, 23, 42, 0.04);
            border-color: rgba(15, 23, 42, 0.12);
        }

        html[data-theme="light"] .nav-hamburger span {
            background: #0f172a;
        }

        html[data-theme="light"] .nav-mobile-menu {
            background: rgba(255, 255, 255, 0.98);
            border-color: rgba(15, 23, 42, 0.12);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        html[data-theme="light"] .nav-mobile-menu a {
            color: #0f172a;
            border-bottom-color: rgba(15, 23, 42, 0.08);
        }

        /* Hero Light Mode */
        html[data-theme="light"] .hero-bg {
            filter: saturate(1.15) contrast(1.08);
        }

        html[data-theme="light"] .hero-overlay {
            background: linear-gradient(
                135deg,
                rgba(248, 250, 252, 0.58) 0%,
                rgba(241, 245, 249, 0.42) 40%,
                rgba(248, 250, 252, 0.62) 100%
            );
        }

        html[data-theme="light"] .hero-vignette {
            background: radial-gradient(ellipse at center, transparent 40%, rgba(248, 250, 252, 0.50) 100%);
        }

        html[data-theme="light"] .hero-title,
        html[data-theme="light"] .page-title,
        html[data-theme="light"] .jasa-title,
        html[data-theme="light"] .tentang-title {
            background: linear-gradient(135deg, #0f172a 0%, #334155 50%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        html[data-theme="light"] .hero-heading-line1 {
            color: #090d16;
            animation: none;
            text-shadow: 0 1px 16px rgba(255, 255, 255, 0.9), 0 2px 28px rgba(255, 255, 255, 0.75);
        }

        html[data-theme="light"] .hero-heading-line2 {
            background: linear-gradient(90deg, #0284c7 0%, #7c3aed 50%, #db2777 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: hero-gradient-flow 8s linear infinite;
            filter: drop-shadow(0 2px 10px rgba(255, 255, 255, 0.8));
        }

        html[data-theme="light"] .hero-subheading {
            color: #020617;
            font-weight: 500;
            opacity: 1;
            animation: none;
            text-shadow: 0 1px 12px rgba(255, 255, 255, 0.95), 0 0 24px rgba(255, 255, 255, 0.85);
        }

        html[data-theme="light"] .page-subtitle,
        html[data-theme="light"] .jasa-subtitle,
        html[data-theme="light"] .section-desc,
        html[data-theme="light"] .tentang-subtitle {
            color: #475569;
        }

        html[data-theme="light"] .hero-badge {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-color: rgba(2, 132, 199, 0.35);
            color: #0284c7;
            box-shadow: 0 2px 12px rgba(2, 132, 199, 0.12);
        }

        html[data-theme="light"] .hero-bottom-fade {
            background: linear-gradient(to top, #f8fafc 0%, transparent 100%);
        }

        html[data-theme="light"] .scroll-indicator span {
            color: #64748b;
        }

        html[data-theme="light"] .scroll-arrow {
            border-right-color: #64748b;
            border-bottom-color: #64748b;
        }

        html[data-theme="light"] .btn-secondary {
            background: #ffffff;
            color: #0f172a;
            border-color: rgba(15, 23, 42, 0.15);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        html[data-theme="light"] .btn-secondary:hover {
            background: #f1f5f9;
            color: #0284c7;
            border-color: #0284c7;
        }

        /* Flow Diagram & Cards */
        html[data-theme="light"] .section-title {
            color: #0f172a;
        }

        html[data-theme="light"] .flow-card {
            background: #ffffff;
            border-color: rgba(15, 23, 42, 0.12);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        html[data-theme="light"] .flow-card:hover {
            border-color: #0284c7;
            box-shadow: 0 12px 30px rgba(2, 132, 199, 0.12);
        }

        html[data-theme="light"] .flow-card.ai-center {
            background: #ffffff;
            border-color: #0284c7;
            box-shadow: 0 6px 24px rgba(2, 132, 199, 0.15);
        }

        html[data-theme="light"] .flow-icon {
            background: rgba(2, 132, 199, 0.08);
            border-color: rgba(2, 132, 199, 0.2);
            color: #0284c7;
        }

        html[data-theme="light"] .flow-card:hover .flow-icon {
            background: rgba(2, 132, 199, 0.16);
            border-color: #0284c7;
            color: #0369a1;
        }

        html[data-theme="light"] .flow-card.ai-center .flow-icon {
            background: linear-gradient(135deg, #00a8ff 0%, #7c3aed 100%);
            color: #ffffff;
            border: none;
        }

        html[data-theme="light"] .flow-text {
            color: #0f172a;
        }

        html[data-theme="light"] .flow-arrow {
            color: #0284c7;
        }

        /* Service Cards */
        html[data-theme="light"] .service-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.10);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        }

        html[data-theme="light"] .service-card:hover {
            background: #ffffff;
            border-color: #0284c7;
            box-shadow: 0 16px 36px rgba(2, 132, 199, 0.12);
        }

        html[data-theme="light"] .card-title,
        html[data-theme="light"] .service-title {
            color: #0f172a;
        }

        html[data-theme="light"] .card-desc,
        html[data-theme="light"] .service-desc {
            color: #475569;
        }

        html[data-theme="light"] .card-number {
            color: rgba(15, 23, 42, 0.05);
        }

        html[data-theme="light"] .card-icon {
            background: rgba(2, 132, 199, 0.08);
            border-color: rgba(2, 132, 199, 0.25);
            color: #0284c7;
        }

        html[data-theme="light"] .card-btn {
            color: #0284c7;
        }

        /* Jasa / Layanan AI Header Light Mode */
        html[data-theme="light"] .jasa-badge {
            background: rgba(2, 132, 199, 0.10);
            border-color: rgba(2, 132, 199, 0.35);
            color: #0369a1;
            font-weight: 700;
        }

        html[data-theme="light"] .jasa-title {
            background: linear-gradient(135deg, #091224 0%, #0f172a 45%, #0284c7 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            color: #091224;
        }

        html[data-theme="light"] .jasa-subtitle {
            color: #475569;
            font-weight: 500;
        }

        html[data-theme="light"] .modal-box {
            background: #ffffff;
            border-color: rgba(2, 132, 199, 0.3);
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.12);
        }

        html[data-theme="light"] .modal-title-text {
            color: #0f172a;
        }

        html[data-theme="light"] .modal-desc {
            color: #475569;
        }

        html[data-theme="light"] .modal-close {
            background: rgba(15, 23, 42, 0.08);
            color: #334155;
        }

        /* Section divider */
        html[data-theme="light"] .section-divider-line {
            background: linear-gradient(90deg, transparent 0%, rgba(2, 132, 199, 0.25) 30%, rgba(124, 58, 237, 0.20) 70%, transparent 100%);
        }
        html[data-theme="light"] .section-divider-line::after {
            background: radial-gradient(ellipse at center, rgba(2, 132, 199, 0.60) 0%, transparent 80%);
            box-shadow: 0 0 12px rgba(2, 132, 199, 0.35);
        }
        html[data-theme="light"] .section-top-aurora {
            background: radial-gradient(ellipse 75% 100% at 50% 0%, rgba(2, 132, 199, 0.12) 0%, rgba(124, 58, 237, 0.08) 45%, transparent 75%);
            filter: blur(50px);
        }
        html[data-theme="light"] .services-ambient-glow-1,
        html[data-theme="light"] .wawasan-ambient-glow,
        html[data-theme="light"] .tentang-ambient-glow-1,
        html[data-theme="light"] .ambient-glow-blue-left,
        html[data-theme="light"] .konsultasi-ambient-glow-left {
            background: radial-gradient(circle, rgba(2, 132, 199, 0.05) 0%, transparent 65%);
        }
        html[data-theme="light"] .services-ambient-glow-2,
        html[data-theme="light"] .wawasan-ambient-glow-purple,
        html[data-theme="light"] .tentang-ambient-glow-2,
        html[data-theme="light"] .ambient-glow-4,
        html[data-theme="light"] .konsultasi-ambient-glow-right {
            background: radial-gradient(circle, rgba(124, 58, 237, 0.04) 0%, transparent 65%);
        }
        html[data-theme="light"] .partner-ambient-aura {
            background: radial-gradient(ellipse at center, rgba(2, 132, 199, 0.07) 0%, rgba(124, 58, 237, 0.04) 45%, transparent 70%);
        }
        html[data-theme="light"] .tentang-center-glow {
            background: radial-gradient(ellipse 65% 55% at 50% 35%, rgba(2, 132, 199, 0.12) 0%, rgba(124, 58, 237, 0.08) 38%, transparent 75%);
        }
        html[data-theme="light"] .tentang-halo-ring {
            background: radial-gradient(ellipse at center, rgba(2, 132, 199, 0.08) 0%, rgba(124, 58, 237, 0.05) 45%, transparent 70%);
        }
        html[data-theme="light"] .tentang-tech-grid {
            background-image: 
                radial-gradient(circle at center, rgba(2, 132, 199, 0.12) 1.5px, transparent 1.5px),
                linear-gradient(rgba(2, 132, 199, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(2, 132, 199, 0.03) 1px, transparent 1px);
        }
        html[data-theme="light"] .services-grid-mesh,
        html[data-theme="light"] .wawasan-grid-mesh,
        html[data-theme="light"] .section-grid-mesh {
            background-image: radial-gradient(rgba(2, 132, 199, 0.08) 1.2px, transparent 1.2px);
        }
        html[data-theme="light"] .wawasan-section {
            background: radial-gradient(ellipse 90% 60% at 50% 0%, rgba(224, 242, 254, 0.45) 0%, rgba(248, 250, 252, 0) 75%);
        }
        html[data-theme="light"] .wawasan-top-aurora {
            background: radial-gradient(ellipse 75% 100% at 50% 0%, rgba(2, 132, 199, 0.12) 0%, rgba(124, 58, 237, 0.08) 45%, transparent 75%);
            filter: blur(50px);
        }
        html[data-theme="light"] .wawasan-ambient-glow-center {
            background: radial-gradient(ellipse, rgba(2, 132, 199, 0.06) 0%, transparent 70%);
        }
        html[data-theme="light"] .wawasan-title {
            background: linear-gradient(135deg, #0f172a 40%, #0369a1 80%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: none;
        }
        html[data-theme="light"] .ambient-glow-top-bridge {
            background: radial-gradient(ellipse 70% 50% at 50% 50%, rgba(2, 132, 199, 0.08) 0%, transparent 70%);
            filter: blur(60px);
        }

        html[data-theme="light"] .hud-telemetry-badge {
            background: rgba(255, 255, 255, 0.90);
            border: 1px solid rgba(2, 132, 199, 0.22);
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.08);
        }
        html[data-theme="light"] .hud-telemetry-badge-alt {
            border-color: rgba(124, 58, 237, 0.25);
            box-shadow: 0 4px 16px rgba(124, 58, 237, 0.08);
        }
        html[data-theme="light"] .hud-status-text {
            color: #0369a1;
        }
        html[data-theme="light"] .hud-telemetry-badge-alt .hud-status-text {
            color: #7c3aed;
        }
        html[data-theme="light"] .hud-metric-label {
            color: #64748b;
        }
        html[data-theme="light"] .hud-metric-val {
            color: #0f172a;
        }
        html[data-theme="light"] .hud-laser-line {
            background: linear-gradient(90deg, transparent, rgba(2, 132, 199, 0.35), transparent);
        }
        html[data-theme="light"] .hud-laser-purple .hud-laser-line {
            background: linear-gradient(90deg, transparent, rgba(124, 58, 237, 0.35), transparent);
        }

        /* Profile & About */
        html[data-theme="light"] .profile-container {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.12);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        }

        html[data-theme="light"] .profile-name {
            color: #0f172a;
        }

        html[data-theme="light"] .profile-desc {
            color: #475569;
        }

        html[data-theme="light"] .profile-photo {
            border-color: #ffffff;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
            background: #f8fafc;
        }

        html[data-theme="light"] .social-btn {
            background: #f1f5f9;
            border-color: rgba(15, 23, 42, 0.12);
            color: #64748b;
        }

        html[data-theme="light"] .social-btn:hover {
            background: rgba(2, 132, 199, 0.12);
            border-color: #0284c7;
            color: #0284c7;
        }

        /* Logo marquee */
        html[data-theme="light"] .logo-marquee-wrapper {
            background: radial-gradient(ellipse at center, rgba(2, 132, 199, 0.04) 0%, transparent 75%);
        }

        html[data-theme="light"] .logo-marquee-wrapper::before {
            background: linear-gradient(to right, #f8fafc, transparent);
        }

        html[data-theme="light"] .logo-marquee-wrapper::after {
            background: linear-gradient(to left, #f8fafc, transparent);
        }

        html[data-theme="light"] .logo-item {
            background: transparent;
            border: none;
            box-shadow: none;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        html[data-theme="light"] .logo-item:hover {
            border: none;
            box-shadow: none;
            transform: translateY(-2px) scale(1.05);
        }

        html[data-theme="light"] .logo-item img {
            opacity: 0.95;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.08));
        }

        html[data-theme="light"] .logo-item:hover img {
            opacity: 1;
            transform: scale(1.05);
            filter: drop-shadow(0 4px 12px rgba(2, 132, 199, 0.25));
        }

        /* Article Cards (Homepage) */
        html[data-theme="light"] .article-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.10);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        }

        html[data-theme="light"] .article-card:hover {
            border-color: #0284c7;
            box-shadow: 0 16px 36px rgba(2, 132, 199, 0.12);
        }

        html[data-theme="light"] .article-img-wrap {
            background: #f8fafc;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }

        html[data-theme="light"] .article-img {
            filter: none;
            opacity: 1;
        }

        html[data-theme="light"] .article-card:hover .article-img {
            filter: none;
            transform: scale(1.05);
        }

        html[data-theme="light"] .article-title {
            color: #0f172a;
        }

        html[data-theme="light"] .article-desc {
            color: #475569;
        }

        html[data-theme="light"] .article-date {
            color: #64748b;
        }

        html[data-theme="light"] .article-btn {
            color: #0284c7;
        }

        html[data-theme="light"] .article-category {
            background: rgba(2, 132, 199, 0.08);
            border-color: rgba(2, 132, 199, 0.25);
            color: #0284c7;
        }

        /* Konsultasi Section */
        html[data-theme="light"] .konsultasi-card {
            background: #ffffff;
            border: 1px solid rgba(2, 132, 199, 0.25);
            box-shadow: 0 15px 45px rgba(2, 132, 199, 0.08);
        }

        html[data-theme="light"] .konsultasi-bg-aurora {
            background: radial-gradient(ellipse at center, rgba(2, 132, 199, 0.08) 0%, rgba(124, 58, 237, 0.05) 45%, transparent 70%);
        }

        html[data-theme="light"] .konsultasi-grid-mesh {
            background-image: linear-gradient(rgba(2, 132, 199, 0.04) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(2, 132, 199, 0.04) 1px, transparent 1px);
        }

        html[data-theme="light"] .card-corner {
            border-color: rgba(2, 132, 199, 0.35);
        }

        html[data-theme="light"] .radar-wave-ring {
            border-color: rgba(2, 132, 199, 0.25);
        }

        html[data-theme="light"] .radar-wave-ring-2 {
            border-color: rgba(124, 58, 237, 0.20);
        }

        html[data-theme="light"] .konsultasi-title {
            color: #0f172a;
        }

        html[data-theme="light"] .konsultasi-desc {
            color: #475569;
        }

        html[data-theme="light"] .konsultasi-telemetry-pill {
            background: rgba(255, 255, 255, 0.92);
            border-color: rgba(2, 132, 199, 0.25);
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.08);
        }
        html[data-theme="light"] .konsultasi-telemetry-pill.pill-alt {
            border-color: rgba(124, 58, 237, 0.25);
            box-shadow: 0 4px 16px rgba(124, 58, 237, 0.08);
        }
        html[data-theme="light"] .pill-title {
            color: #0369a1;
        }
        html[data-theme="light"] .pill-alt .pill-title {
            color: #7c3aed;
        }
        html[data-theme="light"] .pill-sub {
            color: #64748b;
        }
        html[data-theme="light"] .konsultasi-eq-bars {
            background: rgba(2, 132, 199, 0.06);
            border-color: rgba(2, 132, 199, 0.20);
        }
        html[data-theme="light"] .konsultasi-response-gauge {
            background: rgba(255, 255, 255, 0.92);
            border-color: rgba(124, 58, 237, 0.25);
            box-shadow: 0 4px 16px rgba(124, 58, 237, 0.08);
        }
        html[data-theme="light"] .gauge-label {
            color: #64748b;
        }

        /* Modals & Forms */
        html[data-theme="light"] .modal-box {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.15);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.15);
            color: #0f172a;
        }

        html[data-theme="light"] .modal-title,
        html[data-theme="light"] .modal-title-text {
            color: #0f172a;
        }

        html[data-theme="light"] .modal-subtitle,
        html[data-theme="light"] .modal-desc {
            color: #475569;
        }

        html[data-theme="light"] .modal-form .form-label-c {
            color: #334155;
        }

        html[data-theme="light"] .modal-form .form-control-c {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        html[data-theme="light"] .modal-form-errors {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        html[data-theme="light"] .modal-form-errors-title {
            color: #dc2626;
        }

        html[data-theme="light"] .modal-form .form-control-c:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }

        html[data-theme="light"] .modal-form .form-control-c::placeholder {
            color: #94a3b8;
        }

        html[data-theme="light"] .modal-close {
            background: #f1f5f9;
            border-color: rgba(15, 23, 42, 0.12);
            color: #475569;
        }

        html[data-theme="light"] .modal-close:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        html[data-theme="light"] .modal-success h3 {
            color: #0f172a;
        }

        html[data-theme="light"] .modal-success p {
            color: #475569;
        }

        html[data-theme="light"] .modal-btn {
            background: #f1f5f9;
            border-color: rgba(15, 23, 42, 0.12);
            color: #0f172a;
        }

        html[data-theme="light"] .modal-btn:hover {
            background: rgba(2, 132, 199, 0.1);
            border-color: #0284c7;
            color: #0284c7;
        }

        /* Search Modal */
        html[data-theme="light"] .search-modal-container {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.15);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        html[data-theme="light"] .search-modal-input {
            background: #f8fafc;
            color: #0f172a;
            border-color: rgba(15, 23, 42, 0.15);
        }

        html[data-theme="light"] .search-modal-input::placeholder {
            color: #94a3b8;
        }

        html[data-theme="light"] .search-result-item {
            color: #0f172a !important;
        }

        html[data-theme="light"] .search-result-item div:first-child {
            color: #0f172a !important;
        }

        html[data-theme="light"] .search-result-item div:last-child {
            color: #64748b !important;
        }

        html[data-theme="light"] .search-result-item:hover {
            background: #f1f5f9 !important;
        }

        /* Site Footer */
        html[data-theme="light"] .site-footer {
            background: linear-gradient(180deg, #f8fafc 0%, #edf2f7 100%) !important;
            border-top: 1px solid rgba(15, 23, 42, 0.10);
            color: #475569;
        }
        html[data-theme="light"] .site-footer::before {
            background: linear-gradient(90deg, transparent, rgba(14, 165, 233, 0.45) 25%, rgba(99, 102, 241, 0.4) 75%, transparent) !important;
            box-shadow: 0 0 15px rgba(14, 165, 233, 0.2) !important;
        }
        html[data-theme="light"] .footer-glow-top,
        html[data-theme="light"] .footer-glow-left,
        html[data-theme="light"] .footer-glow-right {
            opacity: 0.2 !important;
        }
        html[data-theme="light"] .footer-cyber-grid {
            background-image: radial-gradient(rgba(14, 165, 233, 0.16) 1.2px, transparent 1.2px) !important;
            opacity: 0.5 !important;
        }
        html[data-theme="light"] .footer-circuit-accents {
            opacity: 0.3 !important;
        }

        html[data-theme="light"] .footer-brand-name {
            color: #0f172a;
        }

        html[data-theme="light"] .footer-brand-desc {
            color: #475569;
        }

        html[data-theme="light"] .footer-col-title {
            color: #0f172a;
        }

        html[data-theme="light"] .footer-nav-list a {
            color: #475569;
        }

        html[data-theme="light"] .footer-nav-list a:hover {
            color: #0284c7;
        }

        html[data-theme="light"] .footer-contact-item {
            color: #475569;
        }

        html[data-theme="light"] .footer-contact-item a {
            color: #475569;
        }

        html[data-theme="light"] .footer-contact-item a:hover {
            color: #0284c7;
        }

        html[data-theme="light"] .footer-social-link {
            background: #ffffff;
            border-color: rgba(15, 23, 42, 0.12);
            color: #64748b;
        }

        html[data-theme="light"] .footer-social-link:hover {
            background: rgba(2, 132, 199, 0.1);
            border-color: #0284c7;
            color: #0284c7;
        }

        html[data-theme="light"] .footer-bottom {
            border-top-color: rgba(15, 23, 42, 0.10);
        }

        html[data-theme="light"] .footer-copyright {
            color: #64748b;
        }

        html[data-theme="light"] .footer-bottom-links a {
            color: #64748b;
        }

        html[data-theme="light"] .footer-bottom-links a:hover {
            color: #0284c7;
        }

        /* Generic light mode inline text safeguards */
        html[data-theme="light"] p[style*="color:rgba(255,255,255"],
        html[data-theme="light"] p[style*="color: rgba(255, 255, 255"],
        html[data-theme="light"] span[style*="color:rgba(255,255,255"],
        html[data-theme="light"] span[style*="color: rgba(255, 255, 255"] {
            color: #475569 !important;
        }

        html[data-theme="light"] h1[style*="color: #fff"],
        html[data-theme="light"] h2[style*="color: #fff"],
        html[data-theme="light"] h3[style*="color: #fff"],
        html[data-theme="light"] h4[style*="color: #fff"] {
            color: #0f172a !important;
        }

        html {
            scroll-behavior: smooth;
        }

        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        body {
            font-family: 'Inter', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--navy-900);
            color: var(--white);
            min-height: 100vh;
        }

        /* ==========================================
           HERO SECTION - FULL SCREEN
        ========================================== */
        .hero-section {
            position: relative;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Background image layer */
        .hero-bg {
            position: absolute;
            inset: 0;
            background-image: url('{{ isset($cmsHomepage) && $cmsHomepage->hero_background ? asset('storage/' . $cmsHomepage->hero_background) : '/images/ai-hero-bg.jpg' }}');
            background-size: cover;
            background-position: center 20%;
            background-repeat: no-repeat;
            z-index: 0;
        }

        /* Dark overlay - thin enough to see background clearly */
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(2, 8, 24, 0.72) 0%,
                rgba(5, 14, 39, 0.60) 30%,
                rgba(10, 21, 53, 0.50) 60%,
                rgba(2, 8, 24, 0.78) 100%
            );
            z-index: 1;
        }

        /* Subtle vignette effect */
        .hero-vignette {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 40%, rgba(2, 8, 24, 0.40) 100%);
            z-index: 2;
        }

        /* Animated ambient glow blobs */
        .hero-glow-1 {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 168, 255, 0.12) 0%, transparent 70%);
            top: -10%;
            left: -5%;
            z-index: 2;
            animation: glow-drift-1 12s ease-in-out infinite;
        }

        .hero-glow-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.15) 0%, transparent 70%);
            bottom: 5%;
            right: 5%;
            z-index: 2;
            animation: glow-drift-2 15s ease-in-out infinite;
        }

        .hero-glow-3 {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.10) 0%, transparent 70%);
            top: 40%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            animation: glow-drift-3 10s ease-in-out infinite;
        }

        @keyframes glow-drift-1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, 20px) scale(1.08); }
            66% { transform: translate(-15px, 35px) scale(0.95); }
        }

        @keyframes glow-drift-2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            40% { transform: translate(-25px, -30px) scale(1.05); }
            70% { transform: translate(20px, -10px) scale(0.98); }
        }

        @keyframes glow-drift-3 {
            0%, 100% { transform: translateX(-50%) scale(1); opacity: 0.8; }
            50% { transform: translateX(-50%) scale(1.15); opacity: 1; }
        }

        /* ==========================================
           NAVBAR
        ========================================== */
        .navbar {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 100;
            width: 100%;
            padding: 0 32px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            background: linear-gradient(to bottom, rgba(2, 8, 24, 0.6) 0%, rgba(2, 8, 24, 0.0) 100%);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-bottom: none;
        }

        /* Logo */
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
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
            font-size: 17px;
            font-weight: 700;
            color: var(--white);
            letter-spacing: -0.3px;
            white-space: nowrap;
        }

        .nav-logo-text span {
            background: linear-gradient(90deg, #00a8ff, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Nav links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--white-80);
            font-size: 14px;
            font-weight: 500;
            padding: 7px 14px;
            border-radius: 8px;
            transition: color 0.2s ease, background 0.2s ease;
            white-space: nowrap;
        }

        .nav-links a:hover {
            color: var(--white);
            background: rgba(255, 255, 255, 0.08);
        }

        /* Nav right side */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        /* Search bar */
        .nav-search {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            padding: 8px 14px;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }

        .nav-search:hover {
            background: rgba(255, 255, 255, 0.10);
            border-color: rgba(0, 168, 255, 0.30);
        }

        .nav-search-icon {
            width: 14px;
            height: 14px;
            color: var(--white-60);
            flex-shrink: 0;
        }

        .nav-search-text {
            font-size: 13px;
            color: var(--white-50);
            color: rgba(255,255,255,0.50);
            white-space: nowrap;
        }

        .nav-search-kbd {
            font-size: 11px;
            color: var(--white-40);
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 5px;
            padding: 1px 6px;
            font-family: 'Inter', monospace;
            white-space: nowrap;
        }

        /* CTA Button */
        .btn-cta-nav {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 20px;
            background: linear-gradient(135deg, #00a8ff 0%, #7c3aed 100%);
            color: white;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 0 20px rgba(0, 168, 255, 0.30);
        }

        .btn-cta-nav:hover {
            opacity: 0.90;
            transform: translateY(-1px);
            box-shadow: 0 4px 24px rgba(0, 168, 255, 0.45);
        }

        /* Mobile hamburger */
        .nav-hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.06);
        }

        .nav-hamburger span {
            display: block;
            width: 20px;
            height: 2px;
            background: var(--white-80);
            border-radius: 2px;
            transition: all 0.3s;
        }

        /* Mobile nav menu */
        .nav-mobile-menu {
            display: none;
            position: absolute;
            top: 72px;
            left: 0;
            right: 0;
            background: rgba(5, 14, 39, 0.97);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 168, 255, 0.15);
            padding: 16px 24px 24px;
            z-index: 200;
            flex-direction: column;
            gap: 4px;
        }

        .nav-mobile-menu.open {
            display: flex;
        }

        .nav-mobile-menu a {
            text-decoration: none;
            color: var(--white-80);
            font-size: 15px;
            font-weight: 500;
            padding: 12px 16px;
            border-radius: 10px;
            transition: color 0.2s, background 0.2s;
        }

        .nav-mobile-menu a:hover {
            color: var(--white);
            background: rgba(0, 168, 255, 0.10);
        }

        .nav-mobile-divider {
            height: 1px;
            background: rgba(255,255,255,0.08);
            margin: 8px 0;
        }

        /* ==========================================
           HERO CONTENT
        ========================================== */
        .hero-content {
            position: relative;
            z-index: 10;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 100px 24px 130px;
        }

        /* Release badge */
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 18px;
            background: rgba(0, 168, 255, 0.10);
            border: 1px solid rgba(0, 168, 255, 0.28);
            border-radius: 100px;
            font-size: 13px;
            font-weight: 500;
            color: var(--cyan-neon);
            margin-bottom: 36px;
            backdrop-filter: blur(8px);
            animation: badge-pulse 3s ease-in-out infinite;
            cursor: default;
            transition: background 0.3s;
        }

        .hero-badge:hover {
            background: rgba(0, 168, 255, 0.16);
        }

        .badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #00d4ff;
            box-shadow: 0 0 8px #00d4ff;
            animation: dot-blink 2s ease-in-out infinite;
            flex-shrink: 0;
        }

        .badge-divider {
            color: rgba(0, 212, 255, 0.40);
            font-size: 12px;
        }

        .badge-speed {
            color: rgba(167, 139, 250, 0.90);
            font-weight: 600;
        }

        @keyframes badge-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(0, 168, 255, 0.15); }
            50% { box-shadow: 0 0 0 6px rgba(0, 168, 255, 0); }
        }

        @keyframes dot-blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Hero heading */
        .hero-heading {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: clamp(40px, 8.5vw, 115px);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -3px;
            margin-bottom: 28px;
            max-width: 1200px;
            animation: hero-text-float 6s ease-in-out infinite alternate;
            will-change: transform;
        }

        .hero-heading-line1 {
            display: block;
            color: var(--white);
            animation: hero-line1-glow 5s ease-in-out infinite alternate;
        }

        .hero-heading-line2 {
            display: block;
            background: linear-gradient(90deg, #00d4ff 0%, #a78bfa 30%, #ec4899 60%, #00a8ff 85%, #00d4ff 100%);
            background-size: 250% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: hero-gradient-flow 7s linear infinite, hero-gradient-glow 5s ease-in-out infinite alternate;
            will-change: background-position, filter;
        }

        /* Hero subheading */
        .hero-subheading {
            font-size: clamp(18px, 2.5vw, 26px);
            font-weight: 400;
            color: #FFFFFF;
            line-height: 1.6;
            max-width: 900px;
            margin-bottom: 48px;
            animation: hero-sub-shimmer 6s ease-in-out infinite alternate;
            animation-delay: -3s;
        }

        /* CTA Buttons */
        .hero-buttons {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 16px 32px;
            background: linear-gradient(135deg, #00a8ff 0%, #7c3aed 100%);
            color: white;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 30px rgba(0, 168, 255, 0.35), 0 0 0 1px rgba(0, 168, 255, 0.20);
            letter-spacing: -0.2px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 40px rgba(0, 168, 255, 0.50), 0 0 0 1px rgba(0, 168, 255, 0.30);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 15px 30px;
            background: rgba(255, 255, 255, 0.07);
            color: var(--white-90);
            color: rgba(255,255,255,0.90);
            font-size: 15px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s ease;
            backdrop-filter: blur(8px);
            letter-spacing: -0.2px;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(0, 168, 255, 0.35);
            transform: translateY(-2px);
            color: var(--white);
            box-shadow: 0 4px 20px rgba(0, 168, 255, 0.15);
        }

        /* ==========================================
           PARTICLE DOTS (subtle)
        ========================================== */
        .particles-container {
            position: absolute;
            inset: 0;
            z-index: 3;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            animation: particle-float linear infinite;
        }

        @keyframes particle-float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; }
        }

        /* ==========================================
           BOTTOM GRADIENT FADE
        ========================================== */
        .hero-bottom-fade {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 180px;
            background: linear-gradient(to top, rgba(2, 8, 24, 0.90) 0%, transparent 100%);
            z-index: 2;
            pointer-events: none;
        }

        /* ==========================================
           SCROLL INDICATOR
        ========================================== */
        .scroll-indicator {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            opacity: 0.50;
            animation: scroll-bounce 2s ease-in-out infinite;
        }

        .scroll-indicator span {
            font-size: 11px;
            color: rgba(255,255,255,0.60);
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .scroll-arrow {
            width: 20px;
            height: 20px;
            border-right: 2px solid rgba(255,255,255,0.50);
            border-bottom: 2px solid rgba(255,255,255,0.50);
            transform: rotate(45deg);
        }

        @keyframes scroll-bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(6px); }
        }

        /* ==========================================
           RESPONSIVE
        ========================================== */
        @media (max-width: 1100px) {
            .navbar {
                padding: 0 20px;
            }
            .nav-links {
                display: none;
            }
            .nav-hamburger {
                display: flex;
            }
            .card-shield { right: 2%; }
            .card-cloud { right: 2%; }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0 16px;
                height: 64px;
            }
            .nav-search {
                display: none;
            }
            .nav-mobile-menu {
                top: 64px;
            }
            .hero-content {
                padding: 48px 20px 90px;
            }
            .hero-badge {
                font-size: 12px;
                padding: 6px 14px;
                margin-bottom: 28px;
            }
            .hero-heading {
                letter-spacing: -1.5px;
                margin-bottom: 20px;
            }
            .hero-subheading {
                font-size: 15px;
                margin-bottom: 36px;
            }
            .btn-primary, .btn-secondary {
                padding: 14px 24px;
                font-size: 14px;
            }
            .hero-buttons {
                gap: 12px;
            }
        }

        @media (max-width: 540px) {
            .hero-buttons {
                flex-direction: column;
                width: 100%;
                max-width: 360px;
                margin: 0 auto;
                gap: 12px;
            }
            .hero-buttons .btn-primary,
            .hero-buttons .btn-secondary {
                width: 100%;
                justify-content: center;
                padding: 14px 20px;
                font-size: 14px;
                box-sizing: border-box;
            }
            .scroll-indicator {
                bottom: 18px;
                transform: translateX(-50%) scale(0.85);
                pointer-events: none;
            }
        }

        @media (max-width: 480px) {
            .hero-badge {
                font-size: 11px;
                padding: 5px 12px;
                gap: 6px;
                max-width: 96%;
                flex-wrap: wrap;
                justify-content: center;
            }
            .hero-badge .badge-speed {
                display: inline-flex;
                font-size: 10.5px;
            }
            .hero-badge .badge-divider {
                display: inline-block;
            }
        }

        /* ==========================================
           SELECTION
        ========================================== */
        /* ==========================================
           PAGE 2 / LAYANAN SECTION
        ========================================== */
        .layanan-section {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 100px 24px;
            display: flex;
            flex-direction: column;
            gap: 80px;
            z-index: 10;
            overflow: hidden;
        }

        .layanan-circuit-decor {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            border-radius: inherit;
        }
        
        .layanan-circuit-left, .layanan-circuit-right {
            position: absolute;
            top: 10%;
            height: 80%;
            width: 250px;
            opacity: 1;
        }
        
        .layanan-circuit-left {
            left: -50px;
        }
        
        .layanan-circuit-right {
            right: -50px;
        }

        .circuit-line {
            fill: none;
            stroke: var(--cyan-neon);
            stroke-width: 1.5;
            opacity: 0.45;
            stroke-dasharray: 8 6;
            animation: circuit-flow 12s linear infinite;
        }
        
        .circuit-line-brown {
            stroke: #a78bfa;
            opacity: 0.50;
            stroke-dasharray: 12 8;
            animation: circuit-flow-reverse 15s linear infinite;
        }
        
        .circuit-line-navy {
            stroke: var(--blue-electric);
            opacity: 0.35;
        }

        .circuit-node {
            fill: var(--cyan-neon);
            opacity: 0.95;
        }
        
        .circuit-node-glow {
            fill: var(--cyan-neon);
            opacity: 0.65;
            filter: blur(5px);
            animation: node-pulse 3s ease-in-out infinite alternate;
        }
        
        @media (max-width: 768px) {
            .layanan-circuit-decor {
                display: block;
                opacity: 0.35;
                pointer-events: none;
            }
            .layanan-circuit-left {
                left: -120px;
                width: 180px;
                height: 60%;
            }
            .layanan-circuit-right {
                right: -120px;
                width: 180px;
                height: 60%;
            }
        }
        @media (max-width: 480px) {
            .layanan-circuit-decor {
                opacity: 0.22;
            }
            .layanan-circuit-left {
                left: -140px;
                width: 150px;
            }
            .layanan-circuit-right {
                right: -140px;
                width: 150px;
            }
        }

        .ambient-glow-top-bridge {
            position: absolute;
            top: -180px;
            left: 50%;
            transform: translateX(-50%);
            width: 1100px;
            max-width: 95vw;
            height: 480px;
            border-radius: 50%;
            background: radial-gradient(ellipse 70% 50% at 50% 50%, rgba(0, 168, 255, 0.22) 0%, rgba(10, 26, 68, 0.10) 45%, transparent 70%);
            pointer-events: none;
            filter: blur(65px);
            z-index: 0;
        }

        .ambient-glow-4 {
            position: absolute;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.16) 0%, rgba(0, 168, 255, 0.12) 45%, transparent 70%);
            top: 15%;
            right: -100px;
            z-index: 0;
            pointer-events: none;
            filter: blur(55px);
            animation: ambient-pulse-purple 16s ease-in-out infinite;
        }
        .ambient-glow-blue-left {
            position: absolute;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 168, 255, 0.22) 0%, rgba(0, 212, 255, 0.12) 45%, transparent 70%);
            top: 25%;
            left: -100px;
            z-index: 0;
            pointer-events: none;
            filter: blur(55px);
            animation: ambient-pulse-blue 15s ease-in-out infinite;
        }
        .section-grid-mesh {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(0, 212, 255, 0.08) 1.2px, transparent 1.2px);
            background-size: 32px 32px;
            mask-image: radial-gradient(ellipse at center, black 30%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse at center, black 30%, transparent 75%);
            z-index: 0;
            pointer-events: none;
        }

        .page-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .page-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(36px, 5vw, 64px);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1.5px;
            margin-bottom: 24px;
            background: linear-gradient(135deg, #ffffff 0%, #a78bfa 50%, #00d4ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            font-size: clamp(16px, 2vw, 20px);
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        .section-what-is {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .section-tag {
            font-size: 13px;
            font-weight: 600;
            color: var(--cyan-neon);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 16px;
        }

        .section-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .section-desc {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.75);
            max-width: 700px;
            line-height: 1.7;
            margin-bottom: 60px;
        }

        .flow-diagram {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            width: 100%;
            flex-wrap: wrap;
        }

        .flow-card {
            background: rgba(10, 20, 50, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 168, 255, 0.2);
            border-radius: 20px;
            padding: 32px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            min-width: 220px;
            position: relative;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .flow-card:hover {
            transform: translateY(-5px);
            border-color: rgba(0, 168, 255, 0.4);
            box-shadow: 0 15px 50px rgba(0, 168, 255, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .flow-card.ai-center {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.15), rgba(0, 168, 255, 0.15));
            border-color: rgba(124, 58, 237, 0.4);
            box-shadow: 0 0 30px rgba(124, 58, 237, 0.2);
        }

        .flow-card.ai-center:hover {
            box-shadow: 0 0 50px rgba(124, 58, 237, 0.3);
            border-color: rgba(124, 58, 237, 0.6);
        }

        .flow-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255, 255, 255, 0.85);
            transition: all 0.3s ease;
        }

        .flow-card:hover .flow-icon {
            color: #00d4ff;
            border-color: rgba(0, 212, 255, 0.4);
            background: rgba(0, 212, 255, 0.1);
        }

        .flow-card.ai-center .flow-icon {
            background: linear-gradient(135deg, #00a8ff 0%, #7c3aed 100%);
            border: none;
            box-shadow: 0 0 20px rgba(0, 168, 255, 0.4);
            animation: pulse-glow 2s infinite alternate;
        }

        .flow-text {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .flow-arrow {
            display: flex;
            align-items: center;
            color: rgba(0, 168, 255, 0.5);
            animation: move-arrow 1.5s infinite;
        }

        @keyframes move-arrow {
            0% { transform: translateX(-5px); opacity: 0.3; }
            50% { opacity: 1; color: var(--cyan-neon); }
            100% { transform: translateX(5px); opacity: 0.3; }
        }

        @keyframes pulse-glow {
            0% { box-shadow: 0 0 15px rgba(124, 58, 237, 0.4); }
            100% { box-shadow: 0 0 30px rgba(0, 212, 255, 0.6); }
        }

        @media (max-width: 768px) {
            .flow-diagram {
                flex-direction: column;
                gap: 16px;
                align-items: center;
                width: 100%;
            }
            .flow-card {
                width: 100%;
                max-width: 320px;
                padding: 24px 20px;
                min-width: auto;
            }
            .flow-arrow {
                transform: rotate(90deg);
                margin: 8px 0;
                animation: move-arrow-mobile 1.5s infinite;
            }
            @keyframes move-arrow-mobile {
                0% { transform: rotate(90deg) translateY(-5px); opacity: 0.3; }
                50% { opacity: 1; color: var(--cyan-neon); }
                100% { transform: rotate(90deg) translateY(5px); opacity: 0.3; }
            }
        }

        /* ==========================================
           PAGE 3 / JASA AI SECTION
        ========================================== */
        /* SERVICES SECTION */
        .services-section { padding: 100px 24px; max-width: 1400px; margin: 0 auto; width: 100%; display: flex; flex-direction: column; align-items: center; position: relative; z-index: 10; scroll-margin-top: 40px; overflow: hidden; }
        .services-ambient-glow-1 {
            position: absolute;
            top: 5%;
            left: -120px;
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, rgba(0, 168, 255, 0.14) 0%, rgba(10, 20, 50, 0) 70%);
            pointer-events: none;
            filter: blur(70px);
            z-index: 0;
            animation: ambient-pulse-blue 8s ease-in-out infinite alternate;
        }
        .services-ambient-glow-2 {
            position: absolute;
            bottom: 5%;
            right: -120px;
            width: 580px;
            height: 580px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.15) 0%, rgba(10, 20, 50, 0) 70%);
            pointer-events: none;
            filter: blur(80px);
            z-index: 0;
            animation: ambient-pulse-purple 10s ease-in-out infinite alternate;
        }
        .services-grid-mesh {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(0, 212, 255, 0.025) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(0, 212, 255, 0.025) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 0;
            mask-image: radial-gradient(ellipse at center, rgba(0,0,0,1) 40%, transparent 80%);
            -webkit-mask-image: radial-gradient(ellipse at center, rgba(0,0,0,1) 40%, transparent 80%);
        }
        /* SERVICES HEADER WRAPPER & SIDE HUD DECOR */
        .services-header-wrapper {
            position: relative;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .services-header-decor {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }
        .services-hud {
            position: absolute;
            top: 48%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 200px;
            pointer-events: none;
        }
        .services-hud-left {
            left: 8px;
            align-items: flex-start;
            animation: float-hud-left 7s ease-in-out infinite;
        }
        .services-hud-right {
            right: 8px;
            align-items: flex-end;
            animation: float-hud-right 8s ease-in-out infinite;
        }

        /* Telemetry Badge */
        .hud-telemetry-badge {
            background: rgba(10, 20, 55, 0.65);
            border: 1px solid rgba(0, 212, 255, 0.25);
            border-radius: 12px;
            padding: 10px 14px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35), 0 0 16px rgba(0, 212, 255, 0.12);
            width: 175px;
            position: relative;
            overflow: hidden;
        }
        .hud-telemetry-badge-alt {
            border-color: rgba(167, 139, 250, 0.28);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35), 0 0 16px rgba(124, 58, 237, 0.15);
        }
        .hud-telemetry-status {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }
        .hud-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--cyan-neon);
            box-shadow: 0 0 8px var(--cyan-neon);
            animation: blink-dot 1.8s ease infinite;
        }
        .hud-status-dot-purple {
            background: #a78bfa;
            box-shadow: 0 0 8px #a78bfa;
        }
        .hud-status-text {
            font-family: 'Plus Jakarta Sans', monospace;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--cyan-neon);
        }
        .hud-telemetry-badge-alt .hud-status-text {
            color: #a78bfa;
        }
        .hud-telemetry-metric {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 6px;
        }
        .hud-metric-label {
            font-size: 9px;
            color: rgba(255, 255, 255, 0.45);
            letter-spacing: 0.5px;
        }
        .hud-metric-val {
            font-weight: 700;
            color: #ffffff;
            font-family: monospace;
        }
        .hud-telemetry-bar {
            width: 100%;
            height: 3px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 2px;
            overflow: hidden;
            position: relative;
        }
        .hud-bar-fill {
            position: absolute;
            top: 0; left: 0; bottom: 0;
            width: 78%;
            background: linear-gradient(90deg, #00d4ff, #7c3aed);
            border-radius: 2px;
            animation: pulse-glow-aura 4s ease-in-out infinite alternate;
        }
        .hud-bar-fill-purple {
            width: 92%;
            background: linear-gradient(90deg, #7c3aed, #00d4ff);
        }

        /* HUD Scanner SVG */
        .hud-scanner-svg {
            width: 160px;
            height: 160px;
            filter: drop-shadow(0 0 12px rgba(0, 212, 255, 0.15));
        }
        .hud-ring-outer {
            transform-origin: 120px 120px;
            animation: hud-spin-cw 24s linear infinite;
        }
        .hud-ring-mid {
            transform-origin: 120px 120px;
            animation: hud-spin-ccw 18s linear infinite;
        }
        .hud-ring-inner {
            transform-origin: 120px 120px;
            animation: hud-spin-cw 12s linear infinite;
        }
        .hud-hex-outer {
            transform-origin: 120px 120px;
            animation: hud-spin-ccw 28s linear infinite;
        }
        .hud-center-node-glow,
        .hud-center-node-glow-purple {
            animation: node-pulse 2.5s ease-in-out infinite;
        }

        /* Horizontal Laser Beam with Data Ticks */
        .hud-vertical-laser {
            position: relative;
            width: 150px;
            height: 24px;
            display: flex;
            align-items: center;
        }
        .hud-laser-line {
            width: 100%;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, rgba(0, 212, 255, 0.45), transparent);
            position: relative;
        }
        .hud-laser-purple .hud-laser-line {
            background: linear-gradient(90deg, transparent, rgba(167, 139, 250, 0.45), transparent);
        }
        .hud-laser-runner {
            position: absolute;
            top: -1px;
            left: 0;
            width: 30px;
            height: 3px;
            background: #00d4ff;
            box-shadow: 0 0 10px #00d4ff;
            border-radius: 3px;
            animation: laser-slide-lr 3.5s ease-in-out infinite;
        }
        .hud-runner-purple {
            background: #a78bfa;
            box-shadow: 0 0 10px #a78bfa;
            animation: laser-slide-rl 3.8s ease-in-out infinite;
        }
        .hud-laser-ticks {
            position: absolute;
            top: 6px;
            left: 10px;
            right: 10px;
            display: flex;
            justify-content: space-between;
        }
        .hud-laser-ticks span {
            width: 1px;
            height: 6px;
            background: rgba(0, 212, 255, 0.35);
        }
        .hud-laser-purple .hud-laser-ticks span {
            background: rgba(167, 139, 250, 0.35);
        }

        @media (max-width: 1280px) {
            .services-hud { width: 160px; }
            .hud-telemetry-badge { width: 145px; padding: 8px 10px; }
            .hud-scanner-svg { width: 125px; height: 125px; }
            .hud-vertical-laser { width: 125px; }
        }
        @media (max-width: 1100px) {
            .services-header-decor {
                display: block;
                opacity: 0.45;
                pointer-events: none;
            }
            .services-hud-left {
                left: -20px;
                transform: translateY(-50%) scale(0.72);
                transform-origin: left center;
            }
            .services-hud-right {
                right: -20px;
                transform: translateY(-50%) scale(0.72);
                transform-origin: right center;
            }
        }
        @media (max-width: 768px) {
            .services-header-decor {
                opacity: 0.28;
            }
            .services-hud-left {
                left: -40px;
                transform: translateY(-50%) scale(0.55);
            }
            .services-hud-right {
                right: -40px;
                transform: translateY(-50%) scale(0.55);
            }
        }
        @media (max-width: 480px) {
            .services-header-decor {
                opacity: 0.20;
            }
            .services-hud-left {
                left: -55px;
                transform: translateY(-50%) scale(0.45);
            }
            .services-hud-right {
                right: -55px;
                transform: translateY(-50%) scale(0.45);
            }
        }

        .jasa-header { text-align: center; max-width: 900px; margin: 0 auto 60px; display: flex; flex-direction: column; align-items: center; position: relative; z-index: 2; }
        .jasa-badge { display: inline-flex; font-size: 13px; font-weight: 600; color: var(--cyan-neon); letter-spacing: 2px; padding: 6px 16px; background: rgba(0, 168, 255, 0.1); border: 1px solid rgba(0, 168, 255, 0.2); border-radius: 100px; margin-bottom: 24px; box-shadow: 0 0 16px rgba(0, 168, 255, 0.15); }
        .jasa-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(36px, 5vw, 64px); font-weight: 800; line-height: 1.1; letter-spacing: -1.5px; margin-bottom: 24px; background: linear-gradient(135deg, #ffffff 0%, #a78bfa 50%, #00d4ff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .jasa-subtitle { font-size: clamp(16px, 2vw, 20px); color: rgba(255, 255, 255, 0.7); line-height: 1.6; margin-bottom: 40px; }
        
        .btn-cta { display: inline-flex; align-items: center; gap: 8px; padding: 16px 32px; background: linear-gradient(135deg, #00a8ff 0%, #7c3aed 100%); color: white; font-size: 15px; font-weight: 700; border: none; border-radius: 14px; cursor: pointer; text-decoration: none; transition: all 0.25s ease; box-shadow: 0 4px 30px rgba(0, 168, 255, 0.35), 0 0 0 1px rgba(0, 168, 255, 0.20); }
        .btn-cta:hover { transform: translateY(-3px); box-shadow: 0 8px 40px rgba(0, 168, 255, 0.50), 0 0 0 1px rgba(0, 168, 255, 0.30); }

        .section-header { text-align: center; margin-bottom: 50px; position: relative; z-index: 2; }

        /* 16 CARDS GRID */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
        }
        
        .service-card {
            background: rgba(10, 20, 50, 0.45);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 32px 24px;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            cursor: pointer;
            box-sizing: border-box;
            min-width: 0;
            width: 100%;
        }
        
        /* Card Laser Accent Line */
        .service-card::after {
            content: '';
            position: absolute;
            top: 0; left: 15%; right: 15%;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, rgba(0, 212, 255, 0.65), rgba(167, 139, 250, 0.65), transparent);
            opacity: 0.6;
            transition: all 0.35s ease;
            pointer-events: none;
        }
        .service-card:hover::after {
            left: 5%; right: 5%;
            opacity: 1;
            box-shadow: 0 0 12px rgba(0, 212, 255, 0.85);
        }

        /* Card Hover Effects */
        .service-card:hover {
            transform: translateY(-8px);
            background: rgba(15, 30, 75, 0.65);
            border-color: rgba(0, 168, 255, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 0 30px rgba(0, 168, 255, 0.2);
        }
        
        /* Glow sweep effect */
        .service-card::before {
            content: ""; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
            transform: skewX(-20deg); transition: 0.5s; z-index: 1; pointer-events: none;
        }
        .service-card:hover::before { left: 150%; }

        /* Card Content */
        .card-number { position: absolute; top: 20px; right: 24px; font-size: 48px; font-weight: 900; color: rgba(255, 255, 255, 0.04); font-family: 'Plus Jakarta Sans', sans-serif; transition: 0.3s; z-index: 0; }
        .service-card:hover .card-number { color: rgba(0, 168, 255, 0.12); transform: scale(1.1); }
        
        .card-icon { width: 50px; height: 50px; border-radius: 14px; background: rgba(0, 168, 255, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 24px; border: 1px solid rgba(0, 168, 255, 0.2); color: var(--cyan-neon); transition: 0.3s; z-index: 2; position: relative; box-shadow: 0 0 15px rgba(0, 168, 255, 0.15); }
        .service-card:hover .card-icon { background: linear-gradient(135deg, #00a8ff 0%, #7c3aed 100%); color: white; border-color: transparent; box-shadow: 0 0 25px rgba(0, 168, 255, 0.55); transform: scale(1.08); }
        
        .card-title { font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--white); z-index: 2; position: relative; font-family: 'Plus Jakarta Sans', sans-serif; }
        .card-desc { font-size: 14px; color: rgba(255, 255, 255, 0.6); line-height: 1.6; margin-bottom: 24px; flex-grow: 1; z-index: 2; position: relative; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        
        .card-btn { margin-top: auto; display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: var(--cyan-neon); background: none; border: none; cursor: pointer; padding: 0; z-index: 2; position: relative; transition: 0.3s; }
        .card-btn svg { width: 14px; height: 14px; transition: transform 0.3s; }
        .service-card:hover .card-btn { color: var(--purple-light); }
        .service-card:hover .card-btn svg { transform: translateX(4px); }

        /* RESPONSIVE GRID - 4 COLUMNS ON ALL SCREENS */
        @media (max-width: 1024px) {
            .services-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 14px;
            }
            .service-card {
                padding: 22px 14px 20px;
                border-radius: 16px;
            }
            .card-number {
                font-size: 28px;
                top: 12px;
                right: 14px;
            }
            .card-icon {
                width: 38px;
                height: 38px;
                border-radius: 11px;
                margin-bottom: 14px;
            }
            .card-icon svg {
                width: 18px;
                height: 18px;
            }
            .card-title {
                font-size: 14px;
                line-height: 1.3;
                margin-bottom: 8px;
            }
            .card-desc {
                font-size: 11.5px;
                line-height: 1.45;
                margin-bottom: 14px;
            }
            .card-btn {
                font-size: 11px;
                gap: 4px;
            }
            .card-btn svg {
                width: 12px;
                height: 12px;
            }
            .layanan-section {
                gap: 60px;
                padding: 80px 24px;
            }
            .services-section {
                padding: 70px 20px 80px;
            }
        }
        @media (max-width: 768px) {
            .services-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 8px;
            }
            .service-card {
                padding: 14px 8px 16px;
                border-radius: 12px;
            }
            .card-number {
                font-size: 18px;
                top: 6px;
                right: 8px;
                opacity: 0.20;
            }
            .card-icon {
                width: 28px;
                height: 28px;
                border-radius: 8px;
                margin-bottom: 8px;
            }
            .card-icon svg {
                width: 14px;
                height: 14px;
            }
            .card-title {
                font-size: 11px;
                line-height: 1.25;
                margin-bottom: 6px;
                word-break: break-word;
            }
            .card-desc {
                font-size: 9px;
                line-height: 1.35;
                margin-bottom: 10px;
                -webkit-line-clamp: 3;
            }
            .card-btn {
                font-size: 9px;
                gap: 3px;
                white-space: nowrap;
            }
            .card-btn svg {
                width: 10px;
                height: 10px;
            }
            .section-header {
                margin-bottom: 30px;
                padding: 0 10px;
            }
            .section-title {
                font-size: clamp(22px, 4.5vw, 30px);
            }
            .section-subtitle {
                font-size: clamp(12.5px, 3vw, 14.5px);
            }
            .jasa-title {
                font-size: 30px;
            }
            .layanan-section {
                gap: 48px;
                padding: 48px 14px;
            }
            .services-section {
                padding: 50px 10px 80px;
            }
        }
        @media (max-width: 480px) {
            .services-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 5px;
            }
            .service-card {
                padding: 10px 5px 12px;
                border-radius: 10px;
            }
            .card-number {
                font-size: 13px;
                top: 4px;
                right: 5px;
                opacity: 0.25;
            }
            .card-icon {
                width: 22px;
                height: 22px;
                border-radius: 6px;
                margin-bottom: 6px;
                box-shadow: 0 0 8px rgba(0, 168, 255, 0.2);
            }
            .card-icon svg {
                width: 12px;
                height: 12px;
            }
            .card-title {
                font-size: clamp(8px, 2.2vw, 9.5px);
                line-height: 1.2;
                margin-bottom: 4px;
            }
            .card-desc {
                font-size: clamp(6.5px, 1.8vw, 8px);
                line-height: 1.3;
                margin-bottom: 6px;
                -webkit-line-clamp: 2;
            }
            .card-btn {
                font-size: clamp(6.5px, 1.8vw, 8px);
                gap: 2px;
                white-space: nowrap;
            }
            .card-btn svg {
                width: 8px;
                height: 8px;
            }
            .section-header {
                margin-bottom: 24px;
                padding: 0 6px;
            }
            .section-title {
                font-size: clamp(18px, 4vw, 24px);
            }
            .section-subtitle {
                font-size: clamp(11.5px, 2.5vw, 13px);
            }
            .services-section {
                padding: 38px 6px 75px;
            }
        }
        @media (max-width: 360px) {
            .services-grid {
                gap: 4px;
            }
            .service-card {
                padding: 8px 4px 10px;
                border-radius: 8px;
            }
            .card-number {
                font-size: 11px;
                top: 3px;
                right: 4px;
            }
            .card-icon {
                width: 18px;
                height: 18px;
                border-radius: 5px;
                margin-bottom: 4px;
            }
            .card-icon svg {
                width: 10px;
                height: 10px;
            }
            .card-title {
                font-size: 7.5px;
                line-height: 1.15;
            }
            .card-desc {
                font-size: 6px;
                line-height: 1.2;
            }
            .card-btn {
                font-size: 6px;
            }
            .card-btn svg {
                width: 7px;
                height: 7px;
            }
            .services-section {
                padding: 32px 4px 70px;
            }
        }

        /* ==========================================
           MODAL 
        ========================================== */
        .modal-overlay { position: fixed; inset: 0; background: rgba(2, 8, 24, 0.85); backdrop-filter: blur(8px); z-index: 1000; display: none; align-items: center; justify-content: center; padding: 20px; opacity: 0; transition: opacity 0.3s; }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-box { background: rgba(10, 20, 50, 0.95); border: 1px solid rgba(0, 168, 255, 0.3); border-radius: 24px; padding: 40px; max-width: 500px; width: 100%; position: relative; transform: translateY(20px); transition: transform 0.3s; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255,255,255,0.1); }
        .modal-overlay.active .modal-box { transform: translateY(0); }
        .modal-close { position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.1); border: none; border-radius: 50%; width: 32px; height: 32px; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s; }
        .modal-close:hover { background: rgba(255,255,255,0.2); }
        .modal-icon { width: 60px; height: 60px; border-radius: 16px; background: linear-gradient(135deg, #00a8ff 0%, #7c3aed 100%); color: white; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 0 20px rgba(0, 168, 255, 0.4); }
        .modal-icon svg { width: 28px; height: 28px; }
        .modal-title-text { font-size: 24px; font-weight: 700; margin-bottom: 16px; font-family: 'Plus Jakarta Sans', sans-serif; }
        .modal-desc { font-size: 15px; color: rgba(255,255,255,0.7); line-height: 1.6; margin-bottom: 30px; }
        .modal-btn { width: 100%; padding: 14px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: white; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .modal-btn:hover { background: rgba(0, 168, 255, 0.15); border-color: var(--blue-electric); color: var(--cyan-neon); }

        ::selection {
            background: rgba(0, 168, 255, 0.30);
            color: white;
        }

        /* ==========================================
           PAGE 4 / TENTANG KAMI SECTION
        ========================================== */
        .tentang-section {
            position: relative;
            padding: 100px 24px 100px;
            max-width: 1200px;
            margin: 0 auto;
            overflow: hidden;
        }
        .tentang-ambient-glow-1 {
            position: absolute;
            top: 15%;
            left: -120px;
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, rgba(0, 168, 255, 0.12) 0%, rgba(10, 20, 55, 0) 70%);
            pointer-events: none;
            filter: blur(65px);
            z-index: 0;
            animation: ambient-pulse-blue 8s ease-in-out infinite alternate;
        }
        .tentang-ambient-glow-2 {
            position: absolute;
            bottom: 10%;
            right: -120px;
            width: 540px;
            height: 540px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.14) 0%, rgba(10, 20, 55, 0) 70%);
            pointer-events: none;
            filter: blur(75px);
            z-index: 0;
            animation: ambient-pulse-purple 10s ease-in-out infinite alternate;
        }

        /* Tentang Kami - Luminous Hero Backdrop Elements */
        .tentang-hero-wrapper {
            position: relative;
            text-align: center;
            max-width: 800px;
            margin: 0 auto 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
        }
        .tentang-center-glow {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 880px;
            max-width: 95vw;
            height: 520px;
            background: radial-gradient(ellipse 65% 55% at 50% 35%, rgba(0, 212, 255, 0.38) 0%, rgba(124, 58, 237, 0.28) 38%, rgba(0, 168, 255, 0.12) 65%, transparent 80%);
            filter: blur(55px);
            pointer-events: none;
            z-index: 0;
            animation: tentang-aurora-glow 8s ease-in-out infinite alternate;
        }
        .tentang-halo-ring {
            position: absolute;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            width: 620px;
            height: 380px;
            border-radius: 50%;
            background: radial-gradient(ellipse at center, rgba(0, 212, 255, 0.22) 0%, rgba(124, 58, 237, 0.15) 45%, transparent 70%);
            filter: blur(30px);
            pointer-events: none;
            z-index: 0;
        }
        .tentang-tech-grid {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 920px;
            max-width: 100vw;
            height: 540px;
            background-image: 
                radial-gradient(circle at center, rgba(0, 212, 255, 0.25) 1.5px, transparent 1.5px),
                linear-gradient(rgba(0, 212, 255, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.06) 1px, transparent 1px);
            background-size: 32px 32px, 64px 64px, 64px 64px;
            mask-image: radial-gradient(ellipse 70% 60% at 50% 45%, black 30%, transparent 80%);
            -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 45%, black 30%, transparent 80%);
            pointer-events: none;
            z-index: 0;
        }
        .tentang-floating-sparks {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }
        .tentang-spark {
            position: absolute;
            border-radius: 50%;
            background: #00d4ff;
            box-shadow: 0 0 10px #00d4ff, 0 0 18px rgba(0, 212, 255, 0.8);
            animation: spark-float 4.5s ease-in-out infinite alternate;
        }
        .spark-1 { width: 5px; height: 5px; top: 18%; left: 10%; animation-delay: 0s; }
        .spark-2 { width: 4px; height: 4px; top: 25%; right: 12%; animation-delay: 1.2s; background: #a78bfa; box-shadow: 0 0 10px #a78bfa; }
        .spark-3 { width: 6px; height: 6px; top: 58%; left: 6%; animation-delay: 2.1s; }
        .spark-4 { width: 4px; height: 4px; top: 68%; right: 8%; animation-delay: 0.8s; background: #ec4899; box-shadow: 0 0 10px #ec4899; }
        .spark-5 { width: 5px; height: 5px; top: 42%; left: 18%; animation-delay: 1.7s; }
        .spark-6 { width: 5px; height: 5px; top: 46%; right: 18%; animation-delay: 2.5s; }

        @keyframes tentang-aurora-glow {
            0% { transform: translateX(-50%) scale(0.95); opacity: 0.85; }
            50% { transform: translateX(-50%) scale(1.08); opacity: 1; filter: blur(48px); }
            100% { transform: translateX(-50%) scale(1); opacity: 0.9; }
        }
        @keyframes spark-float {
            0% { transform: translateY(0) scale(0.8); opacity: 0.4; }
            50% { transform: translateY(-12px) scale(1.2); opacity: 1; }
            100% { transform: translateY(6px) scale(0.9); opacity: 0.5; }
        }

        .section-divider-line {
            position: relative;
            width: 100%;
            height: 1.5px;
            background: linear-gradient(90deg, transparent 0%, rgba(0, 212, 255, 0.40) 18%, rgba(0, 212, 255, 0.95) 50%, rgba(124, 58, 237, 0.75) 82%, transparent 100%);
            margin: 0;
            overflow: visible;
            z-index: 15;
        }
        .section-divider-line::before {
            content: '';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 480px;
            max-width: 90vw;
            height: 26px;
            background: radial-gradient(ellipse at center, rgba(0, 212, 255, 0.60) 0%, rgba(124, 58, 237, 0.35) 45%, transparent 75%);
            filter: blur(8px);
            pointer-events: none;
        }
        .section-divider-line::after {
            content: '';
            position: absolute;
            top: -1.5px;
            left: 50%;
            transform: translateX(-50%);
            width: 340px;
            height: 4px;
            background: radial-gradient(ellipse at center, #ffffff 0%, rgba(0, 212, 255, 1) 40%, rgba(124, 58, 237, 0.85) 70%, transparent 85%);
            filter: blur(1.2px);
            border-radius: 50%;
            pointer-events: none;
            box-shadow: 0 0 20px rgba(0, 212, 255, 1), 0 0 42px rgba(0, 212, 255, 0.85), 0 0 75px rgba(124, 58, 237, 0.6);
        }

        .section-top-aurora {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 950px;
            max-width: 95vw;
            height: 360px;
            background: radial-gradient(ellipse 75% 100% at 50% 0%, rgba(0, 212, 255, 0.35) 0%, rgba(124, 58, 237, 0.22) 45%, transparent 75%);
            pointer-events: none;
            filter: blur(45px);
            z-index: 0;
            animation: wawasan-aurora-breathe 8s ease-in-out infinite alternate;
        }

        @keyframes blink-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Profile Card */
        .profile-container {
            display: flex; align-items: center; gap: 60px;
            max-width: 900px; margin: 0 auto;
            background: rgba(10, 20, 55, 0.50);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(0, 168, 255, 0.15);
            border-radius: 28px; padding: 48px 56px;
            box-shadow: 0 12px 60px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.07);
            position: relative; overflow: hidden;
        }
        .profile-container::before {
            content: ''; position: absolute; top: -80px; right: -80px;
            width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(0,168,255,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .profile-photo-wrap {
            flex-shrink: 0; position: relative;
            width: 200px; height: 200px;
        }
        .profile-photo-wrap::before {
            content: ''; position: absolute; inset: -4px; border-radius: 50%;
            background: linear-gradient(135deg, #00a8ff 0%, #7c3aed 50%, #00d4ff 100%);
            z-index: 0; animation: rotateGlow 6s linear infinite;
        }
        .profile-photo-wrap::after {
            content: ''; position: absolute; inset: -4px; border-radius: 50%;
            background: linear-gradient(135deg, #00a8ff 0%, #7c3aed 50%, #00d4ff 100%);
            z-index: 0; filter: blur(16px); opacity: 0.55;
        }
        @keyframes rotateGlow {
            from { filter: hue-rotate(0deg); }
            to   { filter: hue-rotate(360deg); }
        }
        .profile-photo {
            width: 200px; height: 200px;
            border-radius: 50%; object-fit: cover; object-position: top;
            border: 4px solid var(--navy-800);
            position: relative; z-index: 1;
        }
        .profile-info { flex: 1; }
        .profile-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 30px; font-weight: 800; letter-spacing: -0.5px;
            margin-bottom: 6px;
        }
        .profile-role {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 14px; font-weight: 600;
            color: var(--cyan-neon); letter-spacing: 0.5px;
            background: rgba(0,168,255,0.10);
            border: 1px solid rgba(0,168,255,0.22);
            border-radius: 100px; padding: 5px 14px;
            margin-bottom: 20px;
        }
        .profile-desc {
            font-size: 16px; color: rgba(255,255,255,0.68); line-height: 1.7;
            margin-bottom: 28px;
        }
        .profile-socials { display: flex; gap: 12px; }
        .social-btn {
            display: flex; align-items: center; justify-content: center;
            width: 40px; height: 40px; border-radius: 10px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.10);
            color: rgba(255,255,255,0.55);
            transition: all 0.25s; text-decoration: none;
        }
        .social-btn:hover {
            background: rgba(0,168,255,0.15);
            border-color: rgba(0,168,255,0.35);
            color: var(--cyan-neon); transform: translateY(-3px);
        }
        .social-btn svg { width: 18px; height: 18px; }

        /* Infinite Logo Marquee */
        .partner-section-wrapper {
            position: relative;
            margin-top: 50px;
            margin-bottom: 50px;
            overflow: hidden;
        }
        .partner-ambient-aura {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 75%;
            height: 140px;
            background: radial-gradient(ellipse, rgba(0, 212, 255, 0.12) 0%, rgba(124, 58, 237, 0.10) 45%, transparent 75%);
            filter: blur(45px);
            pointer-events: none;
            z-index: 0;
            animation: pulse-glow-aura 6s ease-in-out infinite alternate;
        }
        .logo-marquee-wrapper {
            position: relative;
            width: 100%;
            overflow: hidden;
            padding: 24px 0;
            background: radial-gradient(ellipse at center, rgba(0,212,255,0.05) 0%, transparent 75%);
            z-index: 1;
        }
        .logo-marquee-wrapper::before,
        .logo-marquee-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            width: 12%;
            height: 100%;
            z-index: 2;
            pointer-events: none;
        }
        .logo-marquee-wrapper::before {
            left: 0;
            background: linear-gradient(to right, var(--navy-900), transparent);
        }
        .logo-marquee-wrapper::after {
            right: 0;
            background: linear-gradient(to left, var(--navy-900), transparent);
        }

        .logo-marquee-track {
            display: flex;
            width: max-content;
            gap: 48px;
            animation: scroll-left 35s linear infinite;
            align-items: center;
        }
        .logo-marquee-track.reverse {
            animation: scroll-right 35s linear infinite;
            margin-top: 24px;
        }
        
        .logo-marquee-wrapper:hover .logo-marquee-track {
            animation-play-state: paused;
        }

        @keyframes scroll-left {
            0% { transform: translateX(0); }
            100% { transform: translateX(calc(-50% - 24px)); }
        }
        @keyframes scroll-right {
            0% { transform: translateX(calc(-50% - 24px)); }
            100% { transform: translateX(0); }
        }

        .logo-item {
            padding: 6px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: transparent;
            border: none;
            border-radius: 0;
            box-shadow: none;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .logo-item:hover {
            transform: translateY(-2px) scale(1.08);
            background: transparent;
            border: none;
            box-shadow: none;
        }
        .logo-item img {
            height: 46px;
            width: auto;
            max-width: 160px;
            object-fit: contain;
            opacity: 1;
            filter: drop-shadow(0 0 1.2px rgba(255, 255, 255, 0.85)) drop-shadow(0 3px 8px rgba(0, 0, 0, 0.45));
            transition: all 0.35s ease;
        }
        .logo-item:hover img {
            opacity: 1;
            filter: drop-shadow(0 0 2.5px #ffffff) drop-shadow(0 0 14px rgba(0, 212, 255, 0.5));
            transform: scale(1.05);
        }

        @media (max-width: 900px) {
            .profile-container { flex-direction: column; text-align: center; padding: 36px 24px; gap: 32px; position: relative; overflow: hidden; }
            .profile-socials {
                justify-content: center;
                align-items: center;
                position: relative;
                z-index: 2;
                list-style: none;
                -webkit-tap-highlight-color: transparent;
            }
            .social-btn {
                -webkit-tap-highlight-color: transparent;
                outline: none;
                -webkit-touch-callout: none;
                user-select: none;
                -webkit-user-select: none;
            }
            .social-btn:focus, .social-btn:active {
                outline: none;
                box-shadow: none;
            }
            .social-btn::before, .social-btn::after,
            .profile-socials::before, .profile-socials::after {
                display: none !important;
                content: none !important;
            }
            .logo-marquee-wrapper::before, .logo-marquee-wrapper::after { width: 8%; }
            .logo-item { padding: 4px 10px; }
            .logo-item img { height: 36px; max-width: 125px; }
            .logo-marquee-track { gap: 32px; }
            .logo-marquee-track.reverse { margin-top: 18px; }
        }
        @media (max-width: 640px) {
            .logo-marquee-wrapper { padding: 12px 0; }
            .logo-item { padding: 2px 6px; }
            .logo-item img { height: 30px; max-width: 100px; }
            .logo-marquee-track { gap: 20px; }
            .logo-marquee-track.reverse { margin-top: 14px; }
        }


        /* Wawasan Bisnis Section */
        .wawasan-section {
            padding: 100px 24px;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
            overflow: hidden;
            background: radial-gradient(ellipse 90% 60% at 50% 0%, rgba(10, 25, 64, 0.55) 0%, rgba(3, 7, 18, 0) 75%);
        }
        .wawasan-top-aurora {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 900px;
            max-width: 95vw;
            height: 380px;
            background: radial-gradient(ellipse 75% 100% at 50% 0%, rgba(0, 212, 255, 0.28) 0%, rgba(124, 58, 237, 0.18) 45%, transparent 75%);
            pointer-events: none;
            filter: blur(40px);
            z-index: 0;
            animation: wawasan-aurora-breathe 8s ease-in-out infinite alternate;
        }
        .wawasan-ambient-glow {
            position: absolute;
            top: 10%;
            left: -50px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.24) 0%, rgba(0, 168, 255, 0.10) 45%, transparent 70%);
            pointer-events: none;
            filter: blur(65px);
            z-index: 0;
            animation: ambient-pulse-blue 9s ease-in-out infinite alternate;
        }
        .wawasan-ambient-glow-purple {
            position: absolute;
            bottom: 5%;
            right: -50px;
            width: 650px;
            height: 650px;
            background: radial-gradient(circle, rgba(147, 51, 234, 0.24) 0%, rgba(219, 39, 119, 0.12) 45%, transparent 70%);
            pointer-events: none;
            filter: blur(75px);
            z-index: 0;
            animation: ambient-pulse-purple 11s ease-in-out infinite alternate;
        }
        .wawasan-ambient-glow-center {
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 450px;
            background: radial-gradient(ellipse, rgba(0, 168, 255, 0.12) 0%, rgba(124, 58, 237, 0.08) 40%, transparent 70%);
            pointer-events: none;
            filter: blur(80px);
            z-index: 0;
        }
        .wawasan-grid-mesh {
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(rgba(0, 212, 255, 0.22) 1.2px, transparent 1.2px),
                linear-gradient(rgba(0, 212, 255, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.04) 1px, transparent 1px);
            background-size: 32px 32px, 64px 64px, 64px 64px;
            pointer-events: none;
            z-index: 0;
            mask-image: radial-gradient(ellipse 85% 75% at 50% 45%, rgba(0,0,0,1) 30%, transparent 85%);
            -webkit-mask-image: radial-gradient(ellipse 85% 75% at 50% 45%, rgba(0,0,0,1) 30%, transparent 85%);
        }
        .wawasan-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(32px, 4vw, 48px);
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.2;
            margin-bottom: 24px;
            background: linear-gradient(135deg, #ffffff 40%, #a5f3fc 75%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 4px 24px rgba(0, 212, 255, 0.22));
        }
        @keyframes wawasan-aurora-breathe {
            0% { opacity: 0.7; transform: translateX(-50%) scale(0.95); }
            100% { opacity: 1; transform: translateX(-50%) scale(1.08); }
        }
        .wawasan-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
            margin-top: 50px;
            position: relative;
            z-index: 2;
        }
        .wawasan-actions {
            display: flex;
            justify-content: center;
            margin-top: 48px;
            position: relative;
            z-index: 2;
        }
        .btn-lihat-semua-artikel {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 34px;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.16) 0%, rgba(124, 58, 237, 0.20) 100%);
            border: 1.5px solid rgba(0, 212, 255, 0.65);
            border-radius: 100px;
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 24px rgba(0, 212, 255, 0.30), 0 4px 20px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.30);
        }
        .btn-lihat-semua-artikel:hover {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.30) 0%, rgba(124, 58, 237, 0.35) 100%);
            border-color: var(--cyan-neon);
            color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 0 35px rgba(0, 212, 255, 0.65), 0 8px 30px rgba(124, 58, 237, 0.50), inset 0 1px 0 rgba(255, 255, 255, 0.50);
        }
        .btn-lihat-semua-artikel svg {
            transition: transform 0.3s ease;
            color: var(--cyan-neon);
            filter: drop-shadow(0 0 6px var(--cyan-neon));
        }
        .btn-lihat-semua-artikel:hover svg {
            transform: translateX(5px);
            filter: drop-shadow(0 0 10px var(--cyan-neon));
        }
        html[data-theme="light"] .btn-lihat-semua-artikel {
            background: #ffffff;
            border-color: rgba(2, 132, 199, 0.35);
            color: #0f172a;
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.10);
        }
        html[data-theme="light"] .btn-lihat-semua-artikel:hover {
            background: rgba(2, 132, 199, 0.08);
            border-color: #0284c7;
            color: #0284c7;
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.20);
        }

        .article-card {
            background: rgba(10, 20, 50, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.08);
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
        }
        .article-card::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(0,168,255,0.08), rgba(124,58,237,0.08));
            opacity: 0; transition: opacity 0.4s;
            pointer-events: none;
        }
        /* Top Laser Line Accent */
        .article-card::after {
            content: '';
            position: absolute;
            top: 0; left: 20%; right: 20%;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, rgba(0, 212, 255, 0.65), rgba(167, 139, 250, 0.65), transparent);
            opacity: 0.5;
            transition: all 0.35s ease;
            pointer-events: none;
            z-index: 3;
        }
        .article-card:hover {
            transform: translateY(-8px);
            border-color: rgba(0, 168, 255, 0.35);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.45), 0 0 30px rgba(0, 168, 255, 0.2);
        }
        .article-card:hover::before { opacity: 1; }
        .article-card:hover::after {
            left: 5%;
            right: 5%;
            opacity: 1;
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.8);
        }
        .article-img-wrap {
            width: 100%;
            height: 200px;
            position: relative;
            overflow: hidden;
        }
        .article-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            filter: brightness(0.85);
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
        }
        .article-date {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.4);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .article-title {
            font-size: 19px;
            font-weight: 700;
            line-height: 1.4;
            color: #fff;
            margin-bottom: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: color 0.3s ease;
            letter-spacing: -0.3px;
        }
        .article-card:hover .article-title {
            color: var(--cyan-neon);
        }
        .article-desc {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.55);
            line-height: 1.6;
            margin-bottom: 28px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .article-btn {
            margin-top: auto;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
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

        @media (max-width: 1024px) {
            .wawasan-grid { grid-template-columns: repeat(2, 1fr); }
            .article-img-wrap { height: 220px; }
        }
        @media (max-width: 768px) {
            .tentang-section { padding: 64px 20px 64px; }
            .wawasan-section { padding: 64px 20px; }
            .konsultasi-section { padding: 64px 20px; }
        }
        @media (max-width: 640px) {
            .wawasan-grid { grid-template-columns: 1fr; gap: 20px; }
            .article-img-wrap { height: 200px; }
            .tentang-section { padding: 48px 16px; }
            .wawasan-section { padding: 48px 16px; }
            .konsultasi-section { padding: 48px 16px; }
        }

        /* ==========================================
           KONSULTASI SECTION
        ========================================== */
        .konsultasi-section {
            padding: 110px 24px;
            max-width: 1360px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
            overflow: visible;
        }
        .konsultasi-bg-aurora {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 85%;
            height: 380px;
            background: radial-gradient(ellipse at center, rgba(0, 212, 255, 0.22) 0%, rgba(124, 58, 237, 0.18) 45%, transparent 72%);
            filter: blur(55px);
            pointer-events: none;
            z-index: 0;
            animation: pulse-glow-aura 7s ease-in-out infinite alternate;
        }
        .konsultasi-grid-mesh {
            position: absolute;
            inset: -40px;
            background-image: linear-gradient(rgba(0, 212, 255, 0.035) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(0, 212, 255, 0.035) 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
            z-index: 0;
            mask-image: radial-gradient(ellipse at center, rgba(0,0,0,1) 40%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse at center, rgba(0,0,0,1) 40%, transparent 75%);
        }
        .konsultasi-ambient-glow-left {
            position: absolute;
            top: 15%;
            left: -80px;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.15) 0%, transparent 68%);
            pointer-events: none;
            filter: blur(65px);
            z-index: 0;
            animation: ambient-pulse-blue 8s ease-in-out infinite alternate;
        }
        .konsultasi-ambient-glow-right {
            position: absolute;
            bottom: 10%;
            right: -80px;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.16) 0%, transparent 68%);
            pointer-events: none;
            filter: blur(65px);
            z-index: 0;
            animation: ambient-pulse-purple 10s ease-in-out infinite alternate;
        }

        /* KONSULTASI SIDE DECORATIONS */
        .konsultasi-wrapper {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .konsultasi-side-decor {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 195px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            pointer-events: none;
            z-index: 2;
        }
        .konsultasi-side-left {
            left: 12px;
            align-items: flex-start;
            animation: float-hud-left 7s ease-in-out infinite;
        }
        .konsultasi-side-right {
            right: 12px;
            align-items: flex-end;
            animation: float-hud-right 8s ease-in-out infinite;
        }

        /* Telemetry Pill */
        .konsultasi-telemetry-pill {
            background: rgba(10, 20, 55, 0.70);
            border: 1px solid rgba(0, 212, 255, 0.28);
            border-radius: 12px;
            padding: 9px 14px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35), 0 0 16px rgba(0, 212, 255, 0.12);
            width: 175px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .konsultasi-telemetry-pill.pill-alt {
            border-color: rgba(167, 139, 250, 0.30);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35), 0 0 16px rgba(124, 58, 237, 0.15);
        }
        .pill-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
            animation: blink-dot 1.8s ease infinite;
        }
        .pill-dot-cyan {
            background: var(--cyan-neon);
            box-shadow: 0 0 10px var(--cyan-neon);
        }
        .pill-dot-purple {
            background: #a78bfa;
            box-shadow: 0 0 10px #a78bfa;
        }
        .pill-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .pill-title {
            font-family: 'Plus Jakarta Sans', monospace;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: var(--cyan-neon);
        }
        .pill-alt .pill-title {
            color: #a78bfa;
        }
        .pill-sub {
            font-size: 9px;
            color: rgba(255, 255, 255, 0.55);
            letter-spacing: 0.3px;
        }

        /* Equalizer Frequency Bars */
        .konsultasi-eq-bars {
            display: flex;
            align-items: flex-end;
            gap: 5px;
            height: 28px;
            padding: 4px 8px;
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid rgba(0, 212, 255, 0.18);
            border-radius: 8px;
            width: 120px;
        }
        .eq-bar {
            flex: 1;
            background: linear-gradient(180deg, #00d4ff 0%, #7c3aed 100%);
            border-radius: 2px;
            transform-origin: bottom;
            animation: eq-pulse 1.6s ease-in-out infinite alternate;
        }
        .eq-b1 { height: 40%; animation-delay: 0.1s; }
        .eq-b2 { height: 75%; animation-delay: 0.3s; }
        .eq-b3 { height: 100%; animation-delay: 0.5s; }
        .eq-b4 { height: 60%; animation-delay: 0.2s; }
        .eq-b5 { height: 90%; animation-delay: 0.4s; }
        .eq-b6 { height: 50%; animation-delay: 0.15s; }
        .eq-b7 { height: 80%; animation-delay: 0.35s; }

        @keyframes eq-pulse {
            0% { transform: scaleY(0.3); opacity: 0.6; }
            50% { transform: scaleY(1); opacity: 1; }
            100% { transform: scaleY(0.4); opacity: 0.7; }
        }

        /* Side SVG widgets */
        .konsultasi-side-svg {
            width: 130px;
            height: 130px;
            filter: drop-shadow(0 0 12px rgba(0, 212, 255, 0.18));
        }

        /* Response Gauge */
        .konsultasi-response-gauge {
            width: 165px;
            background: rgba(10, 20, 55, 0.65);
            border: 1px solid rgba(167, 139, 250, 0.25);
            border-radius: 10px;
            padding: 8px 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .gauge-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: rgba(255, 255, 255, 0.55);
            font-family: 'Plus Jakarta Sans', monospace;
        }
        .gauge-val {
            font-weight: 700;
            color: #a78bfa;
        }
        .gauge-bar {
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 2px;
            overflow: hidden;
            position: relative;
        }
        .gauge-fill {
            position: absolute;
            top: 0; left: 0; bottom: 0;
            width: 85%;
            background: linear-gradient(90deg, #7c3aed, #00d4ff);
            border-radius: 2px;
            animation: pulse-glow-aura 4s ease-in-out infinite alternate;
        }

        @media (max-width: 1240px) {
            .konsultasi-side-decor { width: 155px; }
            .konsultasi-telemetry-pill { width: 140px; padding: 7px 10px; }
            .konsultasi-side-svg { width: 105px; height: 105px; }
            .konsultasi-response-gauge { width: 135px; }
        }
        @media (max-width: 1024px) {
            .konsultasi-side-decor {
                display: flex;
                opacity: 0.40;
                pointer-events: none;
                z-index: 1;
            }
            .konsultasi-side-left {
                left: -20px;
                transform: translateY(-50%) scale(0.72);
                transform-origin: left center;
            }
            .konsultasi-side-right {
                right: -20px;
                transform: translateY(-50%) scale(0.72);
                transform-origin: right center;
            }
        }
        @media (max-width: 768px) {
            .konsultasi-side-decor {
                opacity: 0.24;
            }
            .konsultasi-side-left {
                left: -40px;
                transform: translateY(-50%) scale(0.52);
            }
            .konsultasi-side-right {
                right: -40px;
                transform: translateY(-50%) scale(0.52);
            }
        }
        @media (max-width: 480px) {
            .konsultasi-side-decor {
                opacity: 0.16;
            }
            .konsultasi-side-left {
                left: -55px;
                transform: translateY(-50%) scale(0.42);
            }
            .konsultasi-side-right {
                right: -55px;
                transform: translateY(-50%) scale(0.42);
            }
        }

        .konsultasi-card {
            background: linear-gradient(135deg, rgba(12, 22, 65, 0.70) 0%, rgba(18, 14, 50, 0.65) 50%, rgba(8, 16, 52, 0.75) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 212, 255, 0.32);
            border-radius: 28px;
            padding: 52px 40px 48px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.50),
                        0 0 35px rgba(0, 212, 255, 0.12),
                        inset 0 1px 0 rgba(255, 255, 255, 0.15),
                        inset 0 0 40px rgba(0, 212, 255, 0.04);
            transition: all 0.4s ease;
            max-width: 860px;
            width: 100%;
            margin: 0 auto;
        }
        .konsultasi-card:hover {
            border-color: rgba(0, 212, 255, 0.50);
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.55),
                        0 0 50px rgba(0, 212, 255, 0.22),
                        inset 0 1px 0 rgba(255, 255, 255, 0.25);
        }
        .konsultasi-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan-neon), var(--purple-neon), transparent);
            z-index: 2;
        }
        .konsultasi-card::after {
            content: '';
            position: absolute;
            top: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 220px;
            height: 3.5px;
            background: radial-gradient(ellipse at center, #ffffff 0%, #00d4ff 40%, #7c3aed 70%, transparent 85%);
            filter: blur(1px);
            border-radius: 50%;
            box-shadow: 0 0 16px #00d4ff, 0 0 32px rgba(124, 58, 237, 0.6);
            z-index: 3;
            pointer-events: none;
        }
        .konsultasi-card-corners {
            position: absolute;
            inset: 12px;
            pointer-events: none;
            z-index: 2;
        }
        .card-corner {
            position: absolute;
            width: 14px;
            height: 14px;
            border-color: rgba(0, 212, 255, 0.5);
            border-style: solid;
        }
        .card-corner-tl { top: 0; left: 0; border-width: 2px 0 0 2px; border-radius: 4px 0 0 0; }
        .card-corner-tr { top: 0; right: 0; border-width: 2px 2px 0 0; border-radius: 0 4px 0 0; }
        .card-corner-bl { bottom: 0; left: 0; border-width: 0 0 2px 2px; border-radius: 0 0 0 4px; }
        .card-corner-br { bottom: 0; right: 0; border-width: 0 2px 2px 0; border-radius: 0 0 4px 0; }

        .konsultasi-glow {
            position: absolute;
            width: 440px; height: 320px;
            background: radial-gradient(ellipse at center, rgba(0, 212, 255, 0.16) 0%, rgba(124, 58, 237, 0.14) 45%, transparent 70%);
            top: 45%; left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
            filter: blur(25px);
            animation: pulse-glow-aura 6s ease-in-out infinite alternate;
        }
        .konsultasi-lines {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(0, 212, 255, 0.035) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(0, 212, 255, 0.035) 1px, transparent 1px);
            background-size: 28px 28px;
            opacity: 0.8;
            z-index: 0;
            pointer-events: none;
            mask-image: radial-gradient(ellipse at center, rgba(0,0,0,1) 35%, transparent 80%);
            -webkit-mask-image: radial-gradient(ellipse at center, rgba(0,0,0,1) 35%, transparent 80%);
        }
        .konsultasi-radar-wave {
            position: absolute;
            top: 75px;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 140px;
            height: 140px;
            pointer-events: none;
            z-index: 1;
        }
        .radar-wave-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 1px dashed rgba(0, 212, 255, 0.35);
            animation: radar-expand 6s linear infinite;
        }
        .radar-wave-ring-2 {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 1px dashed rgba(167, 139, 250, 0.35);
            animation: radar-expand 6s linear infinite;
            animation-delay: -3s;
        }
        @keyframes radar-expand {
            0% { transform: scale(0.6); opacity: 0; }
            40% { opacity: 0.8; }
            100% { transform: scale(1.6); opacity: 0; }
        }
        .konsultasi-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .konsultasi-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.18), rgba(124, 58, 237, 0.22));
            border: 1.5px solid rgba(0, 212, 255, 0.45);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            color: var(--cyan-neon);
            box-shadow: 0 0 25px rgba(0, 212, 255, 0.35), inset 0 0 12px rgba(0, 212, 255, 0.15);
            animation: float-icon 6s ease-in-out infinite;
        }
        .konsultasi-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--cyan-neon);
            margin-bottom: 10px;
        }
        .konsultasi-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(26px, 3.2vw, 38px);
            font-weight: 800;
            letter-spacing: -0.8px;
            margin-bottom: 12px;
            color: #fff;
        }
        .konsultasi-desc {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.60);
            line-height: 1.6;
            max-width: 520px;
            margin-bottom: 28px;
        }
        .konsultasi-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 13px 28px;
            background: linear-gradient(135deg, #00a8ff 0%, #7c3aed 100%);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: none;
            border-radius: 100px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 18px rgba(0, 168, 255, 0.28);
        }
        .konsultasi-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 26px rgba(0, 168, 255, 0.42);
        }
        @keyframes float-icon {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        /* ==========================================
           KONSULTASI OPEN-MODAL BUTTON
        ========================================== */
        .konsultasi-open-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: linear-gradient(135deg, var(--cyan-neon) 0%, #7c3aed 100%);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: none;
            border-radius: 100px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 24px rgba(0, 168, 255, 0.32);
            text-decoration: none;
            margin-bottom: 0;
        }
        .konsultasi-open-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0, 168, 255, 0.48);
        }
        .konsultasi-btn-row {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
        }
        /* ==========================================
           MODAL
        ========================================== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0, 5, 20, 0.80);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-box {
            position: relative;
            width: 100%;
            max-width: 520px;
            background: linear-gradient(160deg, rgba(10,20,60,0.98) 0%, rgba(5,10,40,0.98) 100%);
            border: 1px solid rgba(0,212,255,0.25);
            border-radius: 22px;
            padding: 40px 36px 36px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.06);
            animation: modalSlideIn 0.35s cubic-bezier(0.34,1.56,0.64,1) both;
        }
        .modal-box::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 2px;
            border-radius: 22px 22px 0 0;
            background: linear-gradient(90deg, transparent, var(--cyan-neon), #7c3aed, transparent);
        }
        @keyframes modalSlideIn {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .modal-close {
            position: absolute;
            top: 16px; right: 18px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.6);
            width: 34px; height: 34px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 18px;
            line-height: 1;
        }
        .modal-close:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }
        .modal-header {
            margin-bottom: 24px;
        }
        .modal-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--cyan-neon);
            margin-bottom: 8px;
        }
        .modal-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            margin: 0 0 6px;
        }
        .modal-subtitle {
            font-size: 13px;
            color: rgba(255,255,255,0.5);
            line-height: 1.5;
            margin: 0;
        }
        .modal-form-errors {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 18px;
            color: #fca5a5;
            font-size: 13px;
            line-height: 1.5;
        }
        .modal-form-errors-title {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            color: #f87171;
        }
        .modal-form .form-group-c { margin-bottom: 16px; }
        .modal-form .form-label-c { display: block; font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.8); margin-bottom: 7px; }
        .modal-form .form-control-c {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.12);
            color: #fff;
            padding: 13px 15px;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        .modal-form .form-control-c:focus {
            outline: none;
            border-color: var(--cyan-neon);
            background: rgba(0,212,255,0.03);
            box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
        }
        .modal-form .form-control-c::placeholder { color: rgba(255,255,255,0.28); }
        .modal-form textarea.form-control-c { resize: vertical; min-height: 100px; }
        .modal-form .btn-submit-c {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--cyan-neon) 0%, #7c3aed 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            box-shadow: 0 6px 20px rgba(0,168,255,0.28);
        }
        .modal-form .btn-submit-c:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(0,168,255,0.42); }
        .modal-success {
            text-align: center;
            padding: 20px 0 10px;
        }
        .modal-success-icon {
            width: 60px; height: 60px;
            background: rgba(74,222,128,0.12);
            border: 1px solid rgba(74,222,128,0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            font-size: 26px;
        }
        .modal-success h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin: 0 0 8px;
        }
        .modal-success p {
            font-size: 14px;
            color: rgba(255,255,255,0.55);
            margin: 0 0 20px;
            line-height: 1.5;
        }
        .modal-success .btn-close-success {
            display: inline-block;
            padding: 10px 28px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.8);
            border-radius: 100px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .modal-success .btn-close-success:hover { background: rgba(255,255,255,0.14); color: #fff; }
        @media (max-width: 640px) {
            .konsultasi-card {
                padding: 32px 18px 28px;
                border-radius: 20px;
                position: relative;
                z-index: 2;
            }
        }
        @media (max-width: 540px) {
            .modal-box {
                padding: 28px 18px 24px;
                border-radius: 20px;
                max-width: 100%;
            }
            .konsultasi-btn-row {
                flex-direction: column;
                gap: 12px;
                width: 100%;
            }
            .konsultasi-btn-row .konsultasi-open-btn,
            .konsultasi-btn-row .konsultasi-btn {
                width: 100%;
                justify-content: center;
                box-sizing: border-box;
                text-align: center;
            }
        }
        @keyframes ambient-pulse-blue {
            0% { transform: scale(1) translate(0, 0); opacity: 0.7; }
            50% { transform: scale(1.15) translate(20px, -15px); opacity: 1; }
            100% { transform: scale(0.95) translate(-10px, 15px); opacity: 0.75; }
        }
        @keyframes ambient-pulse-purple {
            0% { transform: scale(1) translate(0, 0); opacity: 0.7; }
            50% { transform: scale(1.18) translate(-25px, 20px); opacity: 1; }
            100% { transform: scale(0.92) translate(15px, -10px); opacity: 0.8; }
        }
        @keyframes pulse-glow-aura {
            0% { opacity: 0.5; transform: translate(-50%, -50%) scale(0.95); }
            100% { opacity: 0.9; transform: translate(-50%, -50%) scale(1.08); }
        }
        @keyframes circuit-flow {
            0% { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }
        @keyframes circuit-flow-reverse {
            0% { stroke-dashoffset: 0; }
            100% { stroke-dashoffset: 100; }
        }
        @keyframes node-pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.5); opacity: 1; }
        }
        @keyframes hud-spin-cw {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes hud-spin-ccw {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }
        @keyframes float-hud-left {
            0%, 100% { transform: translateY(-50%) translate(0, 0); }
            50% { transform: translateY(-50%) translate(4px, -12px); }
        }
        @keyframes float-hud-right {
            0%, 100% { transform: translateY(-50%) translate(0, 0); }
            50% { transform: translateY(-50%) translate(-4px, -14px); }
        }
        @keyframes laser-slide-lr {
            0% { left: 0%; opacity: 0; }
            20% { opacity: 1; }
            80% { opacity: 1; }
            100% { left: calc(100% - 30px); opacity: 0; }
        }
        @keyframes laser-slide-rl {
            0% { left: calc(100% - 30px); opacity: 0; }
            20% { opacity: 1; }
            80% { opacity: 1; }
            100% { left: 0%; opacity: 0; }
        }

        @keyframes hero-text-float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-9px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        @keyframes hero-line1-glow {
            0% {
                text-shadow: 0 0 25px rgba(255, 255, 255, 0.15), 0 0 50px rgba(0, 212, 255, 0.15);
            }
            50% {
                text-shadow: 0 0 35px rgba(255, 255, 255, 0.35), 0 0 70px rgba(0, 212, 255, 0.40), 0 0 90px rgba(124, 58, 237, 0.25);
            }
            100% {
                text-shadow: 0 0 25px rgba(255, 255, 255, 0.15), 0 0 50px rgba(0, 212, 255, 0.15);
            }
        }

        @keyframes hero-gradient-flow {
            0% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 250% 50%;
            }
        }

        @keyframes hero-gradient-glow {
            0% {
                filter: drop-shadow(0 0 16px rgba(0, 168, 255, 0.25));
            }
            50% {
                filter: drop-shadow(0 0 32px rgba(167, 139, 250, 0.55)) drop-shadow(0 0 50px rgba(0, 212, 255, 0.40));
            }
            100% {
                filter: drop-shadow(0 0 16px rgba(0, 168, 255, 0.25));
            }
        }

        @keyframes hero-sub-shimmer {
            0% {
                opacity: 0.75;
                transform: translateY(0px);
            }
            50% {
                opacity: 0.95;
                transform: translateY(-4px);
            }
            100% {
                opacity: 0.75;
                transform: translateY(0px);
            }
        }
    </style>
</head>
<body>

<!-- ==========================================
     HERO SECTION (Full Screen)
========================================== -->
<section class="hero-section" id="home">

    <!-- Background image -->
    <div class="hero-bg" role="img" aria-label="AI Technology Background"></div>

    <!-- Dark transparent overlay -->
    <div class="hero-overlay"></div>

    <!-- Vignette -->
    <div class="hero-vignette"></div>

    <!-- Ambient glow blobs -->
    <div class="hero-glow-1"></div>
    <div class="hero-glow-2"></div>
    <div class="hero-glow-3"></div>

    <!-- Animated particles -->
    <div class="particles-container" id="particles-container"></div>

    <!-- Bottom fade -->
    <div class="hero-bottom-fade"></div>

    <!-- ==========================================
         NAVBAR
    ========================================== -->
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        @php
            $isCustomLogo = (isset($cmsHomepage) && ($cmsHomepage->site_logo_type ?? '') === 'custom' && $cmsHomepage->site_logo_url);
            $siteHl = isset($cmsHomepage) && $cmsHomepage->site_name_highlight ? $cmsHomepage->site_name_highlight : 'AI';
            $siteRs = isset($cmsHomepage) && $cmsHomepage->site_name_rest ? $cmsHomepage->site_name_rest : 'Jogja Solutions';
            $siteFull = trim($siteHl . ' ' . $siteRs);
        @endphp
        <!-- Logo -->
        <a href="/" class="nav-logo" aria-label="{{ $siteFull }} Home">
            <div class="nav-logo-icon" aria-hidden="true">
                @if($isCustomLogo)
                    <img src="{{ $cmsHomepage->site_logo_url }}" alt="{{ $siteFull }}" style="width:100%;height:100%;object-fit:contain;padding:3px;">
                @else
                    <!-- AI icon: neural network / brain -->
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

        <!-- Desktop nav links -->
        <ul class="nav-links" role="list">
            <li><a href="/#layanan" id="nav-layanan">Layanan</a></li>
            <li>
                <!-- Nomor diambil dari CMS Kontak (waUrl) -->
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" id="nav-kontak" style="display:inline-flex;align-items:center;gap:6px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>Kontak</a>
            </li>
            <li><a href="/#tentang" id="nav-tentang">Tentang Kami</a></li>
            <li><a href="/#wawasan" id="nav-wawasan">Wawasan</a></li>
        </ul>

        <!-- Nav right: search + cta -->
        <div class="nav-right">
            <!-- Search bar -->
            <button class="nav-search" id="nav-search-btn" aria-label="Cari artikel, layanan AI, atau konten" onclick="document.getElementById('search-modal').style.display='flex'; setTimeout(function(){ document.getElementById('search-input').focus(); }, 60);">
                <svg class="nav-search-icon" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M11 11L14 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <span class="nav-search-text">Cari artikel, layanan...</span>
                <kbd class="nav-search-kbd">Ctrl K</kbd>
            </button>



            <!-- Mobile hamburger -->
            <button class="nav-hamburger" id="hamburger-btn" aria-label="Toggle menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- Mobile menu -->
    <div class="nav-mobile-menu" id="mobile-menu" role="menu">
        <a href="/#layanan" role="menuitem">Layanan</a>
        <!-- Nomor diambil dari CMS Kontak (waUrl) -->
        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" role="menuitem">Kontak</a>
        <a href="/#tentang" role="menuitem">Tentang Kami</a>
        <a href="/#wawasan" role="menuitem">Wawasan Bisnis</a>

    </div>

    <!-- ==========================================
         HERO CONTENT (center text)
    ========================================== -->
    <div class="hero-content">
        @php
            $heroBadge = (isset($cmsHomepage) && $cmsHomepage->hero_badge) ? $cmsHomepage->hero_badge : 'JASA AI UNTUK KEBUTUHAN ANDA';
            
            $heroTitle = (isset($cmsHomepage) && $cmsHomepage->hero_title) ? $cmsHomepage->hero_title : "Solusi AI Cerdas Untuk semua pekerjaan";
            $words = explode(' ', $heroTitle);
            $half = ceil(count($words) / 2);
            $titleLine1 = implode(' ', array_slice($words, 0, $half));
            $titleLine2 = implode(' ', array_slice($words, $half));

            $heroDesc  = (isset($cmsHomepage) && $cmsHomepage->hero_description) ? $cmsHomepage->hero_description : 'Layanan AI inovatif untuk membantu bisnis dan individu bekerja lebih efektif, meningkatkan produktivitas, dan mewujudkan ide menjadi solusi nyata.';
            
            $heroBtn1Text = (isset($cmsHomepage) && $cmsHomepage->hero_btn1_text) ? $cmsHomepage->hero_btn1_text : 'Lihat Layanan AI';
            $heroBtn1Link = (isset($cmsHomepage) && $cmsHomepage->hero_btn1_link) ? $cmsHomepage->hero_btn1_link : '#layanan';
            
            $heroBtn2Text = (isset($cmsHomepage) && $cmsHomepage->hero_btn2_text) ? $cmsHomepage->hero_btn2_text : 'Konsultasikan Kebutuhan';
            $heroBtn2Link = (isset($cmsHomepage) && $cmsHomepage->hero_btn2_link) ? $cmsHomepage->hero_btn2_link : '#konsultasi';
        @endphp

        <!-- Release badge -->
        <div class="hero-badge" role="status" aria-label="AI SOLUTION">
            <span class="badge-dot" aria-hidden="true"></span>
            AI SOLUTION
            <span class="badge-divider" aria-hidden="true">•</span>
            <span class="badge-speed">{{ $heroBadge }}</span>
        </div>

        <!-- Main heading -->
        <h1 class="hero-heading">
            <span class="hero-heading-line1">{{ $titleLine1 }}</span>
            <span class="hero-heading-line2">{{ $titleLine2 }}</span>
        </h1>

        <!-- Subheading -->
        <p class="hero-subheading">
            {{ $heroDesc }}
        </p>

        <!-- CTA Buttons -->
        <div class="hero-buttons">
            @if($heroBtn1Text)
            <a href="{{ $heroBtn1Link }}" class="btn-primary" id="hero-btn-studio">
                {{ $heroBtn1Text }}
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
            @endif
            @if($heroBtn2Text)
            <a href="{{ $heroBtn2Link }}" class="btn-secondary" id="hero-btn-benchmark">
                {{ $heroBtn2Text }}
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 6px;">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
            @endif
        </div>

    </div>
    <!-- /hero content -->

    <!-- Bottom fade -->
    <div class="hero-bottom-fade"></div>

    <!-- Scroll indicator -->
    <div class="scroll-indicator" aria-hidden="true">
        <span>SCROLL</span>
        <div class="scroll-arrow"></div>
    </div>

</section>
<!-- /hero section -->

<!-- ==========================================
     PENGENALAN / KREASI CERDAS SECTION
========================================== -->
<div class="section-divider-line"></div>

<section id="pengenalan" class="layanan-section">
    <div class="section-top-aurora"></div>
    <div class="ambient-glow-top-bridge"></div>
    <div class="ambient-glow-4"></div>
    <div class="ambient-glow-blue-left"></div>
    <div class="section-grid-mesh"></div>
    
    <!-- AI Circuit Decoration -->
    <div class="layanan-circuit-decor">
        <svg class="layanan-circuit-left" viewBox="0 0 200 600" preserveAspectRatio="xMinYMid meet">
            <path class="circuit-line" d="M0,50 L40,50 L80,90 L80,250 L120,290 L120,400" />
            <path class="circuit-line circuit-line-brown" d="M0,100 L30,100 L60,130 L60,350 L90,380 L90,450" />
            <path class="circuit-line circuit-line-navy" d="M0,150 L20,150 L50,180 L50,420 L70,440" />
            
            <circle class="circuit-node-glow" cx="120" cy="400" r="10"/>
            <circle class="circuit-node" cx="120" cy="400" r="3"/>
            
            <circle class="circuit-node-glow" cx="80" cy="250" r="8"/>
            <circle class="circuit-node" cx="80" cy="250" r="2.5"/>
            
            <circle class="circuit-node-glow" cx="90" cy="450" r="8"/>
            <circle class="circuit-node" cx="90" cy="450" r="2"/>
        </svg>
        <svg class="layanan-circuit-right" viewBox="0 0 200 600" preserveAspectRatio="xMaxYMid meet">
            <path class="circuit-line" d="M200,80 L160,80 L120,120 L120,300 L80,340 L80,450" />
            <path class="circuit-line circuit-line-brown" d="M200,120 L170,120 L140,150 L140,250 L100,290 L100,380" />
            <path class="circuit-line circuit-line-navy" d="M200,180 L180,180 L150,210 L150,400 L130,420" />
            
            <circle class="circuit-node-glow" cx="80" cy="450" r="10"/>
            <circle class="circuit-node" cx="80" cy="450" r="3"/>
            
            <circle class="circuit-node-glow" cx="100" cy="290" r="8"/>
            <circle class="circuit-node" cx="100" cy="290" r="2.5"/>
            
            <circle class="circuit-node-glow" cx="120" cy="120" r="8"/>
            <circle class="circuit-node" cx="120" cy="120" r="2"/>
        </svg>
    </div>

    <!-- HEADER -->
    <header class="page-header">
        <h1 class="page-title">Kreasi Cerdas AI</h1>
        <p class="page-subtitle">Beragam layanan berbasis AI untuk membantu bisnis dan individu menyederhanakan pekerjaan, meningkatkan produktivitas, dan mencapai hasil yang lebih optimal.</p>
    </header>

    <!-- WHAT IS AI SOLUTION -->
    <div class="section-what-is">
        <div class="section-tag">Pengenalan</div>
        <h2 class="section-title">Apa Itu AI Solution?</h2>
        <p class="section-desc">
            AI Solution adalah penyedia jasa dan layanan berbasis kecerdasan buatan (AI) yang membantu bisnis dan individu memenuhi berbagai kebutuhan pekerjaan melalui solusi teknologi yang efektif, kreatif, dan inovatif.
        </p>

        <!-- FLOW DIAGRAM -->
        <div class="flow-diagram">
            
            <!-- Kebutuhan -->
            <div class="flow-card">
                <div class="flow-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M12 8V12L15 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="flow-text">KEBUTUHAN</div>
            </div>

            <!-- Arrow 1 -->
            <div class="flow-arrow">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <!-- AI Solution (Center) -->
            <div class="flow-card ai-center">
                <div class="flow-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 3L2 12H5V21H19V12H22L12 3Z" stroke="white" stroke-width="1.5" stroke-linejoin="round"/>
                        <circle cx="12" cy="14" r="3" fill="white"/>
                    </svg>
                </div>
                <div class="flow-text">AI</div>
            </div>

            <!-- Arrow 2 -->
            <div class="flow-arrow">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <!-- Solusi -->
            <div class="flow-card">
                <div class="flow-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 11.08V12C21.9988 14.1564 21.3005 16.2547 20.0093 17.9818C18.7182 19.709 16.9033 20.9725 14.8354 21.5839C12.7674 22.1953 10.5573 22.1219 8.53447 21.3746C6.51168 20.6273 4.78465 19.2461 3.61096 17.4371C2.43727 15.628 1.87979 13.4881 2.02168 11.3363C2.16356 9.18455 2.99721 7.13631 4.39828 5.49706C5.79935 3.85781 7.69279 2.71537 9.79619 2.24013C11.8996 1.7649 14.1003 1.98232 16.07 2.85999" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 4L12 14.01L9 11.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="flow-text">SOLUSI</div>
            </div>

        </div>
    </div>
</section>

<!-- ==========================================
     LAYANAN AI SECTION
========================================== -->
<div class="section-divider-line"></div>

<section id="layanan" class="services-section">
    <div class="section-top-aurora"></div>
    <div class="services-ambient-glow-1"></div>
    <div class="services-ambient-glow-2"></div>
    <div class="services-grid-mesh"></div>
    <div id="jasa"></div>
    <div class="services-header-wrapper">
        <div class="services-header-decor" aria-hidden="true">
            <!-- LEFT SIDE HUD -->
            <div class="services-hud services-hud-left">
                <!-- Floating Telemetry Glass Card -->
                <div class="hud-telemetry-badge">
                    <div class="hud-telemetry-status">
                        <span class="hud-status-dot"></span>
                        <span class="hud-status-text">NEURAL_CORE // v2.8</span>
                    </div>
                    <div class="hud-telemetry-metric">
                        <span class="hud-metric-label">CLUSTER</span>
                        <span class="hud-metric-val">16 NODES</span>
                    </div>
                    <div class="hud-telemetry-bar"><div class="hud-bar-fill"></div></div>
                </div>

                <!-- Cyber HUD Ring & Scanner Widget SVG -->
                <svg class="hud-scanner-svg" viewBox="0 0 240 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Corner Brackets -->
                    <path d="M10,40 L10,10 L40,10" stroke="rgba(0, 212, 255, 0.45)" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M230,40 L230,10 L200,10" stroke="rgba(0, 212, 255, 0.45)" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M10,200 L10,230 L40,230" stroke="rgba(0, 212, 255, 0.45)" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M230,200 L230,230 L200,230" stroke="rgba(0, 212, 255, 0.45)" stroke-width="1.5" stroke-linecap="round"/>

                    <!-- Outer Dashed Rotating Ring -->
                    <circle class="hud-ring-outer" cx="120" cy="120" r="88" stroke="rgba(0, 212, 255, 0.4)" stroke-width="1.5" stroke-dasharray="14 10" stroke-linecap="round"/>
                    
                    <!-- Mid Segmented Ring (Purple) -->
                    <circle class="hud-ring-mid" cx="120" cy="120" r="68" stroke="rgba(167, 139, 250, 0.45)" stroke-width="2" stroke-dasharray="40 20 8 20" stroke-linecap="round"/>
                    
                    <!-- Inner Glowing Ring -->
                    <circle class="hud-ring-inner" cx="120" cy="120" r="46" stroke="rgba(0, 212, 255, 0.55)" stroke-width="1.5" stroke-dasharray="6 6"/>
                    
                    <!-- Center Crosshair & Core Node -->
                    <line x1="120" y1="92" x2="120" y2="148" stroke="rgba(0, 212, 255, 0.4)" stroke-width="1"/>
                    <line x1="92" y1="120" x2="148" y2="120" stroke="rgba(0, 212, 255, 0.4)" stroke-width="1"/>
                    <circle cx="120" cy="120" r="14" fill="rgba(0, 212, 255, 0.12)" stroke="rgba(0, 212, 255, 0.7)" stroke-width="1.5"/>
                    <circle class="hud-center-node" cx="120" cy="120" r="5" fill="#00d4ff"/>
                    <circle class="hud-center-node-glow" cx="120" cy="120" r="10" fill="rgba(0, 212, 255, 0.35)"/>
                </svg>

                <!-- Horizontal Laser Beam with Data Ticks -->
                <div class="hud-vertical-laser">
                    <div class="hud-laser-line"></div>
                    <div class="hud-laser-runner"></div>
                    <div class="hud-laser-ticks">
                        <span></span><span></span><span></span><span></span><span></span>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE HUD -->
            <div class="services-hud services-hud-right">
                <!-- Floating Telemetry Glass Card -->
                <div class="hud-telemetry-badge hud-telemetry-badge-alt">
                    <div class="hud-telemetry-status">
                        <span class="hud-status-dot hud-status-dot-purple"></span>
                        <span class="hud-status-text">AI_PIPELINE // ACTIVE</span>
                    </div>
                    <div class="hud-telemetry-metric">
                        <span class="hud-metric-label">LATENCY</span>
                        <span class="hud-metric-val">&lt; 12MS · 99.9%</span>
                    </div>
                    <div class="hud-telemetry-bar hud-bar-purple"><div class="hud-bar-fill hud-bar-fill-purple"></div></div>
                </div>

                <!-- Cyber Hexagonal Matrix & Target Reticle SVG -->
                <svg class="hud-scanner-svg" viewBox="0 0 240 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Corner Brackets -->
                    <path d="M10,40 L10,10 L40,10" stroke="rgba(167, 139, 250, 0.45)" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M230,40 L230,10 L200,10" stroke="rgba(167, 139, 250, 0.45)" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M10,200 L10,230 L40,230" stroke="rgba(167, 139, 250, 0.45)" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M230,200 L230,230 L200,230" stroke="rgba(167, 139, 250, 0.45)" stroke-width="1.5" stroke-linecap="round"/>

                    <!-- Outer Hexagon Tech Shell -->
                    <polygon class="hud-hex-outer" points="120,28 198,73 198,167 120,212 42,167 42,73" stroke="rgba(124, 58, 237, 0.4)" stroke-width="1.5" stroke-dasharray="16 8"/>

                    <!-- Mid Orbital Ring -->
                    <circle class="hud-ring-outer" cx="120" cy="120" r="72" stroke="rgba(0, 212, 255, 0.45)" stroke-width="1.5" stroke-dasharray="24 16"/>

                    <!-- Inner Hexagon -->
                    <polygon points="120,55 176,87 176,153 120,185 64,153 64,87" stroke="rgba(0, 212, 255, 0.4)" stroke-width="1.2"/>

                    <!-- Center Target Node & Crosshairs -->
                    <line x1="78" y1="120" x2="162" y2="120" stroke="rgba(167, 139, 250, 0.5)" stroke-width="1"/>
                    <line x1="120" y1="78" x2="120" y2="162" stroke="rgba(167, 139, 250, 0.5)" stroke-width="1"/>
                    <circle cx="120" cy="120" r="14" fill="rgba(124, 58, 237, 0.15)" stroke="rgba(167, 139, 250, 0.7)" stroke-width="1.5"/>
                    <circle class="hud-center-node-purple" cx="120" cy="120" r="5" fill="#a78bfa"/>
                    <circle class="hud-center-node-glow-purple" cx="120" cy="120" r="10" fill="rgba(167, 139, 250, 0.35)"/>
                </svg>

                <!-- Horizontal Laser Beam with Data Ticks -->
                <div class="hud-vertical-laser hud-laser-purple">
                    <div class="hud-laser-line"></div>
                    <div class="hud-laser-runner hud-runner-purple"></div>
                    <div class="hud-laser-ticks">
                        <span></span><span></span><span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="jasa-header">
            <div class="jasa-badge">LAYANAN AI</div>
            <h1 class="jasa-title">Solusi AI yang Dirancang untuk Kebutuhan Anda</h1>
            <p class="jasa-subtitle">Kami membantu merancang dan menerapkan solusi berbasis AI untuk menyederhanakan proses, mengotomatisasi pekerjaan, dan menciptakan hasil yang lebih efektif.</p>
            <a href="#konsultasi" class="btn-cta">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                Konsultasikan Kebutuhan Anda
            </a>
        </div>
    </div>

    <div class="section-header">
        <h2 class="section-title">Jelajahi Layanan Kami</h2>
        <p class="section-subtitle">Berbagai solusi AI yang dapat disesuaikan dengan kebutuhan dan proses kerja Anda.</p>
    </div>

    <div class="services-grid">
        
        @foreach($services as $index => $service)
        <a href="/layanan/{{ $service['slug'] }}" class="service-card" style="text-decoration: none;">
            <div class="card-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
            <div class="card-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="{{ $service['icon'] }}"></path>
                </svg>
            </div>
            <h3 class="card-title">{{ $service['title'] }}</h3>
            <p class="card-desc">{{ \Illuminate\Support\Str::limit($service['desc'], 130) }}</p>
            <span class="card-btn">
                Lihat Detail
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </span>
        </a>
        @endforeach

    </div>
</section>

<!-- DETAIL MODAL -->
<div class="modal-overlay" id="detail-modal" onclick="closeModal(event)">
    <div class="modal-box" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeModal(event)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <div class="modal-icon" id="modal-icon-container">
            <svg id="modal-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="" id="modal-icon-path"></path>
            </svg>
        </div>
        <h3 class="modal-title-text" id="modal-title-text">Judul Layanan</h3>
        <p class="modal-desc" id="modal-desc">Deskripsi lengkap mengenai layanan AI akan tampil di sini. Solusi ini disesuaikan dengan infrastruktur bisnis Anda untuk mencapai efisiensi tertinggi.</p>
        <button class="modal-btn" onclick="closeModal(event)">Tutup Panel</button>
    </div>
</div>

<!-- ==========================================
     TENTANG KAMI SECTION
========================================== -->
<div class="section-divider-line"></div>

<section id="tentang" class="tentang-section">
    <div class="section-top-aurora"></div>
    <div class="tentang-ambient-glow-1"></div>
    <div class="tentang-ambient-glow-2"></div>
    <div class="section-grid-mesh"></div>
    <!-- AI Circuit Decoration -->
    <div class="layanan-circuit-decor">
        <svg class="layanan-circuit-left" viewBox="0 0 200 600" preserveAspectRatio="xMinYMid meet">
            <path class="circuit-line" d="M0,50 L40,50 L80,90 L80,250 L120,290 L120,400" />
            <path class="circuit-line circuit-line-brown" d="M0,100 L30,100 L60,130 L60,350 L90,380 L90,450" />
            <path class="circuit-line circuit-line-navy" d="M0,150 L20,150 L50,180 L50,420 L70,440" />
            
            <circle class="circuit-node-glow" cx="120" cy="400" r="10"/>
            <circle class="circuit-node" cx="120" cy="400" r="3"/>
            
            <circle class="circuit-node-glow" cx="80" cy="250" r="8"/>
            <circle class="circuit-node" cx="80" cy="250" r="2.5"/>
            
            <circle class="circuit-node-glow" cx="90" cy="450" r="8"/>
            <circle class="circuit-node" cx="90" cy="450" r="2"/>
        </svg>
        <svg class="layanan-circuit-right" viewBox="0 0 200 600" preserveAspectRatio="xMaxYMid meet">
            <path class="circuit-line" d="M200,80 L160,80 L120,120 L120,300 L80,340 L80,450" />
            <path class="circuit-line circuit-line-brown" d="M200,120 L170,120 L140,150 L140,250 L100,290 L100,380" />
            <path class="circuit-line circuit-line-navy" d="M200,180 L180,180 L150,210 L150,400 L130,420" />
            
            <circle class="circuit-node-glow" cx="80" cy="450" r="10"/>
            <circle class="circuit-node" cx="80" cy="450" r="3"/>
            
            <circle class="circuit-node-glow" cx="100" cy="290" r="8"/>
            <circle class="circuit-node" cx="100" cy="290" r="2.5"/>
            
            <circle class="circuit-node-glow" cx="120" cy="120" r="8"/>
            <circle class="circuit-node" cx="120" cy="120" r="2"/>
        </svg>
    </div>

    @php
        $aboutJudul     = ($cmsAbout && $cmsAbout->judul)        ? $cmsAbout->judul        : 'Membangun Solusi AI untuk Kebutuhan Nyata';
        $aboutDeskripsi = ($cmsAbout && $cmsAbout->deskripsi)     ? $cmsAbout->deskripsi     : 'Kami menyediakan berbagai jasa berbasis teknologi AI yang disesuaikan dengan kebutuhan setiap klien, mulai dari mendukung pekerjaan digital hingga membantu bisnis meningkatkan efisiensi, kreativitas, dan produktivitas.';
        $ceoNama        = ($cmsAbout && $cmsAbout->nama_ceo)      ? $cmsAbout->nama_ceo      : 'Hero Swaradani';
        $ceoJabatan     = ($cmsAbout && $cmsAbout->jabatan_ceo)   ? $cmsAbout->jabatan_ceo   : 'Founder & CEO · AI Solution';
        $ceoDeskripsi   = ($cmsAbout && $cmsAbout->deskripsi_ceo) ? $cmsAbout->deskripsi_ceo : 'Seorang praktisi teknologi AI berpengalaman yang berdedikasi dalam membangun solusi berbasis kecerdasan buatan yang berdampak nyata.';

        $ceoFoto = '/images/team-profile.jpg';
        if ($cmsAbout && $cmsAbout->foto_ceo) {
            $ceoFoto = str_starts_with($cmsAbout->foto_ceo, 'http')
                ? $cmsAbout->foto_ceo
                : asset('storage/' . $cmsAbout->foto_ceo);
        }
    @endphp

    <!-- HERO -->
    <div class="tentang-hero-wrapper">
        <!-- Luminous Glow, Halo, Grid & AI Radar Elements -->
        <div class="tentang-center-glow" aria-hidden="true"></div>
        <div class="tentang-halo-ring" aria-hidden="true"></div>
        <div class="tentang-tech-grid" aria-hidden="true"></div>
        
        <div class="tentang-floating-sparks" aria-hidden="true">
            <span class="tentang-spark spark-1"></span>
            <span class="tentang-spark spark-2"></span>
            <span class="tentang-spark spark-3"></span>
            <span class="tentang-spark spark-4"></span>
            <span class="tentang-spark spark-5"></span>
            <span class="tentang-spark spark-6"></span>
        </div>

        <div style="display:inline-flex; align-items:center; gap:8px; font-size:12px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:var(--cyan-neon); padding:7px 18px; background:rgba(0,212,255,0.08); border:1px solid rgba(0,212,255,0.20); border-radius:100px; margin-bottom:28px; position:relative; z-index:2;">
            <span style="width:7px;height:7px;border-radius:50%;background:var(--cyan-neon);box-shadow:0 0 8px var(--cyan-neon);animation:blink-dot 2s ease infinite;"></span>
            TENTANG KAMI
        </div>
        <h2 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:clamp(32px,4.5vw,56px);font-weight:800;line-height:1.1;letter-spacing:-1.5px;background:linear-gradient(135deg,#ffffff 0%,#a78bfa 45%,#00d4ff 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:20px;position:relative;z-index:2;">{{ $aboutJudul }}</h2>
        <p style="font-size:clamp(15px,1.8vw,18px);color:var(--white-80);line-height:1.65;max-width:620px;position:relative;z-index:2;">{{ $aboutDeskripsi }}</p>
    </div>

    <!-- PROFILE -->
    <div style="text-align:center;margin-bottom:16px;position:relative;z-index:2;">
        <div style="font-size:12px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;color:var(--cyan-neon);">CEO</div>
    </div>
    <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:clamp(24px,3vw,38px);font-weight:700;letter-spacing:-0.8px;text-align:center;margin-bottom:50px;position:relative;z-index:2;">Kenali CEO Kami</h3>

    <div class="profile-container">
        <div class="profile-photo-wrap">
            <img
                src="{{ $ceoFoto }}"
                alt="{{ $ceoNama }}"
                class="profile-photo"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            >
            <div style="display:none;width:200px;height:200px;border-radius:50%;background:linear-gradient(135deg,#00a8ff,#7c3aed);align-items:center;justify-content:center;position:relative;z-index:1;">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
        </div>
        <div class="profile-info">
            <h4 class="profile-name">{{ $ceoNama }}</h4>
            <div class="profile-role">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                {{ $ceoJabatan }}
            </div>
            <p class="profile-desc">{{ $ceoDeskripsi }}</p>
            <div class="profile-socials">
                {{-- YouTube --}}
                @if(isset($cmsContact) && $cmsContact->youtube)
                <a href="{{ $cmsContact->youtube }}"
                   target="_blank" rel="noopener noreferrer"
                   class="social-btn" aria-label="YouTube">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
                @endif
                {{-- TikTok --}}
                @if(isset($cmsContact) && $cmsContact->tiktok)
                <a href="{{ $cmsContact->tiktok }}"
                   target="_blank" rel="noopener noreferrer"
                   class="social-btn" aria-label="TikTok">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.32 6.32 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/></svg>
                </a>
                @endif
                {{-- Instagram --}}
                @if(isset($cmsContact) && $cmsContact->instagram)
                <a href="{{ $cmsContact->instagram }}"
                   target="_blank" rel="noopener noreferrer"
                   class="social-btn" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </a>
                @endif
                {{-- LinkedIn --}}
                @if(isset($cmsContact) && $cmsContact->linkedin)
                <a href="{{ $cmsContact->linkedin }}"
                   target="_blank" rel="noopener noreferrer"
                   class="social-btn" aria-label="LinkedIn">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452z"/></svg>
                </a>
                @endif
                {{-- WhatsApp --}}
            </div>
        </div>
    </div>

    <!-- PARTNER SECTION -->
    <div class="section-divider-line" style="margin-top:70px; margin-bottom:50px;"></div>
    <div class="partner-section-wrapper">
        <div class="section-top-aurora" style="height:220px; opacity:0.8;"></div>
        <div class="partner-ambient-aura"></div>

        {{-- Section Header --}}
        <div style="text-align:center; max-width:680px; margin:0 auto 36px; display:flex; flex-direction:column; align-items:center;">
            <div style="display:inline-flex; align-items:center; gap:8px; font-size:12px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:var(--cyan-neon); padding:7px 18px; background:rgba(0,212,255,0.08); border:1px solid rgba(0,212,255,0.20); border-radius:100px; margin-bottom:24px;">
                <span style="width:7px;height:7px;border-radius:50%;background:var(--cyan-neon);box-shadow:0 0 8px var(--cyan-neon);animation:blink-dot 2s ease infinite;"></span>
                {{ strtoupper($cmsHomepage->partner_title ?? 'Mitra & Kolaborasi') }}
            </div>
            <h2 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:clamp(28px,3.5vw,46px);font-weight:800;letter-spacing:-1px;line-height:1.15;background:linear-gradient(135deg,#ffffff 0%,#a78bfa 45%,#00d4ff 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:16px;">
                Dipercaya oleh Perusahaan Terkemuka
            </h2>
            <p style="font-size:clamp(15px,1.8vw,18px);color:var(--white-80);line-height:1.65;max-width:560px;">
                Kami berkolaborasi dengan berbagai perusahaan dan institusi dalam membangun solusi berbasis AI yang berdampak nyata.
            </p>
        </div>

        <div class="logo-marquee-wrapper">
            @php
            $rawPartners = isset($partners) ? collect($partners) : \App\Models\Partner::where('is_active', true)->orderBy('urutan', 'asc')->get();
            $dbPartners = $rawPartners->filter(function($p) {
                return is_object($p) ? ($p->is_active ?? true) : ($p['is_active'] ?? true);
            })->values();

            if ($dbPartners->count() > 0) {
                $half = ceil($dbPartners->count() / 2);
                $partnersRow1 = $dbPartners->slice(0, $half)->values();
                $partnersRow2 = $dbPartners->slice($half)->values();
                if ($partnersRow2->isEmpty()) {
                    $partnersRow2 = $partnersRow1;
                }
            } else {
                $fallback = [
                    ['nama' => 'Pertamina', 'logo' => 'https://icon.horse/icon/pertamina.com'],
                    ['nama' => 'Telkom Indonesia', 'logo' => 'https://icon.horse/icon/telkom.co.id'],
                    ['nama' => 'Gojek', 'logo' => 'https://icon.horse/icon/gojek.com'],
                    ['nama' => 'Tokopedia', 'logo' => 'https://icon.horse/icon/tokopedia.com'],
                    ['nama' => 'Bank Mandiri', 'logo' => 'https://icon.horse/icon/bankmandiri.co.id'],
                    ['nama' => 'BCA', 'logo' => 'https://icon.horse/icon/bca.co.id'],
                    ['nama' => 'PLN', 'logo' => 'https://icon.horse/icon/pln.co.id'],
                    ['nama' => 'XL Axiata', 'logo' => 'https://icon.horse/icon/xl.co.id'],
                ];
                $partnersRow1 = collect(array_slice($fallback, 0, 4));
                $partnersRow2 = collect(array_slice($fallback, 4));
            }
            @endphp

            <div class="logo-marquee-track">
                @for ($i = 0; $i < ($partnersRow1->count() < 6 ? 4 : 2); $i++)
                    @foreach($partnersRow1 as $partner)
                    @php
                        $pNama = is_array($partner) ? $partner['nama'] : $partner->nama;
                        $pLogo = is_array($partner) ? $partner['logo'] : $partner->logo;
                        $namaLower = strtolower($pNama ?? '');
                        $logoLower = strtolower($pLogo ?? '');
                        
                        $src = null;
                        $fallbackSrc = '';
                        if ($pLogo) {
                            if (str_contains($namaLower, 'gojek') || str_contains($logoLower, 'gojek')) {
                                $src = 'https://cdn.simpleicons.org/gojek';
                                $fallbackSrc = 'https://unavatar.io/gojek.com';
                            } elseif (str_contains($namaLower, 'tokopedia') || str_contains($logoLower, 'tokopedia')) {
                                $src = 'https://images.tokopedia.net/assets-tokopedia-lite/v2/zeus/production/e5b8438b.svg';
                                $fallbackSrc = 'https://unavatar.io/tokopedia.com';
                            } elseif (str_contains($namaLower, 'shopee') || str_contains($logoLower, 'shopee')) {
                                $src = 'https://cdn.simpleicons.org/shopee';
                                $fallbackSrc = 'https://unavatar.io/shopee.co.id';
                            } elseif (str_contains($namaLower, 'bukalapak') || str_contains($logoLower, 'bukalapak')) {
                                $src = 'https://cdn.simpleicons.org/bukalapak';
                                $fallbackSrc = 'https://unavatar.io/bukalapak.com';
                            } elseif (str_starts_with($pLogo, 'http://') || str_starts_with($pLogo, 'https://')) {
                                if (str_contains($pLogo, 'clearbit.com/')) {
                                    $domain = ltrim(parse_url($pLogo, PHP_URL_PATH), '/');
                                    $src = 'https://unavatar.io/' . $domain;
                                    $fallbackSrc = 'https://icon.horse/icon/' . $domain;
                                } else {
                                    $src = $pLogo;
                                    $host = parse_url($pLogo, PHP_URL_HOST);
                                    if ($host) {
                                        $fallbackSrc = 'https://icon.horse/icon/' . $host;
                                    }
                                }
                            } else {
                                $src = asset('storage/' . $pLogo);
                            }
                        }
                    @endphp
                    @if($src)
                    <div class="logo-item" title="{{ $pNama }}">
                        <img src="{{ $src }}" alt="{{ $pNama }}"
                             loading="lazy"
                             crossorigin="anonymous"
                             class="partner-marquee-img"
                             @if($fallbackSrc)
                             data-fallback="{{ $fallbackSrc }}"
                             onerror="if(this.dataset.fallback && this.src !== this.dataset.fallback){ this.src = this.dataset.fallback; } else { this.style.opacity = '0.5'; }"
                             @else
                             onerror="this.style.opacity = '0.5';"
                             @endif
                        >
                    </div>
                    @endif
                    @endforeach
                @endfor
            </div>

            <div class="logo-marquee-track reverse">
                @for ($i = 0; $i < ($partnersRow2->count() < 6 ? 4 : 2); $i++)
                    @foreach($partnersRow2 as $partner)
                    @php
                        $pNama = is_array($partner) ? $partner['nama'] : $partner->nama;
                        $pLogo = is_array($partner) ? $partner['logo'] : $partner->logo;
                        $namaLower = strtolower($pNama ?? '');
                        $logoLower = strtolower($pLogo ?? '');
                        
                        $src = null;
                        $fallbackSrc = '';
                        if ($pLogo) {
                            if (str_contains($namaLower, 'gojek') || str_contains($logoLower, 'gojek')) {
                                $src = 'https://cdn.simpleicons.org/gojek';
                                $fallbackSrc = 'https://unavatar.io/gojek.com';
                            } elseif (str_contains($namaLower, 'tokopedia') || str_contains($logoLower, 'tokopedia')) {
                                $src = 'https://images.tokopedia.net/assets-tokopedia-lite/v2/zeus/production/e5b8438b.svg';
                                $fallbackSrc = 'https://unavatar.io/tokopedia.com';
                            } elseif (str_contains($namaLower, 'shopee') || str_contains($logoLower, 'shopee')) {
                                $src = 'https://cdn.simpleicons.org/shopee';
                                $fallbackSrc = 'https://unavatar.io/shopee.co.id';
                            } elseif (str_contains($namaLower, 'bukalapak') || str_contains($logoLower, 'bukalapak')) {
                                $src = 'https://cdn.simpleicons.org/bukalapak';
                                $fallbackSrc = 'https://unavatar.io/bukalapak.com';
                            } elseif (str_starts_with($pLogo, 'http://') || str_starts_with($pLogo, 'https://')) {
                                if (str_contains($pLogo, 'clearbit.com/')) {
                                    $domain = ltrim(parse_url($pLogo, PHP_URL_PATH), '/');
                                    $src = 'https://unavatar.io/' . $domain;
                                    $fallbackSrc = 'https://icon.horse/icon/' . $domain;
                                } else {
                                    $src = $pLogo;
                                    $host = parse_url($pLogo, PHP_URL_HOST);
                                    if ($host) {
                                        $fallbackSrc = 'https://icon.horse/icon/' . $host;
                                    }
                                }
                            } else {
                                $src = asset('storage/' . $pLogo);
                            }
                        }
                    @endphp
                    @if($src)
                    <div class="logo-item" title="{{ $pNama }}">
                        <img src="{{ $src }}" alt="{{ $pNama }}"
                             loading="lazy"
                             crossorigin="anonymous"
                             class="partner-marquee-img"
                             @if($fallbackSrc)
                             data-fallback="{{ $fallbackSrc }}"
                             onerror="if(this.dataset.fallback && this.src !== this.dataset.fallback){ this.src = this.dataset.fallback; } else { this.style.opacity = '0.5'; }"
                             @else
                             onerror="this.style.opacity = '0.5';"
                             @endif
                        >
                    </div>
                    @endif
                    @endforeach
                @endfor
            </div>
        </div>
    </div>
</section>

{{-- Automatic White Background Stripper for Partner Logos --}}
<script>
(function() {
    function autoStripWhiteBg(img) {
        if (!img || img.dataset.bgStripped) return;
        img.dataset.bgStripped = "true";

        function process() {
            try {
                const w = img.naturalWidth;
                const h = img.naturalHeight;
                if (!w || !h || w < 8 || h < 8) return;

                const canvas = document.createElement('canvas');
                canvas.width = w;
                canvas.height = h;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);

                const imgData = ctx.getImageData(0, 0, w, h);
                const d = imgData.data;

                // Check 4 corners
                function getCorner(x, y) {
                    const idx = (y * w + x) * 4;
                    return [d[idx], d[idx+1], d[idx+2], d[idx+3]];
                }
                const corners = [getCorner(0, 0), getCorner(w-1, 0), getCorner(0, h-1), getCorner(w-1, h-1)];
                let lightCount = 0;
                corners.forEach(([r, g, b, a]) => {
                    if (a > 50 && r > 200 && g > 200 && b > 200) lightCount++;
                });

                // Only strip if corners are white/light box
                if (lightCount >= 2) {
                    const visited = new Uint8Array(w * h);
                    const queue = [];
                    for (let x = 0; x < w; x++) { queue.push(x); queue.push((h - 1) * w + x); }
                    for (let y = 0; y < h; y++) { queue.push(y * w); queue.push(y * w + (w - 1)); }

                    let stripped = 0;
                    while (queue.length > 0) {
                        const pos = queue.pop();
                        if (visited[pos]) continue;
                        visited[pos] = 1;

                        const idx = pos * 4;
                        const r = d[idx], g = d[idx+1], b = d[idx+2], a = d[idx+3];

                        if (a === 0 || (r > 205 && g > 205 && b > 205)) {
                            d[idx + 3] = 0;
                            stripped++;
                            const x = pos % w, y = Math.floor(pos / w);
                            if (x > 0 && !visited[pos - 1]) queue.push(pos - 1);
                            if (x < w - 1 && !visited[pos + 1]) queue.push(pos + 1);
                            if (y > 0 && !visited[pos - w]) queue.push(pos - w);
                            if (y < h - 1 && !visited[pos + w]) queue.push(pos + w);
                        }
                    }

                    if (stripped > 0) {
                        ctx.putImageData(imgData, 0, 0);
                        img.src = canvas.toDataURL('image/png');
                    }
                }
            } catch (err) {
                // Silently fallback if cross-origin canvas is restricted
            }
        }

        if (img.complete && img.naturalWidth > 0) {
            process();
        } else {
            img.addEventListener('load', process, { once: true });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.partner-marquee-img').forEach(autoStripWhiteBg);
        });
    } else {
        document.querySelectorAll('.partner-marquee-img').forEach(autoStripWhiteBg);
    }
})();
</script>


<!-- ==========================================
     WAWASAN BISNIS SECTION
========================================== -->
<div class="section-divider-line"></div>

<section id="wawasan" class="wawasan-section">
    <div class="wawasan-top-aurora"></div>
    <div class="wawasan-ambient-glow"></div>
    <div class="wawasan-ambient-glow-purple"></div>
    <div class="wawasan-ambient-glow-center"></div>
    <div class="wawasan-grid-mesh"></div>
    <div style="text-align:center; max-width:700px; margin:0 auto; display:flex; flex-direction:column; align-items:center; position:relative; z-index:2;">
        <div style="display:inline-flex; align-items:center; gap:8px; font-size:12px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:var(--cyan-neon); padding:7px 18px; background:rgba(0,212,255,0.08); border:1px solid rgba(0,212,255,0.20); border-radius:100px; margin-bottom:28px;">
            <span style="width:7px;height:7px;border-radius:50%;background:var(--cyan-neon);box-shadow:0 0 8px var(--cyan-neon);animation:blink-dot 2s ease infinite;"></span>
            Wawasan Bisnis
        </div>
        <h2 class="wawasan-title">Wawasan &amp; Inovasi AI Solution</h2>
        <p style="font-size:clamp(16px,2vw,19px);color:var(--white-80);line-height:1.6;">Value seputar AI, perusahaan, dan perkembangan teknologi untuk membantu bisnis berkembang.</p>
    </div>

    <div class="wawasan-grid">
        @if(isset($homepageArticles) && count($homepageArticles) > 0)
            @foreach($homepageArticles as $article)
            <a href="{{ route('article.detail', ['slug' => $article['slug']]) }}" class="article-card">
                <div class="article-img-wrap">
                    @php
                        $articleImg = $article['image'] ? (str_starts_with($article['image'], 'http') ? $article['image'] : asset('storage/' . $article['image'])) : 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=600';
                    @endphp
                    <img src="{{ $articleImg }}" alt="{{ $article['title'] }}" class="article-img">
                </div>
                <div class="article-content">
                    <div class="article-meta">
                        <span class="article-category">{{ $article['category'] }}</span>
                        @php
                            $dateStr = str_ireplace(
                                ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
                                ['January','February','March','April','May','June','July','August','September','October','November','December'],
                                $article['date']
                            );
                            try {
                                $fmtDate = \Carbon\Carbon::parse($dateStr)->locale('id')->translatedFormat('d M Y');
                            } catch (\Exception $e) {
                                $fmtDate = $article['date'];
                            }
                        @endphp
                        <span class="article-date">{{ $fmtDate }}</span>
                    </div>
                    <h3 class="article-title">{{ $article['title'] }}</h3>
                    <p class="article-desc">{{ $article['summary'] }}</p>
                    <div class="article-btn">
                        Baca Selengkapnya
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </div>
                </div>
            </a>
            @endforeach
        @endif
    </div>

    <div class="wawasan-actions">
        <a href="{{ route('articles.index') }}" class="btn-lihat-semua-artikel" id="btn-lihat-semua-artikel">
            <span>Lihat Semua Artikel</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </div>
</section>


<!-- ==========================================
     KONSULTASI SECTION
========================================== -->
<div class="section-divider-line"></div>

<section id="konsultasi" class="konsultasi-section">
    <div class="section-top-aurora"></div>
    <div class="konsultasi-bg-aurora"></div>
    <div class="konsultasi-grid-mesh"></div>
    <div class="konsultasi-ambient-glow-left"></div>
    <div class="konsultasi-ambient-glow-right"></div>
    
    <div class="konsultasi-wrapper">
        <!-- LEFT SIDE DECOR -->
        <div class="konsultasi-side-decor konsultasi-side-left" aria-hidden="true">
            <div class="konsultasi-telemetry-pill">
                <div class="pill-dot pill-dot-cyan"></div>
                <div class="pill-info">
                    <span class="pill-title">AI_ADVISORY // 24/7</span>
                    <span class="pill-sub">CONSULTATION READY</span>
                </div>
            </div>
            
            <div class="konsultasi-eq-bars">
                <span class="eq-bar eq-b1"></span>
                <span class="eq-bar eq-b2"></span>
                <span class="eq-bar eq-b3"></span>
                <span class="eq-bar eq-b4"></span>
                <span class="eq-bar eq-b5"></span>
                <span class="eq-bar eq-b6"></span>
                <span class="eq-bar eq-b7"></span>
            </div>

            <svg class="konsultasi-side-svg" viewBox="0 0 160 160" fill="none">
                <circle cx="80" cy="80" r="64" stroke="rgba(0, 212, 255, 0.35)" stroke-width="1.5" stroke-dasharray="12 8" class="hud-ring-outer"/>
                <path d="M40,80 A40,40 0 0,1 120,80" stroke="rgba(167, 139, 250, 0.45)" stroke-width="2" stroke-linecap="round"/>
                <path d="M55,80 A25,25 0 0,1 105,80" stroke="rgba(0, 212, 255, 0.6)" stroke-width="2" stroke-linecap="round"/>
                <circle cx="80" cy="80" r="10" fill="rgba(0, 212, 255, 0.15)" stroke="rgba(0, 212, 255, 0.7)" stroke-width="1.5"/>
                <circle cx="80" cy="80" r="4" fill="#00d4ff"/>
                <path d="M80,80 L135,80 L155,95" stroke="rgba(0, 212, 255, 0.45)" stroke-width="1.5" stroke-dasharray="4 4"/>
            </svg>
        </div>

        <!-- CENTER CARD -->
        <div class="konsultasi-card">
            <div class="konsultasi-card-corners">
                <span class="card-corner card-corner-tl"></span>
                <span class="card-corner card-corner-tr"></span>
                <span class="card-corner card-corner-bl"></span>
                <span class="card-corner card-corner-br"></span>
            </div>
            <div class="konsultasi-glow"></div>
            <div class="konsultasi-lines"></div>
            <div class="konsultasi-radar-wave">
                <div class="radar-wave-ring"></div>
                <div class="radar-wave-ring-2"></div>
            </div>
            <div class="konsultasi-content">
                <div class="konsultasi-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        <path d="M12 11V7"></path>
                        <path d="M9 11l3-3 3 3"></path>
                    </svg>
                </div>
                @php
                    $ctaTitle = (isset($cmsContact) && $cmsContact->judul) ? $cmsContact->judul : 'Punya Kebutuhan AI?';
                    $ctaDesc  = (isset($cmsContact) && $cmsContact->deskripsi) ? $cmsContact->deskripsi : 'Ceritakan kebutuhan Anda kepada kami. Kami akan membantu memahami kebutuhan dan mendiskusikan solusi AI yang sesuai.';
                    $ctaBtnText = (isset($cmsContact) && $cmsContact->teks_tombol) ? $cmsContact->teks_tombol : 'Konsultasikan Kebutuhan';
                    $ctaBtnLink = (isset($cmsContact) && $cmsContact->link_tombol) ? $cmsContact->link_tombol : $waUrl;
                @endphp
                <div class="konsultasi-label">KONSULTASI</div>
                <h2 class="konsultasi-title">{{ $ctaTitle }}</h2>
                <p class="konsultasi-desc">{{ $ctaDesc }}</p>
                
                <div class="konsultasi-btn-row">
                    <button type="button" class="konsultasi-open-btn" id="btn-open-modal-konsultasi" onclick="openKonsultasiModal()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        {{ $ctaBtnText }}
                    </button>

                    <a href="{{ $ctaBtnLink }}"
                       class="konsultasi-btn"
                       target="_blank"
                       rel="noopener noreferrer"
                       id="btn-konsultasi-wa">
                        <svg width="18" height="18" viewBox="0 0 448 512" fill="currentColor"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
                        {{ $ctaBtnText }} via WA
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE DECOR -->
        <div class="konsultasi-side-decor konsultasi-side-right" aria-hidden="true">
            <div class="konsultasi-telemetry-pill pill-alt">
                <div class="pill-dot pill-dot-purple"></div>
                <div class="pill-info">
                    <span class="pill-title">CHANNELS // ACTIVE</span>
                    <span class="pill-sub">WHATSAPP &amp; FORM</span>
                </div>
            </div>

            <svg class="konsultasi-side-svg" viewBox="0 0 160 160" fill="none">
                <polygon points="80,18 134,49 134,111 80,142 26,111 26,49" stroke="rgba(124, 58, 237, 0.4)" stroke-width="1.5" stroke-dasharray="14 7" class="hud-hex-outer"/>
                <circle cx="80" cy="80" r="42" stroke="rgba(0, 212, 255, 0.45)" stroke-width="1.5" stroke-dasharray="18 12" class="hud-ring-outer"/>
                <circle cx="80" cy="80" r="10" fill="rgba(124, 58, 237, 0.18)" stroke="rgba(167, 139, 250, 0.7)" stroke-width="1.5"/>
                <circle cx="80" cy="80" r="4" fill="#a78bfa"/>
                <path d="M80,80 L25,80 L5,95" stroke="rgba(167, 139, 250, 0.45)" stroke-width="1.5" stroke-dasharray="4 4"/>
            </svg>

            <div class="konsultasi-response-gauge">
                <div class="gauge-label">
                    <span>RESPONSE TIME</span>
                    <span class="gauge-val">&lt; 15 MIN</span>
                </div>
                <div class="gauge-bar"><div class="gauge-fill"></div></div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================
     MODAL KONSULTASI
========================================== -->
<div class="modal-overlay" id="modal-konsultasi" role="dialog" aria-modal="true" aria-labelledby="modal-konsultasi-title">
    <div class="modal-box">
        <button class="modal-close" onclick="closeKonsultasiModal()" aria-label="Tutup modal">&times;</button>

        {{-- SUCCESS STATE --}}
        @if(session('success'))
        <div class="modal-success" id="modal-success-state">
            <div class="modal-success-icon">✓</div>
            <h3>Pesan Terkirim!</h3>
            <p>{{ session('success') }}<br>Kami akan segera menghubungi Anda.</p>
            <button class="btn-close-success" onclick="closeKonsultasiModal()">Tutup</button>
        </div>
        @else
        <div id="modal-form-state">
            <div class="modal-header">
                <div class="modal-label">KONSULTASI GRATIS</div>
                <h2 class="modal-title" id="modal-konsultasi-title">Ceritakan Kebutuhan Anda</h2>
                <p class="modal-subtitle">Isi form berikut dan tim kami akan menghubungi Anda segera.</p>
            </div>

            @if($errors->any())
            <div class="modal-form-errors">
                <div class="modal-form-errors-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>Perhatian:</span>
                </div>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('konsultasi.store') }}" method="POST" class="modal-form" id="form-konsultasi-modal">
                @csrf
                <div class="form-group-c">
                    <label class="form-label-c" for="modal-nama">Nama Lengkap <span style="color:#f87171">*</span></label>
                    <input type="text" id="modal-nama" name="nama" class="form-control-c" required placeholder="Masukkan nama Anda" value="{{ old('nama') }}">
                </div>
                <div class="form-group-c">
                    <label class="form-label-c" for="modal-email">Alamat Email <span style="color:#f87171">*</span></label>
                    <input type="email" id="modal-email" name="email" class="form-control-c" required placeholder="email@perusahaan.com" value="{{ old('email') }}">
                </div>
                <div class="form-group-c">
                    <label class="form-label-c" for="modal-wa">Nomor WhatsApp <span style="color:rgba(255,255,255,0.3);font-weight:400">(Opsional)</span></label>
                    <input type="text" id="modal-wa" name="whatsapp" class="form-control-c" placeholder="0812xxxx" value="{{ old('whatsapp') }}">
                </div>
                <div class="form-group-c">
                    <label class="form-label-c" for="modal-kebutuhan">Kebutuhan AI Anda <span style="color:#f87171">*</span></label>
                    <textarea id="modal-kebutuhan" name="kebutuhan" class="form-control-c" rows="3" required placeholder="Ceritakan kebutuhan atau masalah bisnis Anda...">{{ old('kebutuhan') }}</textarea>
                </div>
                <button type="submit" class="btn-submit-c" id="btn-modal-submit">Kirim Pesan</button>
            </form>
        </div>
        @endif
    </div>
</div>

<!-- ==========================================
     SITE FOOTER
========================================== -->
<div class="section-divider-line"></div>

<style>
    /* ==========================================
       SITE FOOTER
    ========================================== */
    .site-footer {
        position: relative;
        background: radial-gradient(130% 100% at 50% 0%, #0d1a3a 0%, #060d22 45%, #020614 100%);
        border-top: 1px solid rgba(0, 212, 255, 0.16);
        padding: 85px 24px 0;
        overflow: hidden;
    }
    .site-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 900px;
        max-width: 92%;
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, rgba(0, 212, 255, 0.85) 25%, rgba(124, 58, 237, 0.8) 75%, transparent 100%);
        box-shadow: 0 0 18px rgba(0, 212, 255, 0.6), 0 0 35px rgba(124, 58, 237, 0.35);
        pointer-events: none;
        z-index: 2;
    }
    .footer-glow-top {
        position: absolute;
        top: -120px;
        left: 50%;
        transform: translateX(-50%);
        width: 850px;
        max-width: 95%;
        height: 280px;
        background: radial-gradient(ellipse 65% 55% at 50% 40%, rgba(0, 212, 255, 0.22) 0%, rgba(124, 58, 237, 0.12) 50%, transparent 75%);
        filter: blur(40px);
        pointer-events: none;
        z-index: 0;
    }
    .footer-glow-left {
        position: absolute;
        left: -100px;
        top: 15%;
        width: 550px;
        height: 450px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.14) 0%, rgba(0, 168, 255, 0.04) 50%, transparent 70%);
        filter: blur(60px);
        pointer-events: none;
        z-index: 0;
    }
    .footer-glow-right {
        position: absolute;
        right: -100px;
        bottom: 5%;
        width: 520px;
        height: 420px;
        background: radial-gradient(circle, rgba(124, 58, 237, 0.16) 0%, rgba(99, 102, 241, 0.05) 50%, transparent 70%);
        filter: blur(60px);
        pointer-events: none;
        z-index: 0;
    }
    .footer-cyber-grid {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(0, 212, 255, 0.15) 1.2px, transparent 1.2px);
        background-size: 28px 28px;
        mask-image: radial-gradient(ellipse 85% 80% at 50% 35%, black 20%, transparent 85%);
        -webkit-mask-image: radial-gradient(ellipse 85% 80% at 50% 35%, black 20%, transparent 85%);
        opacity: 0.8;
        pointer-events: none;
        z-index: 0;
    }
    .footer-circuit-accents {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
        z-index: 0;
    }
    .footer-circuit-accents svg {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .footer-glow {
        display: none;
    }
    .footer-inner {
        position: relative;
        z-index: 1;
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.4fr 1fr 1fr;
        gap: 60px;
    }

    /* Brand column */
    .footer-brand-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        text-decoration: none;
    }
    .footer-brand-icon {
        width: 38px;
        height: 38px;
        background: #ffffff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25), 0 0 16px rgba(255, 255, 255, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.6);
        overflow: hidden;
    }
    .footer-brand-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 3px;
    }
    .footer-brand-icon svg {
        width: 22px;
        height: 22px;
    }
    .footer-brand-name {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: #fff;
    }
    .footer-brand-name span {
        color: var(--cyan-neon);
    }
    .footer-brand-desc {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.45);
        line-height: 1.7;
        max-width: 340px;
        margin-bottom: 28px;
    }
    .footer-socials {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .footer-social-link {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.45);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .footer-social-link:hover {
        background: rgba(0, 212, 255, 0.10);
        border-color: rgba(0, 212, 255, 0.30);
        color: var(--cyan-neon);
        transform: translateY(-2px);
    }
    .footer-social-link svg {
        width: 16px;
        height: 16px;
    }

    /* Navigation column */
    .footer-col-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.70);
        margin-bottom: 24px;
    }
    .footer-nav-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .footer-nav-list a {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.40);
        text-decoration: none;
        transition: color 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .footer-nav-list a:hover {
        color: var(--cyan-neon);
    }
    .footer-nav-list a svg {
        width: 14px;
        height: 14px;
        opacity: 0;
        transform: translateX(-4px);
        transition: all 0.25s ease;
    }
    .footer-nav-list a:hover svg {
        opacity: 1;
        transform: translateX(0);
    }

    /* Contact column */
    .footer-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 20px;
        font-size: 14px;
        color: rgba(255, 255, 255, 0.40);
        line-height: 1.6;
    }
    .footer-contact-item a {
        color: rgba(255, 255, 255, 0.40);
        text-decoration: none;
        transition: color 0.25s ease;
    }
    .footer-contact-item a:hover {
        color: var(--cyan-neon);
    }
    .footer-contact-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(0, 212, 255, 0.06);
        border: 1px solid rgba(0, 212, 255, 0.10);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--cyan-neon);
    }
    .footer-contact-icon svg {
        width: 14px;
        height: 14px;
    }

    /* Bottom bar */
    .footer-bottom {
        position: relative;
        z-index: 1;
        max-width: 1200px;
        margin: 60px auto 0;
        padding: 24px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .footer-copyright {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.25);
    }
    .footer-bottom-links {
        display: flex;
        gap: 24px;
    }
    .footer-bottom-links a {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.25);
        text-decoration: none;
        transition: color 0.25s ease;
    }
    .footer-bottom-links a:hover {
        color: var(--cyan-neon);
    }

    /* Responsive */
    @media (max-width: 900px) {
        .site-footer {
            padding: 60px 24px 0;
        }
        .footer-inner {
            grid-template-columns: 1fr 1fr;
            gap: 48px 40px;
        }
        .footer-brand-col {
            grid-column: 1 / -1;
        }
        .footer-bottom {
            margin-top: 48px;
        }
    }
    @media (max-width: 640px) {
        .site-footer {
            padding: 48px 20px 0;
        }
        .footer-inner {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        .footer-brand-col {
            grid-column: auto;
        }
        .footer-brand-desc {
            max-width: 100%;
        }
        .footer-bottom {
            flex-direction: column;
            gap: 12px;
            text-align: center;
            margin-top: 40px;
            padding: 20px 0;
        }
        .footer-bottom-links {
            gap: 16px;
        }
    }
</style>

<footer class="site-footer" id="site-footer">
    <!-- Ambient Tech Glow & Grid Background -->
    <div class="footer-glow-top" aria-hidden="true"></div>
    <div class="footer-glow-left" aria-hidden="true"></div>
    <div class="footer-glow-right" aria-hidden="true"></div>
    <div class="footer-cyber-grid" aria-hidden="true"></div>
    <div class="footer-circuit-accents" aria-hidden="true">
        <svg viewBox="0 0 1440 240" fill="none" preserveAspectRatio="none">
            <path d="M-100 180 C300 40, 650 200, 1100 80 C1300 20, 1450 140, 1600 100" stroke="url(#footer-line-grad)" stroke-width="1.5" stroke-dasharray="6 8" opacity="0.45"/>
            <path d="M-50 90 C350 160, 800 30, 1200 130 C1380 180, 1500 70, 1600 90" stroke="url(#footer-line-grad-2)" stroke-width="1" opacity="0.3"/>
            <defs>
                <linearGradient id="footer-line-grad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#00d4ff" stop-opacity="0.1"/>
                    <stop offset="40%" stop-color="#00d4ff" stop-opacity="0.6"/>
                    <stop offset="70%" stop-color="#7c3aed" stop-opacity="0.5"/>
                    <stop offset="100%" stop-color="#00d4ff" stop-opacity="0"/>
                </linearGradient>
                <linearGradient id="footer-line-grad-2" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#7c3aed" stop-opacity="0"/>
                    <stop offset="50%" stop-color="#7c3aed" stop-opacity="0.4"/>
                    <stop offset="100%" stop-color="#00d4ff" stop-opacity="0.2"/>
                </linearGradient>
            </defs>
        </svg>
    </div>
    <div class="footer-inner">

        {{-- Brand Column --}}
        <div class="footer-brand-col">
            <a href="/" class="footer-brand-logo" aria-label="{{ $siteFull }} Home">
                <div class="footer-brand-icon" aria-hidden="true">
                    @if($isCustomLogo)
                        <img src="{{ $cmsHomepage->site_logo_url }}" alt="{{ $siteFull }}" style="width:100%;height:100%;object-fit:contain;padding:3px;">
                    @else
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="3" fill="#0284c7" opacity="0.95"/>
                            <circle cx="5" cy="7" r="1.5" fill="#7c3aed" opacity="0.85"/>
                            <circle cx="19" cy="7" r="1.5" fill="#7c3aed" opacity="0.85"/>
                            <circle cx="5" cy="17" r="1.5" fill="#7c3aed" opacity="0.85"/>
                            <circle cx="19" cy="17" r="1.5" fill="#7c3aed" opacity="0.85"/>
                            <line x1="9" y1="11" x2="6.12" y2="7.88" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                            <line x1="15" y1="11" x2="17.88" y2="7.88" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                            <line x1="9" y1="13" x2="6.12" y2="16.12" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                            <line x1="15" y1="13" x2="17.88" y2="16.12" stroke="#0284c7" stroke-width="1.2" opacity="0.75"/>
                        </svg>
                    @endif
                </div>
                <span class="footer-brand-name"><span>{{ $siteHl }}</span> {{ $siteRs }}</span>
            </a>
            <p class="footer-brand-desc">Platform AI cerdas yang membantu bisnis bekerja lebih cepat, lebih efisien, dan lebih inovatif melalui solusi kecerdasan buatan yang disesuaikan.</p>
            <div class="footer-socials">
                {{-- Instagram --}}
                @if(isset($cmsContact) && $cmsContact->instagram)
                <a href="{{ $cmsContact->instagram }}" target="_blank" rel="noopener" class="footer-social-link" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </a>
                @endif
                {{-- LinkedIn --}}
                @if(isset($cmsContact) && $cmsContact->linkedin)
                <a href="{{ $cmsContact->linkedin }}" target="_blank" rel="noopener" class="footer-social-link" aria-label="LinkedIn">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
                @endif
                {{-- YouTube --}}
                @if(isset($cmsContact) && $cmsContact->youtube)
                <a href="{{ $cmsContact->youtube }}" target="_blank" rel="noopener" class="footer-social-link" aria-label="YouTube">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
                @endif
                {{-- TikTok --}}
                @if(isset($cmsContact) && $cmsContact->tiktok)
                <a href="{{ $cmsContact->tiktok }}" target="_blank" rel="noopener" class="footer-social-link" aria-label="TikTok">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.32 6.32 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/></svg>
                </a>
                @endif
                {{-- WhatsApp --}}
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                   class="footer-social-link" aria-label="WhatsApp">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>
            </div>
        </div>

        {{-- Navigation Column --}}
        <div>
            <div class="footer-col-title">Navigasi</div>
            <ul class="footer-nav-list">
                <li>
                    <a href="/#home">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="/#layanan">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        Layanan
                    </a>
                </li>
                <li>
                    <a href="/#tentang">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        Tentang Kami
                    </a>
                </li>
                <li>
                    <a href="/#wawasan">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        Wawasan
                    </a>
                </li>
                <li>
                    <a href="/#konsultasi">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        Konsultasi
                    </a>
                </li>
            </ul>
        </div>

        {{-- Contact Column --}}
        <div>
            <div class="footer-col-title">Hubungi Kami</div>
            <div class="footer-contact-item">
                <div class="footer-contact-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer">Chat via WhatsApp</a><br>
                    <span style="font-size:12px;color:rgba(255,255,255,0.25);">Respon cepat di jam kerja</span>
                </div>
            </div>
            @if(isset($cmsContact) && $cmsContact->email)
            <div class="footer-contact-item">
                <div class="footer-contact-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <div>
                    <a href="mailto:{{ $cmsContact->email }}">{{ $cmsContact->email }}</a><br>
                    <span style="font-size:12px;color:rgba(255,255,255,0.25);">Email kami</span>
                </div>
            </div>
            @endif
            @if(isset($cmsContact) && $cmsContact->alamat)
            <div class="footer-contact-item">
                <div class="footer-contact-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                    {{ $cmsContact->alamat }}<br>
                    <span style="font-size:12px;color:rgba(255,255,255,0.25);">Alamat kantor</span>
                </div>
            </div>
            @endif
            <div class="footer-contact-item">
                <div class="footer-contact-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    Senin — Jumat<br>
                    <span style="font-size:12px;color:rgba(255,255,255,0.25);">09:00 — 17:00 WIB</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Bottom Bar --}}
    <div class="footer-bottom">
        <div class="footer-copyright">© {{ date('Y') }} AI Solution. All rights reserved.</div>
        <div class="footer-bottom-links">
            <a href="#home">Kembali ke atas ↑</a>
        </div>
    </div>
</footer>

<!-- ==========================================
     SEARCH MODAL (Ctrl+K)
========================================== -->
<div id="search-modal" style="
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(2, 8, 24, 0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    align-items: flex-start;
    justify-content: center;
    padding-top: 80px;
" role="dialog" aria-modal="true" aria-label="Pencarian">
    <div class="search-modal-container" style="
        width: 100%;
        max-width: 620px;
        background: linear-gradient(160deg, rgba(10, 20, 60, 0.96) 0%, rgba(6, 12, 38, 0.98) 100%);
        border: 1px solid rgba(0, 212, 255, 0.35);
        border-radius: 20px;
        padding: 22px;
        box-shadow: 0 25px 80px rgba(0,0,0,0.70), 0 0 50px rgba(0, 212, 255, 0.18);
        margin: 0 16px;
    ">
        <div style="display: flex; align-items: center; gap: 12px; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 16px;">
            <svg width="20" height="20" viewBox="0 0 16 16" fill="none" style="flex-shrink:0; color: #00d4ff;">
                <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.6"/>
                <path d="M11 11L14 14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
            <input
                id="search-input"
                class="search-modal-input"
                type="text"
                placeholder="Cari artikel, layanan AI, topik, atau isi web..."
                autocomplete="off"
                style="
                    flex: 1;
                    background: none;
                    border: none;
                    outline: none;
                    color: white;
                    font-size: 16px;
                    font-family: 'Inter', sans-serif;
                "
            />
            <button onclick="document.getElementById('search-modal').style.display='none'" style="
                background: rgba(255,255,255,0.08);
                border: 1px solid rgba(255,255,255,0.15);
                color: rgba(255,255,255,0.60);
                border-radius: 6px;
                padding: 4px 9px;
                font-size: 11px;
                cursor: pointer;
                font-family: 'Inter', sans-serif;
            ">ESC</button>
        </div>

        <!-- Quick filter chips -->
        <div style="display: flex; align-items: center; gap: 8px; overflow-x: auto; padding: 12px 0 8px; border-bottom: 1px dashed rgba(255,255,255,0.08);">
            <span style="font-size: 11px; color: rgba(255,255,255,0.40); white-space: nowrap;">Cepat:</span>
            <button type="button" onclick="document.getElementById('search-input').value='artikel'; document.getElementById('search-input').dispatchEvent(new Event('input'));" style="background: rgba(0,212,255,0.08); border: 1px solid rgba(0,212,255,0.22); color: #00d4ff; border-radius: 100px; padding: 3px 10px; font-size: 11px; cursor: pointer; white-space: nowrap;">Semua Artikel</button>
            <button type="button" onclick="document.getElementById('search-input').value='layanan'; document.getElementById('search-input').dispatchEvent(new Event('input'));" style="background: rgba(124,58,237,0.10); border: 1px solid rgba(124,58,237,0.25); color: #a78bfa; border-radius: 100px; padding: 3px 10px; font-size: 11px; cursor: pointer; white-space: nowrap;">Layanan AI</button>
            <button type="button" onclick="document.getElementById('search-input').value='vision'; document.getElementById('search-input').dispatchEvent(new Event('input'));" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.10); color: rgba(255,255,255,0.70); border-radius: 100px; padding: 3px 10px; font-size: 11px; cursor: pointer; white-space: nowrap;">Computer Vision</button>
            <button type="button" onclick="document.getElementById('search-input').value='nlp'; document.getElementById('search-input').dispatchEvent(new Event('input'));" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.10); color: rgba(255,255,255,0.70); border-radius: 100px; padding: 3px 10px; font-size: 11px; cursor: pointer; white-space: nowrap;">NLP &amp; Chatbot</button>
            <button type="button" onclick="document.getElementById('search-input').value='machine learning'; document.getElementById('search-input').dispatchEvent(new Event('input'));" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.10); color: rgba(255,255,255,0.70); border-radius: 100px; padding: 3px 10px; font-size: 11px; cursor: pointer; white-space: nowrap;">Machine Learning</button>
        </div>

        <div id="search-results" style="padding-top: 8px; max-height: 350px; overflow-y: auto;">
            <div id="search-placeholder" style="padding: 24px 0; color: rgba(255,255,255,0.40); font-size: 13px; text-align: center;">
                Ketik untuk mencari artikel, layanan AI, topik, atau isi web...
            </div>
        </div>
    </div>
</div>


<!-- ==========================================
     JAVASCRIPT
========================================== -->
<script>
(function() {
    // Jasa Modal Functions
    window.openModal = function(title, desc, iconPath) {
        document.getElementById('modal-title-text').textContent = title;
        document.getElementById('modal-desc').textContent = desc + ' Hubungi tim konsultan kami untuk mengetahui implementasi teknis dan use-case spesifik untuk industri Anda.';
        document.getElementById('modal-icon-path').setAttribute('d', iconPath);
        
        const modal = document.getElementById('detail-modal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scroll
    };

    window.closeModal = function(e) {
        const modal = document.getElementById('detail-modal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto'; // Enable scroll
    };
    'use strict';

    // --- Hamburger menu ---
    const hamburger = document.getElementById('hamburger-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function() {
            const isOpen = mobileMenu.classList.toggle('open');
            hamburger.setAttribute('aria-expanded', isOpen.toString());
        });

        // Close mobile menu on link click
        mobileMenu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('open');
                hamburger.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // --- Search modal keyboard shortcut ---
    const searchModal = document.getElementById('search-modal');
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    const searchPlaceholder = document.getElementById('search-placeholder');

@php
    $searchData = [];

    // 1. Artikel (Semua artikel aktif / terbit dari database)
    $searchArticlesList = $articles ?? $homepageArticles ?? [];
    foreach ($searchArticlesList as $art) {
        $artTitle = $art['title'] ?? '';
        $artSummary = $art['summary'] ?? '';
        $artCategory = $art['category'] ?? 'AI';
        $artSlug = $art['slug'] ?? '';
        $artDate = $art['date'] ?? '';

        if (!empty($artTitle) && !empty($artSlug)) {
            $descClean = !empty($artSummary) ? \Illuminate\Support\Str::limit(strip_tags($artSummary), 85) : 'Artikel seputar ' . $artCategory;
            $searchData[] = [
                'label'    => $artTitle,
                'desc'     => $descClean . ' • ' . $artCategory,
                'url'      => route('article.detail', ['slug' => $artSlug]),
                'keywords' => array_values(array_filter(array_unique(array_merge(
                    explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9 ]/', '', $artTitle))),
                    explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9 ]/', '', $artSummary))),
                    ['artikel', 'wawasan', 'baca', 'berita', 'insight', strtolower($artCategory)]
                )))),
                'icon'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>',
                'category' => 'Artikel',
            ];
        }
    }

    // 2. Layanan AI (Semua layanan aktif dari DB)
    $searchServicesList = $services ?? [];
    foreach ($searchServicesList as $srv) {
        $srvTitle = $srv['title'] ?? '';
        $srvDesc = $srv['desc'] ?? '';
        $srvSlug = $srv['slug'] ?? '';
        $srvSolutions = is_array($srv['solutions'] ?? null) ? (string)json_encode($srv['solutions']) : (string)($srv['solutions'] ?? '');
        $srvBenefits = is_array($srv['benefits'] ?? null) ? (string)json_encode($srv['benefits']) : (string)($srv['benefits'] ?? '');

        if (!empty($srvTitle)) {
            $srvDescClean = !empty($srvDesc) ? \Illuminate\Support\Str::limit(strip_tags($srvDesc), 90) : 'Layanan implementasi solusi AI';
            $searchData[] = [
                'label'    => $srvTitle,
                'desc'     => $srvDescClean,
                'url'      => !empty($srvSlug) ? url('/layanan/' . $srvSlug) : '/#layanan',
                'keywords' => array_values(array_filter(array_unique(array_merge(
                    explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9 ]/', '', $srvTitle))),
                    explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9 ]/', '', $srvDesc))),
                    explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9 ]/', '', $srvSolutions))),
                    explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9 ]/', '', $srvBenefits))),
                    ['layanan', 'service', 'jasa', 'ai', 'solusi', 'fitur', 'teknologi', 'penerapan', 'bisnis']
                )))),
                'icon'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10H12V2z"></path><path d="M12 12 2.1 7.1"></path><path d="M12 12v10"></path></svg>',
                'category' => 'Layanan AI',
            ];
        }
    }

    // 3. Topik & Kapabilitas Solusi AI Utama
    $aiTopics = [
        [
            'label' => 'Computer Vision & Deteksi Objek',
            'desc'  => 'Deteksi visual, pengenalan wajah, OCR dokumen, dan analitik video AI',
            'url'   => '/#layanan',
            'keywords' => ['computer', 'vision', 'gambar', 'kamera', 'deteksi', 'objek', 'ocr', 'citra', 'visual', 'wajah', 'recognition', 'video'],
            'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
            'category' => 'Topik AI',
        ],
        [
            'label' => 'Natural Language Processing (NLP) & Chatbot',
            'desc'  => 'Pemrosesan bahasa alami, customer service bot cerdas, analisis sentimen teks',
            'url'   => '/#layanan',
            'keywords' => ['nlp', 'natural', 'language', 'chatbot', 'bot', 'chat', 'bahasa', 'teks', 'sentimen', 'virtual', 'assistant'],
            'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
            'category' => 'Topik AI',
        ],
        [
            'label' => 'Machine Learning & Predictive Analytics',
            'desc'  => 'Pemodelan data prediksi, forecasting tren bisnis, churn detection & algoritma cerdas',
            'url'   => '/#layanan',
            'keywords' => ['machine', 'learning', 'ml', 'predictive', 'prediksi', 'analytics', 'forecasting', 'data', 'analitik', 'algoritma', 'model'],
            'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>',
            'category' => 'Topik AI',
        ],
        [
            'label' => 'Otomatisasi Proses Bisnis (RPA)',
            'desc'  => 'Automasi pekerjaan manual berulang, integrasi sistem, dan percepatan efisiensi kerja',
            'url'   => '/#layanan',
            'keywords' => ['otomasi', 'otomatisasi', 'proses', 'rpa', 'robotic', 'workflow', 'automasi', 'efisiensi', 'operasional'],
            'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 3 21 3 21 8"></polyline><line x1="4" y1="20" x2="21" y2="3"></line><polyline points="21 16 21 21 16 21"></polyline><line x1="15" y1="15" x2="21" y2="21"></line><line x1="4" y1="4" x2="9" y2="9"></line></svg>',
            'category' => 'Topik AI',
        ],
        [
            'label' => 'Generative AI & Kustom LLM',
            'desc'  => 'Integrasi Large Language Model, pembuatan prompt cerdas, dan generator konten AI',
            'url'   => '/#layanan',
            'keywords' => ['generative', 'ai', 'llm', 'gpt', 'large', 'language', 'model', 'kustom', 'generator', 'prompt', 'generatif'],
            'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
            'category' => 'Topik AI',
        ],
    ];
    foreach ($aiTopics as $topic) {
        $searchData[] = $topic;
    }

    // 4. Mitra & Partner dari database
    if (isset($partners) && count($partners) > 0) {
        foreach ($partners as $partner) {
            $partnerName = is_array($partner) ? ($partner['nama'] ?? $partner['nama_partner'] ?? '') : ($partner->nama ?? $partner->nama_partner ?? '');
            if (!empty($partnerName)) {
                $searchData[] = [
                    'label'    => 'Partner: ' . $partnerName,
                    'desc'     => 'Kemitraan strategis implementasi teknologi AI',
                    'url'      => '/#tentang',
                    'keywords' => ['partner', 'mitra', 'klien', 'kolaborasi', 'perusahaan', strtolower($partnerName)],
                    'icon'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
                    'category' => 'Mitra',
                ];
            }
        }
    }

    // 5. Halaman & Aksi Navigasi
    $searchData[] = [
        'label'    => 'Wawasan & Semua Artikel AI',
        'desc'     => 'Eksplorasi seluruh publikasi artikel, wawasan dan tren AI terlengkap',
        'url'      => route('articles.index'),
        'keywords' => ['semua', 'artikel', 'wawasan', 'blog', 'daftar', 'berita', 'insight', 'baca', 'publikasi'],
        'icon'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>',
        'category' => 'Halaman',
    ];
    $searchData[] = [
        'label'    => 'Tentang AI Solution & Profil CEO',
        'desc'     => 'Profil perusahaan, visi misi transformasi kecerdasan buatan, dan pimpinan',
        'url'      => '/#tentang',
        'keywords' => ['tentang', 'about', 'profil', 'ceo', 'perusahaan', 'visi', 'misi', 'pemimpin', 'intan'],
        'icon'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
        'category' => 'Halaman',
    ];
    $searchData[] = [
        'label'    => 'Konsultasikan Kebutuhan AI Anda',
        'desc'     => 'Kirim pesan atau formulir rencana proyek implementasi AI perusahaan',
        'url'      => '/#konsultasi',
        'keywords' => ['konsultasi', 'formulir', 'pesan', 'kebutuhan', 'rencana', 'gratis', 'tanya', 'form'],
        'icon'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
        'category' => 'Aksi',
    ];
    $searchData[] = [
        'label'    => 'Chat Tim Ahli via WhatsApp',
        'desc'     => 'Konsultasi cepat langsung via WhatsApp dengan tim spesialis AI Solution',
        'url'      => $waUrl,
        'external' => true,
        'keywords' => ['whatsapp', 'wa', 'chat', 'kontak', 'telepon', 'cs', 'hubungi', 'bantuan'],
        'icon'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.26h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.18 6.18l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
        'category' => 'Aksi',
    ];
    $searchData[] = [
        'label'    => 'Kembali ke Atas / Beranda',
        'desc'     => 'Scroll ke bagian paling atas landing page AI Solution',
        'url'      => '#',
        'action'   => 'scrollTop',
        'keywords' => ['beranda', 'home', 'atas', 'top', 'awal', 'homepage', 'scroll'],
        'icon'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
        'category' => 'Aksi',
    ];
@endphp

    // Define searchable items dynamically from DB (articles, services, AI topics, partners, pages)
    const searchItems = {!! json_encode($searchData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) !!};

    function renderSearchResults(query) {
        if (!searchResults) return;
        var q = query.trim().toLowerCase();

        if (!q) {
            searchResults.innerHTML = '';
            searchResults.appendChild(searchPlaceholder);
            searchPlaceholder.style.display = 'block';
            return;
        }

        searchPlaceholder.style.display = 'none';

        var qWords = q.split(/\s+/).filter(Boolean);

        var matched = searchItems.filter(function(item) {
            var labelLower = (item.label || '').toLowerCase();
            var descLower = (item.desc || '').toLowerCase();
            var catLower = (item.category || '').toLowerCase();
            var kwStr = (item.keywords || []).join(' ').toLowerCase();
            var haystack = labelLower + ' ' + descLower + ' ' + catLower + ' ' + kwStr;

            // Direct substring match
            if (haystack.indexOf(q) !== -1) return true;

            // Multi-word matching: all typed words must appear in haystack
            return qWords.every(function(word) {
                return haystack.indexOf(word) !== -1;
            });
        });

        if (matched.length === 0) {
            searchResults.innerHTML = '<div style="padding:28px 0;text-align:center;color:rgba(255,255,255,0.45);font-size:13.5px;"><div style="font-size:15px;font-weight:600;margin-bottom:4px;color:rgba(255,255,255,0.70);">Tidak ada hasil untuk "' + query.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '"</div>Coba kata kunci lain seperti "artikel", "layanan", "vision", "nlp", atau "machine learning"</div>';
            return;
        }

        // Group by category in prioritized order
        var order = ['Artikel', 'Layanan AI', 'Topik AI', 'Halaman', 'Mitra', 'Aksi'];
        var groups = {};
        matched.forEach(function(item) {
            if (!groups[item.category]) groups[item.category] = [];
            groups[item.category].push(item);
        });

        var badgeStyles = {
            'Artikel': 'background:rgba(0,212,255,0.12);color:#00d4ff;border:1px solid rgba(0,212,255,0.3);',
            'Layanan AI': 'background:rgba(124,58,237,0.14);color:#c084fc;border:1px solid rgba(124,58,237,0.35);',
            'Topik AI': 'background:rgba(236,72,153,0.12);color:#f472b6;border:1px solid rgba(236,72,153,0.3);',
            'Halaman': 'background:rgba(59,130,246,0.12);color:#60a5fa;border:1px solid rgba(59,130,246,0.3);',
            'Mitra': 'background:rgba(245,158,11,0.12);color:#fbbf24;border:1px solid rgba(245,158,11,0.3);',
            'Aksi': 'background:rgba(16,185,129,0.12);color:#34d399;border:1px solid rgba(16,185,129,0.3);'
        };

        var html = '';
        var sortedCats = Object.keys(groups).sort(function(a, b) {
            var ia = order.indexOf(a);
            var ib = order.indexOf(b);
            if (ia === -1) ia = 999;
            if (ib === -1) ib = 999;
            return ia - ib;
        });

        sortedCats.forEach(function(cat) {
            var bStyle = badgeStyles[cat] || 'background:rgba(0,212,255,0.10);color:#00d4ff;';
            html += '<div style="font-size:10.5px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(0,212,255,0.70);padding:10px 12px 6px;">' + cat + ' (' + groups[cat].length + ')</div>';
            groups[cat].forEach(function(item) {
                var target = item.external ? ' target="_blank" rel="noopener noreferrer"' : '';
                var dataAction = item.action ? ' data-action="' + item.action + '"' : '';
                html += '<a href="' + item.url + '"' + target + dataAction + ' class="search-result-item" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;text-decoration:none;color:rgba(255,255,255,0.88);transition:all 0.15s ease;cursor:pointer;margin-bottom:2px;">';
                html += '<span style="flex-shrink:0;opacity:0.75;display:flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;background:rgba(255,255,255,0.04);">' + item.icon + '</span>';
                html += '<span style="flex:1;min-width:0;"><div style="font-size:13.5px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + item.label + '</div><div style="font-size:11.5px;color:rgba(255,255,255,0.48);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + item.desc + '</div></span>';
                html += '<span style="font-size:10px;padding:3px 9px;border-radius:100px;font-weight:600;white-space:nowrap;' + bStyle + '">' + cat + '</span>';
                html += '</a>';
            });
        });

        searchResults.innerHTML = html;

        // Add hover styles and click handlers
        searchResults.querySelectorAll('.search-result-item').forEach(function(el) {
            el.addEventListener('mouseenter', function() { this.style.background = 'rgba(0,212,255,0.09)'; });
            el.addEventListener('mouseleave', function() { this.style.background = 'none'; });
            el.addEventListener('click', function(e) {
                if (this.dataset.action === 'scrollTop') {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
                searchModal.style.display = 'none';
                searchInput.value = '';
                renderSearchResults('');
            });
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            renderSearchResults(this.value);
        });
    }

    document.addEventListener('keydown', function(e) {
        // Ctrl+K or Cmd+K
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            if (searchModal) {
                searchModal.style.display = 'flex';
                if (searchInput) { searchInput.value = ''; searchInput.focus(); }
                renderSearchResults('');
            }
        }
        // ESC to close
        if (e.key === 'Escape') {
            if (searchModal) {
                searchModal.style.display = 'none';
                if (searchInput) searchInput.value = '';
                renderSearchResults('');
            }
        }
        // Arrow keys for navigation inside results
        if (searchModal && searchModal.style.display === 'flex' && searchResults) {
            var items = searchResults.querySelectorAll('.search-result-item');
            if (!items.length) return;
            var focused = searchResults.querySelector('.search-result-item:focus');
            var idx = Array.from(items).indexOf(focused);
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
        }
    });

    // Close modal on backdrop click
    if (searchModal) {
        searchModal.addEventListener('click', function(e) {
            if (e.target === searchModal) {
                searchModal.style.display = 'none';
                if (searchInput) searchInput.value = '';
                renderSearchResults('');
            }
        });
    }

    // --- Particle generator ---
    function createParticles() {
        const container = document.getElementById('particles-container');
        if (!container) return;

        const particleCount = 22;
        const colors = [
            'rgba(0, 168, 255, 0.55)',
            'rgba(0, 212, 255, 0.45)',
            'rgba(124, 58, 237, 0.45)',
            'rgba(167, 139, 250, 0.40)',
            'rgba(236, 72, 153, 0.35)',
        ];

        for (let i = 0; i < particleCount; i++) {
            const p = document.createElement('div');
            p.className = 'particle';

            const size = Math.random() * 4 + 1.5; // 1.5–5.5px
            const color = colors[Math.floor(Math.random() * colors.length)];
            const left = Math.random() * 100;
            const duration = Math.random() * 20 + 15; // 15–35s
            const delay = Math.random() * -30; // stagger

            p.style.cssText = `
                width: ${size}px;
                height: ${size}px;
                background: ${color};
                left: ${left}%;
                bottom: 0;
                animation-duration: ${duration}s;
                animation-delay: ${delay}s;
                box-shadow: 0 0 ${size * 3}px ${color};
            `;

            container.appendChild(p);
        }
    }

    createParticles();

    // --- Navbar scroll effect ---
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            navbar.classList.toggle('scrolled', window.scrollY > 30);
        }, { passive: true });
    }

    // --- Subtle parallax on mouse move (hero bg) ---
    const heroBg = document.querySelector('.hero-bg');
    const heroSection = document.querySelector('.hero-section');

    if (heroBg && heroSection && window.innerWidth > 1024) {
        heroSection.addEventListener('mousemove', function(e) {
            const rect = heroSection.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;

            const moveX = (x - 0.5) * 12;
            const moveY = (y - 0.5) * 8;

            heroBg.style.transform = `translate(${moveX}px, ${moveY}px) scale(1.04)`;
            heroBg.style.transition = 'transform 0.6s cubic-bezier(0.2, 0, 0.2, 1)';
        });

        heroSection.addEventListener('mouseleave', function() {
            heroBg.style.transform = 'translate(0, 0) scale(1)';
            heroBg.style.transition = 'transform 1s cubic-bezier(0.2, 0, 0.2, 1)';
        });
    }

})();

    // ==========================================
    //  MODAL KONSULTASI
    // ==========================================
    const modalKonsultasi = document.getElementById('modal-konsultasi');

    function openKonsultasiModal() {
        if (!modalKonsultasi) return;
        modalKonsultasi.classList.add('active');
        document.body.style.overflow = 'hidden';
        // Focus first input
        const firstInput = modalKonsultasi.querySelector('input, textarea');
        if (firstInput) setTimeout(function() { firstInput.focus(); }, 100);
    }

    function closeKonsultasiModal() {
        if (!modalKonsultasi) return;
        modalKonsultasi.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close on backdrop click
    if (modalKonsultasi) {
        modalKonsultasi.addEventListener('click', function(e) {
            if (e.target === modalKonsultasi) closeKonsultasiModal();
        });
    }

    // ESC key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modalKonsultasi && modalKonsultasi.classList.contains('active')) {
            closeKonsultasiModal();
        }
    });

    // Auto-open modal if session success or validation errors (form just submitted)
    @if(session('success') || $errors->any())
    window.addEventListener('DOMContentLoaded', function() {
        openKonsultasiModal();
    });
    @endif

</script>

{{-- WhatsApp Chat Widget --}}
@include('partials.wa-widget')

</body>
</html>
