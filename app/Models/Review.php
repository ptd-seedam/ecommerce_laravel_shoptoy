<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'ProductId',
        'UserId',
        'Rating',
        'Comment',
    ];

    protected $table = 'reviews';

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductId', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value);
    }
}
