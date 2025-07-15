@extends('layouts.main_layout')
@section('content')
@use('App\Utilities\PersianNumbers')
<!-- Cart Content -->
<div class="container py-5 cart-content">
    <h2 class="mb-5">سبد خرید شما <i class="fas fa-shopping-cart"></i></h2>

    <div class="row">
        <div class="col-lg-8">
            <div class="cart-items">
                @php($cartProducts = $cartRepay ?? json_decode(Cookie::get('basket'),true))
                @empty($cartProducts)
                <div class="alert alert-info" id="emptyCart" style="display: block">
                    سبد خرید شما خالی است <i class="fas fa-shopping-cart"></i>
                </div>
                @endempty

                @session('fail')
                <x-fail-alert>{{$value}}</x-fail-alert>
                @endsession

                <table class="table" id="cartTable">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>محصول</th>
                            <th>قیمت</th>
                            <th>تعداد</th>
                            <th>جمع</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cartItems">
                        @php($totalPrice = 0)
                        @if(isset($cartProducts))
                        @foreach($cartProducts as $product)
                        <tr>
                            <td>{{PersianNumbers::toNumber($loop->iteration)}}</td>
                            <td>{{$product['title']}}</td>
                            <td>{{PersianNumbers::toPrice($product['price'])}}</td>
                            <td>{{PersianNumbers::toNumber($product['quantity'])}}</td>
                            <td>{{PersianNumbers::toPrice($product['price'] * $product['quantity'])}}</td>
                        </tr>
                        @php($totalPrice += ($product['price'] * $product['quantity']))
                        @endforeach
                        <tr>
                            <td><b>جمع کل</b></td>
                            <td colspan="4"><b>{{PersianNumbers::toPrice($totalPrice)}} تومان</b></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @if($errors->any())
            @foreach($errors->all() as $e)
                <p>{{$e}}<br></p>
            @endforeach
        @endif
        <div class="col-lg-4">
            <form id="complete-payment-form" action="{{route('basket.complete-payment')}}" method="post">
                @csrf
                @session('discountCode')
                    <input type="hidden" name="discountCode" value="{{$value}}">
                @endsession
            </form>
            <form id="setDiscountCode-form" action="{{route('basket.setDiscountCode')}}" method="post">
                @csrf
            </form>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mt-5">خلاصه سبد خرید</h5>
                        @if(!empty($cartProducts))
                        <div class="d-flex justify-content-between my-4">
                            <span>هزینه ارسال:</span>
                            <span>{{PersianNumbers::toPrice(50000)}} تومان</span>
                        </div>
                        <div class="d-flex justify-content-between my-4">
                            <span>آیا کد تخفیف دارید؟</span>
                            <span id="cartTotal">
                                <div class="input-group mb-3">
                                    
                                        <input type="text" name="code" class="form-control" placeholder="کد تخفیف" aria-describedby="basic-addon2" form="setDiscountCode-form">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-success rounded" type="submit" form="setDiscountCode-form">اعمال</button>
                                        </div>
                                    
                                </div>
                            </span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between my-4">
                            <span><b>جمع کل:</b></span>
                            <?php
                            if(session('discountValue')){
                                $discountValue = (int)session('discountValue');
                                echo '<span id="cartTotal" style="color:red;"><del>'.PersianNumbers::toPrice($totalPrice+50000).' تومان</del>('.PersianNumbers::toNumber($discountValue).'%)</span><br>';
                                $totalPrice = $totalPrice - ($totalPrice * ($discountValue/100));
                            }
                        ?>
                        @php($totalPrice+=50000)
                        <span id="cartTotal"><b>{{PersianNumbers::toPrice($totalPrice)}}</b> تومان</span>
                        </div>
                        <input type="hidden" name="total_price" value="{{$totalPrice}}" form="complete-payment-form">
                        <button type="submit" class="btn btn-primary btn-block mb-5" form="complete-payment-form">تکمیل سفارش</button>
                    </div>
                </div>
        </div>
    </div>
</div>

<script>
    @session('codeFail')
        Swal.fire({
            title: 'خطای کد تخفیف',
            text: '{{$value}}',
            icon: 'warning',
        confirmButtonText: "تائید"});
    @endsession

    @session('discountValue')
        Swal.fire({
            title: 'موفق',
            text: 'کد تخفیف وارد شده اعمال شد',
            icon: 'success',
        confirmButtonText: "تائید"});
    @endsession
</script>
@endsection