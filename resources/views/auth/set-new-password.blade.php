@extends('layouts.main_layout')

@section('content')
<x-breadcrumb>ثبت رمز عبور جدید</x-breadcrumb>
<div class="container py-5">
    <div class="row justify-content-center">
        <!-- Login Form -->
        <div class="col-8">
            <div class="card shadow-sm" style="text-align: right;">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">بازیابی رمز عبور</h4>
                </div>
                <div class="card-body">
                    @session('fail')
                        <x-fail-alert>{{$value}}</x-fail-alert>
                    @endsession
                    @error('resetKey')
                        <x-fail-alert>{{$message}}</x-fail-alert>
                    @enderror
                    <form method="POST" action="{{route('auth.set-new-password-post')}}">
                        @csrf
                        <input type="hidden" name="resetKey" value="{{request()->query('resetKey')}}">
                        <div class="form-group">
                            <h4 class="my-4 text-muted">رمز عبور جدید خود را وارد نمائید <i class="fas fa-user-lock"></i></h4>
                            <label for="password">رمز عبور جدید</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password"
                                placeholder="رمز عبور">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password-confirmation">تکرار رمز عبور</label>
                            <input type="password" class="form-control"
                                id="password-confirmation" name="password_confirmation"
                                placeholder="تکرار رمز عبور">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block py-2 my-4">ثبت رمز عبور جدید</button>
                    </form>
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