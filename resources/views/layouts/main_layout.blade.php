<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <title>فروشگاه لوازم یدکی خودرو سامان یدک</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Vazir Font -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- owl.carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{asset('images/logo.png')}}" alt="سامان یدک">
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ml-auto text-right">
                    <li @class(['nav-item','active'=>request()->routeIs('home')])>
                        <a class="nav-link" href="{{route('home')}}">خانه</a>
                    </li>
                    <li @class(['nav-item','active'=>request()->routeIs('product.productFilters')])>
                        <a class="nav-link" href="{{route('product.productFilters')}}">محصولات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">دسته بندی ها</a>
                    </li>
                    <li @class(['nav-item','active'=>request()->routeIs('contact-us')])>
                        <a class="nav-link" href="{{route('contact-us')}}">تماس با ما</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    <div class="dropdown ml-3">
                        <div class="input-group" style="width: 250px;">
                            <input type="text" class="form-control" id="productSearch" placeholder="جستجوی محصول..."
                                autocomplete="off" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <button class="btn btn-outline-light" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <div class="dropdown-menu search-results-dropdown" aria-labelledby="productSearch">
                            <div class="dropdown-item disabled text-center">محصولات را جستجو کنید</div>
                        </div>
                    </div>

                    <button id="cartBtn" class="btn btn-outline-light mx-3 position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cartCount"
                            class="position-absolute top-0 start-100 translate-middle badge badge-pill badge-danger"></span>
                    </button>

                    @guest
                    <a href="{{route('auth.form.login')}}" class="btn btn-outline-light">
                        <i class="fas fa-user"></i> ورود/ثبت نام
                    </a>
                    @endguest
                    @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                            {{Auth::user()->name}}
                        </button>
                        <div class="dropdown-menu text-right">
                            <a class="dropdown-item" href="{{Auth::user()->role=="admin" ? route('users.index') : route('userdashboard.profile')}}">حساب کاربری <i class="fas fa-user"></i></a>
                            <div class="dropdown-divider"></div>
                            <form action="{{route('auth.logout')}}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item">خروج از حساب <i class="fas fa-sign-out-alt"></i></button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar">
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">سبد خرید</h5>
                <button id="closeCart" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <hr>

            <div id="cartItems" class="mb-3">

            </div>

            <div class="border-top pt-3">
                <div class="d-flex justify-content-between mb-2">
                    <span>جمع کل:</span>
                    <span id="cartTotal" class="font-weight-bold">۳۴۰,۰۰۰ تومان</span>
                </div>
                <a href="{{route('basket.cart')}}" class="btn btn-danger btn-block">تکمیل سفارش</a>
            </div>
        </div>
    </div>
    <div class="cart-overlay"></div>

    @yield('content')

    <!-- Footer -->
    <footer class="footer py-5 text-right"> <!-- Added text-right -->
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="mb-3">درباره ما</h5>
                    <p>فروشگاه اینترنتی لوازم یدکی سامان یدک با بیش از 10 سال سابقه در زمینه تأمین و توزیع قطعات با کیفیت
                        خودرو</p>
                </div>
                <div class="col-lg-2 col-6 mb-4 mb-lg-0">
                    <h5 class="mb-3">لینک های مفید</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{route('home')}}" class="text-white">خانه</a></li>
                        <li class="mb-2"><a href="{{route('product.productFilters')}}" class="text-white">محصولات</a></li>
                        <li class="mb-2"><a href="#" class="text-white">تماس با ما</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-6 mb-4 mb-lg-0">
                    <h5 class="mb-3">تماس با ما</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt ml-2"></i> ارومیه، خیابان رودکی</li>
                        <li class="mb-2"><i class="fas fa-phone ml-2"></i> 044-12345678</li>
                        <li class="mb-2"><i class="fas fa-envelope ml-2"></i> info@carparts.com</li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="mb-3">شبکه های اجتماعی</h5>
                    <div>
                        <a href="https://www.instagram.com/saman_parts/" class="text-white mr-3"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-white mr-3"><i class="fab fa-telegram fa-2x"></i></a>
                        <a href="#" class="text-white mr-3"><i class="fab fa-whatsapp fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-light mt-4">
            <div class="text-center pt-3">
                <p class="mb-0">©  کلیه حقوق برای فروشگاه لوازم یدکی سامان یدک محفوظ است.</p>
            </div>
        </div>
    </footer>

    <script>
        var owl = $('.owl-carousel');
        owl.owlCarousel({
            items: 4,
            loop: true,
            margin: 30,
            autoplay: true,
            autoplayTimeout: 3500,
            autoplayHoverPause: true,
            rtl: true,
            navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                600: {
                    items: 2,
                    nav: false
                },
                1000: {
                    items: 4,
                    nav: true,
                    loop: true
                }
            }
        });
        $('.play').on('click', function() {
            owl.trigger('play.owl.autoplay', [1000])
        })
        $('.stop').on('click', function() {
            owl.trigger('stop.owl.autoplay')
        })
    </script>

    <script>
        $(document).ready(function() {
            let cart = [];

            function fillCart() {
                cart = [];
                $.getJSON("{{route('basket.get')}}", function(response) {
                    let data = JSON.parse(response);
                    Object.entries(data).forEach(([key, item]) => {
                        cart.push({
                            'id': key.toString(),
                            'name': item['title'],
                            'price': item['price'],
                            'img': item['image'],
                            'quantity': item['quantity']
                        });
                    });
                    // Initialize cart
                    updateCart();
                });
                // Initialize cart
                updateCart();
            }

            fillCart();


            // Navbar toggle fix
            $('.navbar-toggler').click(function() {
                if ($('#navbarContent').hasClass('show')) {
                    $('#navbarContent').collapse('hide');
                } else {
                    $('#navbarContent').collapse('show');
                }
            });

            // Cart toggle
            $('#cartBtn').click(function() {
                $('.cart-sidebar').addClass('show');
                $('.cart-overlay').addClass('show');
            });

            $('#closeCart, .cart-overlay').click(function() {
                $('.cart-sidebar').removeClass('show');
                $('.cart-overlay').removeClass('show');
            });

            // Add to cart
            $('.add-to-cart').click(function() {
                const id = $(this).data('id');
                $.get("{{route('basket.add')}}/?product_id=" + id, function(data, status) {
                    if (status == 'success') {
                        fillCart();
                    }
                });
                $('.cart-sidebar').addClass('show');
                $('.cart-overlay').addClass('show');

                // Show success message
                Swal.fire({
                    title: "موفق",
                    text: 'محصول به سبد خرید اضافه شد',
                    icon: "success",
                    confirmButtonText: "تائید",
                });
            });

            // Update cart function
            function updateCart() {
                $('#cartCount').text(cart.reduce((sum, item) => sum + item.quantity, 0));

                if (cart.length === 0) {
                    $('#cartItems').html(`
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <p>سبد خرید شما خالی است</p>
                        </div>
                    `);
                    $('#cartTotal').text('۰ تومان');
                    return;
                }

                let cartHTML = '';
                let total = 0;

                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;

                    cartHTML += `
                        <div class="d-flex mb-3 cart-item" data-id="${item.id}">
                            <img src="${item.img}" alt="${item.name}" style="width: 60px; height: 60px; object-fit: contain;" class="border rounded">
                            <div class="flex-grow-1 mr-3">
                                <h6 class="mb-1">${item.name}</h6>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <span class="text-muted">${item.price.toLocaleString()} تومان</span>
                                        <span class="mx-2">×</span>
                                        <span class="text-muted">${item.quantity}</span>
                                    </div>
                                    <span>${itemTotal.toLocaleString()} تومان</span>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-outline-danger remove-item" data-id="${item.id}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                });

                $('#cartItems').html(cartHTML);
                $('#cartTotal').text(total.toLocaleString() + ' تومان');

                // Add remove item functionality
                $('.remove-item').click(function() {
                    const id = $(this).data('id');
                    $.get("{{route('basket.remove')}}/?product_id=" + id, function(data, status) {
                        if (status == 'success') {
                            fillCart();
                        }
                    });
                });
            }
        });

        function changeMainImage(src, alt) {
            // Change main image source and alt text
            $('#mainProductImage').attr('src', src).attr('alt', alt);

            // Add active class to clicked thumbnail and remove from others
            $('.thumbnail-container img').removeClass('active-thumbnail');
            $(event.target).addClass('active-thumbnail');
        }

        $('#productSearch').on('input', function() {
            var query = $(this).val().trim();
            var dropdown = $('.search-results-dropdown');

            if (query.length >= 2) {
                $.ajax({
                    url: '{{route('search.products')}}',
                    method: 'GET',
                    data: {
                        q: query
                    },
                    success: function(response) {
                        dropdown.empty();

                        if (response.results && response.results.length > 0) {
                            $.each(response.results, function(index, product) {
                                dropdown.append(
                                    '<a class="dropdown-item d-flex justify-content-between" href="' + product.url + '">' +
                                    '<span>' + product.name + '</span>' +
                                    (product.price ? '<span class="text-muted">' + product.price + '</span>' : '') +
                                    '</a>'
                                );
                            });
                        } else {
                            dropdown.append('<div class="dropdown-item disabled text-center">محصولی یافت نشد</div>');
                        }
                        // Show the dropdown
                        dropdown.addClass('show');
                    },
                    error: function() {
                        dropdown.html('<div class="dropdown-item disabled text-center">خطا در جستجو</div>');
                    }
                });
            } else if (query.length === 0) {
                dropdown.html('<div class="dropdown-item disabled text-center">محصولات را جستجو کنید</div>');
                // Hide the dropdown when search is empty
                dropdown.removeClass('show');
            }
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.search-results-dropdown').removeClass('show');
            }
        });

        $("#auth-btn").click(function() {
            $(".user-button-items").slideToggle(200);
        });
    </script>
</body>