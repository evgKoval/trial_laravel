<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Auth;
use DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->select('orders.*', 'products.name', 'products.price', 'products.img')
            ->where('orders.user_id', Auth::user()->id)
            ->get();

        return view('cart', compact('orders'));
    }

    public function addToCart($productId)
    {
        Order::add(['user_id' => Auth::user()->id, 'product_id' => $productId]);

        return redirect()->back();
    }

    public function deleteFromCart($productId)
    {
        Order::where('id', '=', $productId)->delete();

        return redirect()->back();
    }
}
