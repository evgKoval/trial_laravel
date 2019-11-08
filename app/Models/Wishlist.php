<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlist';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'product_id'];

    public static function add($fields) 
    {
        $wishlist = new static;
        $wishlist->fill($fields);
        $wishlist->save();

        return $wishlist;
    }
}
