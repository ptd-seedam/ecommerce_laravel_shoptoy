<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'UserId',
    ];

    protected $table = 'carts';

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'CartId', 'id');
    }
}
