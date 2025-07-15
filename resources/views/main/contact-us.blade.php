@extends('layouts.main_layout')
@section('content')
<x-breadcrumb>تماس با ما</x-breadcrumb>
<div class="container my-5">
    <!-- Custom Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4 text-right">تماس با ما</h2>
        </div>
    </div>

    <div class="row" dir="rtl">
        <!-- Contact Info -->
        <div class="col-lg-4 mb-5 mb-lg-0">
            <div class="card shadow-sm h-100">
                <div class="card-body text-right">
                    <h4 class="card-title text-primary mb-4">اطلاعات تماس</h4>
                    
                    <div class="contact-info mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <i class="fas fa-map-marker-alt text-primary ml-3 mt-1"></i>
                            <div>
                                <h5 class="mb-1">آدرس</h5>
                                <p class="text-muted mb-0">ارومیه، خیابان رودکی، فروشگاه لوازم یدکی سامان یدک</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <i class="fas fa-phone text-primary ml-3 mt-1"></i>
                            <div>
                                <h5 class="mb-1">تلفن</h5>
                                <p class="text-muted mb-0">044-12345678</p>
                                <p class="text-muted mb-0">09141234567</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <i class="fas fa-envelope text-primary ml-3 mt-1"></i>
                            <div>
                                <h5 class="mb-1">ایمیل</h5>
                                <p class="text-muted mb-0">info@saman-yadak.com</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start">
                            <i class="fas fa-clock text-primary ml-3 mt-1"></i>
                            <div>
                                <h5 class="mb-1">ساعات کاری</h5>
                                <p class="text-muted mb-0">شنبه تا چهارشنبه: 8:30 صبح تا 5 بعدازظهر</p>
                                <p class="text-muted mb-0">پنجشنبه: 8:30 صبح تا 1 بعدازظهر</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3 text-right">ما را دنبال کنید</h5>
                    <div class="social-media text-right">
                        <a href="#" class="btn btn-outline-primary btn-sm ml-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-primary btn-sm ml-2"><i class="fab fa-telegram"></i></a>
                        <a href="#" class="btn btn-outline-primary btn-sm"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body text-right">
                    <h4 class="card-title text-primary mb-4">فرم تماس با ما</h4>
                    <p class="text-muted mb-4 text-right">برای ارتباط با ما، فرم زیر را پر کنید. در اسرع وقت با شما تماس خواهیم گرفت.</p>
                    
                    <form id="contactForm" method="POST" action="#">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name" class="float-right">نام و نام خانوادگی <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-right" id="name" name="name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phone" class="float-right">شماره تماس <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control text-left" dir="ltr" id="phone" name="phone" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="float-right">آدرس ایمیل</label>
                            <input type="email" class="form-control text-left" dir="ltr" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="subject" class="float-right">موضوع <span class="text-danger">*</span></label>
                            <select class="form-control text-right" id="subject" name="subject" required>
                                <option value="" selected disabled>انتخاب کنید</option>
                                <option value="پیشنهاد">پیشنهاد</option>
                                <option value="انتقاد">انتقاد</option>
                                <option value="سوال درباره محصول">سوال درباره محصول</option>
                                <option value="پیگیری سفارش">پیگیری سفارش</option>
                                <option value="سایر">سایر</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message" class="float-right">متن پیام <span class="text-danger">*</span></label>
                            <textarea class="form-control text-right" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary float-left">ارسال پیام</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Map -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3229.123456789012!2d45.12345678901234!3d37.12345678901234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzfCsDA3JzI0LjQiTiA0NcKwMDcnMjQuNCJF!5e0!3m2!1sen!2sir!4v1234567890123!5m2!1sen!2sir" 
                            width="100%" 
                            height="400" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#contactForm').submit(function(e) {
            e.preventDefault();
            
            // Here you would typically send the form data via AJAX
            // For demonstration, we'll show a success message
            
            Swal.fire({
                title: "پیام شما ارسال شد!",
                text: "با تشکر از ارتباط شما. در اسرع وقت پاسخ داده خواهد شد.",
                icon: "success",
                confirmButtonText: "تائید",
            }).then(() => {
                $('#contactForm')[0].reset();
            });
        });
    });
</script>
@endsection