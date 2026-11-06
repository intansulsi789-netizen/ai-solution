<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('127.0.0.1|admin_login');
    }

    public function test_login_page_renders_with_password_only()
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('Password Admin');
        $response->assertDontSee('name="email"', false);
    }

    public function test_admin_website_can_login_with_existing_database_password()
    {
        $response = $this->post(route('admin.login.post'), [
            'password' => 'onlyadmincangetin',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_admin_website_login_fails_with_wrong_password()
    {
        $response = $this->post(route('admin.login.post'), [
            'password' => 'passwordsalah123',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_master_password_is_removed_and_cannot_overwrite_admin_password()
    {
        $admin = User::where('role', User::ROLE_ADMIN_WEBSITE)->first();
        $newPassword = 'NewSecretPassword99!';
        $admin->update(['password' => Hash::make($newPassword)]);

        // 1. Password lama/master tidak boleh berhasil login
        $failedResponse = $this->post(route('admin.login.post'), [
            'password' => 'onlyadmincangetin',
        ]);
        $failedResponse->assertSessionHasErrors(['password']);
        $this->assertGuest();

        // 2. Password admin di database TIDAK tertimpa kembali ke master password
        $admin->refresh();
        $this->assertTrue(Hash::check($newPassword, $admin->password));
        $this->assertFalse(Hash::check('onlyadmincangetin', $admin->password));

        // 3. Password baru yang sah dari database berhasil login
        RateLimiter::clear('127.0.0.1|admin_login');
        $successResponse = $this->post(route('admin.login.post'), [
            'password' => $newPassword,
        ]);
        $successResponse->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_rate_limiting_prevents_brute_force()
    {
        RateLimiter::clear('127.0.0.1|admin_login');

        // Lakukan 5 percobaan salah
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login.post'), [
                'password' => 'wrongpass' . $i,
            ]);
        }

        // Percobaan ke-6 harus diblokir oleh rate limiting
        $response = $this->post(route('admin.login.post'), [
            'password' => 'wrongpass6',
        ]);

        $response->assertSessionHasErrors(['password']);
        $errors = session('errors')->get('password');
        $this->assertStringContainsString('Terlalu banyak percobaan login', $errors[0]);
    }

    public function test_admin_utama_can_login_with_email_and_password()
    {
        RateLimiter::clear('127.0.0.1|admin_login');
        $response = $this->post(route('admin.login.post'), [
            'email'    => 'admin@aisolution.id',
            'password' => 'onlyadmincangetin',
        ]);
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_user_with_marketing_role_gets_403_on_admin_routes()
    {
        $marketing = User::where('role', 'admin_marketing')->first();
        if (!$marketing) {
            $marketing = User::factory()->create(['role' => 'admin_marketing']);
        }

        $this->actingAs($marketing);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(403);

        $response = $this->get(route('admin.articles.index'));
        $response->assertStatus(403);

        $response = $this->get(route('admin.seo.index'));
        $response->assertStatus(403);
    }

    public function test_admin_marketing_cannot_login_with_marketing_password()
    {
        RateLimiter::clear('127.0.0.1|admin_login');
        $response = $this->post(route('admin.login.post'), [
            'email'    => 'marketing@aisolution.com',
            'password' => 'password123',
        ]);
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_admin_utama_has_full_access_to_all_features()
    {
        $admin = User::where('role', User::ROLE_ADMIN_WEBSITE)->first() ?? User::first();
        $this->actingAs($admin);

        // Akses seluruh modul admin
        $routes = [
            'admin.dashboard',
            'admin.services.index',
            'admin.articles.index',
            'admin.articles.calendar',
            'admin.partners.index',
            'admin.seo.index',
            'admin.cms.homepage.index',
            'admin.cms.logo.index',
            'admin.cms.about.index',
            'admin.cms.contact.index',
            'admin.consultations.index',
            'admin.profile.index',
        ];

        foreach ($routes as $routeName) {
            $response = $this->get(route($routeName));
            $response->assertStatus(200);
        }
    }

    public function test_unauthenticated_user_cannot_access_admin_routes()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));

        $response = $this->get(route('admin.seo.index'));
        $response->assertRedirect(route('admin.login'));
    }
}
