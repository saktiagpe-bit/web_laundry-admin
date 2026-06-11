<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model DeliverySchedule ini bertindak sebagai jembatan penghubung ke tabel 'delivery_schedules' di database Supabase untuk jadwal kurir antar-jemput pakaian.
class DeliverySchedule extends Model
{
    protected $guarded = [];

    // Relasi Many-to-One (BelongsTo): Setiap jadwal logistik ditautkan ke satu transaksi/pesanan utama
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
