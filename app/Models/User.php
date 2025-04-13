<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'Username',
        'PasswordHash', // Đúng tên cột từ database
        'Email',
        'FullName',
        'PhoneNumber',
        'Address',
        'Role',
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'UserId', 'id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function isAdmin()
    {
        return $this->Role === 'Admin';
    }
}
