@extends('layouts.main_layout')

@section('content')
<x-breadcrumb>ورود</x-breadcrumb>
<div class="container py-5">
    <div class="row justify-content-center">
        <!-- Login Form -->
        <div class="col-8">

            <div class="card shadow-sm" style="text-align: right;">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">ورود به حساب کاربری</h4>
                </div>
                <div class="card-body">
                    @session('success')
                        <x-success-alert>{{$value}}</x-success-alert>
                    @endsession
                    @error('attempt-failed')
                        <x-fail-alert>{{ $message }}</x-fail-alert>
                    @enderror
                    <form method="POST" action="{{route('auth.login')}}">
                        @csrf
                        <div class="form-group">
                            <label for="loginUsername">نام کاربری</label>
                            <input type="text" value="{{old('name')}}" class="form-control @error('name') is-invalid @enderror" 
                                   id="loginUsername" name="name" 
                                   placeholder="نام کاربری خود را وارد کنید">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="loginPassword">رمز عبور</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="loginPassword" name="password" 
                                   placeholder="رمز عبور خود را وارد کنید">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label mr-3" for="remember">مرا به خاطر بسپار</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block py-2">ورود</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="{{route('auth.resest-password.set-email')}}" class="text-muted">رمز عبور خود را فراموش کرده اید؟</a>
                    </div>
                    <hr class="my-4">
                    <div class="text-center">
                        <p class="mb-2">حساب کاربری ندارید؟ <a href="{{route('auth.register')}}">ثبت نام کنید</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
