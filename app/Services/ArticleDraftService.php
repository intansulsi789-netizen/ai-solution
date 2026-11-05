<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * Menyimpan artikel berstatus DRAFT dari data terstruktur (dipakai connector MCP Claude).
 *
 * Draft tidak tampil di website. Admin meninjau lalu menerbitkannya dari halaman admin.
 * Isi artikel diterima sebagai Markdown lalu diubah menjadi HTML bersih
 * dengan format yang sama seperti hasil editor Quill di admin.
 */
class ArticleDraftService
{
    /**
     * @param  array{judul: string, kategori: string, ringkasan: string, konten_markdown: string, takeaway?: ?string, nama_penulis?: ?string, gambar_url?: ?string, sumber_nama?: ?string, sumber_url?: ?string}  $input
     * @return array{article: Article, notes: list<string>}
     */
    public function createDraft(User $author, array $input): array
    {
        $notes = [];
        $html = $this->markdownToHtml($input['konten_markdown']);

        $gambar = null;
        if (! empty($input['gambar_url'])) {
            [$gambar, $imageNote] = $this->resolveImage($input['gambar_url']);
            $notes[] = $imageNote;
        }

        $article = Article::create([
            'judul' => trim($input['judul']),
            'slug' => $this->uniqueSlug($input['judul']),
            'kategori' => trim($input['kategori']),
            'author_name' => Str::limit(trim($input['nama_penulis'] ?? '') ?: $author->name, 100, ''),
            'source_name' => $input['sumber_nama'] ?? null,
            'source_url' => $input['sumber_url'] ?? null,
            'ringkasan' => trim($input['ringkasan']),
            'isi_artikel' => json_encode([
                'content' => $html,
                'takeaway' => trim($input['takeaway'] ?? ''),
                'readTime' => $this->readTime($html),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'gambar' => $gambar,
            'status' => 'draft',
            'published_at' => null,
            'tanggal' => null,
            'is_active' => true,
        ]);

        return ['article' => $article, 'notes' => $notes];
    }

    public function findByTitle(string $judul): ?Article
    {
        return Article::whereRaw('LOWER(TRIM(judul)) = ?', [Str::lower(trim($judul))])->first();
    }

    public function wordCount(string $html): int
    {
        $text = trim(html_entity_decode(strip_tags(str_replace('<', ' <', $html))));

        return $text === '' ? 0 : count(preg_split('/\s+/u', $text));
    }

    /**
     * Markdown -> HTML aman: HTML mentah dibuang, link berbahaya (javascript: dll) diblokir,
     * gambar di dalam isi dibuang, heading disesuaikan dengan yang didukung editor (h2-h3).
     */
    public function markdownToHtml(string $markdown): string
    {
        $html = Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 20,
        ]);

        $html = preg_replace('/<img\b[^>]*>/i', '', $html);
        $html = preg_replace('/<(\/?)h1\b/i', '<$1h2', $html);
        $html = preg_replace('/<(\/?)h[4-6]\b/i', '<$1h3', $html);
        $html = preg_replace('/<p>\s*<\/p>/', '', $html);

        return trim($html);
    }

    private function readTime(string $html): string
    {
        return max(1, (int) ceil($this->wordCount($html) / 200)).' mnt';
    }

    private function uniqueSlug(string $judul): string
    {
        $base = Str::limit(Str::slug($judul), 200, '') ?: 'artikel';
        $slug = $base;
        $i = 2;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    /**
     * Gambar gagal tidak membatalkan artikel: draft tetap tersimpan, admin bisa menambah gambar.
     *
     * @return array{0: ?string, 1: string}
     */
    private function resolveImage(string $url): array
    {
        $resolved = ArticleImageService::resolveUrl(trim($url));
        if (! $resolved['success'] || empty($resolved['image_url'])) {
            return [null, 'Gambar tidak dipakai: '.($resolved['message'] ?? 'URL gambar tidak valid.')];
        }

        $download = ArticleImageService::downloadAndStore($resolved['image_url']);
        if ($download['success'] && ! empty($download['path'])) {
            ImageThumbnail::url($download['path'], 900); // siapkan thumbnail homepage sekarang

            return [$download['path'], 'Gambar banner diunduh ke server.'];
        }

        return [$resolved['image_url'], 'Gambar dipakai sebagai link eksternal (gagal diunduh: '.($download['error'] ?? 'tidak diketahui').').'];
    }
}
