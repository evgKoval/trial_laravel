<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\MadeOrders;

class AdminController extends Controller
{
    public function index()
    {
        $products = Product::all();

        $madeOrders = MadeOrders::all();

        return view('admin.index', compact('products', 'madeOrders'));
    }

    public function create() 
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'category' => 'required'
        ]);

        Product::add([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'img' => $request->input('image') || ''
        ], $request->input('category'));

        return redirect('admin');
    }

    public function edit($productId)
    {
        $product = Product::find($productId);

        return view("admin.edit", compact('product'));
    }

    public function update(Request $request, $productId)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'required',
            'category' => 'required'
        ]);

        $product = Product::find($productId);

        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->img = $request->input('image');
        $product->category = $request->input('category');

        $product->save();

        return redirect('admin');
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return 1;
    }

    public function changeStatus($orderId, $status)
    {
        $order = MadeOrders::find($orderId);

        $order->status = $status;

        $order->save();

        return 1;
    }
}