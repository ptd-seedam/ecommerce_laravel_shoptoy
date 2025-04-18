<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'OrderId',
        'TrackingNumber',
        'ShippedDate',
        'EstimatedDeliveryDate',
        'DeliveryStatus',
    ];

    protected $table = 'shipments';

    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderId', 'id');
    }
}
