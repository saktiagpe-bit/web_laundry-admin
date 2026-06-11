<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model OrderItem ini bertindak sebagai jembatan penghubung ke tabel 'order_items' di database Supabase untuk detail cucian per transaksi.
class OrderItem extends Model
{
    protected $guarded = [];

    // Relasi Many-to-One (BelongsTo): Setiap item cucian terikat ke satu transaksi/pesanan utama
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi Many-to-One (BelongsTo): Setiap item cucian merujuk ke layanan tarif master tertentu di database Supabase
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
