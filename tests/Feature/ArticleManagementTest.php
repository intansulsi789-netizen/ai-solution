<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleManagementTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::first();
    }

    public function test_admin_can_view_articles_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.articles.index'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Artikel');
    }

    public function test_create_article_with_external_image_url_and_source()
    {
        $payload = [
            'judul'        => 'Artikel Uji Coba URL & Sumber',
            'slug'         => 'artikel-uji-coba-url-sumber',
            'kategori'     => 'AI & Inovasi',
            'source_name'  => 'Kementerian Perhubungan RI',
            'source_url'   => 'https://dephub.go.id/berita/baca/uji-coba',
            'image_type'   => 'url',
            'gambar_url'   => 'https://images.unsplash.com/photo-1518770660439-4636190af475',
            'ringkasan'    => 'Ringkasan artikel uji coba untuk URL gambar dan sumber.',
            'isi_artikel'  => json_encode(['content' => '<p>Konten artikel</p>', 'takeaway' => 'Intinya AI hebat', 'readTime' => '3 mnt']),
            'status'       => 'published',
            'is_active'    => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $response->assertRedirect(route('admin.articles.index'));

        $article = Article::where('slug', 'artikel-uji-coba-url-sumber')->first();
        $this->assertNotNull($article);
        $this->assertEquals('Kementerian Perhubungan RI', $article->source_name);
        $this->assertEquals('https://dephub.go.id/berita/baca/uji-coba', $article->source_url);
        $this->assertEquals('https://images.unsplash.com/photo-1518770660439-4636190af475', $article->gambar);
        $this->assertEquals('published', $article->status);
        $this->assertNotNull($article->published_at);

        // Pastikan tampil pada halaman publik detail artikel beserta sumbernya
        $detailResponse = $this->get('/wawasan/' . $article->slug);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Kementerian Perhubungan RI');
        $detailResponse->assertSee('https://dephub.go.id/berita/baca/uji-coba');
    }

    public function test_create_article_with_file_upload()
    {
        Storage::fake('public');

        // Valid 1x1 PNG binary data (tidak membutuhkan ekstensi PHP GD)
        $pngData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $file = UploadedFile::fake()->createWithContent('banner_test.png', $pngData);

        $payload = [
            'judul'        => 'Artikel Uji Coba Upload Gambar',
            'slug'         => 'artikel-uji-coba-upload-gambar',
            'kategori'     => 'Teknologi',
            'image_type'   => 'upload',
            'gambar'       => $file,
            'ringkasan'    => 'Ringkasan upload gambar',
            'status'       => 'published',
            'is_active'    => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $response->assertRedirect(route('admin.articles.index'));

        $article = Article::where('slug', 'artikel-uji-coba-upload-gambar')->first();
        $this->assertNotNull($article);
        $this->assertStringStartsWith('articles/', $article->gambar);
        Storage::disk('public')->assertExists($article->gambar);
    }

    public function test_create_draft_article_does_not_appear_on_public_pages()
    {
        $payload = [
            'judul'        => 'Artikel Draft Rahasia',
            'slug'         => 'artikel-draft-rahasia',
            'status'       => 'draft',
            'is_active'    => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $response->assertRedirect(route('admin.articles.index'));

        $article = Article::where('slug', 'artikel-draft-rahasia')->first();
        $this->assertNotNull($article);
        $this->assertEquals('draft', $article->status);

        // Halaman detail publik harus 404 untuk draft
        $detailResponse = $this->get('/wawasan/' . $article->slug);
        $detailResponse->assertStatus(404);
    }

    public function test_scheduled_article_does_not_appear_before_due_time_and_publishes_via_artisan()
    {
        // Buat artikel yang dijadwalkan 2 jam di masa depan
        $futureTime = now()->addHours(2);

        $payload = [
            'judul'        => 'Artikel Masa Depan Terjadwal',
            'slug'         => 'artikel-masa-depan-terjadwal',
            'status'       => 'scheduled',
            'published_at' => $futureTime->format('Y-m-d\TH:i'),
            'is_active'    => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $response->assertRedirect(route('admin.articles.index'));

        $article = Article::where('slug', 'artikel-masa-depan-terjadwal')->first();
        $this->assertNotNull($article);
        $this->assertEquals('scheduled', $article->status);

        // Sebelum waktu jadwal, tidak boleh bisa diakses publik (404)
        $detailResponse = $this->get('/wawasan/' . $article->slug);
        $detailResponse->assertStatus(404);

        // Sekarang kita simulasikan artikel yang waktu jadwalnya sudah lampau (misal 5 menit lalu)
        $article->update([
            'published_at' => now()->subMinutes(5)
        ]);

        // Jalankan artisan scheduler command
        Artisan::call('articles:publish-scheduled');

        // Refresh artikel dari database
        $article->refresh();
        $this->assertEquals('published', $article->status);

        // Setelah dipublikasikan, artikel kini harus bisa diakses publik (200)
        $detailResponseAfter = $this->get('/wawasan/' . $article->slug);
        $detailResponseAfter->assertStatus(200);
        $detailResponseAfter->assertSee('Artikel Masa Depan Terjadwal');
    }

    public function test_editing_article_preserves_old_image_and_created_at()
    {
        $article = Article::create([
            'judul'        => 'Artikel Asli Lama',
            'slug'         => 'artikel-asli-lama',
            'gambar'       => 'https://images.unsplash.com/photo-old-image',
            'status'       => 'published',
            'published_at' => now()->subDays(10),
            'is_active'    => true,
        ]);

        $originalCreatedAt = $article->created_at->toDateTimeString();
        $originalGambar = $article->gambar;

        // Edit tanpa mengubah gambar (image_type = current / kosong)
        $payload = [
            'judul'        => 'Artikel Asli Lama - Judul Diperbarui',
            'slug'         => 'artikel-asli-lama',
            'image_type'   => 'current',
            'source_name'  => 'Sumber Baru Ditambahkan',
            'status'       => 'published',
            'is_active'    => '1',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.articles.update', $article->id), $payload);
        $response->assertRedirect(route('admin.articles.index'));

        $article->refresh();
        $this->assertEquals('Artikel Asli Lama - Judul Diperbarui', $article->judul);
        $this->assertEquals($originalGambar, $article->gambar); // Gambar lama tetap dipertahankan
        $this->assertEquals($originalCreatedAt, $article->created_at->toDateTimeString()); // created_at tidak ditimpa
        $this->assertEquals('Sumber Baru Ditambahkan', $article->source_name);
    }

    public function test_invalid_image_url_and_non_image_files_are_rejected()
    {
        // 1. Invalid URL
        $payloadUrl = [
            'judul'        => 'Artikel URL Invalid',
            'slug'         => 'artikel-url-invalid',
            'image_type'   => 'url',
            'gambar_url'   => 'bukan-url-valid',
            'status'       => 'draft',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payloadUrl);
        $response->assertSessionHasErrors(['gambar_url']);

        // 2. Non-image file upload
        Storage::fake('public');
        $pdfFile = UploadedFile::fake()->create('document.pdf', 100);

        $payloadFile = [
            'judul'        => 'Artikel File Invalid',
            'slug'         => 'artikel-file-invalid',
            'image_type'   => 'upload',
            'gambar'       => $pdfFile,
            'status'       => 'draft',
        ];

        $responseFile = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payloadFile);
        $responseFile->assertSessionHasErrors(['gambar']);
    }

    public function test_homepage_displays_maximum_three_published_articles_and_has_all_articles_button()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Lihat Semua Artikel');
        $response->assertSee(route('articles.index'));

        $homepageArticles = $response->viewData('homepageArticles');
        $this->assertNotNull($homepageArticles);
        $this->assertLessThanOrEqual(3, count($homepageArticles));
    }

    public function test_wawasan_index_page_displays_all_published_articles_with_pagination()
    {
        $response = $this->get('/wawasan');
        $response->assertStatus(200);
        $response->assertSee('Wawasan & Inovasi AI Solution');
        $response->assertViewHas('articles');

        $articles = $response->viewData('articles');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $articles);

        // Pastikan setiap artikel memiliki tautan ke detailnya
        foreach ($articles as $article) {
            $response->assertSee(route('article.detail', ['slug' => $article->slug]));
        }
    }

    public function test_newly_published_article_from_admin_automatically_appears_in_wawasan_and_homepage()
    {
        $uniqueSlug = 'artikel-integrasi-ai-terbaru-' . time();
        $payload = [
            'judul'        => 'Artikel Integrasi AI Terbaru Otomatis',
            'slug'         => $uniqueSlug,
            'kategori'     => 'Teknologi Masa Depan',
            'image_type'   => 'url',
            'gambar_url'   => 'https://images.unsplash.com/photo-1518770660439-4636190af475',
            'ringkasan'    => 'Ringkasan artikel integrasi otomatis dari admin ke wawasan dan beranda.',
            'isi_artikel'  => json_encode(['content' => '<p>Konten artikel otomatis</p>', 'takeaway' => 'Otomatisasi sukses', 'readTime' => '2 mnt']),
            'status'       => 'published',
            'is_active'    => '1',
        ];

        // 1. Simpan artikel melalui form admin
        $postResponse = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $postResponse->assertRedirect(route('admin.articles.index'));

        // 2. Cek apakah langsung muncul di halaman daftar /wawasan
        $wawasanResponse = $this->get('/wawasan');
        $wawasanResponse->assertStatus(200);
        $wawasanResponse->assertSee('Artikel Integrasi AI Terbaru Otomatis');
        $wawasanResponse->assertSee(route('article.detail', ['slug' => $uniqueSlug]));

        // 3. Cek apakah langsung muncul di halaman utama (karena merupakan artikel terbaru teratas)
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Artikel Integrasi AI Terbaru Otomatis');
        $homeResponse->assertSee(route('article.detail', ['slug' => $uniqueSlug]));

        // 4. Cek apakah halaman detailnya dapat dibuka dengan status 200
        $detailResponse = $this->get('/wawasan/' . $uniqueSlug);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Artikel Integrasi AI Terbaru Otomatis');
    }

    public function test_homepage_search_modal_indexes_articles_and_services()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('id="search-modal"', false);
        $response->assertSee('id="search-input"', false);
        $response->assertSee('Computer Vision', false);
        $response->assertSee('NLP', false);
        $response->assertSee('Semua Artikel', false);
        $response->assertSee('Cari artikel, layanan', false);
    }

    public function test_scheduled_status_requires_published_at_validation()
    {
        $payload = [
            'judul'        => 'Artikel Jadwal Tanpa Tanggal',
            'slug'         => 'artikel-jadwal-tanpa-tanggal',
            'status'       => 'scheduled',
            'published_at' => '',
            'is_active'    => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), $payload);
        $response->assertSessionHasErrors(['published_at']);
    }

    public function test_admin_can_update_existing_article_to_scheduled()
    {
        $article = Article::create([
            'judul'     => 'Artikel Draft Awal',
            'slug'      => 'artikel-draft-awal',
            'status'    => 'draft',
            'is_active' => true,
        ]);

        $futureDate = now()->addDays(2)->format('Y-m-d\TH:i');

        $updatePayload = [
            'judul'        => 'Artikel Draft Awal (Dijadwalkan)',
            'slug'         => 'artikel-draft-awal',
            'status'       => 'scheduled',
            'published_at' => $futureDate,
            'is_active'    => '1',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.articles.update', $article->id), $updatePayload);
        $response->assertRedirect(route('admin.articles.index'));

        $article->refresh();
        $this->assertEquals('scheduled', $article->status);
        $this->assertNotNull($article->published_at);
        $this->assertNotNull($article->tanggal);
    }

    public function test_article_calendar_tab_and_route_render_properly()
    {
        // 1. Akses halaman index artikel biasa
        $indexResponse = $this->actingAs($this->admin)->get(route('admin.articles.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Daftar Artikel');
        $indexResponse->assertSee('Kalender Penjadwalan');
        $indexResponse->assertViewHas('calendarArticles');
        $indexResponse->assertViewHas('activeTab', 'table');

        // 2. Akses halaman kalender langsung via rute admin.articles.calendar
        $calendarResponse = $this->actingAs($this->admin)->get(route('admin.articles.calendar'));
        $calendarResponse->assertStatus(200);
        $calendarResponse->assertSee('Kalender Penjadwalan');
        $calendarResponse->assertViewHas('calendarArticles');
        $calendarResponse->assertViewHas('activeTab', 'calendar');

        // 3. Akses via query param tab=calendar
        $tabResponse = $this->actingAs($this->admin)->get(route('admin.articles.index', ['tab' => 'calendar']));
        $tabResponse->assertStatus(200);
        $tabResponse->assertViewHas('activeTab', 'calendar');
    }

    public function test_article_calendar_formats_multiple_articles_with_correct_statuses_and_dates()
    {
        $targetDate = '2026-11-15';

        // Buat 3 artikel pada tanggal yang sama dengan 3 status berbeda: scheduled, published, draft
        $scheduled = Article::create([
            'judul'        => 'Artikel Jadwal Target',
            'slug'         => 'artikel-jadwal-target',
            'status'       => 'scheduled',
            'published_at' => Carbon::parse($targetDate . ' 10:30:00', 'Asia/Jakarta'),
            'is_active'    => true,
        ]);

        $published = Article::create([
            'judul'        => 'Artikel Published Target',
            'slug'         => 'artikel-published-target',
            'status'       => 'published',
            'published_at' => Carbon::parse($targetDate . ' 14:00:00', 'Asia/Jakarta'),
            'is_active'    => true,
        ]);

        $draft = Article::create([
            'judul'        => 'Artikel Draft Target',
            'slug'         => 'artikel-draft-target',
            'status'       => 'draft',
            'published_at' => Carbon::parse($targetDate . ' 16:00:00', 'Asia/Jakarta'),
            'is_active'    => false,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.articles.calendar'));
        $response->assertStatus(200);

        $calendarArticles = $response->viewData('calendarArticles');
        $this->assertNotEmpty($calendarArticles);

        // Filter artikel yang jatuh pada tanggal target
        $targetArticles = $calendarArticles->where('date', $targetDate);
        $this->assertCount(3, $targetArticles);

        // Periksa status masing-masing
        $statuses = $targetArticles->pluck('status')->all();
        $this->assertContains('scheduled', $statuses);
        $this->assertContains('published', $statuses);
        $this->assertContains('draft', $statuses);
    }

    public function test_create_article_page_presets_scheduled_date_when_date_query_param_is_passed()
    {
        $presetDate = '2026-12-25';
        $response = $this->actingAs($this->admin)->get(route('admin.articles.create', ['date' => $presetDate]));
        $response->assertStatus(200);

        // Harus memilih radio scheduled dan mengisi input published_at
        $response->assertSee('value="scheduled"', false);
        $response->assertSee('checked', false);
        $response->assertSee('2026-12-25T09:00', false);
    }
}



