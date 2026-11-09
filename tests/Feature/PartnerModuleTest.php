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
}

