<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverySchedule extends Model
{
    protected $fillable = [
        'order_id',
        'pickup_date',
        'pickup_time',
        'delivery_date',
        'delivery_time',
    ];
}
