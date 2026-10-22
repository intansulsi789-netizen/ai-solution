<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Article;
use App\Models\Service;
use App\Models\CmsSeo;

class SeoMetaTagsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Ensure the homepage contains Open Graph, Twitter Card, canonical URL, and favicon.
     */
    public function test_homepage_has_seo_meta_tags(): void
    {
        // Create a minimal CmsSeo record
        CmsSeo::create([
            'meta_title' => 'AI Solution - Platform AI Cerdas',
            'meta_description' => 'Platform AI terdepan untuk semua profesi.',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        // Open Graph
        $response->assertSee('og:type', false);
        $response->assertSee('og:title', false);
        $response->assertSee('og:description', false);
        $response->assertSee('og:image', false);
        $response->assertSee('og:url', false);
        $response->assertSee('og:site_name', false);
        $response->assertSee('og:locale', false);

        // Twitter Card
        $response->assertSee('twitter:card', false);
        $response->assertSee('twitter:title', false);
        $response->assertSee('twitter:description', false);
        $response->assertSee('twitter:image', false);

        // Canonical URL
        $response->assertSee('rel="canonical"', false);
        $response->assertSee('https://aisolution.duckdns.org/', false);

        // Favicon
        $response->assertSee('rel="icon"', false);
        $response->assertSee('favicon.jpg', false);
    }

    /**
     * Ensure the wawasan (articles listing) page has full SEO meta tags.
     */
    public function test_wawasan_page_has_seo_meta_tags(): void
    {
        $response = $this->get('/wawasan');
        $response->assertStatus(200);

        // Open Graph
        $response->assertSee('og:type', false);
        $response->assertSee('og:title', false);
        $response->assertSee('og:url', false);
        $response->assertSee('og:image', false);

        // Twitter Card
        $response->assertSee('twitter:card', false);

        // Canonical URL
        $response->assertSee('rel="canonical"', false);
        $response->assertSee('https://aisolution.duckdns.org/wawasan', false);

        // Favicon
        $response->assertSee('rel="icon"', false);
    }

    /**
     * Ensure the article detail page has dynamic SEO meta tags from the article data.
     */
    public function test_article_detail_has_dynamic_seo_meta_tags(): void
    {
        $article = Article::create([
            'judul' => 'Test Article Title SEO',
            'slug' => 'test-article-title-seo',
            'kategori' => 'AI',
            'ringkasan' => 'This is a test summary for SEO verification purposes.',
            'isi_artikel' => json_encode([
                'content' => '<p>Test content</p>',
                'takeaway' => 'Test takeaway',
                'readTime' => '5 min',
            ]),
            'gambar' => 'https://example.com/test-image.jpg',
            'is_active' => true,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('/wawasan/test-article-title-seo');
        $response->assertStatus(200);

        // Open Graph should use article data
        $response->assertSee('og:type', false);
        $response->assertSee('Test Article Title SEO', false);
        $response->assertSee('This is a test summary', false);
        $response->assertSee('https://example.com/test-image.jpg', false);
        $response->assertSee('https://aisolution.duckdns.org/wawasan/test-article-title-seo', false);

        // Twitter Card
        $response->assertSee('twitter:card', false);
        $response->assertSee('summary_large_image', false);

        // Canonical URL
        $response->assertSee('rel="canonical"', false);

        // Favicon
        $response->assertSee('rel="icon"', false);
    }

    /**
     * Ensure draft articles are NOT accessible publicly and return 404.
     */
    public function test_draft_article_not_accessible_publicly(): void
    {
        Article::create([
            'judul' => 'Draft Article',
            'slug' => 'draft-article',
            'kategori' => 'AI',
            'ringkasan' => 'This should not be visible.',
            'isi_artikel' => json_encode(['content' => 'Draft', 'takeaway' => '', 'readTime' => '']),
            'gambar' => null,
            'is_active' => true,
            'status' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->get('/wawasan/draft-article');
        $response->assertStatus(404);
    }

    /**
     * Ensure scheduled articles with future publish date are NOT accessible publicly.
     */
    public function test_scheduled_future_article_not_accessible_publicly(): void
    {
        Article::create([
            'judul' => 'Scheduled Future Article',
            'slug' => 'scheduled-future-article',
            'kategori' => 'AI',
            'ringkasan' => 'This should not be visible yet.',
            'isi_artikel' => json_encode(['content' => 'Scheduled', 'takeaway' => '', 'readTime' => '']),
            'gambar' => null,
            'is_active' => true,
            'status' => 'scheduled',
            'published_at' => now()->addDays(7),
        ]);

        $response = $this->get('/wawasan/scheduled-future-article');
        $response->assertStatus(404);
    }

    /**
     * Ensure draft/scheduled articles do NOT appear in the sitemap.
     */
    public function test_sitemap_excludes_draft_and_scheduled_articles(): void
    {
        Article::create([
            'judul' => 'Published Article',
            'slug' => 'published-article',
            'kategori' => 'AI',
            'ringkasan' => 'Published.',
            'isi_artikel' => json_encode(['content' => 'Live', 'takeaway' => '', 'readTime' => '']),
            'gambar' => null,
            'is_active' => true,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        Article::create([
            'judul' => 'Draft Hidden',
            'slug' => 'draft-hidden',
            'kategori' => 'AI',
            'ringkasan' => 'Draft.',
            'isi_artikel' => json_encode(['content' => 'Draft', 'takeaway' => '', 'readTime' => '']),
            'gambar' => null,
            'is_active' => true,
            'status' => 'draft',
            'published_at' => null,
        ]);

        Article::create([
            'judul' => 'Scheduled Hidden',
            'slug' => 'scheduled-hidden',
            'kategori' => 'AI',
            'ringkasan' => 'Scheduled.',
            'isi_artikel' => json_encode(['content' => 'Scheduled', 'takeaway' => '', 'readTime' => '']),
            'gambar' => null,
            'is_active' => true,
            'status' => 'scheduled',
            'published_at' => now()->addDays(7),
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertSee('published-article', false);
        $response->assertDontSee('draft-hidden', false);
        $response->assertDontSee('scheduled-hidden', false);
    }

    /**
     * Ensure robots.txt contains proper directives.
     */
    public function test_robots_txt_has_proper_directives(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertStatus(200);
        $response->assertSee('Disallow: /admin', false);
        $response->assertSee('Sitemap: https://aisolution.duckdns.org/sitemap.xml', false);
    }

    /**
     * Ensure the service detail page has correct SEO tags and fixed branding.
     */
    public function test_service_detail_has_seo_meta_tags(): void
    {
        $slug = 'seo-test-service-' . uniqid();
        $service = Service::create([
            'nama_layanan' => 'AI Chatbot Test',
            'slug' => $slug,
            'icon' => 'fa-robot',
            'deskripsi_singkat' => 'Layanan chatbot AI untuk bisnis Anda.',
            'deskripsi_lengkap' => json_encode(['solutions' => [], 'benefits' => []]),
            'is_active' => true,
            'nomor' => 99,
        ]);

        $response = $this->get('/layanan/' . $slug);
        $response->assertStatus(200);

        // Check correct branding (should be 'AI Solution', NOT 'AI Jogja Solutions')
        $response->assertSee('AI Chatbot Test - AI Solution', false);
        $response->assertDontSee('AI Jogja Solutions', false);

        // Meta description
        $response->assertSee('Layanan chatbot AI', false);

        // Open Graph
        $response->assertSee('og:type', false);
        $response->assertSee('og:title', false);
        $response->assertSee('https://aisolution.duckdns.org/layanan/' . $slug, false);

        // Twitter Card
        $response->assertSee('twitter:card', false);

        // Canonical URL
        $response->assertSee('rel="canonical"', false);

        // Favicon
        $response->assertSee('rel="icon"', false);
    }
}
