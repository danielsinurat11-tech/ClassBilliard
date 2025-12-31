<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model {
    use SoftDeletes;

    protected $fillable = [
        'category_menu_id', 'name', 'slug', 'short_description', 
        'description', 'price', 'image_path', 'labels'
    ];

    protected $casts = [
        'price' => 'float'
    ];

    public function categoryMenu(): BelongsTo {
        return $this->belongsTo(CategoryMenu::class, 'category_menu_id');
    }
}