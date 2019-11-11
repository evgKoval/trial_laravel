<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MadeOrders extends Model
{
    protected $table = 'made_orders';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'name', 'email', 'country', 'state_province', 'city', 'zip', 'adress', 'phone', 'paid'];

    public static function add($fields) 
    {
        $order = new static;
        $order->fill($fields);
        $order->save();

        return $order;
    }
}
