<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'source_name',
        'source_url',
        'tanggal',
        'gambar',
        'ringkasan',
        'isi_artikel',
        'is_active',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Scope query untuk hanya mengambil artikel aktif yang telah terbit.
     */
    public function scopePublished($query)
    {
        return $query->where('is_active', true)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Helper URL gambar (upload lokal vs eksternal URL).
     */
    public function getImageUrlAttribute()
    {
        if (!$this->gambar) {
            return '';
        }
        if (str_starts_with($this->gambar, 'http://') || str_starts_with($this->gambar, 'https://')) {
            return $this->gambar;
        }
        return asset('storage/' . $this->gambar);
    }
}
