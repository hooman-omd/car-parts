@extends('user.dashboard')

@section('user-title', 'اطلاعات حساب کاربری')

@section('user-content')
<form method="POST" action="{{route('userdashboard.profile.edit')}}">
    @csrf
    @method('patch')
    @session('success')
        <x-success-alert>{{$value}}</x-success-alert>
    @endsession
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">نام کامل</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', auth()->user()->name) }}">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">آدرس ایمیل</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone">تلفن همراه</label>
                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                       id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        
    </div>
    
    <hr>
    
    <h5 class="mb-3">تغییر رمز عبور</h5>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="current_password">رمز عبور فعلی</label>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                       id="current_password" name="current_password">
                @error('current_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="new_password">رمز عبور جدید</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="new_password" name="password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="new_password_confirmation">تکرار رمز عبور جدید</label>
                <input type="password" class="form-control" 
                       id="new_password_confirmation" name="password_confirmation">
            </div>
        </div>
    </div>
    
    <div class="text-left mt-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save ml-2"></i> ذخیره تغییرات
        </button>
    </div>
</form>
@endsection
