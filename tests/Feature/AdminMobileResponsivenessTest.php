<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Article;
use App\Models\Service;
use App\Models\Partner;
use App\Models\Consultation;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminMobileResponsivenessTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test_resp@jogjasolutions.com'],
            [
                'name' => 'Admin Website Test',
                'password' => bcrypt('password123'),
                'role' => User::ROLE_ADMIN_WEBSITE,
            ]
        );
    }

    /**
     * Test viewport meta tag and core layout elements exist
     */
    public function test_admin_layout_has_viewport_meta_and_hamburger_button()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('<meta name="viewport" content="width=device-width, initial-scale=1.0">', false);
        $response->assertSee('id="hamburger-btn"', false);
        $response->assertSee('id="admin-sidebar"', false);
        $response->assertSee('id="sidebar-overlay"', false);
        $response->assertSee('id="sidebar-close-btn"', false);
        $response->assertSee('id="theme-toggle-btn"', false);
    }

    /**
     * Test overlay styling protects pointer events and prevents blocking clicks when closed
     */
    public function test_sidebar_overlay_has_safe_pointer_events_and_z_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $content = $response->getContent();

        // Overlay harus memiliki pointer-events: none secara default saat tidak visible
        $this->assertStringContainsString('#sidebar-overlay {', $content);
        $this->assertStringContainsString('pointer-events: none;', $content);
        $this->assertStringContainsString('visibility: hidden;', $content);

        // Overlay saat visible mengaktifkan pointer-events: auto
        $this->assertStringContainsString('#sidebar-overlay.visible {', $content);
        $this->assertStringContainsString('pointer-events: auto;', $content);

        // Memastikan tidak ada rule '#sidebar-overlay { display: block; }' yang aktif tanpa kelas .visible
        $this->assertDoesNotMatchRegularExpression('/@media\s*\([^\)]*max-width:\s*768px[^\)]*\)\s*\{[^}]*#sidebar-overlay\s*\{\s*display:\s*block;\s*\}/s', $content);
    }

    /**
     * Test topbar title does not collide with theme toggle button on mobile
     */
    public function test_topbar_title_and_theme_toggle_have_safe_responsive_styling()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $content = $response->getContent();

        // Topbar title pada mobile memiliki truncate ellipsis dan min-width: 0
        $this->assertStringContainsString('overflow: hidden;', $content);
        $this->assertStringContainsString('text-overflow: ellipsis;', $content);
        $this->assertStringContainsString('white-space: nowrap;', $content);

        // Quick search dan date disembunyikan pada mobile agar tidak memakan ruang
        $this->assertStringContainsString('.quick-search-wrap {', $content);
        $this->assertStringContainsString('display: none !important;', $content);
    }

    /**
     * Test dashboard stat grid responsive rules
     */
    public function test_dashboard_stat_grid_responsive_rules()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('stat-grid', false);
        $response->assertSee('stat-card', false);

        $content = $response->getContent();
        // 768px mobile breakpoint contains stat-grid with repeat(2, 1fr) and 360px has 1fr
        $this->assertStringContainsString('grid-template-columns: repeat(2, 1fr) !important;', $content);
        $this->assertStringContainsString('grid-template-columns: 1fr !important;', $content);
    }

    /**
     * Test Artikel table has horizontal scroll and action buttons
     */
    public function test_article_index_table_has_responsive_scroll_and_action_buttons()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.articles.index'));

        $response->assertStatus(200);
        $content = $response->getContent();

        // Scroll wrapper exists
        $this->assertStringContainsString('overflow-x:auto', str_replace(' ', '', $content));
        $this->assertStringContainsString('article-nav-tabs', $content);
        $this->assertStringContainsString('table-container', $content);

        // Edit and hapus action buttons
        $this->assertStringContainsString('action-btn', $content);
    }

    /**
     * Test Layanan AI table has horizontal scroll and action buttons
     */
    public function test_service_index_table_has_responsive_scroll_and_action_buttons()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.services.index'));

        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString('overflow-x:auto', str_replace(' ', '', $content));
        $this->assertStringContainsString('table-container', $content);
        $this->assertStringContainsString('action-links', $content);
        $this->assertStringContainsString('min-width: 620px', $content);
    }

    /**
     * Test Konsultasi table has horizontal scroll and detail buttons
     */
    public function test_consultation_index_table_has_responsive_scroll_and_action_buttons()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.consultations.index'));

        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString('overflow-x:auto', str_replace(' ', '', $content));
        $this->assertStringContainsString('table-container', $content);
        $this->assertStringContainsString('min-width: 580px', $content);
    }

    /**
     * Test Partner index page has responsive grid
     */
    public function test_partner_index_has_responsive_grid()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.partners.index'));

        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString('partner-grid', $content);
        $this->assertStringContainsString('grid-template-columns: repeat(2, 1fr) !important;', $content);
    }

    /**
     * Test Konsultasi show has responsive meta grid
     */
    public function test_consultation_show_has_responsive_meta_grid()
    {
        $consultation = Consultation::firstOrCreate(
            ['email' => 'resp_test@example.com'],
            [
                'nama' => 'Budi Client',
                'whatsapp' => '081234567890',
                'kebutuhan' => 'Konsultasi implementasi AI',
                'is_read' => false,
            ]
        );

        $response = $this->actingAs($this->admin)->get(route('admin.consultations.show', $consultation->id));

        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString('detail-card', $content);
        $this->assertStringContainsString('meta-grid', $content);
        $this->assertStringContainsString('grid-template-columns: 1fr;', $content);
    }

    /**
     * Test desktop styles are preserved (sidebar width, topbar height, stat-grid 4 columns)
     */
    public function test_desktop_styles_are_preserved()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $content = $response->getContent();

        // Desktop sidebar width
        $this->assertStringContainsString('--sidebar-w:  240px;', $content);
        // Desktop stat-grid 4 columns
        $this->assertStringContainsString('grid-template-columns: repeat(4, 1fr);', $content);
        // Desktop topbar height
        $this->assertStringContainsString('height: 64px;', $content);
        // Desktop overlay hidden by default
        $this->assertStringContainsString('#sidebar-overlay {', $content);
        $this->assertStringContainsString('display: none;', $content);
    }
}
