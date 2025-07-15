@use('App\Utilities\PersianNumbers')
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرداخت موفق | سامان یدک</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Vazir', Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .payment-success-header {
            background-color: #28a745;
            color: white;
            border-radius: 5px 5px 0 0;
            padding: 25px;
            text-align: center;
        }
        .payment-success-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .tracking-code {
            background-color: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 20px;
            display: inline-block;
            margin: 10px 0;
            font-size: 1.1rem;
        }
        .order-card {
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .order-card-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-weight: bold;
        }
        .order-summary {
            padding: 15px;
        }
        .action-buttons {
            margin-top: 30px;
            text-align: center;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                background: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="payment-success-header">
            <div class="payment-success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>پرداخت شما با موفقیت انجام شد</h2>
            <div class="tracking-code">
                شماره تراکنش : <strong>{{PersianNumbers::toNumber($payment->ref_id)}}</strong>
            </div>
            <p>سفارش شما ثبت شد و در حال پردازش است</p>
        </div>

        <div class="card mt-4 order-card">
            <div class="order-card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-receipt ml-2"></i> خلاصه سفارش</span>
                <span class="badge bg-success">شماره سفارش: {{PersianNumbers::toNumber($order->id)}}</span>
            </div>
            <div class="order-summary">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h5><i class="fas fa-user ml-2"></i> اطلاعات خریدار</h5>
                        <p>نام:  {{$order->user->name}}</p>
                        <p>تلفن: {{PersianNumbers::toNumber($order->user->phone)}}</p>
                        <p>ایمیل: {{$order->user->email}}</p>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-truck ml-2"></i> اطلاعات ارسال</h5>
                        <p>آدرس : {{PersianNumbers::toNumber($address->address)}}</p>
                        <p>تحویل گیرنده: {{$address->receiver_name}} ({{PersianNumbers::toNumber($address->receiver_phone)}})</p>
                    </div>
                </div>
                @php($products = json_decode($order->cart,true))
                <h5 class="mt-4"><i class="fas fa-boxes ml-2"></i> اقلام سفارش</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>محصول</th>
                                <th class="text-center">تعداد</th>
                                <th class="text-left">قیمت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>
                                    {{$product['title']}}
                                </td>
                                <td class="text-center">{{PersianNumbers::toNumber($product['quantity'])}}</td>
                                <td class="text-left">{{PersianNumbers::toPrice($product['price'])}} تومان</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="text-left mt-3">
                    <h5>جمع کل: {{PersianNumbers::toPrice($order->total_price)}} تومان</h5>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button onclick="window.print()" class="btn btn-outline-dark no-print">
                <i class="fas fa-print ml-2"></i> چاپ فاکتور
            </button>
            <a href="{{route('home')}}" class="btn btn-primary no-print">
                <i class="fas fa-home ml-2"></i> بازگشت به صفحه اصلی
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>