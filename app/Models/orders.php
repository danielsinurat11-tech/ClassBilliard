<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    protected $fillable = [
        'customer_name',
        'table_number',
        'room',
        'total_price',
        'payment_method',
        'status'
    ];

    public function orderItems()
    {
        return $this->hasMany(order_items::class, 'order_id');
    }
}
