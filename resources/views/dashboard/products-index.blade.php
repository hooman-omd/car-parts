@extends('layouts.dashboard_layout')

@section('content')
@use('App\Utilities\PersianNumbers')
<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">محصولات</h2>
    </div>

    <!-- Insert data -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <form action="{{route('products.index')}}" method="get">
                        <div class="input-group">
                            <input name="title" value="{{request()->query('title')}}" type="text" class="form-control me-4" placeholder="نام محصول">
                            <button class="btn btn-primary ms-4" type="submit">
                                <i class="fa fa-search" aria-hidden="true"></i> جستجو
                            </button>
                        </div>
                    </form>
                    @session('success-message')
                    <x-success-alert>{{$value}}</x-success-alert>
                    @endsession

                    @session('fail-message')
                    <x-fail-alert>{{$value}}</x-fail-alert>
                    @endsession

                    @if($errors->any())
                    <x-fail-alert>
                        @foreach($errors->all() as $e)
                        {{$e}}<br>
                        @endforeach
                    </x-fail-alert>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="70">ردیف</th>
                            <th width="100">دسته بندی</th>
                            <th width="170">نام محصول</th>
                            <th>قیمت</th>
                            <th>تخفیف</th>
                            <th>موجودی</th>
                            <th>عملیات</th>
                            <th>افزایش موجودی</th>
                            <th>تخفیف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{PersianNumbers::toNumber($loop->iteration)}}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>{{$product->category->title}}</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>{{$product->title}}</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        @if(isset($product->percentage))
                                        <span style="text-decoration: line-through red;">{{PersianNumbers::toPrice($product->price)}}</span>
                                        <br>
                                        <span style="color: red;">{{PersianNumbers::toPrice($product->price - ($product->price * ($product->percentage/100)))}}</span>
                                        @else
                                        {{PersianNumbers::toPrice($product->price)}}
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        @if(isset($product->percentage))
                                        {{ PersianNumbers::toNumber($product->percentage) }} %
                                        <br>
                                        {{PersianNumbers::toNumber(jdate($product->discount_expiry)->format('Y/m/d'))}}
                                        @else
                                        <span style="color: red;">ندارد</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>{{PersianNumbers::toNumber($product->inventory)}}</div>
                                </div>
                            </td>
                            <td>
                                <a href="{{route('products.edit',$product->id)}}" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form id="destroy-form" style="display: inline;" action="{{route('products.destroy',$product->id)}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick="return confirmSubmit()" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <!-- Increase Inventory -->
                                <form action="{{route('products.increaseInventory',$product->id)}}" method="get" class="d-inline">
                                    <input type="number" style="width: 80px;" class="py-1 px-3" name="count">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-arrow-up" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <!-- Discount -->
                                <form action="{{route('products.discount',$product->id)}}" method="get" class="d-inline">
                                    <input type="number" style="width: 80px;" class="py-1 px-3" name="discount" placeholder="٪">
                                    <input type="number" style="width: 60px;" name="day" placeholder="روز">
                                    <select name="month">
                                        <option value="1">فروردین</option>
                                        <option value="2">اردیبهشت</option>
                                        <option value="3">خرداد</option>
                                        <option value="4">تیر</option>
                                        <option value="5">مرداد</option>
                                        <option value="6">شهریور</option>
                                        <option value="7">مهر</option>
                                        <option value="8">آبان</option>
                                        <option value="9">آذر</option>
                                        <option value="10">دی</option>
                                        <option value="11">بهمن</option>
                                        <option value="12">اسفند</option>
                                    </select>
                                    <input type="number" style="width: 90px;" name="year" placeholder="سال">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-arrow-up" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                محصولی یافت نشد <i class="fa fa-car" aria-hidden="true"></i>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    function confirmSubmit() {
        return confirm("آیا می خواهید محصول انتخاب شده حذف شود؟");
        // Returns `true` if "OK", `false` if "Cancel"
    }
</script>

@endsection