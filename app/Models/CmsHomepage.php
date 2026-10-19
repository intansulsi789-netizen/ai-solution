<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsHomepage extends Model
{
    protected $fillable = [
        'site_logo',
        'site_logo_type',
        'site_name_highlight',
        'site_name_rest',
        'hero_badge',
        'hero_title',
        'hero_description',
        'hero_background',
        'hero_btn1_text',
        'hero_btn1_link',
        'hero_btn2_text',
        'hero_btn2_link',
        'partner_title',
    ];

    /**
     * Dapatkan URL penuh untuk logo website (jika ada).
     */
    public function getSiteLogoUrlAttribute(): ?string
    {
        if (!$this->site_logo) {
            return null;
        }

        if (str_starts_with($this->site_logo, 'http://') || str_starts_with($this->site_logo, 'https://')) {
            return $this->site_logo;
        }

        return asset('storage/' . $this->site_logo);
    }

    /**
     * Nama website lengkap.
     */
    public function getSiteNameFullAttribute(): string
    {
        $highlight = $this->site_name_highlight ?? 'AI';
        $rest = $this->site_name_rest ?? 'Jogja Solutions';
        return trim($highlight . ' ' . $rest);
    }
}
