<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'nomor',
        'nama_layanan',
        'slug',
        'image',
        'deskripsi_singkat',
        'deskripsi_lengkap',
        'icon',
        'is_active',
    ];
}
