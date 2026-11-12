<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\User;
use Tests\TestCase;

class PartnerModuleTest extends TestCase
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

    public function test_partner_data_preserved_and_displays_on_homepage(): void
    {
        $partners = Partner::where('is_active', true)->get();
        $this->assertGreaterThan(0, $partners->count(), 'Data partner tidak boleh hilang.');

        $response = $this->get('/');
        $response->assertStatus(200);

        foreach ($partners as $partner) {
            $response->assertSee($partner->nama, false);
        }
    }

    public function test_admin_can_access_partner_management(): void
    {
        $admin = $this->getAdminUser();

        $this->actingAs($admin)->get(route('admin.partners.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.partners.create'))->assertStatus(200);

        $partner = Partner::first();
        if ($partner) {
            $this->actingAs($admin)->get(route('admin.partners.edit', $partner->id))->assertStatus(200);
        }
    }

    public function test_partner_added_without_urutan_automatically_placed_at_the_end_and_preserves_order(): void
    {
        $admin = $this->getAdminUser();

        // Ambil urutan dan nama partner lama sebelum penambahan
        $existingPartnersBefore = Partner::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->pluck('nama')
            ->toArray();

        $maxUrutanBefore = (int) (Partner::max('urutan') ?? 0);

        $testName = 'Test Partner Auto Akhir ' . uniqid();

        try {
            // 1. Simpan partner baru dengan kolom urutan dikosongkan ('')
            $response = $this->actingAs($admin)->post(route('admin.partners.store'), [
                'nama'      => $testName,
                'urutan'    => '',
                'logo_url'  => 'https://example.com/auto-akhir.png',
                'is_active' => '1',
            ]);

            $response->assertRedirect(route('admin.partners.index'));
            $response->assertSessionHas('success');

            // 2. Verifikasi partner tersimpan dengan urutan max + 1
            $newPartner = Partner::where('nama', $testName)->first();
            $this->assertNotNull($newPartner, 'Partner baru harus tersimpan di database.');
            $this->assertSame($maxUrutanBefore + 1, (int) $newPartner->urutan, 'Partner baru tanpa urutan harus mendapat urutan paling akhir.');

            // 3. Verifikasi urutan partner di halaman beranda
            $homeResponse1 = $this->get('/');
            $homeResponse1->assertStatus(200);
            $homeResponse1->assertSee($testName, false);

            // Verifikasi bahwa posisi partner lama tidak berubah mendahului partner lama
            $partnersAfter = Partner::where('is_active', true)
                ->orderBy('urutan', 'asc')
                ->orderBy('id', 'asc')
                ->pluck('nama')
                ->toArray();

            // Partner baru harus berada di posisi paling akhir dalam daftar
            $this->assertSame($testName, end($partnersAfter), 'Partner baru harus berada di urutan paling akhir.');

            // Seluruh partner lama tetap mempertahankan urutan relatifnya
            array_pop($partnersAfter);
            $this->assertSame($existingPartnersBefore, $partnersAfter, 'Urutan partner lama tidak boleh berubah ketika partner baru ditambahkan.');

            // 4. Verifikasi konsistensi urutan setelah reload beranda
            $homeResponse2 = $this->get('/');
            $homeResponse2->assertStatus(200);

            $partnersNames1 = $homeResponse1->viewData('partners')->pluck('nama')->toArray();
            $partnersNames2 = $homeResponse2->viewData('partners')->pluck('nama')->toArray();
            $this->assertSame($partnersNames1, $partnersNames2, 'Urutan tampil partner harus konsisten setelah halaman dimuat ulang.');
            $this->assertSame($testName, end($partnersNames2), 'Partner baru harus tetap berada di posisi akhir setelah reload.');

        } finally {
            // Bersihkan data tes agar data partner lama tidak berubah
            Partner::where('nama', $testName)->delete();
        }
    }

    public function test_partner_added_with_explicit_urutan_uses_specified_order(): void
    {
        $admin = $this->getAdminUser();
        $testName = 'Test Partner Urutan Diisi ' . uniqid();

        try {
            // Simpan partner dengan nilai urutan ditentukan secara eksplisit
            $response = $this->actingAs($admin)->post(route('admin.partners.store'), [
                'nama'      => $testName,
                'urutan'    => 55,
                'logo_url'  => 'https://example.com/urutan-55.png',
                'is_active' => '1',
            ]);

            $response->assertRedirect(route('admin.partners.index'));
            $response->assertSessionHas('success');

            $partner = Partner::where('nama', $testName)->first();
            $this->assertNotNull($partner);
            $this->assertSame(55, (int) $partner->urutan, 'Urutan partner harus sesuai dengan yang diisi admin.');

        } finally {
            Partner::where('nama', $testName)->delete();
        }
    }

    public function test_partner_model_event_defaults_urutan_to_last_when_null_or_empty(): void
    {
        $maxBefore = (int) (Partner::max('urutan') ?? 0);
        $testName = 'Test Model Event ' . uniqid();

        try {
            $partner = Partner::create([
                'nama'      => $testName,
                'urutan'    => null,
                'logo'      => 'https://example.com/model-event.png',
                'is_active' => true,
            ]);

            $this->assertSame($maxBefore + 1, (int) $partner->urutan);
        } finally {
            Partner::where('nama', $testName)->delete();
        }
    }
}

