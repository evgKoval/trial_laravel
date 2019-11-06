<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'product_id'];

    public static function add($fields) 
    {
        $order = new static;
        $order->fill($fields);
        $order->save();

        return $order;
    }
}
