<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleImageService
{
    /**
     * Maksimal ukuran file gambar yang diizinkan untuk diunduh (5MB).
     */
    const MAX_IMAGE_BYTES = 5242880; // 5 MB

    /**
     * Timeout request HTTP (detik).
     */
    const HTTP_TIMEOUT = 6;

    /**
     * Ekstensi file gambar yang diizinkan.
     */
    protected static array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    /**
     * MIME types gambar yang diizinkan.
     */
    protected static array $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
    ];

    /**
     * Periksa keamanan URL dan cegah SSRF (Server-Side Request Forgery).
     * Memastikan URL tidak mengarah ke IP privat, loopback, link-local, atau metadata server.
     */
    public static function validateUrlSafety(string $url): array
    {
        $url = trim($url);

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return ['safe' => false, 'error' => 'Format URL tidak valid.'];
        }

        $parts = parse_url($url);
        if (!$parts || empty($parts['scheme']) || empty($parts['host'])) {
            return ['safe' => false, 'error' => 'URL harus memiliki protokol dan nama domain yang valid.'];
        }

        $scheme = strtolower($parts['scheme']);
        if (!in_array($scheme, ['http', 'https'], true)) {
            return ['safe' => false, 'error' => 'Hanya protokol HTTP dan HTTPS yang diizinkan.'];
        }

        // Larang kredensial di dalam URL (misal http://user:pass@domain)
        if (!empty($parts['user']) || !empty($parts['pass'])) {
            return ['safe' => false, 'error' => 'URL tidak boleh mengandung informasi autentikasi pengguna.'];
        }

        // Port harus default (80/443) jika ada
        if (!empty($parts['port']) && !in_array((int)$parts['port'], [80, 443, 8080], true)) {
            return ['safe' => false, 'error' => 'Port jaringan khusus tidak diizinkan demi keamanan.'];
        }

        $host = strtolower($parts['host']);

        // Blokir domain internal umum
        $blockedHosts = [
            'localhost',
            '127.0.0.1',
            '0.0.0.0',
            'metadata.google.internal',
            'instance-data',
        ];
        if (in_array($host, $blockedHosts, true) ||
            str_ends_with($host, '.localhost') ||
            str_ends_with($host, '.local') ||
            str_ends_with($host, '.internal') ||
            str_ends_with($host, '.test') ||
            str_ends_with($host, '.lan')) {
            return ['safe' => false, 'error' => 'Akses ke domain lokal atau internal tidak diizinkan.'];
        }

        // Resolusi DNS untuk memeriksa alamat IP fisik host
        $ips = @gethostbynamel($host);
        if (!$ips || empty($ips)) {
            // Coba gethostbyname jika gethostbynamel kosong
            $singleIp = @gethostbyname($host);
            if ($singleIp && $singleIp !== $host) {
                $ips = [$singleIp];
            } else {
                return ['safe' => false, 'error' => 'Alamat host tidak dapat ditemukan atau tidak memiliki catatan DNS yang valid.'];
            }
        }

        foreach ($ips as $ip) {
            if (static::isPrivateOrReservedIp($ip)) {
                return ['safe' => false, 'error' => 'Akses ke alamat IP lokal, privat, atau rentan SSRF ditolak.'];
            }
        }

        return ['safe' => true, 'error' => null];
    }

    /**
     * Periksa apakah alamat IP merupakan rentang privat, loopback, link-local, atau khusus.
     */
    public static function isPrivateOrReservedIp(string $ip): bool
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return true;
        }

        // Filter standar PHP untuk private & reserved
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return true;
        }

        $long = ip2long($ip);
        if ($long === false) {
            return true;
        }

        // 127.0.0.0/8 (Loopback)
        if (($long & 0xFF000000) === 0x7F000000) return true;
        // 0.0.0.0/8 (Current network)
        if (($long & 0xFF000000) === 0x00000000) return true;
        // 10.0.0.0/8 (Private network)
        if (($long & 0xFF000000) === 0x0A000000) return true;
        // 169.254.0.0/16 (Link-local / Cloud metadata AWS/GCP)
        if (($long & 0xFFFF0000) === 0xA9FE0000) return true;
        // 172.16.0.0/12 (Private network)
        if (($long & 0xFFF00000) === 0xAC100000) return true;
        // 192.168.0.0/16 (Private network)
        if (($long & 0xFFFF0000) === 0xC0A80000) return true;
        // 100.64.0.0/10 (Carrier-grade NAT)
        if (($long & 0xFFC00000) === 0x64400000) return true;
        // 224.0.0.0/4 (Multicast)
        if (($long & 0xF0000000) === 0xE0000000) return true;
        // 240.0.0.0/4 (Reserved)
        if (($long & 0xF0000000) === 0xF0000000) return true;

        return false;
    }

    /**
     * Cek apakah URL merupakan URL pencarian Google Images.
     */
    public static function isGoogleImagesUrl(string $url): bool
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        $query = parse_url($url, PHP_URL_QUERY) ?? '';

        $isGoogleDomain = (bool) preg_match('/(^|\.)google\.[a-z.]{2,}$/i', $host);
        if ($isGoogleDomain && (str_contains($query, 'imgurl=') || str_contains($query, 'imgrefurl='))) {
            return true;
        }

        return false;
    }

    /**
     * Ekstrak URL gambar langsung dari tautan Google Images.
     */
    public static function extractGoogleImageUrl(string $url): ?string
    {
        $query = parse_url($url, PHP_URL_QUERY) ?? '';
        parse_str($query, $params);

        if (!empty($params['imgurl'])) {
            $extracted = urldecode($params['imgurl']);
            if (filter_var($extracted, FILTER_VALIDATE_URL)) {
                return $extracted;
            }
        }

        return null;
    }

    /**
     * Cek apakah URL berasal dari ekosistem Pinterest.
     */
    public static function isPinterestUrl(string $url): bool
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        return str_ends_with($host, 'pinterest.com') ||
               str_ends_with($host, 'pin.it') ||
               str_ends_with($host, 'pinimg.com');
    }

    /**
     * Cek apakah URL langsung mengarah ke file gambar berdasarkan ekstensi atau CDN umum.
     */
    public static function isDirectImageUrl(string $url): bool
    {
        $path = strtolower(parse_url($url, PHP_URL_PATH) ?? '');
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

        // Cek ekstensi file gambar di path
        if (preg_match('/\.(jpg|jpeg|png|webp|gif|avif)(\?.*)?$/i', $path)) {
            return true;
        }

        // Cek CDN gambar populer yang menyajikan stream gambar langsung
        if (str_contains($host, 'images.unsplash.com') ||
            str_contains($host, 'i.pinimg.com') ||
            str_contains($host, 'picsum.photos') ||
            str_contains($host, 'via.placeholder.com') ||
            str_contains($host, 'dummyimage.com') ||
            str_contains($host, 'imgur.com') && str_contains($path, '.')) {
            return true;
        }

        return false;
    }

    /**
     * Kenali, proses, dan dapatkan URL gambar langsung dari berbagai sumber (Google Images, Pinterest, atau Halaman Web).
     */
    public static function resolveUrl(string $inputUrl): array
    {
        $inputUrl = trim($inputUrl);

        // 1. Validasi keamanan awal
        $safety = static::validateUrlSafety($inputUrl);
        if (!$safety['safe']) {
            return [
                'success'     => false,
                'image_url'   => null,
                'source_type' => 'invalid',
                'message'     => $safety['error'],
            ];
        }

        // 2. Jika tautan berasal dari Google Images
        if (static::isGoogleImagesUrl($inputUrl)) {
            $extracted = static::extractGoogleImageUrl($inputUrl);
            if ($extracted) {
                // Validasi ulang keamanan URL hasil ekstraksi
                $extSafety = static::validateUrlSafety($extracted);
                if ($extSafety['safe']) {
                    return [
                        'success'     => true,
                        'image_url'   => $extracted,
                        'source_type' => 'google_images',
                        'message'     => '✓ URL gambar asli berhasil diekstrak dari Google Images.',
                    ];
                }
            }
            return [
                'success'     => false,
                'image_url'   => null,
                'source_type' => 'google_images',
                'message'     => 'Tidak dapat menemukan parameter gambar asli (imgurl) dari tautan Google Images tersebut.',
            ];
        }

        // 3. Jika tautan gambar langsung (Direct Image URL)
        if (static::isDirectImageUrl($inputUrl)) {
            try {
                $headResp = \Illuminate\Support\Facades\Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept'     => 'image/webp,image/apng,image/*,*/*;q=0.8',
                ])->timeout(4)->head($inputUrl);

                if ($headResp->status() === 404 || $headResp->status() === 410 || $headResp->serverError()) {
                    return [
                        'success'     => false,
                        'image_url'   => null,
                        'source_type' => 'direct',
                        'message'     => 'Gambar tidak ditemukan di server sumber (HTTP ' . $headResp->status() . '). Periksa kembali URL gambar.',
                    ];
                }
            } catch (\Throwable $e) {
                // Abaikan jika HEAD tidak didukung host atau timeout ringan
            }

            return [
                'success'     => true,
                'image_url'   => $inputUrl,
                'source_type' => 'direct',
                'message'     => '✓ Tautan gambar langsung valid.',
            ];
        }

        // 4. Jika tautan Pinterest (Pin atau pin.it)
        if (static::isPinterestUrl($inputUrl)) {
            return static::resolvePinterestUrl($inputUrl);
        }

        // 5. Jika tautan halaman web umum (Coba ekstrak og:image atau twitter:image)
        return static::resolveWebpageOgImage($inputUrl);
    }

    /**
     * Resolusi URL Pin Pinterest melalui oEmbed resmi atau ekstraksi Open Graph.
     */
    protected static function resolvePinterestUrl(string $pinterestUrl): array
    {
        // Jika tautan pin.it (shortlink), ikuti redirect untuk mendapatkan URL asli
        $targetUrl = $pinterestUrl;
        if (str_contains(strtolower(parse_url($pinterestUrl, PHP_URL_HOST) ?? ''), 'pin.it')) {
            $expanded = static::followRedirectsSafely($pinterestUrl, 3);
            if ($expanded) {
                $targetUrl = $expanded;
            }
        }

        // 1. Coba oEmbed resmi Pinterest
        try {
            $oembedEndpoint = 'https://www.pinterest.com/oembed.json?url=' . urlencode($targetUrl);
            $oembedSafety = static::validateUrlSafety($oembedEndpoint);
            if ($oembedSafety['safe']) {
                $responseBody = static::fetchHttpContent($oembedEndpoint, 4);
                if ($responseBody) {
                    $json = json_decode($responseBody, true);
                    if (!empty($json['thumbnail_url'])) {
                        $thumb = $json['thumbnail_url'];
                        // Tingkatkan resolusi thumbnail Pinterest jika memungkinkan
                        $highRes = preg_replace('/\/(150x150|236x)\//', '/736x/', $thumb);
                        return [
                            'success'     => true,
                            'image_url'   => $highRes ?: $thumb,
                            'source_type' => 'pinterest',
                            'message'     => '✓ Gambar berhasil ditemukan dari Pinterest (oEmbed).',
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            // Lanjut ke fallback OpenGraph jika oEmbed gagal
        }

        // 2. Fallback: Ambil dari Open Graph tag halaman Pin
        $ogResult = static::resolveWebpageOgImage($targetUrl);
        if ($ogResult['success']) {
            $ogResult['source_type'] = 'pinterest';
            $ogResult['message']     = '✓ Gambar banner berhasil ditemukan dari halaman Pinterest.';
            return $ogResult;
        }

        return [
            'success'     => false,
            'image_url'   => null,
            'source_type' => 'pinterest',
            'message'     => 'Tidak dapat mengambil gambar dari tautan Pinterest ini (mungkin memerlukan login atau akses dibatasi). Disarankan klik kanan gambar pada Pinterest lalu pilih "Salin tautan gambar" (Copy image address).',
        ];
    }

    /**
     * Ekstrak og:image atau twitter:image dari halaman web publik.
     */
    protected static function resolveWebpageOgImage(string $pageUrl): array
    {
        try {
            $html = static::fetchHttpContent($pageUrl, static::HTTP_TIMEOUT, 1048576); // Max 1MB HTML
            if (!$html) {
                return [
                    'success'     => false,
                    'image_url'   => null,
                    'source_type' => 'webpage',
                    'message'     => 'Halaman web tidak merespons atau tidak dapat diakses secara publik.',
                ];
            }

            $imageUrl = null;

            // Cari meta property="og:image"
            if (preg_match('/<meta[^>]+property=[\'"](?:og:image|og:image:url)[\'"][^>]+content=[\'"]([^\'"]+)[\'"]/i', $html, $matches) ||
                preg_match('/<meta[^>]+content=[\'"]([^\'"]+)[\'"][^>]+property=[\'"](?:og:image|og:image:url)[\'"]/i', $html, $matches)) {
                $imageUrl = html_entity_decode(trim($matches[1]));
            } elseif (preg_match('/<meta[^>]+name=[\'"]twitter:image[\'"][^>]+content=[\'"]([^\'"]+)[\'"]/i', $html, $matches) ||
                      preg_match('/<meta[^>]+content=[\'"]([^\'"]+)[\'"][^>]+name=[\'"]twitter:image[\'"]/i', $html, $matches)) {
                $imageUrl = html_entity_decode(trim($matches[1]));
            }

            if ($imageUrl) {
                // Tangani URL relatif jika ada
                if (str_starts_with($imageUrl, '//')) {
                    $imageUrl = 'https:' . $imageUrl;
                } elseif (str_starts_with($imageUrl, '/')) {
                    $parsed = parse_url($pageUrl);
                    $imageUrl = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? '') . $imageUrl;
                }

                $safety = static::validateUrlSafety($imageUrl);
                if ($safety['safe']) {
                    return [
                        'success'     => true,
                        'image_url'   => $imageUrl,
                        'source_type' => 'webpage',
                        'message'     => '✓ Gambar banner berhasil ditemukan dari metadata halaman web (Open Graph).',
                    ];
                }
            }

            return [
                'success'     => false,
                'image_url'   => null,
                'source_type' => 'webpage',
                'message'     => 'Tidak ditemukan metadata gambar publik (og:image) pada halaman tersebut. Gunakan tautan gambar langsung.',
            ];
        } catch (\Throwable $e) {
            return [
                'success'     => false,
                'image_url'   => null,
                'source_type' => 'webpage',
                'message'     => 'Gagal memproses halaman sumber: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Unduh gambar secara aman (terproteksi SSRF), validasi tipe data dan ukuran, lalu simpan ke disk public.
     * Mengembalikan array ['success' => bool, 'path' => ?string, 'url' => ?string, 'error' => ?string].
     */
    public static function downloadAndStore(string $imageUrl): array
    {
        $safety = static::validateUrlSafety($imageUrl);
        if (!$safety['safe']) {
            return [
                'success' => false,
                'path'    => null,
                'url'     => null,
                'error'   => $safety['error'],
            ];
        }

        try {
            $content = static::fetchHttpContent($imageUrl, static::HTTP_TIMEOUT, static::MAX_IMAGE_BYTES);
            if (!$content || strlen($content) === 0) {
                return [
                    'success' => false,
                    'path'    => null,
                    'url'     => null,
                    'error'   => 'Gambar tidak dapat diunduh atau respons kosong.',
                ];
            }

            if (strlen($content) > static::MAX_IMAGE_BYTES) {
                return [
                    'success' => false,
                    'path'    => null,
                    'url'     => null,
                    'error'   => 'Ukuran gambar melebihi batas maksimal 5MB.',
                ];
            }

            // Validasi integritas gambar nyata via getimagesizefromstring
            $imageInfo = @getimagesizefromstring($content);
            if (!$imageInfo || empty($imageInfo['mime'])) {
                return [
                    'success' => false,
                    'path'    => null,
                    'url'     => null,
                    'error'   => 'Data yang diunduh bukan merupakan format gambar yang valid.',
                ];
            }

            $mime = strtolower($imageInfo['mime']);
            if (!in_array($mime, static::$allowedMimes, true)) {
                return [
                    'success' => false,
                    'path'    => null,
                    'url'     => null,
                    'error'   => "Tipe gambar ({$mime}) tidak didukung. Gunakan JPG, PNG, WEBP, atau GIF.",
                ];
            }

            $ext = match ($mime) {
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif',
                default      => 'jpg',
            };

            $filename = 'articles/' . uniqid('banner_') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            Storage::disk('public')->put($filename, $content);

            return [
                'success' => true,
                'path'    => $filename,
                'url'     => asset('storage/' . $filename),
                'error'   => null,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'path'    => null,
                'url'     => null,
                'error'   => 'Terjadi kesalahan saat mengunduh gambar: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Ikuti redirect URL secara aman dengan batas iterasi dan validasi SSRF di setiap hop.
     */
    protected static function followRedirectsSafely(string $url, int $maxRedirects = 3): ?string
    {
        $currentUrl = $url;
        for ($i = 0; $i < $maxRedirects; $i++) {
            $safety = static::validateUrlSafety($currentUrl);
            if (!$safety['safe']) {
                return null;
            }

            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AI-Solution-Bot/1.0',
                ])->withoutRedirecting()->timeout(3)->head($currentUrl);

                $location = $response->header('Location');
                if ($location) {
                    if (str_starts_with($location, '/')) {
                        $parsed = parse_url($currentUrl);
                        $currentUrl = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? '') . $location;
                    } else {
                        $currentUrl = $location;
                    }
                } else {
                    return $currentUrl;
                }
            } catch (\Throwable $e) {
                return $currentUrl;
            }
        }

        return $currentUrl;
    }

    /**
     * Mengambil konten HTTP dengan batas waktu dan proteksi ukuran maksimal.
     */
    protected static function fetchHttpContent(string $url, int $timeout = 5, int $maxBytes = 5242880): ?string
    {
        $safety = static::validateUrlSafety($url);
        if (!$safety['safe']) {
            return null;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml,image/webp,image/apng,image/*,*/*;q=0.8',
                'Accept-Language' => 'id,en;q=0.9',
            ])->timeout($timeout)->get($url);

            if (!$response->successful()) {
                return null;
            }

            $body = $response->body();
            if (strlen($body) > $maxBytes) {
                return substr($body, 0, $maxBytes);
            }

            return $body;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
