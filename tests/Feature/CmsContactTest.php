<?php

namespace Tests\Feature;

use App\Models\CmsContact;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CmsContactTest extends TestCase
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
     * Buka Admin -> Kelola Konten -> Kontak.
     */
    public function test_admin_can_view_cms_contact_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.cms.contact.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola CTA / Kontak', false);
        $response->assertSee('Informasi Kontak & Sosial Media', false);
    }

    /**
     * Uji format input email yang tidak valid menghasilkan pesan bahasa Indonesia.
     */
    public function test_invalid_email_format_shows_indonesian_error_message(): void
    {
        $response = $this->actingAs($this->admin)->from(route('admin.cms.contact.index'))->put(route('admin.cms.contact.update'), [
            'email' => 'bukan-email-valid',
        ]);

        $response->assertRedirect(route('admin.cms.contact.index'));
        $response->assertSessionHasErrors(['email']);

        $errors = session('errors')->get('email');
        $this->assertContains('Format email tidak valid.', $errors);
    }

    /**
     * Uji validasi panjang karakter melebihi batas (max 255) menghasilkan pesan bahasa Indonesia.
     */
    public function test_max_character_exceeded_shows_indonesian_error_message(): void
    {
        $longText = str_repeat('a', 256);

        $response = $this->actingAs($this->admin)->from(route('admin.cms.contact.index'))->put(route('admin.cms.contact.update'), [
            'judul'       => $longText,
            'whatsapp'    => $longText,
            'teks_tombol' => $longText,
        ]);

        $response->assertRedirect(route('admin.cms.contact.index'));
        $response->assertSessionHasErrors(['judul', 'whatsapp', 'teks_tombol']);

        $errorsJudul = session('errors')->get('judul');
        $this->assertContains('Judul CTA tidak boleh lebih dari 255 karakter.', $errorsJudul);

        $errorsWa = session('errors')->get('whatsapp');
        $this->assertContains('Nomor WhatsApp tidak boleh lebih dari 255 karakter.', $errorsWa);

        $errorsBtn = session('errors')->get('teks_tombol');
        $this->assertContains('Teks tombol CTA tidak boleh lebih dari 255 karakter.', $errorsBtn);
    }

    /**
     * Pastikan seluruh pesan kustom validasi kontak diterjemahkan ke bahasa Indonesia dengan benar.
     */
    public function test_contact_validation_messages_are_in_indonesian(): void
    {
        $validator = \Illuminate\Support\Facades\Validator::make(
            [
                'email'       => 'bukan-email',
                'judul'       => str_repeat('a', 256),
                'whatsapp'    => str_repeat('1', 256),
                'teks_tombol' => str_repeat('b', 256),
            ],
            [
                'email'       => 'required|email|max:255',
                'judul'       => 'required|string|max:255',
                'whatsapp'    => 'required|string|max:255',
                'teks_tombol' => 'required|string|max:255',
            ],
            (new \ReflectionMethod(\App\Http\Controllers\Admin\AdminCmsContactController::class, 'update'))
                ->getClosure(new \App\Http\Controllers\Admin\AdminCmsContactController()) ? [] : []
        );

        // Uji response langsung melalui HTTP PUT untuk memastikan pipeline controller menggunakan pesan Indonesia
        $response = $this->actingAs($this->admin)
            ->from(route('admin.cms.contact.index'))
            ->put(route('admin.cms.contact.update'), [
                'email' => 'format-salah',
                'judul' => str_repeat('x', 300),
            ]);

        $response->assertSessionHasErrors(['email', 'judul']);
        $errors = session('errors');

        $this->assertEquals('Format email tidak valid.', $errors->first('email'));
        $this->assertEquals('Judul CTA tidak boleh lebih dari 255 karakter.', $errors->first('judul'));
    }

    /**
     * Pastikan penyimpanan data kontak yang valid tetap berfungsi normal.
     */
    public function test_admin_can_update_cms_contact_successfully(): void
    {
        $payload = [
            'judul'       => 'Konsultasikan Kebutuhan AI Anda',
            'deskripsi'   => 'Deskripsi pengujian pembaruan kontak CMS.',
            'teks_tombol' => 'Hubungi Kami Sekarang',
            'link_tombol' => '#konsultasi',
            'whatsapp'    => '6281234567890',
            'email'       => 'kontak@ai-solution.com',
            'alamat'      => 'Jl. Kaliurang KM 5, Yogyakarta',
            'instagram'   => 'https://instagram.com/jogjaaisolutions',
            'linkedin'    => 'https://linkedin.com/company/jogjaaisolutions',
            'youtube'     => 'https://youtube.com/@jogjaaisolutions',
            'tiktok'      => 'https://tiktok.com/@jogjaaisolutions',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.cms.contact.update'), $payload);

        $response->assertRedirect(route('admin.cms.contact.index'));
        $response->assertSessionHas('success', 'Konten CTA / Kontak berhasil diperbarui.');

        $this->assertDatabaseHas('cms_contacts', [
            'judul'       => 'Konsultasikan Kebutuhan AI Anda',
            'whatsapp'    => '6281234567890',
            'email'       => 'kontak@ai-solution.com',
            'alamat'      => 'Jl. Kaliurang KM 5, Yogyakarta',
        ]);
    }
}
