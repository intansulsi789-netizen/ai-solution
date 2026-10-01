<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsAbout extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'nama_ceo',
        'jabatan_ceo',
        'deskripsi_ceo',
        'foto_ceo',
    ];
}
