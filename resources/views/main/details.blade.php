@extends('layouts.main_layout')

@section('content')
@use('App\utilities\PersianNumbers')

<!-- Product Detail Content -->
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <div class="main-image mb-4">
                <img id="mainProductImage" src="{{asset($product->thumbnail_1)}}" class="img-fluid rounded border" alt="{{$product->title}}">
            </div>
            <div class="thumbnail-container d-flex">
                @for($i = 1;$i<=4;$i++)
                    @php($thumbnail="thumbnail_$i" )
                    @isset($product->$thumbnail)
                    <img src="{{asset($product->$thumbnail)}}" class="img-thumbnail mr-2" style="width: 80px; cursor: pointer"
                        onclick="changeMainImage('{{asset($product->$thumbnail)}}', '{{$product->title}}')">
                    @endisset
                    @endfor
            </div>
        </div>

        <div class="col-md-6 product-detailes-section">
            <h1 class="mb-3">{{$product->title}}</h1>

            <div class="price-section mb-4">
                @isset($product->percentage)
                    @php($discountedPrice = PersianNumbers::toPrice($product->price - ($product->price * ($product->percentage/100))))
                    <span class="price h4 text-danger">{{$discountedPrice}} تومان</span>
                    <span class="old-price text-muted ml-2"><del>{{PersianNumbers::toPrice($product->price)}} تومان</del></span>
                    <span class="badge badge-danger mr-2">{{PersianNumbers::toNumber($product->percentage)}}% تخفیف</span>
                @else
                    <span class="price h4 text-danger">{{PersianNumbers::toPrice($product->price)}} تومان</span>
                @endisset
            </div>

            <div class="product-specs mb-4">
                <h4 class="mb-3">مشخصات فنی</h4>
                <ul class="list-unstyled specs-list">
                    <li><strong>نوع موتور:</strong> {{$product->engine_type}}</li>
                    <li><strong>گارانتی:</strong> {{$product->has_guarantee ? "دارد" : "ندارد"}}</li>
                    <li><strong>کشور سازنده:</strong> {{$product->country_of_origin}}</li>
                    <li><strong>وضعیت موجودی:</strong> {{$product->inventory == 0 ? "اتمام موجودی" : "موجود"}}</li>
                    <li><strong>قابل استفاده در خودروهای:</strong>
                        @foreach($product->car as $car)
                            {{ $car->model }}@if(!$loop->last)، @endif
                        @endforeach
                    </li>
                </ul>
            </div>

            <button class="btn btn-primary btn-lg btn-block add-to-cart" data-id="{{$product->id}}" data-name="{{$product->title}}"
                data-price="{{$product->percentage ? $product->price - ($product->price * ($product->percentage/100)) : $product->price}}" data-img="{{asset($product->thumbnail_1)}}">
                <i class="fas fa-cart-plus"></i> افزودن به سبد خرید
            </button>

            <div class="product-description mt-5">
                <h4 class="mb-3 text-center">توضیحات محصول</h4>
                <p class="text-justify">
                    {{$product->description}}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection