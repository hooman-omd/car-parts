<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرداخت ناموفق</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-danger text-white text-center py-3">
                        <h3 class="mb-0">
                            <i class="fas fa-times-circle ml-2"></i> پرداخت ناموفق بود
                        </h3>
                    </div>

                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-times-circle fa-5x text-danger mb-3"></i>
                            <h4 class="text-danger">پرداخت شما تکمیل نشد</h4>
                            <p class="text-muted mt-3">
                                متاسفانه پرداخت شما با مشکل مواجه شد. در صورت کسر وجه از حساب شما، مبلغ تا ۷۲ ساعت آینده بازگشت داده خواهد شد.
                            </p>
                        </div>

                        <div class="alert alert-warning text-right">
                            <h5 class="alert-heading">علل احتمالی:</h5>
                            <ul class="mb-0 pr-3">
                                <li>عدم تأیید پرداخت توسط بانک</li>
                                <li>کمبود موجودی حساب</li>
                                <li>انصراف از پرداخت</li>
                                <li>مشکلات فنی موقت</li>
                            </ul>
                        </div>

                        <div class="mt-4">
                            <p class="mb-3">
                                شماره سفارش : 
                                <span class="font-weight-bold">{{request()->route('order_id')}}</span>
                            </p>
                            <p class="text-muted">
                                در صورت نیاز به کمک می‌توانید با پشتیبانی تماس بگیرید.
                            </p>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-center">
                            <a href="{{route('home')}}" class="btn btn-outline-secondary">
                                <i class="fas fa-home ml-2"></i> بازگشت به صفحه اصلی
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>