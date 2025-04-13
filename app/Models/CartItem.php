<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'CartId',
        'ProductId',
        'Quantity',
    ];

    protected $table = 'cart_items';

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'CartId', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductId', 'id');
    }
}
