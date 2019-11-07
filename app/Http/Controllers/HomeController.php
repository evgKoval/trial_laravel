<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Product;
use App\Wishlist;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userId = Auth::user()->id;

        $products = Product::all();

        $wishlist = Wishlist::where('user_id', '=', $userId)->get();

        foreach ($products as $product) {
            $find = $wishlist->where('product_id', $product['id'])->first();

            $product['user_id'] = isset($find) ? $find->user_id : null;
        }

        return view('front', compact('products', 'userId'));
    }

    public function search(Request $request)
    {
        $userId = Auth::user()->id;

        $products = DB::table('products')
            ->leftJoin('wishlist', 'products.id', '=', 'wishlist.product_id')
            ->select('products.*', 'wishlist.product_id')
            ->get();
        $query = $request->input('query');

        return view('search', compact('query', 'products', 'userId'));
    }

    public function wishlist()
    {
        $userId = Auth::user()->id;

        $products = DB::table('wishlist')->leftJoin('products', 'wishlist.product_id', '=', 'products.id')->where('wishlist.user_id', Auth::user()->id)->get();

        return view('wishlist', compact('products', 'userId'));
    }

    public function addToWishlist($productId)
    {
        Wishlist::add(['user_id' => Auth::user()->id, 'product_id' => $productId]);

        return redirect()->back();
    }

    public function deleteFromWishlist($productId)
    {
        Wishlist::where('product_id', '=', $productId)->delete();

        return redirect()->back();
    }
}
