<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TentangKami extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'visi',
        'misi',
        'arah_gerak',
        'video_url',
        'video_description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
