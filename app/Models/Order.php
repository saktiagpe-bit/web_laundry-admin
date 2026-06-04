<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
