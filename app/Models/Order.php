<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'amount',
        'quantity',
    ];

    public function products()
    {
        return $this->hasMany(FarmProduct::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
