<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    // Fillable
    protected $fillable = [
        'email', 'name', 'phone', 'product_ids',
        'zipcode', 'delivery_address', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
