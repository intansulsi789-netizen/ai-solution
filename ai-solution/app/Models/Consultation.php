<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'whatsapp',
        'kebutuhan',
        'is_read',
    ];
}
