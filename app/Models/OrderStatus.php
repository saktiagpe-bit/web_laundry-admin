<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model OrderStatus ini bertindak sebagai jembatan penghubung ke tabel 'order_statuses' di database Supabase untuk mencatat riwayat status pengerjaan laundry.
class OrderStatus extends Model
{
    protected $guarded = [];

    // Relasi Many-to-One (BelongsTo): Setiap catatan status ditautkan ke satu transaksi/pesanan utama
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
