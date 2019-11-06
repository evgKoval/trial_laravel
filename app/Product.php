<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'img'
    ];

    public static function add($fields)
    {
        $product = new static;
        $product->fill($fields);
        $product->save();

        return $product;
    }
}
