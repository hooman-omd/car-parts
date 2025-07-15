<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsRequest;
use App\Models\Car;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Utilities\ImageUpload;
use App\Utilities\PersianNumbers;
use PhpOption\None;

class ProductsController extends Controller
{

    public function create()
    {
        $categories = Category::get();
        $cars = Car::get();
        return view('dashboard.products-add', ['categories' => $categories, 'cars' => $cars]);
        // $cars =[
        //     ['brand'=>'سایپا','model'=>'پراید'],
        //     ['brand'=>'سایپا','model'=>'کوئیک'],
        //     ['brand'=>'سایپا','model'=>'تیبا'],
        //     ['brand'=>'سایپا','model'=>'شاهین'],
        //     ['brand'=>'پژو','model'=>'405'],
        //     ['brand'=>'پژو','model'=>'206'],
        //     ['brand'=>'پژو','model'=>'207'],
        //     ['brand'=>'پژو','model'=>'پارس'],
        //     ['brand'=>'EF7','model'=>'سمند'],
        //     ['brand'=>'EF7','model'=>'دنا'],
        //     ['brand'=>'EF7','model'=>'رانا'],
        //     ['brand'=>'EF7','model'=>'تارا'],
        //     ['brand'=>'رنو','model'=>'L90'],
        //     ['brand'=>'رنو','model'=>'مگان'],
        // ];
        // foreach ($cars as $car) {
        //     Car::create($car);
        // }
    }

    public function edit(int $productId)
    {
        $product = Product::findOrFail($productId);
        $categories = Category::get();
        $cars = Car::get();
        return view('dashboard.products-edit', ['product' => $product, 'categories' => $categories, 'cars' => $cars]);
    }

    public function update(ProductsRequest $request)
    {
        $productId = $request->input('product_id');
        $product = Product::findOrFail($productId);

        $validatedData = $request->validated();

        $data = [
            'category_id' => $validatedData['category_id'],
            'title' => $validatedData['title'],
            'price' => $validatedData['price'],
            'engine_type' => $validatedData['engine_type'],
            'has_guarantee' => isset($validatedData['has_guarantee']),
            'country_of_origin' => $validatedData['country_of_origin'],
            'description' => $validatedData['description'],
            'inventory' => $validatedData['inventory'],
        ];

        for ($i = 1; $i <= 4; $i++) {
            $field = "thumbnail_$i";
            if (isset($validatedData[$field])) {
                if ($product->$field != null) {
                    ImageUpload::unlinkImage($product->$field);
                }

                $data[$field] = ImageUpload::uploadImage('product-images', $validatedData[$field]);
            }
        }

        $product->car()->sync($validatedData['cars']);
        $product->update($data);
        return redirect()->route('products.index')->with('success-message', 'بروزرسانی با موفقیت انجام شد');
    }

    public function store(ProductsRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::find($validatedData['category_id']);

        $data = [
            'title' => $validatedData['title'],
            'price' => $validatedData['price'],
            'engine_type' => $validatedData['engine_type'],
            'has_guarantee' => isset($validatedData['has_guarantee']),
            'country_of_origin' => $validatedData['country_of_origin'],
            'description' => $validatedData['description'],
            'inventory' => $validatedData['inventory'],
        ];

        for ($i = 1; $i <= 4; $i++) {
            if (!isset($validatedData['thumbnail_' . $i])) {
                continue;
            }
            $data['thumbnail_' . $i] =  ImageUpload::uploadImage('product-images', $validatedData['thumbnail_' . $i]);
        }

        $product = $category->product()->create($data);

        $product->car()->attach($validatedData['cars']);

        return back()->with('success-message', 'محصول جدید با موفقیت اضافه شد');
    }

    public function index(Request $request)
    {
        $productName = $request->query('title');
        if (isset($productName)) {
            $products = Product::where('title', 'like', "%$productName%")->get();
        } else {
            $products = Product::get();
        }
        return view('dashboard.products-index', ['products' => $products]);
    }

    public function deleteImage(int $productId, string $thumbnailId)
    {
        $product = Product::findOrFail($productId);
        ImageUpload::unlinkImage($product->$thumbnailId);
        $product->update([$thumbnailId => null]);
        return back();
    }

    public function destroy(int $productId)
    {
        $product = Product::find($productId);
        for ($i = 1; $i <= 4; $i++) {
            $thumbnail = "thumbnail_$i";
            if ($product->$thumbnail != null) {
                ImageUpload::unlinkImage($product->$thumbnail);
            }
        }
        $product->delete();
        return back()->with('success-message', 'محصول انتخاب شده با موفقیت حذف شد');
    }

    public function increaseInventory(int $productId, Request $request)
    {
        $validatedCount = $request->validate([
            'count' => ['numeric', 'min:1'],
        ], [
            'count.numeric' => 'لطفا عدد وارد نمایید',
            'count.min' => 'تعداد افزایش موجودی محصول حداقل باید یک باشد'
        ]);
        $product = Product::findOrFail($productId);
        $product->inventory += $validatedCount['count'];
        $product->save();
        return back()->with('success-message', 'افزایش موجودی ' . $product->title . ' با موفقیت انجام شد');
    }

    public function discount(int $productId, Request $request)
    {
        $validatedCount = $request->validate([
            'discount' => ['required', 'numeric', 'min:1', 'max:99'],
            'day' => ['required', 'numeric', 'min:1', 'max:31'],
            'month' => ['required', 'numeric', 'min:1', 'max:12'],
            'year' => ['required', 'numeric', 'min:' . jdate()->getYear()]
        ], [
            'discount.required' => 'درصد تخفیف نا معتبر',
            'discount.numeric' => 'لطفا عدد وارد نمایید',
            'discount.min' => 'درصد وارد شده نا معتبر است',
            'discount.max' => 'درصد وارد شده نا معتبر است',
            'day' => 'روز نا معتبر',
            'month' => 'ماه نا معتبر',
            'year' => 'سال نا معتبر',
        ]);
        $product = Product::findOrFail($productId);
        $date = (new Jalalian($validatedCount['year'], $validatedCount['month'], $validatedCount['day'])); //->toCarbon() ->toDateString()
        if ($date->lessThan(jdate())) {
            return back()->with('fail-message', 'تاریخ وارد شده گذشته است');
        }
        $product->update(['percentage' => $validatedCount['discount'], 'discount_expiry' => $date->toCarbon()]);
        return back()->with('success-message', 'تخفیف جدید با موفقیت اعمال شد');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $results = Product::where('title', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->take(10)
            ->get(['id', 'title', 'price']);

        return response()->json([
            'results' => $results->map(function ($product) {
                return [
                    'name' => $product->title, 
                    'price' => $product->price ? PersianNumbers::toPrice($product->price) : 'نامشخص',
                    'url' => route('product.details', $product->id)
                ];
            })
        ]);
    }
}
