<?php

namespace App\Mcp\Tools;

use App\Models\Article;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Title;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('daftar-artikel')]
#[Title('Daftar Artikel')]
#[Description('Melihat judul artikel yang sudah ada di website (semua status) beserta daftar kategori yang dipakai. Panggil SEBELUM menulis untuk menghindari topik/judul yang sama dan memakai kategori yang konsisten.')]
#[IsReadOnly]
class DaftarArtikelTool extends Tool
{
    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'cari' => 'nullable|string|max:100',
            'jumlah' => 'nullable|integer|min:1|max:200',
        ]);

        $query = Article::query()->latest('id');
        if (! empty($data['cari'])) {
            $term = '%'.$data['cari'].'%';
            $query->where(fn ($q) => $q->where('judul', 'like', $term)->orWhere('kategori', 'like', $term));
        }

        $articles = $query->limit($data['jumlah'] ?? 50)->get(['id', 'judul', 'kategori', 'status', 'author_name', 'created_at']);
        $total = Article::count();

        $kategori = Article::query()
            ->whereNotNull('kategori')->where('kategori', '!=', '')
            ->selectRaw('kategori, COUNT(*) as jumlah')
            ->groupBy('kategori')->orderByDesc('jumlah')->limit(50)
            ->get()
            ->map(fn ($k) => "{$k->kategori} ({$k->jumlah})")
            ->implode(', ');

        $lines = ["Total artikel di website: {$total}.", 'Kategori yang dipakai: '.($kategori ?: '-'), ''];
        $lines[] = empty($data['cari'])
            ? "Artikel terbaru ({$articles->count()}):"
            : "Hasil pencarian \"{$data['cari']}\" ({$articles->count()}):";

        foreach ($articles as $a) {
            $tgl = $a->created_at?->timezone('Asia/Jakarta')->format('d M Y') ?? '-';
            $lines[] = "- [{$a->status}] {$a->judul} | {$a->kategori} | {$tgl}".($a->author_name ? " | {$a->author_name}" : '');
        }

        return Response::text(implode("\n", $lines));
    }

    /**
     * @return array<string, \Illuminate\JsonSchema\Types\Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'cari' => $schema->string()->max(100)
                ->description('Opsional. Kata kunci untuk mencari di judul atau kategori, contoh: "UMKM".'),
            'jumlah' => $schema->integer()->min(1)->max(200)
                ->description('Opsional. Jumlah artikel yang ditampilkan (default 50, maks 200).'),
        ];
    }
}
