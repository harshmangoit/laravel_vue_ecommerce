<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_no',
        'order_id',
        'order_address_id',
        'tracking_no',
        'status',
        'shipped_at',
        'delivered_at'
    ];
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_no', 'invoice_no');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Define the relationship with OrderAddress model
    public function orderAddress()
    {
        return $this->belongsTo(OrderAddress::class, 'order_address_id');
    }
}
