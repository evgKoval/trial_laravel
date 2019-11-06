<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Product;
use App\Wishlist;
use Illuminate\Http\Request;
use Nesk\Puphpeteer\Puppeteer;
// use Nesk\Rialto\Data\JsFunction;
use Nesk\Puphpeteer\Resources\ElementHandle;

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
        $products = DB::table('products')
            ->leftJoin('wishlist', 'products.id', '=', 'wishlist.product_id')
            // ->where('wishlist.user_id', Auth::user()->id)
            ->select('products.*', 'wishlist.product_id')
            ->get();

        return view('front', compact('products'));
    }

    public function search(Request $request)
    {
        $products = DB::table('products')
            ->leftJoin('wishlist', 'products.id', '=', 'wishlist.product_id')
            ->select('products.*', 'wishlist.product_id')
            ->get();
        $query = $request->input('query');

        return view('search', compact('query', 'products'));
    }

    public function wishlist()
    {
        $products = DB::table('wishlist')->leftJoin('products', 'wishlist.product_id', '=', 'products.id')->where('wishlist.user_id', Auth::user()->id)->get();

        return view('wishlist', compact('products'));
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
