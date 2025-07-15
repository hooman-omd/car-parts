@extends('layouts.main_layout')

@section('content')
<!--            main                 -->

<!-- Hero Section -->
<div id="heroCarousel" class="carousel slide" data-ride="carousel" data-interval="4000">
    <ol class="carousel-indicators">
        <li data-target="#heroCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#heroCarousel" data-slide-to="1"></li>
        <li data-target="#heroCarousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active bg-primary text-white py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 slide-item-group">
                        <h1 class="display-4">بهترین لوازم یدکی خودرو</h1>
                        <p class="lead">با کیفیت ترین قطعات با مناسب ترین قیمت</p>
                        <a href="#" class="btn btn-light btn-lg">مشاهده محصولات</a>
                    </div>
                    <div class="col-md-6 d-none d-md-block">
                        <img src="images/slide 1.png" alt="Car Parts" class="img-fluid rounded slide-img">
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item bg-primary text-white py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 slide-item-group">
                        <h1 class="display-4">قطعات اصلی و اورجینال</h1>
                        <p class="lead">تضمین اصالت و گارانتی محصولات</p>
                        <a href="#" class="btn btn-light btn-lg">خرید قطعات</a>
                    </div>
                    <div class="col-md-6 d-none d-md-block">
                        <img src="images/slide 2.png" alt="Original Parts" class="img-fluid rounded slide-img">
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-item bg-primary text-white py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 slide-item-group">
                        <h1 class="display-4">ارسال سریع و مطمئن</h1>
                        <p class="lead">تحویل در کوتاه‌ترین زمان ممکن</p>
                        <a href="#" class="btn btn-light btn-lg">سفارش دهید</a>
                    </div>
                    <div class="col-md-6 d-none d-md-block">
                        <img src="images/slide 3.png" alt="Fast Delivery" class="img-fluid rounded slide-img">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">قبلی</span>
    </a>
    <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">بعدی</span>
    </a>
</div>

<!-- Categories -->
<div class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">دسته بندی محصولات</h2>

        <div class="row">
            <div class="owl-carousel owl-theme">
                @foreach($categories as $category)
                <div class="category-card card text-center">
                    <img src="{{asset($category->thumbnail)}}" class="card-img-top category-img" alt="{{$category->title}}">
                    <div class="card-body">
                        <h5 class="card-title">{{$category->title}}</h5>
                        <a href="/products?category_id={{$category->id}}" target="_blank" class="btn btn-outline-primary">مشاهده محصولات</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- فروش ویژه -->
<div class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="mb-0">فروش ویژه</h2>
            <a href="{{route('product.productFilters')}}?discount=on" class="btn btn-outline-primary">مشاهده همه</a>
        </div>

        <div class="row">
            <div class="owl-carousel owl-theme">
                @foreach($discountedProducts as $product)
                <x-discount-card id="{{$product->id}}" title="{{$product->title}}" image="{{$product->thumbnail_1}}" price="{{$product->price}}" percentage="{{$product->percentage}}" />
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- banner -->
<div class="container banner">
    <img src="images/banner-1.jpg" alt="banner" class="border d-lg-block d-md-none d-none">
    <img src="images/banner-1-mobile.jpg" alt="banner" class="border d-lg-none d-md-block d-block">
</div>

<!-- کیت ها -->
<div class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="mb-0">کیت ها</h2>
            <a href="{{route('product.productFilters')}}?category_id=2" class="btn btn-outline-primary">مشاهده همه</a>
        </div>

        <div class="row">
            <div class="owl-carousel owl-theme">
                @foreach($kitProducts as $product)
                    @if($product->percentage != null)
                        <x-discount-card id="{{$product->id}}" title="{{$product->title}}" image="{{$product->thumbnail_1}}" price="{{$product->price}}" percentage="{{$product->percentage}}" />
                    @else
                        <x-card id="{{$product->id}}" title="{{$product->title}}" image="{{$product->thumbnail_1}}" price="{{$product->price}}" />
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- banner -->
    <div class="container banner">
        <img src="images/banner-1.jpg" alt="banner" class="border d-lg-block d-md-none d-none">
        <img src="images/banner-1-mobile.jpg" alt="banner" class="border d-lg-none d-md-block d-block">
    </div>

    <!-- محصولات جدید -->
    <div class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="mb-0">محصولات جدید</h2>
                <a href="{{route('product.productFilters')}}" class="btn btn-outline-primary">مشاهده همه</a>
            </div>

            <div class="row">
                <div class="owl-carousel owl-theme">
                @foreach($lastProducts as $product)
                    @if($product->percentage != null)
                        <x-discount-card id="{{$product->id}}" title="{{$product->title}}" image="{{$product->thumbnail_1}}" price="{{$product->price}}" percentage="{{$product->percentage}}" />
                    @else
                        <x-card id="{{$product->id}}" title="{{$product->title}}" image="{{$product->thumbnail_1}}" price="{{$product->price}}" />
                    @endif
                @endforeach
                </div>
            </div>
        </div>
    </div>

    <!--           end main              -->
    @endsection