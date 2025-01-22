<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'brand_name',
        'description',
        'image',
        'condition',
        'status',
        'user_id',
        'color_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'product_category')->withTimestamps();
    }

    public function likedByUsers() {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function purchase() {
        return $this->hasOne(Purchase::class);
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if(!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
        return $query;
    }
}
