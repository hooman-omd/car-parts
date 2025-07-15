@extends('layouts.dashboard_layout')

@section('content')
@use('App\Utilities\PersianNumbers')
<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">لیست سفارشات <i class="fas fa-chart-bar me-2"></i></h2>
    </div>

    <!-- Insert data -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10 mb-3 mb-md-0">
                    <form action="{{route('orders.get')}}" method="get">
                        <div class="input-group">
                            <input name="order_id" value="{{request()->query('order_id')}}" type="number" class="form-control me-4" placeholder="شماره سفارش">
                            <input name="user" value="{{request()->query('user')}}" type="text" class="form-control me-4" placeholder="نام کاربری مشتری">
                            <select name="status" class="form-select">
                                <option selected disabled>وضعیت پرداخت</option>
                                <option value="paid">پرداخت شده</option>
                                <option value="draft">در انتظار پرداخت</option>
                            </select>
                            <button class="btn btn-primary ms-4" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i> جستجو
                            </button>
                            <a href="{{url()->current()}}" class="btn btn-danger mx-4">نمایش همه</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @session('success')
    <x-success-alert>{{$value}}</x-success-alert>
@endsession

    <!-- Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="90">ردیف</th>
                            <th>شماره سفارش</th>
                            <th>نام کاربری مشتری</th>
                            <th>مبلغ سفارش</th>
                            <th>شماره تراکنش</th>
                            <th>وضعیت</th>
                            <th>کد تخفیف</th>
                            <th>تاریخ ثبت سفارش</th>
                            <th>تاریخ پرداخت</th>
                            <th>مشاهده جزئیات</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{PersianNumbers::toNumber($loop->iteration)}}</td>
                            <td>{{PersianNumbers::toNumber($order->id)}}</td>
                            <td>{{$order->user->name}}</td>
                            <td>{{PersianNumbers::toPrice($order->total_price)}} تومان</td>
                            <td>{{PersianNumbers::toNumber($order->payment->ref_id ?? 'پرداخت نشده')}}</td>
                            <td>{{$order->status == 'paid' ? 'پرداخت شده':'پرداخت نشده'}}</td>
                            <td>{{$order->discount_code_id == null ? 'بدون کد تخفیف':$order->discountCode->code}}</td>
                            <td>{{PersianNumbers::toNumber(jdate($order->created_at)->format('H:i - Y/m/d'))}}</td>
                            <td>{{empty($order->payment) ? 'پرداخت نشده':PersianNumbers::toNumber(jdate($order->payment->created_at)->format('H:i - Y/m/d'))}}</td>
                            <td><button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#myModal" details-data="{{$order->cart}}">
                                    مشاهده
                                </button></td>
                            <td>
                                <form id="destroy-form" style="display: inline;" action="{{route('orders.delete',$order->id)}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick="return confirmSubmit()" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">
                                سفارشی یافت نشد <i class="fas fa-chart-bar me-2"></i>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
 <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">جزئیات سفارش</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <table id="details-modal" class="table table-striped"></table>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">خروج</button>
      </div>

    </div>
  </div>
</div>




<script>
    function confirmSubmit() {
        return confirm("آیا می خواهید سفارش انتخاب شده حذف شود؟");
        // Returns `true` if "OK", `false` if "Cancel"
    }

    $(document).ready(function() {
    $('#myModal').on('show.bs.modal', function(event) {
        // Get the button that triggered the modal
        var button = $(event.relatedTarget);
        
        // Get the JSON data from the button's details-data attribute
        var detailsData = button.attr('details-data');
        
        try {
            // Parse the JSON data
            var items = JSON.parse(detailsData);
            
            // Prepare the table HTML
            var tableHtml = `
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>تصویر</th>
                            <th>عنوان</th>
                            <th>قیمت</th>
                            <th>تعداد</th>
                            <th>جمع</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            // Calculate total
            var total = 0;
            
            // Add each item to the table
            $.each(items, function(index, item) {
                var itemTotal = item.price * item.quantity;
                total += itemTotal;
                
                tableHtml += `
                    <tr>
                        <td><img src="${item.image}" alt="${item.title}" style="width: 50px; height: auto;"></td>
                        <td>${item.title}</td>
                        <td>${item.price.toLocaleString()} تومان</td>
                        <td>${item.quantity}</td>
                        <td>${itemTotal.toLocaleString()} تومان</td>
                    </tr>
                `;
            });
            
            // Add total row
            tableHtml += `
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">جمع کل:</th>
                            <th>${total.toLocaleString()} تومان</th>
                        </tr>
                    </tfoot>
                </table>
            `;
            
            // Insert the table into the modal body
            $('#details-modal').html(tableHtml);
            
        } catch (e) {
            // Handle JSON parse error
            $('#details-modal').html('<div class="alert alert-danger">خطا در نمایش جزئیات سفارش</div>');
            console.error('Error parsing details data:', e);
        }
    });
    
    // Clear modal content when hidden
    $('#myModal').on('hidden.bs.modal', function() {
        $('#details-modal').empty();
    });
});
</script>

@endsection