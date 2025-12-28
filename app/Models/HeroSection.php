<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'background_image',
        'logo_image',
        'title',
        'subtitle',
        'tagline',
        'cta_text_1',
        'cta_text_2',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
