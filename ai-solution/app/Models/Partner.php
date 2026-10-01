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
}
