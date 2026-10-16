<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_page_renders_with_password_only()
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('Password Admin');
        $response->assertDontSee('name="email"', false);
    }

    public function test_admin_can_login_with_only_password_onlyadmincangetin()
    {
        $response = $this->post(route('admin.login.post'), [
            'password' => 'onlyadmincangetin',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_admin_login_fails_with_wrong_password()
    {
        $response = $this->post(route('admin.login.post'), [
            'password' => 'passwordsalah123',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }
}
