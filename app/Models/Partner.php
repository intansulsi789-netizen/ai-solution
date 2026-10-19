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
