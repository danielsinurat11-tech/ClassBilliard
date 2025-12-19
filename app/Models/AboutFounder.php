<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutFounder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'name',
        'description',
        'photo',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
