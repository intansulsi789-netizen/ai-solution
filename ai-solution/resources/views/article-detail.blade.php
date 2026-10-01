<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article['title'] }} - AI Solution</title>
    <meta name="description" content="{{ $article['summary'] }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
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
            background-image:
                radial-gradient(circle at 15% 50%, rgba(0, 212, 255, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(124, 58, 237, 0.06) 0%, transparent 50%);
            background-attachment: fixed;
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
            max-width: 900px;
            margin: 0 auto;
            padding: 20px 5% 0;
            animation: fadeUp 0.7s ease forwards;
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
                    <img src="{{ $related['image'] }}" alt="{{ $related['title'] }}" class="sidebar-related-img">
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
    <section class="related-section">
        <h2 class="related-section-title">Artikel Terkait</h2>
        <div class="related-grid">
            @foreach($allArticles as $related)
            <a href="{{ route('article.detail', ['slug' => $related['slug']]) }}" class="related-card">
                <img src="{{ $related['image'] }}" alt="{{ $related['title'] }}" class="related-card-img">
                <div class="related-card-body">
                    <div class="related-card-cat">{{ $related['category'] }}</div>
                    <div class="related-card-title">{{ $related['title'] }}</div>
                    <div class="related-card-date">{{ $related['date'] }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </section>

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
