<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Cviebrock\LaravelElasticsearch\Facade as Elasticsearch;

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

        $categories = Product::select('category')->groupBy('category')->get()->toArray();

        $products = Product::all();

        $wishlist = Wishlist::where('user_id', '=', $userId)->get();

        foreach ($products as $product) {
            $find = $wishlist->where('product_id', $product['id'])->first();

            $product['user_id'] = isset($find) ? $find->user_id : null;
        }

        return view('front', compact('products', 'userId', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $params = [
            'index' => 'products',
            'body'  => [
                'query' => [
                    'match' => [
                        'name' => $query
                    ]
                ]
            ]
        ];

        $stats = Elasticsearch::search($params);
        $doc   = $stats['hits']['hits'];

        $products = [];

        foreach ($stats['hits']['hits'] as $product) {
            $products[] = $product['_source'];
        }

        $userId = Auth::user()->id;

        return view('search', compact('query', 'products', 'userId'));
    }

    public function searchProducts($query) 
    {

        $params = [
            'index' => 'products',
            'body'  => [
                'query' => [
                    'match' => [
                        'name' => $query
                    ]
                ]
            ]
        ];

        $stats = Elasticsearch::search($params);
        $doc   = $stats['hits']['hits'];

        $products = [];

        foreach ($stats['hits']['hits'] as $product) {
            $products[] = $product['_source'];
        }

        return json_encode($products);
    }

    public function wishlist()
    {
        $userId = Auth::user()->id;

        $products = Wishlist::leftJoin('products', 'wishlist.product_id', '=', 'products.id')->where('wishlist.user_id', Auth::user()->id)->get();

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
