<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order_items extends Model
{
    protected $fillable = [
        'order_id',
        'menu_name',
        'price',
        'quantity',
        'image',
    ];

    public function order()
    {
        return $this->belongsTo(orders::class, 'order_id');
    }
}
