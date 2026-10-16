<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ThemeToggleTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::first();
    }

    public function test_public_homepage_renders_theme_toggle_and_script()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('id="theme-toggle-btn"', false);
        $response->assertSee('localStorage.getItem(\'theme\')', false);
        $response->assertSee('localStorage.setItem(\'theme\'', false);
        $response->assertSee('data-theme="light"', false);
    }

    public function test_article_detail_renders_theme_toggle()
    {
        $article = Article::published()->first();
        if ($article) {
            $response = $this->get('/wawasan/' . $article->slug);
            $response->assertStatus(200);
            $response->assertSee('id="theme-toggle-btn"', false);
            $response->assertSee('data-theme="light"', false);
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_service_detail_renders_theme_toggle()
    {
        $service = Service::first();
        if ($service) {
            $response = $this->get('/layanan/' . $service->slug);
            $response->assertStatus(200);
            $response->assertSee('id="theme-toggle-btn"', false);
            $response->assertSee('data-theme="light"', false);
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_admin_dashboard_renders_theme_toggle()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('id="theme-toggle-btn"', false);
        $response->assertSee('data-theme="light"', false);
    }

    public function test_admin_login_renders_theme_toggle()
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('id="theme-toggle-btn"', false);
        $response->assertSee('data-theme="light"', false);
    }
}
