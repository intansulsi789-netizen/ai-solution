<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'tanggal',
        'gambar',
        'ringkasan',
        'isi_artikel',
        'is_active',
        'status',
    ];
}
