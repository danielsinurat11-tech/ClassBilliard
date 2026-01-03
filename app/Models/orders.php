<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Order
 *
 * PENTING: Order dengan status completed, processing, atau rejected TIDAK BOLEH DIHAPUS
 * untuk mencegah kecurangan saat tutup buku akhir bulan/tahun.
 * Data penjualan harus tersimpan permanen untuk keperluan audit dan laporan keuangan.
 */
class orders extends Model
{
    protected $fillable = [
        'customer_name',
        'table_number',
        'room',
        'total_price',
        'payment_method',
        'status',
        'shift_id',
        'idempotency_key',
    ];

    public function orderItems()
    {
        return $this->hasMany(order_items::class, 'order_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Cek apakah order bisa dihapus
     * Hanya order dengan status pending yang bisa dihapus
     */
    public function canBeDeleted(): bool
    {
        return $this->status === 'pending';
    }
}
