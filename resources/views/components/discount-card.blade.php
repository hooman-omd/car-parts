@props(['id','title','image','price','percentage','inventory'])


<div class="product-card card h-100">
    <div class="position-relative">
        <a href="{{route('product.details',$id)}}" target="_blank"><img src="{{asset($image)}}" class="card-img-top product-img" alt="{{$title}}"></a>
        <span class="badge badge-danger badge-discount">{{$percentage}}% تخفیف</span>
    </div>
    <div class="card-body">
        <a href="{{route('product.details',$id)}}" target="_blank" style="text-decoration: none; color:#000;">
            <h6 class="card-title">{{$title}}</h6>
        </a>
        <div class="mb-2">
            <span class="price">{{$applyDiscount($price,$percentage)}} تومان</span>
            <span class="old-price">{{\App\Utilities\PersianNumbers::toPrice($price)}} تومان</span>
        </div>

        @if($inventory > 0)
        <button class="btn btn-sm btn-outline-primary btn-block add-to-cart" data-id="{{$id}}"
            data-name="{{$title}}" data-price="{{$price}}" data-img="{{asset($image)}}">
            <i class="fas fa-cart-plus"></i> افزودن به سبد
        </button>
        @else
        <button class="btn btn-sm btn-outline-secondary btn-block" disabled>
            <i class="fas fa-battery-quarter"></i> اتمام موجودی
        </button>
        @endif
    </div>
</div>