<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'ProductId',
        'DiscountId',
    ];

    public $incrementing = true;

    protected $table = 'product_discounts';

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductId', 'id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'DiscountId', 'id');
    }
}
