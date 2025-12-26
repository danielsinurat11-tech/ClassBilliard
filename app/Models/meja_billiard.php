<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class meja_billiard extends Model
{
    protected $table = 'meja_billiards';
    
    protected $fillable = [
        'name',
        'room',
        'slug',
        'qrcode'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($table) {
            if (empty($table->slug)) {
                $table->slug = Str::slug($table->name);
            }
        });
    }
}
