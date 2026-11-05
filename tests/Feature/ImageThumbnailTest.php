<?php

namespace Tests\Feature;

use App\Services\ImageThumbnail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageThumbnailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('GD dengan dukungan WebP tidak tersedia.');
        }

        Storage::fake('public');
    }

    private function putJpeg(string $path, int $w, int $h): void
    {
        $img = imagecreatetruecolor($w, $h);
        for ($i = 0; $i < 200; $i++) { // isi acak supaya file tidak terlalu kecil
            imagefilledrectangle($img, random_int(0, $w), random_int(0, $h), random_int(0, $w), random_int(0, $h), random_int(0, 0xFFFFFF));
        }
        ob_start();
        imagejpeg($img, null, 95);
        Storage::disk('public')->put($path, ob_get_clean());
        imagedestroy($img);
    }

    public function test_large_image_is_downscaled_to_webp_thumbnail(): void
    {
        $this->putJpeg('articles/big.jpg', 3000, 2000);

        $url = ImageThumbnail::url('articles/big.jpg', 900);

        $this->assertSame(asset('storage/thumbs/900x0/articles/big.webp'), $url);
        $size = getimagesize(Storage::disk('public')->path('thumbs/900x0/articles/big.webp'));
        $this->assertSame([900, 600], [$size[0], $size[1]]);
        $this->assertSame('image/webp', $size['mime']);
    }

    public function test_full_storage_url_is_mapped_and_thumbnail_is_reused(): void
    {
        $this->putJpeg('articles/a.jpg', 2000, 2000);
        $full = asset('storage/articles/a.jpg');

        $first = ImageThumbnail::url($full, 900);
        $mtime = filemtime(Storage::disk('public')->path('thumbs/900x0/articles/a.webp'));
        $second = ImageThumbnail::url($full, 900);

        $this->assertSame(asset('storage/thumbs/900x0/articles/a.webp'), $first);
        $this->assertSame($first, $second);
        $this->assertSame($mtime, filemtime(Storage::disk('public')->path('thumbs/900x0/articles/a.webp')));
    }

    public function test_transparent_logo_keeps_alpha_and_fits_box(): void
    {
        $img = imagecreatetruecolor(1600, 1600);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        imagefill($img, 0, 0, imagecolorallocatealpha($img, 0, 0, 0, 127));
        imagefilledellipse($img, 800, 800, 900, 900, imagecolorallocatealpha($img, 200, 0, 0, 0));
        ob_start();
        imagepng($img);
        Storage::disk('public')->put('partners/logo.png', ob_get_clean());
        imagedestroy($img);

        $url = ImageThumbnail::url('partners/logo.png', 320, 120);

        $this->assertSame(asset('storage/thumbs/320x120/partners/logo.webp'), $url);
        $thumb = imagecreatefromwebp(Storage::disk('public')->path('thumbs/320x120/partners/logo.webp'));
        $this->assertSame([120, 120], [imagesx($thumb), imagesy($thumb)]);
        $corner = imagecolorsforindex($thumb, imagecolorat($thumb, 0, 0));
        $this->assertSame(127, $corner['alpha']);
    }

    public function test_small_image_keeps_original_url(): void
    {
        $this->putJpeg('partners/tiny.jpg', 200, 80);

        $this->assertSame(asset('storage/partners/tiny.jpg'), ImageThumbnail::url('partners/tiny.jpg', 320, 120));
        $this->assertFalse(Storage::disk('public')->exists('thumbs/320x120/partners/tiny.webp'));
    }

    public function test_external_missing_and_unsafe_sources_are_left_alone(): void
    {
        $this->assertSame('https://cdn.example.com/x.jpg', ImageThumbnail::url('https://cdn.example.com/x.jpg', 900));
        $this->assertNull(ImageThumbnail::url(null, 900));
        $this->assertSame('../secret.png', ImageThumbnail::url('../secret.png', 900));
        $this->assertSame(asset('storage/articles/missing.jpg'), ImageThumbnail::url('articles/missing.jpg', 900));
    }
}
