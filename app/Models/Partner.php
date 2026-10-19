<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'nama',
        'logo',
        'urutan',
        'is_active',
    ];

    /**
     * Dapatkan URL logo yang valid (otomatis mengganti Clearbit yang sudah tidak aktif ke Unavatar/Google).
     */
    public function getResolvedLogoAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        // Pastikan brand populer yang memiliki SVG resmi transparan langsung digunakan
        $namaLower = strtolower($this->nama ?? '');
        $logoLower = strtolower($this->logo);

        if (str_contains($namaLower, 'gojek') || str_contains($logoLower, 'gojek')) {
            return 'https://cdn.simpleicons.org/gojek';
        }
        if (str_contains($namaLower, 'shopee') || str_contains($logoLower, 'shopee')) {
            return 'https://cdn.simpleicons.org/shopee';
        }
        if (str_contains($namaLower, 'bukalapak') || str_contains($logoLower, 'bukalapak')) {
            return 'https://cdn.simpleicons.org/bukalapak';
        }

        if (str_contains($this->logo, 'clearbit.com/')) {
            $domain = ltrim(parse_url($this->logo, PHP_URL_PATH), '/');
            return 'https://unavatar.io/' . $domain;
        }

        return $this->logo;
    }

    /**
     * Dapatkan URL lengkap untuk tag <img src="..."> baik file lokal maupun URL web.
     */
    public function getLogoSrcAttribute(): ?string
    {
        $logo = $this->resolved_logo;
        if (!$logo) {
            return null;
        }

        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) {
            return $logo;
        }

        return asset('storage/' . $logo);
    }
}
