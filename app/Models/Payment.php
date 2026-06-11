<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model Payment ini bertindak sebagai jembatan penghubung ke tabel 'payments' di database Supabase untuk mencatat status pembayaran transaksi.
class Payment extends Model
{
    protected $guarded = [];

    // Relasi Many-to-One (BelongsTo): Setiap catatan pembayaran ditautkan ke satu transaksi/pesanan utama
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
