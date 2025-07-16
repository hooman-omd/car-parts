<?php

namespace App\Http\Controllers\main;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        $discountedProducts = Product::whereNotNull('percentage')->orderBy('id','desc')->limit(10)->get();
        $kitProducts = Category::with(['product' => function ($query) {
            $query->orderBy('id','desc')->limit(10);
        }])->find(2)->product;
        $lastProducts = Product::orderBy('id','desc')->limit(10)->get();

        return view('main.index', ['categories' => $categories, 'discountedProducts' => $discountedProducts, 'kitProducts' => $kitProducts, 'lastProducts' => $lastProducts]);
    }

    public function contactUs(){
        $user = Auth::user() ?? null;
        return \view('main.contact-us',['user'=>$user]);
    }

    public function details(int $product_id)
    {
        $product = Product::findOrFail($product_id);
        return view('main.details', ['product' => $product]);
    }

    public function productFilters(Request $request)
    {
        $filter = Product::query();
        $categories = Category::get();
        $cars = Car::get();

        if ($request->filled('car_id')) {
            $filter = $filter->whereHas('car', function ($query) use ($request) {
                $query->where('car_id', $request->query('car_id'));
            });
        }
        if ($request->filled('category_id')) {
            $filter = $filter->where('category_id', $request->query('category_id'));
        }
        if ($request->filled('minPrice') && $request->filled('maxPrice')) {
            $filter = $filter->whereBetween('price', [$request->query('minPrice'), $request->query('maxPrice')]);
        }
        if ($request->filled('discount')) {
            $filter = $filter->whereNotNull('percentage');
        }


        $filter = $filter->orderBy('id','desc')->paginate(9)->appends($request->query());
        return view('main.products', ['products' => $filter, 'categories' => $categories, 'cars' => $cars]);
    }

    public function cart(){
        return view('main.cart');
    }
}