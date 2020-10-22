<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    protected $fillable = ['quantity', 'product_id', 'order_id'];

    protected $hidden = ['product_id', 'order_id'];

    public $timestamps = false;

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
