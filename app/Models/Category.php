<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'Name',
        'Description',
        'Parent_category_id',
    ];

    protected $table = 'categories';

    public function products()
    {
        return $this->hasMany(Product::class, 'CategoryId', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'Parent_category_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category_id', 'id');
    }
}
