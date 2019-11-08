<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Product;
use App\Models\Wishlist;

class CategoryController extends Controller
{
    public function index($category)
    {
        $userId = Auth::user()->id;

        $categories = Product::select('category')->groupBy('category')->get()->toArray();

        $products = Product::where('category', '=', $category)->get();

        $wishlist = Wishlist::where('user_id', '=', $userId)->get();

        foreach ($products as $product) {
            $find = $wishlist->where('product_id', $product['id'])->first();

            $product['user_id'] = isset($find) ? $find->user_id : null;
        }

        return view('front', compact('products', 'userId', 'categories'));
    }
}
