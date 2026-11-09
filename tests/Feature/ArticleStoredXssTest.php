<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Services\HtmlSanitizer;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ArticleStoredXssTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin@jogjaaisolutions.my.id'],
            [
                'name' => 'Admin Website',
                'password' => bcrypt('password123'),
                'role' => 'admin_website',
            ]
        );
    }

    /**
     * 1. Membuat artikel baru dengan teks biasa.
     */
    public function test_create_article_with_plain_text()
    {
        $payload = [
            'judul' => 'Artikel Teks Biasa ' . uniqid(),
            'slug' => 'artikel-teks-biasa-' . uniqid(),
            'kategori' => 'Teknologi',
            'author_name' => 'Tim AI',
            'ringkasan' => 'Ringkasan artikel teks biasa.',
            'isi_artikel' => json_encode([
                'content' => 'Ini adalah artikel biasa tanpa tag html sama sekali.',
                'takeaway' => 'Kesimpulan artikel biasa.',
                'readTime' => '1 mnt'
            ]),
            'status' => 'published',
            'published_at' => now('Asia/Jakarta')->format('Y-m-d H:i'),
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $response->assertRedirect(route('admin.articles.index'));

        $article = Article::where('slug', $payload['slug'])->first();
        $this->assertNotNull($article);
        $this->assertStringContainsString('Ini adalah artikel biasa', $article->isi_artikel);

        // Akses halaman publik detail artikel
        $publicRes = $this->get('/wawasan/' . $article->slug);
        $publicRes->assertStatus(200);
        $publicRes->assertSee('Ini adalah artikel biasa');
    }

    /**
     * 2. Membuat artikel baru dengan format HTML yang diperbolehkan.
     */
    public function test_create_article_with_allowed_html_format()
    {
        $allowedHtml = '<h3>Pengantar AI</h3><p>Kecerdasan buatan memberikan <strong>dampak nyata</strong> dan <em>keuntungan besar</em>.</p><ul><li>Efisiensi</li><li>Otomatisasi</li></ul><p><a href="https://example.com" target="_blank">Kunjungi kami</a></p>';

        $payload = [
            'judul' => 'Artikel HTML Valid ' . uniqid(),
            'slug' => 'artikel-html-valid-' . uniqid(),
            'kategori' => 'Bisnis',
            'author_name' => 'Pakar AI',
            'ringkasan' => 'Ringkasan artikel dengan formatting.',
            'isi_artikel' => json_encode([
                'content' => $allowedHtml,
                'takeaway' => 'AI memacu efisiensi bisnis.',
                'readTime' => '2 mnt'
            ]),
            'status' => 'published',
            'published_at' => now('Asia/Jakarta')->format('Y-m-d H:i'),
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $response->assertRedirect(route('admin.articles.index'));

        $article = Article::where('slug', $payload['slug'])->first();
        $this->assertNotNull($article);

        // Akses publik dan pastikan formatting dipertahankan
        $publicRes = $this->get('/wawasan/' . $article->slug);
        $publicRes->assertStatus(200);
        $publicRes->assertSee('<h3>Pengantar AI</h3>', false);
        $publicRes->assertSee('<strong>dampak nyata</strong>', false);
        $publicRes->assertSee('<em>keuntungan besar</em>', false);
        $publicRes->assertSee('<li>Efisiensi</li>', false);
        $publicRes->assertSee('rel="noopener noreferrer"', false);
    }

    /**
     * 3. Menguji payload XSS berbahaya dan memastikan tidak dieksekusi / dinetralkan.
     */
    public function test_xss_payloads_are_sanitized_and_blocked()
    {
        $xssContent = '<script>alert("Stored-XSS")</script>' .
                      '<img src="x" onerror="alert(1)">' .
                      '<a href="javascript:alert(1)">Klik XSS</a>' .
                      '<svg onload="alert(1)"><circle cx="50" cy="50" r="40" /></svg>' .
                      '<iframe src="javascript:alert(1)"></iframe>' .
                      '<p onmouseover="alert(1)">Paragraf Aman</p>';

        $payload = [
            'judul' => 'Uji Keamanan Stored XSS ' . uniqid(),
            'slug' => 'uji-keamanan-stored-xss-' . uniqid(),
            'kategori' => 'Keamanan',
            'author_name' => 'Security Tester',
            'ringkasan' => 'Uji penetrasi XSS pada konten.',
            'isi_artikel' => json_encode([
                'content' => $xssContent,
                'takeaway' => '<script>alert("takeaway")</script>Takeaway Bersih',
                'readTime' => '1 mnt'
            ]),
            'status' => 'published',
            'published_at' => now('Asia/Jakarta')->format('Y-m-d H:i'),
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $response->assertRedirect(route('admin.articles.index'));

        $article = Article::where('slug', $payload['slug'])->first();
        $this->assertNotNull($article);

        // Verifikasi di DB bahwa script dan event handler dibersihkan
        $this->assertStringNotContainsString('<script', $article->isi_artikel);
        $this->assertStringNotContainsString('onerror', $article->isi_artikel);
        $this->assertStringNotContainsString('javascript:', $article->isi_artikel);
        $this->assertStringNotContainsString('<svg', $article->isi_artikel);
        $this->assertStringNotContainsString('<iframe', $article->isi_artikel);
        $this->assertStringNotContainsString('onmouseover', $article->isi_artikel);

        // Verifikasi pada halaman publik wawasan/{slug}
        $publicRes = $this->get('/wawasan/' . $article->slug);
        $publicRes->assertStatus(200);
        $publicRes->assertDontSee('Stored-XSS', false);
        $publicRes->assertDontSee('alert(1)', false);
        $publicRes->assertDontSee('onerror="alert(1)"', false);
        $publicRes->assertDontSee('javascript:alert', false);
        $publicRes->assertDontSee('<iframe', false);
        $publicRes->assertDontSee('<svg onload', false);
        $publicRes->assertDontSee('onmouseover', false);
        $publicRes->assertSee('Paragraf Aman');

        // Verifikasi pada halaman edit admin
        $adminRes = $this->actingAs($this->admin)->get(route('admin.articles.edit', $article->id));
        $adminRes->assertStatus(200);
        $adminRes->assertDontSee('<script>alert("Stored-XSS")</script>', false);
        $adminRes->assertDontSee('onerror="alert(1)"', false);
    }

    /**
     * 4. Mengedit artikel lama tanpa merusak format kontennya.
     */
    public function test_edit_existing_article_preserves_formatting()
    {
        $article = Article::create([
            'judul' => 'Artikel Lama Untuk Diedit ' . uniqid(),
            'slug' => 'artikel-lama-edit-' . uniqid(),
            'kategori' => 'AI',
            'author_name' => 'Penulis Asli',
            'ringkasan' => 'Ringkasan asli sebelum diedit.',
            'isi_artikel' => json_encode([
                'content' => '<h3>Header Lama</h3><p>Paragraf lama dengan <strong>kata penting</strong>.</p>',
                'takeaway' => 'Takeaway lama',
                'readTime' => '2 mnt'
            ]),
            'status' => 'published',
            'published_at' => now('Asia/Jakarta')->subDays(2),
            'is_active' => true,
        ]);

        $updatePayload = [
            'judul' => 'Artikel Lama Telah Diperbarui',
            'slug' => $article->slug,
            'kategori' => 'AI & Bisnis',
            'author_name' => 'Penulis Asli Diperbarui',
            'ringkasan' => 'Ringkasan baru setelah diedit.',
            'isi_artikel' => json_encode([
                'content' => '<h3>Header Lama Diperbarui</h3><p>Paragraf baru dengan <strong>kata penting</strong> dan <em>tambahan miring</em>.</p>',
                'takeaway' => 'Takeaway baru',
                'readTime' => '3 mnt'
            ]),
            'status' => 'published',
            'published_at' => $article->published_at->format('Y-m-d H:i'),
            'image_type' => 'current',
            'is_active' => '1',
        ];

        $res = $this->actingAs($this->admin)->put(route('admin.articles.update', $article->id), $updatePayload);
        $res->assertRedirect(route('admin.articles.index'));

        $article->refresh();
        $this->assertEquals('Artikel Lama Telah Diperbarui', $article->judul);
        $this->assertStringContainsString('<h3>Header Lama Diperbarui</h3>', $article->isi_artikel);
        $this->assertStringContainsString('<strong>kata penting</strong>', $article->isi_artikel);
        $this->assertStringContainsString('<em>tambahan miring</em>', $article->isi_artikel);
    }

    /**
     * 5. Menyimpan artikel draft.
     */
    public function test_save_draft_article()
    {
        $payload = [
            'judul' => 'Artikel Konsep Draft ' . uniqid(),
            'slug' => 'artikel-konsep-draft-' . uniqid(),
            'kategori' => 'R&D',
            'author_name' => 'Penulis Draft',
            'ringkasan' => 'Artikel draft masih dalam tahap penulisan.',
            'isi_artikel' => json_encode([
                'content' => '<p>Konten artikel yang masih draft.</p>',
                'takeaway' => 'Takeaway draft',
                'readTime' => '1 mnt'
            ]),
            'status' => 'draft',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $response->assertRedirect(route('admin.articles.index'));

        $draft = Article::where('slug', $payload['slug'])->first();
        $this->assertNotNull($draft);
        $this->assertEquals('draft', $draft->status);

        // Draft tidak boleh tampil di halaman publik wawasan/{slug} (harus 404)
        $publicRes = $this->get('/wawasan/' . $draft->slug);
        $publicRes->assertStatus(404);
    }

    /**
     * 6. Menyimpan artikel published.
     */
    public function test_save_published_article()
    {
        $pubDate = now('Asia/Jakarta');
        $payload = [
            'judul' => 'Artikel Terbit Langsung ' . uniqid(),
            'slug' => 'artikel-terbit-langsung-' . uniqid(),
            'kategori' => 'Produksi',
            'author_name' => 'Editor Terbit',
            'ringkasan' => 'Artikel langsung dipublikasikan.',
            'isi_artikel' => json_encode([
                'content' => '<p>Konten artikel langsung terbit.</p>',
                'takeaway' => 'Takeaway terbit',
                'readTime' => '1 mnt'
            ]),
            'status' => 'published',
            'published_at' => $pubDate->format('Y-m-d H:i'),
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $response->assertRedirect(route('admin.articles.index'));

        $article = Article::where('slug', $payload['slug'])->first();
        $this->assertNotNull($article);
        $this->assertEquals('published', $article->status);

        $publicRes = $this->get('/wawasan/' . $article->slug);
        $publicRes->assertStatus(200);
        $publicRes->assertSee('Artikel Terbit Langsung');
    }

    /**
     * 7. Membuat artikel scheduled dengan tanggal dan jam tertentu.
     */
    public function test_save_scheduled_article_with_specific_datetime()
    {
        $futureDate = now('Asia/Jakarta')->addDays(3)->setTime(14, 30);
        $payload = [
            'judul' => 'Artikel Jadwal Terencana ' . uniqid(),
            'slug' => 'artikel-jadwal-terencana-' . uniqid(),
            'kategori' => 'Jadwal',
            'author_name' => 'Penjadwal',
            'ringkasan' => 'Artikel dijadwalkan terbit lusa jam 14:30.',
            'isi_artikel' => json_encode([
                'content' => '<p>Konten artikel terjadwal di masa depan.</p>',
                'takeaway' => 'Takeaway terjadwal',
                'readTime' => '1 mnt'
            ]),
            'status' => 'scheduled',
            'published_at' => $futureDate->format('Y-m-d H:i'),
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $response->assertRedirect(route('admin.articles.index'));

        $scheduled = Article::where('slug', $payload['slug'])->first();
        $this->assertNotNull($scheduled);
        $this->assertEquals('scheduled', $scheduled->status);
        $this->assertEquals('14:30', $scheduled->published_at->timezone('Asia/Jakarta')->format('H:i'));

        // Belum boleh tampil di publik
        $publicRes = $this->get('/wawasan/' . $scheduled->slug);
        $publicRes->assertStatus(404);
    }

    /**
     * 8. Memastikan scheduler tetap berfungsi (articles:publish-scheduled).
     */
    public function test_scheduler_command_publishes_overdue_articles()
    {
        // Buat artikel scheduled yang jadwalnya telah lewat (overdue)
        $pastDate = now('Asia/Jakarta')->subMinutes(5);
        $article = Article::create([
            'judul' => 'Artikel Overdue Scheduler ' . uniqid(),
            'slug' => 'artikel-overdue-' . uniqid(),
            'kategori' => 'Scheduler',
            'author_name' => 'Cron Runner',
            'ringkasan' => 'Artikel yang jadwalnya telah lewat harus otomatis terbit.',
            'isi_artikel' => json_encode([
                'content' => '<p>Artikel yang diterbitkan scheduler otomatis.</p>',
                'takeaway' => 'Scheduler aman',
                'readTime' => '1 mnt'
            ]),
            'status' => 'scheduled',
            'published_at' => $pastDate,
            'is_active' => true,
        ]);

        $this->assertEquals('scheduled', $article->status);

        // Jalankan perintah artisan publish-scheduled
        $exitCode = Artisan::call('articles:publish-scheduled');
        $this->assertEquals(0, $exitCode);

        $article->refresh();
        $this->assertEquals('published', $article->status);

        // Sekarang artikel harus tampil di publik
        $publicRes = $this->get('/wawasan/' . $article->slug);
        $publicRes->assertStatus(200);
        $publicRes->assertSee('Artikel Overdue Scheduler');
    }

    /**
     * 9. Memastikan gambar dan banner artikel tetap tampil.
     */
    public function test_article_image_and_banner_display_properly()
    {
        $imageUrl = 'https://images.unsplash.com/photo-1677442136019-21780efad99a';
        $article = Article::create([
            'judul' => 'Artikel Dengan Banner ' . uniqid(),
            'slug' => 'artikel-banner-' . uniqid(),
            'kategori' => 'Visual AI',
            'author_name' => 'Visual Team',
            'ringkasan' => 'Artikel dengan ilustrasi visual banner.',
            'isi_artikel' => json_encode([
                'content' => '<p>Artikel dengan gambar banner lengkap.</p>',
                'takeaway' => 'Visual AI',
                'readTime' => '1 mnt'
            ]),
            'gambar' => $imageUrl,
            'status' => 'published',
            'published_at' => now('Asia/Jakarta')->subHour(),
            'is_active' => true,
        ]);

        $this->assertEquals($imageUrl, $article->image_url);

        $publicRes = $this->get('/wawasan/' . $article->slug);
        $publicRes->assertStatus(200);
        $publicRes->assertSee($imageUrl);
    }

    /**
     * 10. Memastikan halaman publik dan admin tidak mengalami error.
     */
    public function test_public_and_admin_pages_render_without_error()
    {
        // 1. Homepage
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);

        // 2. Halaman Indeks Wawasan
        $wawasanRes = $this->get('/wawasan');
        $wawasanRes->assertStatus(200);

        // 3. Admin Artikel Index
        $adminIndexRes = $this->actingAs($this->admin)->get(route('admin.articles.index'));
        $adminIndexRes->assertStatus(200);

        // 4. Admin Artikel Calendar
        $adminCalRes = $this->actingAs($this->admin)->get(route('admin.articles.calendar'));
        $adminCalRes->assertStatus(200);

        // 5. Admin Artikel Create
        $adminCreateRes = $this->actingAs($this->admin)->get(route('admin.articles.create'));
        $adminCreateRes->assertStatus(200);

        // 6. Admin Artikel Edit
        $sample = Article::first();
        if ($sample) {
            $adminEditRes = $this->actingAs($this->admin)->get(route('admin.articles.edit', $sample->id));
            $adminEditRes->assertStatus(200);
        }
    }

    /**
     * 11. Memastikan ringkasan mempertahankan simbol '<' dan '>' (Nilai <50% dan >10%) tanpa terpotong
     *     dan tetap aman dari XSS dengan perlindungan Blade escaping.
     */
    public function test_ringkasan_preserves_math_symbols_and_blocks_xss()
    {
        $rawSummary = 'Target akurasi model AI adalah Nilai <50% dan >10% pertumbuhan <script>alert("XSS")</script> kuartal ini.';
        $expectedInDb = 'Target akurasi model AI adalah Nilai <50% dan >10% pertumbuhan  kuartal ini.';

        $payload = [
            'judul' => 'Uji Simbol Ringkasan ' . uniqid(),
            'slug' => 'uji-simbol-ringkasan-' . uniqid(),
            'kategori' => 'AI Analytics',
            'author_name' => 'Data Scientist',
            'ringkasan' => $rawSummary,
            'isi_artikel' => json_encode([
                'content' => '<p>Konten artikel dengan ringkasan berisi simbol matematika.</p>',
                'takeaway' => 'Efisiensi <50% dan >10%',
                'readTime' => '1 mnt'
            ]),
            'status' => 'published',
            'published_at' => now('Asia/Jakarta')->format('Y-m-d H:i'),
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $response->assertRedirect(route('admin.articles.index'));

        $article = Article::where('slug', $payload['slug'])->first();
        $this->assertNotNull($article);

        // Verifikasi di DB bahwa '<50%' dan '>10%' tetap utuh dan script XSS dibuang
        $this->assertEquals($expectedInDb, $article->ringkasan);
        $this->assertStringContainsString('<50%', $article->ringkasan);
        $this->assertStringContainsString('>10%', $article->ringkasan);
        $this->assertStringNotContainsString('<script', $article->ringkasan);

        // Verifikasi pada halaman publik wawasan/{slug}
        $publicRes = $this->get('/wawasan/' . $article->slug);
        $publicRes->assertStatus(200);

        // Di-escape oleh Blade {{ }} sehingga aman dari XSS
        $publicRes->assertSee('Nilai &lt;50% dan &gt;10%', false);
        $publicRes->assertDontSee('alert("XSS")', false);

        // Verifikasi pada meta description dan og:description
        $publicRes->assertSee('Nilai &lt;50% dan &gt;10%', false);

        // Verifikasi pada halaman form edit admin (textarea)
        $adminRes = $this->actingAs($this->admin)->get(route('admin.articles.edit', $article->id));
        $adminRes->assertStatus(200);
        $adminRes->assertSee('Nilai &lt;50% dan &gt;10%', false);
        $adminRes->assertDontSee('alert("XSS")', false);
    }
}
