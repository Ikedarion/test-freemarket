<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'postal_code',
        'address',
        'building_name',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function purchases() {
        return $this->hasMany(Purchase::class);
    }
}
