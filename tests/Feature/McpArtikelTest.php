<?php

namespace Tests\Feature;

use App\Mcp\Servers\ArtikelServer;
use App\Mcp\Tools\BuatDraftArtikelTool;
use App\Mcp\Tools\DaftarArtikelTool;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use phpseclib4\Crypt\RSA;
use Tests\TestCase;

class McpArtikelTest extends TestCase
{
    use DatabaseTransactions;

    private static ?RSA\PrivateKey $rsa = null;

    protected function setUp(): void
    {
        parent::setUp();

        // Key OAuth sementara (di server dibuat oleh passport:keys saat container start)
        self::$rsa ??= RSA::createKey(2048);
        config([
            'passport.private_key' => self::$rsa->toString('PKCS8'),
            'passport.public_key' => self::$rsa->getPublicKey()->toString('PKCS8'),
        ]);
    }

    private function writer(): User
    {
        return User::factory()->create(['name' => 'Rindu Penulis', 'role' => User::ROLE_ADMIN_MARKETING]);
    }

    private function payload(array $override = []): array
    {
        return array_merge([
            'judul' => 'Cara UMKM Memakai AI untuk Melayani Pelanggan '.Str::random(6),
            'kategori' => 'AI & Bisnis',
            'ringkasan' => 'Panduan praktis memakai AI agar UMKM bisa membalas pelanggan lebih cepat.',
            'konten_markdown' => "# Judul Ganda\n\n## Mengapa Penting\n\n"
                .str_repeat('Kalimat isi artikel yang cukup panjang untuk pengujian. ', 12)
                ."\n\n- poin satu\n- poin **dua**\n\n<script>alert(1)</script>\n\n"
                ."[klik](javascript:alert(1)) dan ![gambar](https://example.com/a.png)\n\n#### Sub kecil\n\nPenutup.",
            'takeaway' => 'AI membantu UMKM merespons lebih cepat.',
        ], $override);
    }

    public function test_creates_sanitized_draft_attributed_to_logged_in_user(): void
    {
        $payload = $this->payload();

        ArtikelServer::actingAs($this->writer())
            ->tool(BuatDraftArtikelTool::class, $payload)
            ->assertOk()
            ->assertHasNoErrors()
            ->assertSee('Draft artikel tersimpan');

        $article = Article::where('judul', $payload['judul'])->firstOrFail();
        $this->assertSame('draft', $article->status);
        $this->assertNull($article->published_at);
        $this->assertTrue((bool) $article->is_active);
        $this->assertSame('Rindu Penulis', $article->author_name);
        $this->assertSame(Str::slug($payload['judul']), $article->slug);

        $isi = json_decode($article->isi_artikel, true);
        $html = $isi['content'];
        $this->assertStringContainsString('<h2>Mengapa Penting</h2>', $html);
        $this->assertStringContainsString('<h2>Judul Ganda</h2>', $html); // h1 diturunkan
        $this->assertStringContainsString('<h3>Sub kecil</h3>', $html);   // h4 dinaikkan
        $this->assertStringContainsString('<strong>dua</strong>', $html);
        $this->assertStringNotContainsString('<script', $html);
        $this->assertStringNotContainsString('javascript:', $html);
        $this->assertStringNotContainsString('<img', $html);
        $this->assertSame('AI membantu UMKM merespons lebih cepat.', $isi['takeaway']);
        $this->assertMatchesRegularExpression('/^\d+ mnt$/', $isi['readTime']);

        // Draft tidak tampil publik
        $this->get('/wawasan')->assertDontSee($payload['judul']);
        $this->assertFalse(Article::published()->whereKey($article->id)->exists());
    }

    public function test_nama_penulis_overrides_shared_account_name(): void
    {
        $payload = $this->payload(['nama_penulis' => '  Elsa AISolutions ']);

        ArtikelServer::actingAs($this->writer())->tool(BuatDraftArtikelTool::class, $payload)->assertHasNoErrors();

        $this->assertSame('Elsa AISolutions', Article::where('judul', $payload['judul'])->value('author_name'));
    }

    public function test_changing_password_revokes_claude_connections(): void
    {
        $user = $this->writer();
        $client = app(ClientRepository::class)->createAuthorizationCodeGrantClient(
            name: 'Claude', redirectUris: ['https://claude.ai/api/mcp/auth_callback'], confidential: false,
        );
        $makeTokens = function () use ($user, $client): string {
            $id = Str::random(80);
            Passport::token()->forceFill(['id' => $id, 'user_id' => $user->id, 'client_id' => $client->id, 'scopes' => [], 'revoked' => false, 'expires_at' => now()->addHour()])->save();
            Passport::refreshToken()->forceFill(['id' => Str::random(80), 'access_token_id' => $id, 'revoked' => false, 'expires_at' => now()->addDays(30)])->save();

            return $id;
        };
        $tokenId = $makeTokens();

        $user->update(['name' => 'Ganti Nama Saja']);
        $this->assertFalse((bool) Passport::token()->find($tokenId)->revoked, 'ganti nama tidak mencabut token');

        $user->update(['password' => 'password-baru-yang-kuat-123']);
        $this->assertTrue((bool) Passport::token()->find($tokenId)->revoked);
        $this->assertTrue((bool) Passport::refreshToken()->where('access_token_id', $tokenId)->value('revoked'));
    }

    public function test_duplicate_title_is_rejected(): void
    {
        $user = $this->writer();
        $payload = $this->payload();

        ArtikelServer::actingAs($user)->tool(BuatDraftArtikelTool::class, $payload)->assertHasNoErrors();
        ArtikelServer::actingAs($user)
            ->tool(BuatDraftArtikelTool::class, array_merge($payload, ['judul' => '  '.strtoupper($payload['judul']).' ']))
            ->assertHasErrors();

        $this->assertSame(1, Article::whereRaw('LOWER(judul) = ?', [Str::lower($payload['judul'])])->count());
    }

    public function test_similar_titles_get_unique_slugs(): void
    {
        $user = $this->writer();
        $suffix = Str::random(6);

        ArtikelServer::actingAs($user)->tool(BuatDraftArtikelTool::class, $this->payload(['judul' => "AI untuk UMKM: Panduan Lengkap {$suffix}!"]))->assertHasNoErrors();
        ArtikelServer::actingAs($user)->tool(BuatDraftArtikelTool::class, $this->payload(['judul' => "AI untuk UMKM - Panduan Lengkap {$suffix}"]))->assertHasNoErrors();

        $base = Str::slug("AI untuk UMKM Panduan Lengkap {$suffix}");
        $this->assertTrue(Article::where('slug', $base)->exists());
        $this->assertTrue(Article::where('slug', $base.'-2')->exists());
    }

    public function test_invalid_input_returns_error_without_saving(): void
    {
        $before = Article::count();

        ArtikelServer::actingAs($this->writer())
            ->tool(BuatDraftArtikelTool::class, $this->payload(['konten_markdown' => 'Terlalu pendek.']))
            ->assertHasErrors();

        $this->assertSame($before, Article::count());
    }

    public function test_bad_image_url_does_not_block_draft(): void
    {
        $payload = $this->payload(['gambar_url' => 'http://127.0.0.1/banner.png']);

        ArtikelServer::actingAs($this->writer())
            ->tool(BuatDraftArtikelTool::class, $payload)
            ->assertHasNoErrors()
            ->assertSee('Gambar tidak dipakai');

        $this->assertNull(Article::where('judul', $payload['judul'])->value('gambar'));
    }

    public function test_daftar_artikel_lists_titles_and_categories(): void
    {
        $payload = $this->payload(['kategori' => 'Kategori Uji Unik']);
        ArtikelServer::actingAs($this->writer())->tool(BuatDraftArtikelTool::class, $payload)->assertHasNoErrors();

        ArtikelServer::actingAs($this->writer())
            ->tool(DaftarArtikelTool::class, ['cari' => 'Kategori Uji Unik'])
            ->assertOk()
            ->assertSee($payload['judul'])
            ->assertSee('[draft]')
            ->assertSee('Kategori Uji Unik (1)');
    }

    public function test_unauthenticated_mcp_request_points_to_oauth_metadata(): void
    {
        $response = $this->postJson('/mcp/artikel', [
            'jsonrpc' => '2.0', 'id' => 1, 'method' => 'initialize',
            'params' => ['protocolVersion' => '2025-06-18', 'capabilities' => new \stdClass, 'clientInfo' => ['name' => 'test', 'version' => '1']],
        ], ['Accept' => 'application/json, text/event-stream']);

        $response->assertStatus(401);
        $this->assertStringContainsString(
            'resource_metadata="'.url('/.well-known/oauth-protected-resource/mcp/artikel').'"',
            $response->headers->get('WWW-Authenticate')
        );

        $this->getJson('/.well-known/oauth-protected-resource/mcp/artikel')
            ->assertOk()
            ->assertJsonPath('resource', url('/mcp/artikel'));

        $meta = $this->getJson('/.well-known/oauth-authorization-server')->assertOk();
        $this->assertContains('S256', $meta->json('code_challenge_methods_supported'));
        $this->assertSame(url('/oauth/register'), $meta->json('registration_endpoint'));
    }

    public function test_only_claude_redirect_uris_can_register(): void
    {
        $this->postJson('/oauth/register', [
            'client_name' => 'Claude',
            'redirect_uris' => ['https://claude.ai/api/mcp/auth_callback'],
        ])->assertCreated()->assertJsonPath('token_endpoint_auth_method', 'none');

        $this->postJson('/oauth/register', [
            'client_name' => 'Jahat',
            'redirect_uris' => ['https://claude.ai.evil.example/callback'],
        ])->assertStatus(400)->assertJsonPath('error', 'invalid_redirect_uri');
    }

    public function test_authorize_page_requires_login_then_shows_consent(): void
    {
        $clientId = $this->postJson('/oauth/register', [
            'client_name' => 'Claude',
            'redirect_uris' => ['https://claude.ai/api/mcp/auth_callback'],
        ])->assertCreated()->json('client_id');

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => 'https://claude.ai/api/mcp/auth_callback',
            'response_type' => 'code',
            'scope' => 'mcp:use',
            'state' => 'abc123',
            'code_challenge' => rtrim(strtr(base64_encode(hash('sha256', 'verifier-'.str_repeat('x', 40), true)), '+/', '-_'), '='),
            'code_challenge_method' => 'S256',
        ]);

        $this->get('/oauth/authorize?'.$query)->assertRedirect(route('admin.login'));

        $this->actingAs($this->writer())
            ->get('/oauth/authorize?'.$query)
            ->assertOk()
            ->assertSee('Izinkan')
            ->assertSee('Rindu Penulis')
            ->assertSee('https://claude.ai/api/mcp/auth_callback');
    }
}
