<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Menyajikan versi kecil (WebP) dari gambar di disk "public".
 *
 * Gambar yang diunggah bisa berukuran puluhan megapiksel, padahal hanya tampil kecil
 * (card artikel, logo partner). Browser tetap harus men-decode resolusi penuhnya dan
 * itu membekukan halaman saat gambar masuk layar. Thumbnail dibuat sekali, disimpan di
 * storage/app/public/thumbs/..., lalu dipakai ulang. File asli tidak pernah diubah.
 *
 * Jika GD tidak tersedia, file bukan milik storage lokal, atau proses gagal,
 * URL asli dikembalikan apa adanya.
 */
class ImageThumbnail
{
    /** Batas piksel sumber yang masih wajar di-decode GD (~4 byte/piksel). */
    private const MAX_SOURCE_PIXELS = 70_000_000;

    /** Gambar yang sudah kecil (dimensi & ukuran file) dipakai langsung tanpa thumbnail. */
    private const SMALL_FILE_BYTES = 150 * 1024;

    /**
     * @param  string|null  $src  Path relatif disk public ("articles/x.webp"), "storage/..." atau URL penuh ke /storage milik situs ini.
     * @param  int  $maxWidth  Lebar maksimum thumbnail (px).
     * @param  int  $maxHeight  Tinggi maksimum thumbnail (px), 0 = tanpa batas.
     */
    public static function url(?string $src, int $maxWidth, int $maxHeight = 0): ?string
    {
        if (! $src) {
            return $src;
        }

        $path = self::toStoragePath($src);
        if ($path === null) {
            return $src; // URL eksternal atau path tidak valid
        }

        $original = asset('storage/'.$path);
        $thumb = self::thumbPath($path, $maxWidth, $maxHeight);
        $disk = Storage::disk('public');

        try {
            if ($disk->exists($thumb)) {
                return asset('storage/'.$thumb);
            }
            if ($disk->exists($thumb.'.skip')) {
                return $original;
            }

            return self::generate($path, $thumb, $maxWidth, $maxHeight)
                ? asset('storage/'.$thumb)
                : $original;
        } catch (\Throwable $e) {
            Log::warning('ImageThumbnail gagal: '.$e->getMessage(), ['path' => $path]);

            return $original;
        }
    }

    private static function toStoragePath(string $src): ?string
    {
        if (preg_match('#^https?://#i', $src)) {
            $base = rtrim(asset('storage'), '/').'/';
            if (! str_starts_with($src, $base)) {
                return null;
            }
            $path = rawurldecode(substr($src, strlen($base)));
        } else {
            $path = ltrim($src, '/');
            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, strlen('storage/'));
            }
        }

        $path = strtok($path, '?#');
        if ($path === false || $path === '' || str_contains($path, '..') || str_starts_with($path, 'thumbs/')) {
            return null;
        }

        return $path;
    }

    private static function thumbPath(string $path, int $maxWidth, int $maxHeight): string
    {
        $dir = trim(pathinfo($path, PATHINFO_DIRNAME), './');
        $name = pathinfo($path, PATHINFO_FILENAME);

        return 'thumbs/'.$maxWidth.'x'.$maxHeight.'/'.($dir !== '' ? $dir.'/' : '').$name.'.webp';
    }

    private static function generate(string $path, string $thumb, int $maxWidth, int $maxHeight): bool
    {
        if (! function_exists('imagecreatetruecolor') || ! function_exists('imagewebp')) {
            return false; // GD belum terpasang: jangan tandai skip, coba lagi setelah GD ada
        }

        $disk = Storage::disk('public');
        $abs = $disk->path($path);
        $info = is_file($abs) ? @getimagesize($abs) : false;
        $decoders = [
            IMAGETYPE_JPEG => 'imagecreatefromjpeg',
            IMAGETYPE_PNG => 'imagecreatefrompng',
            IMAGETYPE_WEBP => 'imagecreatefromwebp',
            IMAGETYPE_GIF => 'imagecreatefromgif',
        ];

        if (! $info || ! isset($decoders[$info[2]]) || ! function_exists($decoders[$info[2]])) {
            return self::markSkip($thumb);
        }

        [$w, $h] = $info;
        if ($w < 1 || $h < 1 || $w * $h > self::MAX_SOURCE_PIXELS) {
            return self::markSkip($thumb);
        }

        // EXIF orientation (foto HP) agar thumbnail tidak terputar
        $orientation = 1;
        if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif = @exif_read_data($abs);
            $orientation = (int) ($exif['Orientation'] ?? 1);
        }
        $rotated = in_array($orientation, [5, 6, 7, 8], true);
        $srcW = $rotated ? $h : $w;
        $srcH = $rotated ? $w : $h;

        $scale = min(1, $maxWidth / $srcW, $maxHeight > 0 ? $maxHeight / $srcH : 1);
        if ($scale >= 1 && $orientation === 1 && filesize($abs) <= self::SMALL_FILE_BYTES) {
            return self::markSkip($thumb); // sudah kecil, pakai asli
        }

        $prevLimit = ini_get('memory_limit');
        ini_set('memory_limit', max(256, (int) ceil($w * $h * 6 / 1048576) + 96).'M');

        $src = $dst = null;
        try {
            $src = @$decoders[$info[2]]($abs);
            if (! $src) {
                return self::markSkip($thumb);
            }
            if (! imageistruecolor($src)) {
                imagepalettetotruecolor($src);
            }

            $src = self::applyOrientation($src, $orientation);

            $nw = max(1, (int) round($srcW * $scale));
            $nh = max(1, (int) round($srcH * $scale));
            $dst = imagecreatetruecolor($nw, $nh);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, imagesx($src), imagesy($src));

            $disk->makeDirectory(dirname($thumb));
            $target = $disk->path($thumb);
            $tmp = $target.'.'.getmypid().'.tmp';
            if (! imagewebp($dst, $tmp, 82)) {
                @unlink($tmp);

                return false;
            }

            return rename($tmp, $target);
        } finally {
            if ($src) {
                imagedestroy($src);
            }
            if ($dst) {
                imagedestroy($dst);
            }
            ini_set('memory_limit', $prevLimit);
        }
    }

    private static function applyOrientation(\GdImage $img, int $orientation): \GdImage
    {
        // Orientasi bercermin (2,4,5,7) = flip horizontal + rotasi; sudut GD berlawanan jarum jam.
        if (in_array($orientation, [2, 4, 5, 7], true)) {
            imageflip($img, IMG_FLIP_HORIZONTAL);
        }
        $angle = match ($orientation) {
            3, 4 => 180,
            6, 7 => -90,
            5, 8 => 90,
            default => 0,
        };
        if ($angle !== 0) {
            $rotated = imagerotate($img, $angle, 0);
            if ($rotated) {
                imagedestroy($img);

                return $rotated;
            }
        }

        return $img;
    }

    private static function markSkip(string $thumb): bool
    {
        $disk = Storage::disk('public');
        $disk->makeDirectory(dirname($thumb));
        $disk->put($thumb.'.skip', '');

        return false;
    }
}
