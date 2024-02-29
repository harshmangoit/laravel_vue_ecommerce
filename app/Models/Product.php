<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'price', 'image_path', 'is_featured', 'status', 'url_slug'
    ];
    public function category()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
