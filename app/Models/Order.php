<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_gender',
        'pickup_type',
        'delivery_type',
        'distance_km',
        'address',
        'latitude',
        'longitude',
        'driver_notes',
        'status',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function statuses()
    {
        return $this->hasMany(OrderStatus::class);
    }

    public function deliverySchedule()
    {
        return $this->hasOne(DeliverySchedule::class);
    }
}
