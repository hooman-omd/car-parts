<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/dashboard-style.css')}}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body onload="checkNavItem()">
    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h4 class="text-center mb-4">پنل مدیریت</h4>
        <hr class="bg-light">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{request()->routeIs('users.*')?'active':null}}" href="{{route('users.index')}}">
                    <i class="fas fa-users me-2"></i> مدیریت کاربران
                </a>
            </li>
            <li class="nav-item">
                <a id="productMenu" class="nav-link cursor-point {{request()->routeIs('products.*')?'active':null}}" onclick="toggleSubMenu('productSubmenu')">
                    <i class="fas fa-box me-2"></i> مدیریت محصولات <i class="fas fa-chevron-down me-2 float-start"></i>
                </a>
                <!-- Submenu for Products Management -->
                <div class="collapse" id="productSubmenu">
                    <ul class="nav flex-column ps-4 mt-2">
                        <li class="nav-item">
                            <a class="nav-link {{request()->routeIs('products.index')?'active':null}}" href="{{route('products.index')}}">
                                <i class="fas fa-list me-2"></i> لیست محصولات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{request()->routeIs('products.create')?'active':null}}" href="{{route('products.create')}}">
                                <i class="fas fa-plus-circle me-2"></i> افزودن محصول جدید
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link {{request()->routeIs('categories.*')?'active':null}}" href="{{route('categories.index')}}">
                    <i class="fa fa-tags me-2"></i> دسته بندی ها
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{request()->routeIs('codes.*')?'active':null}}" href="{{route('codes.index')}}">
                    <i class="fas fa-percent"></i> کد های تخفیف
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.get') }}">
                    <i class="fas fa-chart-bar me-2"></i> سفارشات
                </a>
            </li>
            <li class="nav-item">
                <form action="{{route('auth.logout')}}" method="post" id="panelLogout">
                    @csrf
                    <a class="nav-link cursor-point" onclick="logout()">
                        <i class="fas fa-sign-out-alt"></i> خروج از حساب
                    </a>
                </form>
            </li>
        </ul>
    </div>

    @yield('content')

    <script>
        function toggleSubMenu(id) {
            let subMenu = document.getElementById(id);
            if (subMenu.style.display != "block") {
                subMenu.style.display = 'block';
            } else {
                subMenu.style.display = 'none';
            }
        }

        function logout() {
            let form = document.getElementById('panelLogout');
            form.submit();
        }

        function checkNavItem() {
            let menu = document.getElementById('productMenu');
            let subMenu = document.getElementById('productSubmenu');
            if (menu.classList.contains('active')) {
                subMenu.style.display = 'block';
            }
        }
    </script>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>