<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsContact extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'teks_tombol',
        'link_tombol',
        'whatsapp',
        'email',
        'instagram',
        'linkedin',
        'youtube',
        'tiktok',
        'alamat',
    ];
}
