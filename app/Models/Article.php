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
     * Otomatis mempublikasikan artikel yang telah melewati jadwal publikasi (WIB).
     */
    public static function autoPublishOverdue(): int
    {
        try {
            $now = now('Asia/Jakarta');
            $articles = static::where('status', 'scheduled')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', $now)
                ->get();

            $count = 0;
            foreach ($articles as $article) {
                $article->update([
                    'status' => 'published',
                ]);
                $count++;
            }
            return $count;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Scope query untuk hanya mengambil artikel aktif yang telah terbit.
     */
    public function scopePublished($query)
    {
        static::autoPublishOverdue();

        $query->where('is_active', true);

        if (\Illuminate\Support\Facades\Schema::hasColumn('articles', 'status')) {
            $query->where(function ($q) {
                $q->where('status', 'published')
                  ->orWhere(function ($sub) {
                      $sub->where('status', 'scheduled')
                          ->whereNotNull('published_at')
                          ->where('published_at', '<=', now('Asia/Jakarta'));
                  });
            });
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('articles', 'published_at')) {
            $query->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now('Asia/Jakarta'));
            });
        }

        return $query;
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

    /**
     * Helper tanggal terformat d M Y.
     */
    public function getFormattedDateAttribute()
    {
        if (!empty($this->published_at)) {
            return $this->published_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y');
        }
        if (!empty($this->tanggal)) {
            $dateStr = str_ireplace(
                ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
                ['January','February','March','April','May','June','July','August','September','October','November','December'],
                $this->tanggal
            );
            try {
                return \Carbon\Carbon::parse($dateStr)->locale('id')->translatedFormat('d M Y');
            } catch (\Throwable $e) {
                return $this->tanggal;
            }
        }
        return $this->created_at ? $this->created_at->locale('id')->translatedFormat('d M Y') : '';
    }

    /**
     * Helper estimasi waktu baca dari JSON isi_artikel.
     */
    public function getReadTimeAttribute()
    {
        $isi = json_decode($this->isi_artikel, true);
        return is_array($isi) ? ($isi['readTime'] ?? '') : '';
    }
}

