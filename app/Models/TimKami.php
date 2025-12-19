<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimKami extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'name',
        'position',
        'photo',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
}
