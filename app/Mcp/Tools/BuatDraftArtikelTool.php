<?php

namespace App\Mcp\Tools;

use App\Services\ArticleDraftService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Title;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;

#[Name('buat-draft-artikel')]
#[Title('Buat Draft Artikel')]
#[Description('Menyimpan SATU artikel baru ke website AI Solution sebagai DRAFT (tidak langsung tayang; admin yang menerbitkan). Nama penulis otomatis diisi dari akun yang login. Isi artikel ditulis dalam Markdown. Panggil sekali untuk setiap artikel.')]
#[IsDestructive(false)]
#[IsOpenWorld(true)]
class BuatDraftArtikelTool extends Tool
{
    public function handle(Request $request, ArticleDraftService $drafts): Response
    {
        $data = $request->validate([
            'judul' => 'required|string|min:10|max:255',
            'kategori' => 'required|string|max:100',
            'ringkasan' => 'required|string|min:30|max:500',
            'konten_markdown' => 'required|string|min:300|max:60000',
            'takeaway' => 'nullable|string|max:500',
            'gambar_url' => 'nullable|url|max:2048',
            'sumber_nama' => 'nullable|string|max:255',
            'sumber_url' => 'nullable|url|max:1000',
        ], [
            'konten_markdown.min' => 'Isi artikel terlalu pendek (minimal 300 karakter). Tulis artikel lengkap.',
            'ringkasan.min' => 'Ringkasan terlalu pendek (minimal 30 karakter).',
            'judul.min' => 'Judul terlalu pendek (minimal 10 karakter).',
        ]);

        if ($existing = $drafts->findByTitle($data['judul'])) {
            return Response::error(
                "Artikel berjudul \"{$existing->judul}\" sudah ada (ID {$existing->id}, status {$existing->status}). ".
                'Jangan kirim ulang. Jika ini artikel berbeda, gunakan judul lain.'
            );
        }

        ['article' => $article, 'notes' => $notes] = $drafts->createDraft($request->user(), $data);

        $isi = json_decode($article->isi_artikel, true);
        $lines = [
            'Draft artikel tersimpan (belum tayang, menunggu ditinjau admin).',
            "- ID: {$article->id}",
            "- Judul: {$article->judul}",
            "- Kategori: {$article->kategori}",
            "- Penulis: {$article->author_name}",
            '- Panjang: ±'.$drafts->wordCount($isi['content'] ?? '').' kata ('.($isi['readTime'] ?? '-').' baca)',
            '- Edit/terbitkan di admin: '.route('admin.articles.edit', $article->id),
        ];
        foreach ($notes as $note) {
            $lines[] = "- Catatan: {$note}";
        }

        return Response::text(implode("\n", $lines));
    }

    /**
     * @return array<string, \Illuminate\JsonSchema\Types\Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'judul' => $schema->string()->max(255)
                ->description('Judul artikel dalam Bahasa Indonesia, menarik dan spesifik (tanpa tanda kutip di awal/akhir).')
                ->required(),
            'kategori' => $schema->string()->max(100)
                ->description('Kategori artikel. Gunakan kategori yang sudah ada (lihat tool daftar-artikel) agar konsisten, contoh: "AI & Bisnis".')
                ->required(),
            'ringkasan' => $schema->string()->max(500)
                ->description('Ringkasan 1-2 kalimat (maks ±300 karakter) yang tampil di kartu artikel dan meta description.')
                ->required(),
            'konten_markdown' => $schema->string()
                ->description('Isi lengkap artikel dalam Markdown: subjudul pakai ## dan ###, paragraf, daftar, **tebal**, link. Jangan ulangi judul di awal, jangan pakai HTML atau gambar. Ideal 600-1200 kata.')
                ->required(),
            'takeaway' => $schema->string()->max(500)
                ->description('Satu kalimat kesimpulan utama (Key Takeaway). Opsional.'),
            'gambar_url' => $schema->string()->max(2048)
                ->description('Opsional. URL gambar banner: link langsung gambar (jpg/png/webp), atau link Pinterest/Google Images/halaman artikel yang punya gambar. Pastikan gambar boleh dipakai.'),
            'sumber_nama' => $schema->string()->max(255)
                ->description('Opsional. Nama sumber rujukan jika artikel merujuk sumber tertentu.'),
            'sumber_url' => $schema->string()->max(1000)
                ->description('Opsional. URL sumber rujukan.'),
        ];
    }
}
