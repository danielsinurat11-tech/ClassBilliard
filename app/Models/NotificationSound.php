<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSound extends Model
{
    protected $fillable = [
        'name',
        'filename',
        'file_path',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];
}
