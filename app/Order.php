<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Fillable
    protected $fillable = [
        'email', 'name', 'phone', 'product_ids',
        'zipcode', 'delivery_address', 'user_id',
        'currency',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(OrderProducts::class, 'order_id');
    }
}
