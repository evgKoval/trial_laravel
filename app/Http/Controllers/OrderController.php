<?php

namespace App\Http\Controllers;

use App\Order;
use Auth;
use DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = DB::table('orders')->leftJoin('products', 'orders.product_id', '=', 'products.id')->where('orders.user_id', Auth::user()->id)->get();

        return view('cart', compact('orders'));
    }

    public function addToCart($productId)
    {
        Order::add(['user_id' => Auth::user()->id, 'product_id' => $productId]);

        return redirect()->back();
    }

    public function deleteFromCart($productId)
    {
        Order::where('product_id', '=', $productId)->delete();

        return redirect()->back();
    }
}
