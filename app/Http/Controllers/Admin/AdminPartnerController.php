<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('urutan')->get();
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'                  => 'required|string|max:150',
            'urutan'                => 'required|integer|min:0',
            'logo_file'             => 'nullable|file|mimetypes:image/png,image/jpeg,image/webp,image/svg+xml|max:5120',
            'logo_url'              => 'nullable|string|max:500',
            'processed_logo_base64' => 'nullable|string',
        ]);

        if (!$request->filled('processed_logo_base64') && !$request->hasFile('logo_file') && !$request->filled('logo_url')) {
            return back()->withInput()->withErrors(['logo_file' => 'Harap unggah file logo atau masukkan link URL logo.']);
        }

        $logoPath = null;
        if ($request->filled('processed_logo_base64')) {
            $logoPath = $this->saveBase64TransparentLogo($request->processed_logo_base64);
        } elseif ($request->hasFile('logo_file')) {
            $uploadedPath = $request->file('logo_file')->store('partners', 'public');
            if ($request->boolean('auto_remove_bg', true)) {
                $processed = $this->removeWhiteBackgroundGd(Storage::disk('public')->path($uploadedPath));
                $logoPath = $processed ?: $uploadedPath;
            } else {
                $logoPath = $uploadedPath;
            }
        } elseif ($request->filled('logo_url')) {
            $logoPath = $this->normalizeLogoUrl($request->logo_url);
        }

        Partner::create([
            'nama'      => $request->nama,
            'logo'      => $logoPath,
            'urutan'    => $request->urutan,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil ditambahkan dengan logo transparan.');
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'nama'                  => 'required|string|max:150',
            'urutan'                => 'required|integer|min:0',
            'logo_file'             => 'nullable|file|mimetypes:image/png,image/jpeg,image/webp,image/svg+xml|max:5120',
            'logo_url'              => 'nullable|string|max:500',
            'processed_logo_base64' => 'nullable|string',
        ]);

        $logoPath = $partner->logo; // keep existing by default

        if ($request->filled('processed_logo_base64')) {
            // Hapus file lama jika lokal
            if ($partner->logo && !str_starts_with($partner->logo, 'http')) {
                Storage::disk('public')->delete($partner->logo);
            }
            $logoPath = $this->saveBase64TransparentLogo($request->processed_logo_base64);
        } elseif ($request->hasFile('logo_file')) {
            // delete old file if it's a stored path (not a URL)
            if ($partner->logo && !str_starts_with($partner->logo, 'http')) {
                Storage::disk('public')->delete($partner->logo);
            }
            $uploadedPath = $request->file('logo_file')->store('partners', 'public');
            if ($request->boolean('auto_remove_bg', true)) {
                $processed = $this->removeWhiteBackgroundGd(Storage::disk('public')->path($uploadedPath));
                $logoPath = $processed ?: $uploadedPath;
            } else {
                $logoPath = $uploadedPath;
            }
        } elseif ($request->filled('logo_url')) {
            // delete old file if replacing local file with URL
            if ($partner->logo && !str_starts_with($partner->logo, 'http')) {
                Storage::disk('public')->delete($partner->logo);
            }
            $logoPath = $this->normalizeLogoUrl($request->logo_url);
        }

        $partner->update([
            'nama'      => $request->nama,
            'logo'      => $logoPath,
            'urutan'    => $request->urutan,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil diperbarui dengan logo transparan.');
    }

    /**
     * Simpan gambar PNG transparan dari Data URL Base64 yang diproses oleh Canvas.
     */
    protected function saveBase64TransparentLogo(string $base64Data): ?string
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64Data)) {
            return null;
        }

        $data = substr($base64Data, strpos($base64Data, ',') + 1);
        $decoded = base64_decode($data);
        if ($decoded === false) {
            return null;
        }

        $filename = 'partners/' . uniqid('partner_trans_') . '.png';
        Storage::disk('public')->put($filename, $decoded);

        return $filename;
    }

    /**
     * Hapus background putih / terang pada gambar menggunakan library GD di server jika tersedia.
     */
    protected function removeWhiteBackgroundGd(string $filePath): ?string
    {
        if (!extension_loaded('gd') || !file_exists($filePath)) {
            return null;
        }

        $info = @getimagesize($filePath);
        if (!$info) return null;

        $mime = $info['mime'];
        $img = match($mime) {
            'image/jpeg' => @imagecreatefromjpeg($filePath),
            'image/png'  => @imagecreatefrompng($filePath),
            'image/webp' => @imagecreatefromwebp($filePath),
            default      => null
        };

        if (!$img) return null;

        $w = imagesx($img);
        $h = imagesy($img);

        // Canvas transparan
        $out = imagecreatetruecolor($w, $h);
        imagealphablending($out, false);
        imagesavealpha($out, true);
        $transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
        imagefill($out, 0, 0, $transparent);

        // Ambil warna 4 sudut untuk mendeteksi warna background
        $corners = [
            imagecolorat($img, 0, 0),
            imagecolorat($img, $w - 1, 0),
            imagecolorat($img, 0, $h - 1),
            imagecolorat($img, $w - 1, $h - 1)
        ];

        $rSum = 0; $gSum = 0; $bSum = 0;
        foreach ($corners as $c) {
            $rSum += ($c >> 16) & 0xFF;
            $gSum += ($c >> 8) & 0xFF;
            $bSum += $c & 0xFF;
        }
        $bgR = $rSum / 4;
        $bgG = $gSum / 4;
        $bgB = $bSum / 4;

        // Hanya proses jika background terang (putih / abu-abu muda)
        if ($bgR < 190 || $bgG < 190 || $bgB < 190) {
            imagedestroy($img);
            imagedestroy($out);
            return null;
        }

        $tolerance = 35;
        $visited = array_fill(0, $w * $h, false);
        $isBgPixel = array_fill(0, $w * $h, false);
        $queue = [];

        for ($x = 0; $x < $w; $x++) {
            $queue[] = [0, $x];
            $queue[] = [$h - 1, $x];
        }
        for ($y = 0; $y < $h; $y++) {
            $queue[] = [$y, 0];
            $queue[] = [$y, $w - 1];
        }

        while (!empty($queue)) {
            [$y, $x] = array_pop($queue);
            $pos = $y * $w + $x;
            if ($visited[$pos]) continue;
            $visited[$pos] = true;

            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $a = ($rgb >> 24) & 0x7F;

            $isNearWhite = ($a > 100) || ($r >= (255 - $tolerance) && $g >= (255 - $tolerance) && $b >= (255 - $tolerance)) ||
                           (sqrt(($r - $bgR)**2 + ($g - $bgG)**2 + ($b - $bgB)**2) <= ($tolerance * 1.5));

            if ($isNearWhite) {
                $isBgPixel[$pos] = true;
                if ($x > 0 && !$visited[$pos - 1]) $queue[] = [$y, $x - 1];
                if ($x < $w - 1 && !$visited[$pos + 1]) $queue[] = [$y, $x + 1];
                if ($y > 0 && !$visited[$pos - $w]) $queue[] = [$y - 1, $x];
                if ($y < $h - 1 && !$visited[$pos + $w]) $queue[] = [$y + 1, $x];
            }
        }

        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $pos = $y * $w + $x;
                if ($isBgPixel[$pos]) {
                    imagesetpixel($out, $x, $y, $transparent);
                } else {
                    $c = imagecolorat($img, $x, $y);
                    imagesetpixel($out, $x, $y, $c);
                }
            }
        }

        $newFilename = 'partners/' . uniqid('partner_trans_') . '.png';
        $savePath = Storage::disk('public')->path($newFilename);
        imagepng($out, $savePath);
        imagedestroy($img);
        imagedestroy($out);

        // Hapus file asli upload karena digantikan versi transparan
        @unlink($filePath);

        return $newFilename;
    }

    /**
     * Normalisasi URL logo agar selalu valid dan kompatibel.
     */
    protected function normalizeLogoUrl(?string $url): ?string
    {
        if (!$url) return null;
        $url = trim($url);

        // Jika link clearbit lama dimasukkan, otomatis dialihkan ke unavatar
        if (str_contains($url, 'clearbit.com/')) {
            $domain = ltrim(parse_url($url, PHP_URL_PATH), '/');
            return 'https://unavatar.io/' . $domain;
        }

        // Jika user hanya memasukkan domain web (misal "indosatooredoo.com" tanpa http)
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            if (str_contains($url, '.') && !str_contains($url, ' ')) {
                return 'https://unavatar.io/' . trim($url, '/');
            }
            $url = 'https://' . $url;
        }

        return $url;
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);

        // delete uploaded file if it's local
        if ($partner->logo && !str_starts_with($partner->logo, 'http')) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil dihapus.');
    }

    /**
     * AJAX endpoint: update display order
     */
    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);

        foreach ($request->order as $pos => $id) {
            Partner::where('id', $id)->update(['urutan' => $pos + 1]);
        }

        return response()->json(['success' => true]);
    }
}
