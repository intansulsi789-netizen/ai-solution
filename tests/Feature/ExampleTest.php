<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_navbar_layanan_links_to_services_section(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('href="/#layanan"', false);
        $response->assertSee('<section id="layanan" class="services-section">', false);
        $response->assertSee('Solusi AI yang Dirancang untuk Kebutuhan Anda');
    }
}
