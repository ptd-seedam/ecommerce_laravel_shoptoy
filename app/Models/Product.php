<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'Name',
        'Description',
        'Price',
        'Stock',
        'so_luong_da_ban',
        'CategoryId',
    ];

    protected $primaryKey = 'ProductId';

    public $incrementing = true;

    protected $table = 'products';

    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryId', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'ProductId', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'ProductId', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'ProductId', 'id');
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'productdiscounts', 'id', 'id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
