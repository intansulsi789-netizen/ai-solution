<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Services\ArticleImageService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleBannerImageTest extends TestCase
{
    use DatabaseTransactions;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::first() ?? User::factory()->create();
    }

    /**
     * 1. Pengujian URL Gambar Langsung
     */
    public function test_resolves_direct_image_url_successfully()
    {
        Http::fake([
            'https://example.com/test-banner.jpg' => Http::response(
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='),
                200,
                ['Content-Type' => 'image/png']
            ),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('admin.articles.resolveBannerUrl'), [
                'url' => 'https://example.com/test-banner.jpg',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'image_url' => 'https://example.com/test-banner.jpg',
                'source_type' => 'direct',
            ]);
    }

    /**
     * 2. Pengujian URL Google Images (ekstraksi imgurl)
     */
    public function test_resolves_and_extracts_google_images_url()
    {
        $directTarget = 'https://images.unsplash.com/photo-1518770660439-4636190af475.jpg';
        $googleUrl = 'https://www.google.com/imgres?imgurl=' . urlencode($directTarget) . '&imgrefurl=https%3A%2F%2Fexample.com&docid=abc123xyz';

        Http::fake([
            $directTarget => Http::response(
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='),
                200,
                ['Content-Type' => 'image/png']
            ),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('admin.articles.resolveBannerUrl'), [
                'url' => $googleUrl,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'image_url' => $directTarget,
                'source_type' => 'google_images',
            ]);
    }

    /**
     * 3. Pengujian URL Pinterest (oEmbed & thumbnail resolution)
     */
    public function test_resolves_pinterest_url_via_oembed()
    {
        $pinterestPinUrl = 'https://www.pinterest.com/pin/123456789012345678/';
        $highResThumbnail = 'https://i.pinimg.com/736x/ab/cd/ef/abcdef123456.jpg';

        Http::fake([
            'https://www.pinterest.com/oembed.json*' => Http::response([
                'thumbnail_url' => 'https://i.pinimg.com/150x150/ab/cd/ef/abcdef123456.jpg',
                'title' => 'Pinterest Art Concept',
                'author_name' => 'Designer',
            ], 200),
            $highResThumbnail => Http::response(
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='),
                200,
                ['Content-Type' => 'image/jpeg']
            ),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('admin.articles.resolveBannerUrl'), [
                'url' => $pinterestPinUrl,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'image_url' => $highResThumbnail,
                'source_type' => 'pinterest',
            ]);
    }

    /**
     * 4. Pengujian Keamanan & SSRF Prevention
     */
    public function test_blocks_ssrf_internal_ips_and_localhost()
    {
        $dangerousUrls = [
            'http://localhost/admin/secret',
            'http://127.0.0.1/admin',
            'http://127.0.0.1:8000/phpmyadmin',
            'http://169.254.169.254/latest/meta-data',
            'http://192.168.1.1/router-login',
            'http://10.0.0.1/internal-dashboard',
            'javascript:alert(1)',
            'ftp://example.com/banner.jpg',
        ];

        foreach ($dangerousUrls as $url) {
            $response = $this->actingAs($this->adminUser)
                ->postJson(route('admin.articles.resolveBannerUrl'), [
                    'url' => $url,
                ]);

            $response->assertStatus(422)
                ->assertJson(['success' => false]);
            
            $msg = strtolower($response->json('message') ?? '');
            $this->assertTrue(
                str_contains($msg, 'ditolak') || str_contains($msg, 'diizinkan') || str_contains($msg, 'tidak valid') || str_contains($msg, 'ssrf'),
                "Pesan penolakan SSRF harus informatif. Diterima: " . $msg
            );
        }
    }

    /**
     * 5. Pengujian URL Tidak Valid & Gambar yang Gagal Dimuat
     */
    public function test_handles_non_existent_or_failed_image_urls()
    {
        // 404 Not Found
        Http::fake([
            'https://example.com/not-found-image.jpg' => Http::response('Not Found', 404),
            'https://example.com/page-without-image' => Http::response('<html><head><title>No Image</title></head><body>Hello</body></html>', 200, ['Content-Type' => 'text/html']),
        ]);

        // Uji URL gambar 404
        $res404 = $this->actingAs($this->adminUser)
            ->postJson(route('admin.articles.resolveBannerUrl'), [
                'url' => 'https://example.com/not-found-image.jpg',
            ]);

        $res404->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        // Uji halaman web tanpa tag OpenGraph image
        $resNoOg = $this->actingAs($this->adminUser)
            ->postJson(route('admin.articles.resolveBannerUrl'), [
                'url' => 'https://example.com/page-without-image',
            ]);

        $resNoOg->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * 6. Pengujian Controller: Simpan Artikel dengan URL & Opsi Simpan ke Server Lokal
     */
    public function test_store_article_with_resolved_url_and_download_to_server()
    {
        Storage::fake('public');

        $imageUrl = 'https://example.com/test-article-image.jpg';
        $dummyImageBinary = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');

        Http::fake([
            $imageUrl => Http::response($dummyImageBinary, 200, ['Content-Type' => 'image/png']),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.articles.store'), [
                'judul' => 'Uji Coba Banner URL Otomatis',
                'slug' => 'uji-coba-banner-url-otomatis-' . uniqid(),
                'kategori' => 'Teknologi',
                'penulis' => 'AI Engineer',
                'ringkasan' => 'Ringkasan uji coba penyimpanan banner artikel dari URL.',
                'isi_artikel' => json_encode(['content' => '<p>Konten artikel</p>', 'takeaway' => 'Takeaway', 'readTime' => '2 mnt']),
                'status' => 'draft',
                'image_type' => 'url',
                'gambar_url' => $imageUrl,
                'simpan_ke_server' => '1',
            ]);

        $response->assertRedirect(route('admin.articles.index'));
        $response->assertSessionHas('success');

        // Pastikan artikel tersimpan di database dengan path gambar di storage lokal (articles/banner_...)
        $created = Article::where('judul', 'Uji Coba Banner URL Otomatis')->first();
        $this->assertNotNull($created);
        $this->assertStringStartsWith('articles/banner_', $created->gambar);

        // Pastikan file gambar benar-benar ada di storage fake
        Storage::disk('public')->assertExists($created->gambar);
    }

    /**
     * 7. Pengujian Controller: Gagal jika URL gambar yang dikirim tidak dapat diakses
     */
    public function test_store_article_fails_when_given_invalid_url()
    {
        Http::fake([
            'https://example.com/broken-link.jpg' => Http::response('Broken', 500),
        ]);

        $response = $this->actingAs($this->adminUser)
            ->from(route('admin.articles.create'))
            ->post(route('admin.articles.store'), [
                'judul' => 'Artikel Link Rusak',
                'slug' => 'artikel-link-rusak-' . uniqid(),
                'kategori' => 'Teknologi',
                'penulis' => 'AI Engineer',
                'ringkasan' => 'Ringkasan uji coba.',
                'isi_artikel' => json_encode(['content' => '<p>Konten</p>']),
                'status' => 'draft',
                'image_type' => 'url',
                'gambar_url' => 'https://example.com/broken-link.jpg',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['gambar_url']);
    }

    /**
     * 8. Pengujian Pertahankan Upload dari Komputer
     */
    public function test_upload_from_computer_remains_fully_functional()
    {
        Storage::fake('public');

        $uploadedFile = UploadedFile::fake()->image('komputer-banner.jpg', 800, 600);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.articles.store'), [
                'judul' => 'Uji Banner Upload Komputer',
                'slug' => 'uji-banner-upload-komputer-' . uniqid(),
                'kategori' => 'Bisnis',
                'penulis' => 'Admin',
                'ringkasan' => 'Uji upload file manual dari komputer.',
                'isi_artikel' => json_encode(['content' => '<p>Konten</p>']),
                'status' => 'draft',
                'image_type' => 'upload',
                'gambar' => $uploadedFile,
            ]);

        $response->assertRedirect(route('admin.articles.index'));
        $response->assertSessionHas('success');

        $created = Article::where('judul', 'Uji Banner Upload Komputer')->first();
        $this->assertNotNull($created);
        Storage::disk('public')->assertExists($created->gambar);
    }
}
