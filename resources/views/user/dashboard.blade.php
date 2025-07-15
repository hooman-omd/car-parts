@extends('layouts.main_layout')

@section('content')
<div class="container">
    <div class="container-fluid py-4 user-dashboard text-right">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <!-- User Info -->
                    <div class="p-4 text-center border-bottom">
                        <div class="user-avatar mb-3">
                            <img src="{{ asset('images/user-avatar.png') }}" alt="پروفایل کاربر" class="rounded-circle" width="80">
                        </div>
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="nav flex-column user-sidebar-nav">
                        <a href="{{route('userdashboard.profile')}}" class="nav-link {{ request()->routeIs('userdashboard.profile') ? 'active' : '' }}">
                            <i class="fas fa-user-edit ml-2"></i> اطلاعات حساب
                        </a>
                        <a href="{{route('userdashboard.orders')}}" class="nav-link {{ request()->routeIs('userdashboard.orders') || request()->routeIs('userdashboard.orderDetail') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list ml-2"></i> تاریخچه سفارشات
                        </a>
                        <a href="{{route('userdashboard.addresses')}}" class="nav-link {{ request()->routeIs('userdashboard.addresses') ? 'active' : '' }}">
                            <i class="fas fa-map-marker-alt ml-2"></i> آدرس‌ها
                        </a>
                        <hr class="my-1 mx-3">
                        <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt ml-2"></i> خروج از سیستم
                        </a>
                        <form id="logout-form" action="{{route('auth.logout')}}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">@yield('user-title')</h4>
                </div>
                <div class="card-body">
                    @yield('user-content')
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
