<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'img', 'category'
    ];

    public static function add($fields, $category)
    {
        $fields['category'] = $category;

        $product = new static;
        $product->fill($fields);
        $product->save();

        return $product;
    }
}
