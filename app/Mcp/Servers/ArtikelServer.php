<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\BuatDraftArtikelTool;
use App\Mcp\Tools\DaftarArtikelTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('AI Solution - Artikel')]
#[Version('1.0.0')]
#[Instructions(<<<'TXT'
Connector ini menyimpan artikel ke website AI Solution (jogjaaisolutions.my.id) sebagai DRAFT.
Draft tidak langsung tayang; admin meninjau lalu menerbitkannya.

Alur kerja:
1. Panggil daftar-artikel dulu untuk melihat judul yang sudah ada dan kategori yang dipakai.
   Hindari topik/judul yang sama atau terlalu mirip. Pakai kategori yang sudah ada bila cocok.
2. Tulis artikel, lalu panggil buat-draft-artikel SEKALI untuk setiap artikel.
   Jika diminta beberapa artikel, kirim satu per satu dan laporkan hasil setiap artikel.
3. Jika tool mengembalikan error, perbaiki sesuai pesan errornya. Jangan kirim ulang artikel yang sudah tersimpan.

Panduan penulisan:
- Bahasa Indonesia yang jelas dan profesional, untuk pemilik bisnis, UMKM, dan pembaca umum di Indonesia.
- Isi dalam Markdown: subjudul ## dan ###, paragraf pendek, daftar bila membantu. Tanpa HTML dan tanpa gambar di dalam isi.
- Jangan mengulang judul di awal isi. Panjang ideal 600-1200 kata.
- Jangan mengarang statistik, kutipan, atau nama sumber. Jika memakai data, isi sumber_nama dan sumber_url.
- Ringkasan 1-2 kalimat; takeaway satu kalimat kesimpulan utama.
TXT)]
class ArtikelServer extends Server
{
    /**
     * @var array<int, class-string<\Laravel\Mcp\Server\Tool>>
     */
    protected array $tools = [
        DaftarArtikelTool::class,
        BuatDraftArtikelTool::class,
    ];

    /**
     * @var array<int, class-string<\Laravel\Mcp\Server\Resource>>
     */
    protected array $resources = [];

    /**
     * @var array<int, class-string<\Laravel\Mcp\Server\Prompt>>
     */
    protected array $prompts = [];
}
