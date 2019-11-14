<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'img', 'category', 'stock'
    ];

    public static function add($fields, $category, $stock)
    {
        $fields['category'] = $category;
        $fields['stock'] = $stock;

        $product = new static;
        $product->fill($fields);
        $product->save();

        return $product;
    }
}
