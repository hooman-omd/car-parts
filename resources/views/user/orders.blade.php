@extends('user.dashboard')

@section('user-title', 'تاریخچه سفارشات')

@section('user-content')
@use('App\Utilities\PersianNumbers')
@session('success')
    <x-success-alert>{{$value}}</x-success-alert>
@endsession
<div class="table-responsive text-right">
    <table class="table table-hover">
        <thead class="bg-light">
            <tr>
                <th>شماره سفارش</th>
                <th>تاریخ</th>
                <th>مبلغ</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ PersianNumbers::toNumber($order->id) }}</td>
                <td>{{ PersianNumbers::toNumber(jdate($order->created_at)->format('Y/m/d')) }}</td>
                <td>{{ PersianNumbers::toPrice($order->total_price) }} تومان</td>
                <td>
                    <span class="badge 
                        @if($order->status == 'paid') badge-success
                        @else badge-secondary @endif">
                        {{$order->status=='paid' ? 'پرداخت شده':'تراکنش ناموفق' }}
                    </span>
                </td>
                <td>
                    <a href="{{route('userdashboard.orderDetail',$order->id)}}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i> مشاهده
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-4">سفارشی یافت نشد</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
