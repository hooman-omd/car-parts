<?php

namespace App\Http\Controllers\main;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    private $minutes = 60 * 24 * 7;

    public function getCart(){
        return response()->json(Cookie::get('basket'));
    }

    public function addToCart(Request $request)
    {
        $product_id = $request->query('product_id');
        $product = Product::findOrFail($product_id);
        $basket = json_decode(Cookie::get('basket'), true) ?? [];

        if (isset($basket[$product_id])) {
            $basket[$product_id]['quantity'] += 1;
        } else {
            $basket[$product_id] = [
                'title' => $product->title,
                'price' => $product->percentage ? ($product->price - ($product->price * ($product->percentage/100))) : $product->price,
                'image' => asset($product->thumbnail_1),
                'quantity' => 1
            ];
        }

        Cookie::queue('basket', json_encode($basket), $this->minutes);
        return back();
    }

    public function removeFromCart(Request $request){
        $product_id = $request->query('product_id');
        $basket = json_decode(Cookie::get('basket'), true) ?? [];
        if (isset($basket[$product_id])){
            unset($basket[$product_id]);
            Cookie::queue('basket', json_encode($basket), $this->minutes);
        }
        return back();
    }
}
