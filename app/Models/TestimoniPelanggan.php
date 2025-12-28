<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestimoniPelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'customer_name',
        'name',
        'customer_role',
        'role',
        'testimonial',
        'rating',
        'photo',
        'image',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
        'order' => 'integer',
    ];
}
