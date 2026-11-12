<?php

namespace Tests\Feature;

use App\Models\Consultation;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicConsultationFormTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        try {
            $limiter = new \Illuminate\Cache\RateLimiter(\Illuminate\Support\Facades\Cache::store('file'));
            $limiter->clear('konsultasi:127.0.0.1');
            $limiter->clear('konsultasi:unknown');
        } catch (\Throwable $e) {}
    }

    /**
     * Test successful consultation submission.
     */
    public function test_consultation_form_submits_successfully_and_saves_to_database()
    {
        $payload = [
            'nama'          => 'Ahmad Test',
            'email'         => 'ahmad.test@example.com',
            'whatsapp'      => '081234567890',
            'kebutuhan'     => 'Saya butuh solusi chatbot AI untuk customer service.',
            '_submit_token' => (string) Str::uuid(),
        ];

        $response = $this->post(route('konsultasi.store'), $payload);

        $response->assertRedirect('/#konsultasi');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('consultations', [
            'nama'      => 'Ahmad Test',
            'email'     => 'ahmad.test@example.com',
            'whatsapp'  => '081234567890',
            'kebutuhan' => 'Saya butuh solusi chatbot AI untuk customer service.',
            'is_read'   => false,
        ]);
    }

    /**
     * Test modal contains hidden _submit_token input.
     */
    public function test_welcome_page_contains_submit_token_and_anti_duplicate_script()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('name="_submit_token"', $content);
        $this->assertStringContainsString('formKonsultasi.addEventListener(\'submit\'', $content);
        $this->assertStringContainsString('btn.disabled = true', $content);
    }

    /**
     * Test that "Kami akan segera menghubungi Anda." appears exactly ONCE in success modal.
     */
    public function test_success_message_does_not_duplicate_kalimat_kami_akan_segera_menghubungi_anda()
    {
        $payload = [
            'nama'          => 'Budi Test',
            'email'         => 'budi.test@example.com',
            'whatsapp'      => '081298765432',
            'kebutuhan'     => 'Konsultasi integrasi sistem rekomendasi AI.',
            '_submit_token' => (string) Str::uuid(),
        ];

        $submitResponse = $this->post(route('konsultasi.store'), $payload);
        $submitResponse->assertRedirect('/#konsultasi');

        // Test rendering with session
        $homeResponse = $this->withSession([
            'success' => session('success', 'Pesan konsultasi Anda berhasil dikirim.')
        ])->get('/');

        $content = $homeResponse->getContent();

        $phrase = 'Kami akan segera menghubungi Anda.';
        $count = substr_count($content, $phrase);

        $this->assertEquals(1, $count, "Frasa '{$phrase}' harus muncul tepat 1 kali, tetapi ditemukan {$count} kali.");
        $this->assertStringContainsString('Pesan konsultasi Anda berhasil dikirim.', $content);
    }

    /**
     * Test safeguard when session('success') already contains the phrase.
     */
    public function test_safeguard_prevents_duplication_even_if_session_already_contains_phrase()
    {
        $homeResponse = $this->withSession([
            'success' => 'Pesan konsultasi Anda berhasil dikirim. Kami akan segera menghubungi Anda.'
        ])->get('/');

        $content = $homeResponse->getContent();

        $phrase = 'Kami akan segera menghubungi Anda.';
        $count = substr_count($content, $phrase);

        $this->assertEquals(1, $count, "Frasa '{$phrase}' harus muncul tepat 1 kali bahkan jika session sudah memuatnya.");
    }

    /**
     * Test duplicate submit protection prevents saving multiple records when token is the same.
     */
    public function test_duplicate_submit_protection_saves_only_one_record_for_identical_token()
    {
        $token = (string) Str::uuid();

        $payload = [
            'nama'          => 'Citra Double',
            'email'         => 'citra.double@example.com',
            'whatsapp'      => '081211112222',
            'kebutuhan'     => 'Pengujian anti duplicate submit konsultasi.',
            '_submit_token' => $token,
        ];

        // First submit
        $response1 = $this->post(route('konsultasi.store'), $payload);
        $response1->assertRedirect('/#konsultasi');

        // Immediate second submit with SAME token (simulating double click or network retry)
        $response2 = $this->post(route('konsultasi.store'), $payload);
        $response2->assertRedirect('/#konsultasi');

        // Verify only 1 record exists in database
        $count = Consultation::where('email', 'citra.double@example.com')
            ->where('kebutuhan', 'Pengujian anti duplicate submit konsultasi.')
            ->count();

        $this->assertEquals(1, $count, 'Hanya boleh tersimpan 1 data konsultasi saat terjadi double submit dengan token yang sama.');
    }

    /**
     * Test legitimate subsequent consultation with new token is saved even if email & needs are the same.
     */
    public function test_legitimate_second_consultation_with_new_token_is_saved()
    {
        $payload1 = [
            'nama'          => 'Dedi Repeat',
            'email'         => 'dedi.repeat@example.com',
            'whatsapp'      => '081233334444',
            'kebutuhan'     => 'Kebutuhan konsultasi pertama dan kedua sama.',
            '_submit_token' => (string) Str::uuid(),
        ];

        $payload2 = [
            'nama'          => 'Dedi Repeat',
            'email'         => 'dedi.repeat@example.com',
            'whatsapp'      => '081233334444',
            'kebutuhan'     => 'Kebutuhan konsultasi pertama dan kedua sama.',
            '_submit_token' => (string) Str::uuid(),
        ];

        // First legitimate submit
        $this->post(route('konsultasi.store'), $payload1)->assertRedirect('/#konsultasi');

        // Second legitimate submit with FRESH token
        $this->post(route('konsultasi.store'), $payload2)->assertRedirect('/#konsultasi');

        // Both should be saved
        $count = Consultation::where('email', 'dedi.repeat@example.com')
            ->where('kebutuhan', 'Kebutuhan konsultasi pertama dan kedua sama.')
            ->count();

        $this->assertEquals(2, $count, 'Konsultasi baru yang sah dengan token baru harus tersimpan meskipun email dan kebutuhannya sama.');
    }

    /**
     * Test validation errors on consultation form.
     */
    public function test_consultation_form_validation_requires_mandatory_fields()
    {
        $response = $this->from('/')->post(route('konsultasi.store'), [
            'nama'      => '',
            'email'     => 'bukan-email',
            'whatsapp'  => '',
            'kebutuhan' => '',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['nama', 'email', 'kebutuhan']);
    }

    /**
     * Test empty email returns Indonesian validation error message.
     */
    public function test_consultation_form_rejects_empty_email_with_indonesian_message()
    {
        $response = $this->from('/')->post(route('konsultasi.store'), [
            'nama'      => 'Budi Santoso',
            'email'     => '',
            'whatsapp'  => '08123456789',
            'kebutuhan' => 'Saya butuh solusi AI untuk otomasi workflow.',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors([
            'email' => 'Alamat email wajib diisi.'
        ]);
    }

    /**
     * Test invalid email format returns Indonesian validation error message.
     */
    public function test_consultation_form_rejects_invalid_email_format_with_indonesian_message()
    {
        // Test non-email string
        $response1 = $this->from('/')->post(route('konsultasi.store'), [
            'nama'      => 'Budi Santoso',
            'email'     => 'bukan-format-email',
            'whatsapp'  => '08123456789',
            'kebutuhan' => 'Saya butuh solusi AI untuk otomasi workflow.',
        ]);

        $response1->assertRedirect('/');
        $response1->assertSessionHasErrors([
            'email' => 'Format alamat email tidak valid.'
        ]);

        // Test email missing domain
        $response2 = $this->from('/')->post(route('konsultasi.store'), [
            'nama'      => 'Budi Santoso',
            'email'     => 'budi@',
            'whatsapp'  => '08123456789',
            'kebutuhan' => 'Saya butuh solusi AI untuk otomasi workflow.',
        ]);

        $response2->assertRedirect('/');
        $response2->assertSessionHasErrors([
            'email' => 'Format alamat email tidak valid.'
        ]);
    }

    /**
     * Test HTML5 validation on welcome page has Indonesian validation messages.
     */
    public function test_consultation_form_html5_validation_contains_indonesian_messages()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('Alamat email wajib diisi.', $content);
        $this->assertStringContainsString('Format alamat email tidak valid.', $content);
        $this->assertStringContainsString('Nama lengkap wajib diisi.', $content);
        $this->assertStringContainsString('Kebutuhan AI Anda wajib diisi.', $content);
        $this->assertStringContainsString('setCustomValidity', $content);
    }
}
