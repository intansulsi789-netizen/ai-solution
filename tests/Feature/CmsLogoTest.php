<?php

namespace Tests\Feature;

use App\Models\CmsHomepage;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CmsLogoTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('role', 'admin_website')->first()
            ?? User::first()
            ?? User::factory()->create(['role' => 'admin_website']);
    }

    /**
     * Pastikan halaman pengaturan logo CMS dapat dibuka tanpa error (200 OK).
     */
    public function test_admin_can_view_cms_logo_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.cms.logo.index'));

        $response->assertStatus(200);
        $response->assertSee('Identitas & Logo Website');
        $response->assertSee('Pilihan Gaya Logo');
    }

    /**
     * Pastikan penyimpanan logo mode default dan update nama brand berhasil (tidak ada SQL 500).
     */
    public function test_admin_can_update_logo_to_default_and_brand_names(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.cms.logo.update'), [
            'site_logo_type'      => 'default',
            'site_name_highlight' => 'AI Cerdas',
            'site_name_rest'      => 'Jogja Solusindo',
        ]);

        $response->assertRedirect(route('admin.cms.logo.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cms_homepages', [
            'site_logo_type'      => 'default',
            'site_logo'           => null,
            'site_name_highlight' => 'AI Cerdas',
            'site_name_rest'      => 'Jogja Solusindo',
        ]);
    }

    /**
     * Pastikan penyimpanan logo kustom berbasis link URL berhasil.
     */
    public function test_admin_can_update_logo_with_url(): void
    {
        $logoUrl = 'https://example.com/assets/logo-test.png';

        $response = $this->actingAs($this->admin)->put(route('admin.cms.logo.update'), [
            'site_logo_type'      => 'custom',
            'site_logo_url'       => $logoUrl,
            'site_name_highlight' => 'AI',
            'site_name_rest'      => 'Solutions Tech',
        ]);

        $response->assertRedirect(route('admin.cms.logo.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cms_homepages', [
            'site_logo_type'      => 'custom',
            'site_logo'           => $logoUrl,
            'site_name_highlight' => 'AI',
            'site_name_rest'      => 'Solutions Tech',
        ]);
    }

    /**
     * Pastikan upload file logo kustom berhasil disimpan ke storage.
     */
    public function test_admin_can_upload_custom_logo_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('custom_logo.png', 200, 200);

        $response = $this->actingAs($this->admin)->put(route('admin.cms.logo.update'), [
            'site_logo_type'      => 'custom',
            'site_logo_file'      => $file,
            'site_name_highlight' => 'AI',
            'site_name_rest'      => 'Jogja Solutions',
        ]);

        $response->assertRedirect(route('admin.cms.logo.index'));
        $response->assertSessionHas('success');

        $cms = CmsHomepage::first();
        $this->assertNotNull($cms->site_logo);
        $this->assertEquals('custom', $cms->site_logo_type);
        Storage::disk('public')->assertExists($cms->site_logo);
    }

    /**
     * Pastikan tampilan logo dan brand dapat diakses di beranda.
     */
    public function test_logo_is_displayed_on_homepage(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('AI');
    }
}
