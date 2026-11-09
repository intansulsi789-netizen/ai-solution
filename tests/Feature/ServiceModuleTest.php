<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServiceModuleTest extends TestCase
{
    protected function getAdminUser(): User
    {
        $user = User::where('role', 'admin_website')->first();
        if (!$user) {
            $user = User::where('email', 'admin@ai-solution.com')->first();
        }
        if (!$user) {
            $user = User::factory()->create([
                'role' => 'admin_website',
                'email' => 'admin@ai-solution.com',
            ]);
        }
        return $user;
    }

    public function test_existing_16_services_display_icons_properly_on_homepage(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $services = Service::where('is_active', true)->get();
        $this->assertGreaterThanOrEqual(16, $services->count());

        foreach ($services as $service) {
            $response->assertSee($service->nama_layanan);
            if (!empty($service->icon)) {
                // Pastikan path SVG muncul pada kartu layanan
                $response->assertSee($service->icon, false);
            }
        }
    }

    public function test_admin_can_view_create_service_page_with_icons_list(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.services.create'));
        $response->assertStatus(200);
        $response->assertSee('Pilihan Ikon Layanan AI');
        $response->assertSee('AI Chatbot / Asisten');
        $response->assertSee('Knowledge Base / RAG');
        $response->assertSee('Gambar Layanan (Opsional)');
    }

    public function test_admin_can_create_service_with_icon_and_it_renders_on_frontend(): void
    {
        $admin = $this->getAdminUser();

        $slug = 'layanan-test-ikon-' . time();
        $iconPath = 'M12 2L2 7l10 5 10-5-10-5z M2 17l10 5 10-5 M2 12l10 5 10-5';

        $postData = [
            'nama_layanan'      => 'Test Layanan Ikon',
            'slug'              => $slug,
            'icon'              => $iconPath,
            'deskripsi_singkat' => 'Deskripsi singkat layanan uji coba ikon.',
            'is_active'         => '1',
        ];

        $response = $this->actingAs($admin)->post(route('admin.services.store'), $postData);
        $response->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseHas('services', [
            'slug' => $slug,
            'icon' => $iconPath,
        ]);

        // Verifikasi di homepage
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Test Layanan Ikon');
        $homeResponse->assertSee($iconPath, false);

        // Verifikasi di detail page
        $detailResponse = $this->get('/layanan/' . $slug);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Test Layanan Ikon');
        $detailResponse->assertSee($iconPath, false);

        // Cleanup
        Service::where('slug', $slug)->delete();
    }

    public function test_admin_can_create_service_with_uploaded_image_and_it_renders_on_card(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        $slug = 'layanan-test-gambar-' . time();
        $fakeImage = UploadedFile::fake()->image('test-service.jpg', 800, 600);

        $postData = [
            'nama_layanan'      => 'Test Layanan Gambar',
            'slug'              => $slug,
            'icon'              => 'M22 12h-4l-3 9L9 3l-3 9H2',
            'image'             => $fakeImage,
            'deskripsi_singkat' => 'Layanan dengan custom gambar terunggah.',
            'is_active'         => '1',
        ];

        $response = $this->actingAs($admin)->post(route('admin.services.store'), $postData);
        $response->assertRedirect(route('admin.services.index'));

        $service = Service::where('slug', $slug)->first();
        $this->assertNotNull($service);
        $this->assertNotEmpty($service->image);
        Storage::disk('public')->assertExists($service->image);

        // Homepage harus menampilkan tag img untuk gambar yang diunggah
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee($service->image_url, false);
        $homeResponse->assertSee('card-icon-img', false);

        // Detail page harus menampilkan gambar pada .detail-image-wrap
        $detailResponse = $this->get('/layanan/' . $slug);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee($service->image_url, false);

        // Cleanup
        $service->delete();
    }

    public function test_updating_service_without_new_image_preserves_existing_image(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        $fakeImage = UploadedFile::fake()->image('preserved.jpg', 600, 400);
        $storedPath = $fakeImage->store('services', 'public');

        $service = Service::create([
            'nama_layanan'      => 'Service Pertahankan Gambar',
            'slug'              => 'service-pertahankan-gambar-' . time(),
            'image'             => $storedPath,
            'icon'              => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
            'deskripsi_singkat' => 'Testing image preservation',
            'is_active'         => true,
            'nomor'             => 99,
        ]);

        // Update tanpa mengunggah file gambar baru
        $updateData = [
            'nama_layanan'      => 'Service Pertahankan Gambar Updated',
            'slug'              => $service->slug,
            'icon'              => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
            'deskripsi_singkat' => 'Updated deskripsi',
        ];

        $response = $this->actingAs($admin)->put(route('admin.services.update', $service->id), $updateData);
        $response->assertRedirect(route('admin.services.index'));

        $fresh = $service->fresh();
        $this->assertEquals($storedPath, $fresh->image, 'Gambar lama tidak boleh hilang saat update tanpa file baru!');
        Storage::disk('public')->assertExists($storedPath);

        // Cleanup
        $fresh->delete();
    }

    public function test_detail_page_falls_back_gracefully_when_no_image_or_icon(): void
    {
        $service = Service::create([
            'nama_layanan'      => 'Service Tanpa Ikon dan Gambar ' . time(),
            'slug'              => 'service-fallback-test-' . time(),
            'image'             => null,
            'icon'              => '',
            'deskripsi_singkat' => 'Deskripsi testing fallback',
            'is_active'         => true,
            'nomor'             => 100,
        ]);

        $response = $this->get('/layanan/' . $service->slug);
        $response->assertStatus(200);
        // Halaman detail harus tetap render gambar fallback hero tanpa error 500
        $response->assertSee('ai-hero-bg.jpg', false);

        $service->delete();
    }

    public function test_database_preserves_16_original_services_and_fallback_images(): void
    {
        $expectedSlugs = [
            'ai-chatbot',
            'ai-knowledge-base',
            'ai-agent',
            'ai-automation',
            'generative-ai',
            'ai-voice-audio',
            'computer-vision',
            'predictive-ai',
            'recommendation-ai',
            'ai-analytics',
            'ai-marketing-sales',
            'ai-customer-service',
            'ai-pendidikan',
            'ai-coding',
            'vertical-ai',
            'ai-saas',
        ];

        foreach ($expectedSlugs as $slug) {
            $service = Service::where('slug', $slug)->first();
            $this->assertNotNull($service, "Layanan lama dengan slug '{$slug}' harus tetap ada.");
            $this->assertNotEmpty($service->icon, "Ikon layanan '{$slug}' tidak boleh kosong.");
            $this->assertFileExists(public_path('images/services/' . $slug . '.jpg'), "File gambar bawaan untuk '{$slug}' harus tetap ada.");
            $this->assertEquals(asset('images/services/' . $slug . '.jpg'), $service->image_url);
        }
    }

    public function test_articles_and_scheduler_are_intact(): void
    {
        $totalArticles = \App\Models\Article::count();
        $this->assertGreaterThan(0, $totalArticles, 'Artikel tidak boleh hilang.');

        // Status breakdown
        $published = \App\Models\Article::where('status', 'published')->get();
        foreach ($published as $p) {
            $this->assertNotNull($p->published_at, "Artikel published #{$p->id} harus memiliki published_at.");
        }

        // Jalankan artisan command scheduled publisher
        $this->artisan('articles:publish-scheduled')->assertExitCode(0);
        $this->assertEquals('Asia/Jakarta', config('app.timezone'));
    }

    public function test_public_routes_remain_functional(): void
    {
        // Homepage
        $this->get('/')->assertStatus(200);

        // Wawasan / Artikel index
        $this->get('/wawasan')->assertStatus(200);

        // First published article detail
        $firstArticle = \App\Models\Article::published()->first();
        if ($firstArticle) {
            $this->get(route('article.detail', ['slug' => $firstArticle->slug]))->assertStatus(200);
        }

        // First active service detail
        $firstService = Service::where('is_active', true)->first();
        if ($firstService) {
            $this->get('/layanan/' . $firstService->slug)->assertStatus(200);
        }
    }
}
