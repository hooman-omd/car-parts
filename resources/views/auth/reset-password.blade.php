@extends('layouts.main_layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <!-- Login Form -->
        <div class="col-8">
            <div class="card shadow-sm" style="text-align: right;">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">بازیابی رمز عبور</h4>
                </div>
                <div class="card-body">
                     @session('success')
                        <x-success-alert>{{$value}}</x-success-alert>
                    @endsession
                    @session('fail')
                        <x-fail-alert>{{$value}}</x-fail-alert>
                    @endsession
                    <form method="POST" action="{{route('auth.set-email')}}">
                        @csrf
                        <div class="form-group">
                            <h4 class="my-4 text-muted">برای بازیابی رمز عبور، ایمیل خود را وارد کنید <i class="fas fa-envelope ml-2"></i></h4>
                            <label for="email">ایمیل</label>
                            <input type="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" 
                                   placeholder="ایمیل خود را وارد کنید">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-block py-2 my-4">بازیابی</button>
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
