<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_no',
        'order_id',
        'order_address_id',
        'qty',
        'total_amount',
        'shipping_fee',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderAddress()
    {
        return $this->belongsTo(OrderAddress::class, 'order_address_id');
    }
}
