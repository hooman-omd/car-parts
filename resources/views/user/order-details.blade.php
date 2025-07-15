@extends('user.dashboard')

@section('user-title', 'جزئیات سفارش')

@section('user-content')
@use('App\Utilities\PersianNumbers')
<div class="row">
    <div class="col-md-12"><span class="d-inline-block rounded p-2 mb-3 font-weight-bold {{$order->payment ? 'badge-success':'badge-danger'}}">{!! $order->payment ? 'پرداخت شده <i class="fa fa-check-circle" aria-hidden="true"></i>' : 'تراکنش نا موفق <i class="fa fa-window-close" aria-hidden="true"></i>' !!}</span></div>
</div>

<div class="row">
    <div class="col-md-4">
        <span class="d-inline-block my-3">شماره سفارش : {{PersianNumbers::toNumber($order->id)}}</span>
    </div>
    <div class="col-md-4">
        <span class="d-inline-block my-3">شماره تراکنش : {{$order->payment ? PersianNumbers::toNumber($order->payment->ref_id) : 'پرداخت نشده'}}</span>
    </div>
    <div class="col-md-4">
        <span class="d-inline-block my-3">کد تخفیف : <span class="badge {{$order->discount_id ? 'badge-success':'badge-info'}}">{{$order->discountCode->code ?? 'فاقد کد تخفیف'}}</span></span>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <span class="d-inline-block my-3">تاریخ ثبت سفارش : {{PersianNumbers::toNumber(jdate($order->created_at)->format('Y/m/d'))}}</span>
    </div>
    <div class="col-md-4">
        <span class="d-inline-block my-3">تاریخ پرداخت : {{$order->payment ? PersianNumbers::toNumber(jdate($order->payment->created_at)->format('Y/m/d')) : 'پرداخت نشده'}}</span>
    </div>
    <div class="col-md-4">
        <span class="d-inline-block my-3">جمع کل سفارش : {{ PersianNumbers::toPrice($order->total_price) }} تومان (با احتساب ۵۰،۰۰۰ تومان هزینه ارسال)</span>
    </div>
</div>

<div class="table-responsive text-right">
    <table class="table table-hover">
        <thead class="bg-light">
            @php($products=json_decode($order->cart,true))
            <tr>
                <th>ردیف</th>
                <th>عنوان محصول</th>
                <th>قیمت</th>
                <th>تعداد</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{PersianNumbers::toNumber($loop->iteration)}}</td>
                <td>{{ $product['title'] }}</td>
                <td>{{ PersianNumbers::toPrice($product['price']) }} تومان</td>
                <td>{{ PersianNumbers::toNumber($product['quantity']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if(!$order->payment)
    <form action="{{route('basket.delete-order',$order->id)}}" method="post">
        @csrf
        @method('delete')
        <button class="btn btn-outline-danger m-3 float-left">لغو سفارش <i class="fa fa-window-close" aria-hidden="true"></i></button>
    </form>
    <form action="{{route('basket.complete-payment',$order->id)}}" method="post">
        @csrf
        <button class="btn btn-outline-primary my-3 float-left">تکمیل پرداخت <i class="fas fa-credit-card"></i></button>
    </form>
    @endif
</div>
@endsection