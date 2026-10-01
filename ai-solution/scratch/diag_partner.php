<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Partner;
use Illuminate\Support\Facades\Storage;

echo "========================================\n";
echo "  DIAGNOSIS PARTNER\n";
echo "========================================\n\n";

// 1. Semua partner (tanpa filter)
$all = Partner::orderBy('id')->get();
echo "[1] TOTAL PARTNER (semua): " . $all->count() . "\n\n";

foreach ($all as $p) {
    echo "----\n";
    echo "  ID        : " . $p->id . "\n";
    echo "  Nama      : " . $p->nama . "\n";
    echo "  Logo (DB) : " . (is_null($p->logo) ? 'NULL' : (empty($p->logo) ? '[EMPTY STRING]' : $p->logo)) . "\n";
    echo "  Urutan    : " . $p->urutan . "\n";
    echo "  is_active : " . ($p->is_active ? 'true (1)' : 'false (0)') . "\n";

    if ($p->logo) {
        $logo = $p->logo;

        if (str_starts_with($logo, 'http')) {
            echo "  Tipe Logo : URL EKSTERNAL\n";
            echo "  <img src> : " . $logo . "\n";
            // Tidak bisa cek file eksternal, skip
        } else {
            echo "  Tipe Logo : PATH LOKAL (storage/)\n";
            $assetUrl = url('storage/' . $logo);
            echo "  <img src> : " . $assetUrl . "\n";

            // Cek apakah file fisik ada
            $storagePublicPath = storage_path('app/public/' . $logo);
            echo "  Path Disk : " . $storagePublicPath . "\n";
            echo "  File Exist: " . (file_exists($storagePublicPath) ? 'YA ✓' : 'TIDAK ADA ✗') . "\n";

            // Cek via Storage facade
            $storageExists = Storage::disk('public')->exists($logo);
            echo "  Storage::exists: " . ($storageExists ? 'true ✓' : 'false ✗') . "\n";
        }
    } else {
        echo "  Tipe Logo : TIDAK ADA (NULL/EMPTY) → ITEM AKAN DI-SKIP DI FRONTEND\n";
    }
    echo "\n";
}

// 2. Yang akan dikirim ke homepage (is_active=true)
echo "========================================\n";
echo "[2] PARTNER AKTIF (dikirim ke homepage):\n";
echo "========================================\n";
$aktif = Partner::where('is_active', true)->orderBy('urutan', 'asc')->get();
echo "Total aktif: " . $aktif->count() . "\n\n";

foreach ($aktif as $p) {
    echo "  - [" . $p->id . "] " . $p->nama . " | logo=" . ($p->logo ?? 'NULL') . "\n";
}

// 3. Cek symlink storage
echo "\n========================================\n";
echo "[3] CEK STORAGE SYMLINK\n";
echo "========================================\n";
$publicStoragePath = public_path('storage');
echo "Path: " . $publicStoragePath . "\n";
echo "Exists: " . (file_exists($publicStoragePath) ? 'YA' : 'TIDAK') . "\n";
echo "Is Symlink: " . (is_link($publicStoragePath) ? 'YA ✓' : 'BUKAN SYMLINK ✗') . "\n";

// 4. Cek folder partners di storage
echo "\n========================================\n";
echo "[4] ISI FOLDER storage/app/public/partners/\n";
echo "========================================\n";
$partnersDir = storage_path('app/public/partners');
if (is_dir($partnersDir)) {
    $files = scandir($partnersDir);
    $files = array_filter($files, fn($f) => !in_array($f, ['.', '..']));
    echo "File ditemukan (" . count($files) . "):\n";
    foreach ($files as $f) {
        $fullPath = $partnersDir . DIRECTORY_SEPARATOR . $f;
        echo "  - " . $f . " (" . filesize($fullPath) . " bytes)\n";
    }
} else {
    echo "FOLDER TIDAK ADA: " . $partnersDir . "\n";
}

echo "\n========================================\n";
echo "  SELESAI\n";
echo "========================================\n";
