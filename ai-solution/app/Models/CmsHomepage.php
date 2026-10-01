<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsHomepage extends Model
{
    protected $fillable = [
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
}
