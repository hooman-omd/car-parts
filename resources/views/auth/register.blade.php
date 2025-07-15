@extends('layouts.main_layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <!-- Register Form -->
        <div class="col-8">
            <div class="card shadow-sm" style="text-align: right;">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">ثبت نام جدید</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('auth.register')}}">
                        @csrf
                        <div class="form-group">
                            <label for="registerName">نام کاربری</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="registerName" name="name" 
                                   placeholder="نام و نام خانوادگی خود را وارد کنید" value="{{old('name')}}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="registerPhone">شماره تلفن</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="registerPhone" name="phone" 
                                   placeholder="مثال: 09123456789" value="{{old('phone')}}">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="registerEmail">ایمیل</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="registerEmail" name="email" 
                                   placeholder="ایمیل خود را وارد کنید" value="{{old('email')}}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="registerPassword">رمز عبور</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="registerPassword" name="password" 
                                   placeholder="حداقل 8 کاراکتر">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="registerPasswordConfirm">تکرار رمز عبور</label>
                            <input type="password" class="form-control" 
                                   id="registerPasswordConfirm" name="password_confirmation" 
                                   placeholder="رمز عبور خود را مجدداً وارد کنید">
                        </div>
                        <button type="submit" class="btn btn-success btn-block py-2">ثبت نام</button>
                    </form>
                    <div class="text-center mt-3">
                        <p class="mb-0">با ثبت نام، <a href="#" class="text-primary">قوانین و شرایط</a> را پذیرفته اید.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
