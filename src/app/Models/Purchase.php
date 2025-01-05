<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'payment_status',
        'payment_method',
        'stripe_payment_id',
        'shipping_address_id',
        'user_id',
        'product_id',
    ];

    protected function user() {
        return $this->belongsTo(User::class);
    }

    protected function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected function shipping_address()
    {
        return $this->belongsTo(ShippingAddress::class);
    }
}
