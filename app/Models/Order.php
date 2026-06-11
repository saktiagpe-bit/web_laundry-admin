<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model Order ini bertindak sebagai jembatan penghubung ke tabel 'orders' di database Supabase.
// Berkat Eloquent ORM Laravel, kita bisa mengelola data pesanan dengan syntax PHP biasa tanpa perlu menulis query SQL manual.
class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // Relasi One-to-Many: Satu pesanan memiliki banyak item cucian (detail pesanan) di database Supabase
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi One-to-One: Satu pesanan memiliki satu catatan pembayaran di database Supabase
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Relasi One-to-Many: Satu pesanan memiliki banyak riwayat status/tracking di database Supabase
    public function statuses()
    {
        return $this->hasMany(OrderStatus::class);
    }

    // Relasi One-to-One: Satu pesanan memiliki satu jadwal penjemputan/pengantaran kurir di database Supabase
    public function deliverySchedule()
    {
        return $this->hasOne(DeliverySchedule::class);
    }

    // Relasi Many-to-One (BelongsTo): Setiap pesanan dimiliki oleh satu user/pelanggan terdaftar di database Supabase
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
