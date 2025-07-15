@extends('layouts.dashboard_layout')

@section('content')
@use('App\Utilities\PersianNumbers')
<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">بروزرسانی دسته بندی <b>"{{$category->title}}"</b></h2>
    </div>

    <!-- Insert data -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mb-3 mb-md-0">
                    <form action="{{route('categories.update',$category->id)}}" method="post" enctype="multipart/form-data">
                        <div class="input-group">
                            @csrf
                            @method('patch')
                            <input name="title" value="{{old('title') ?? $category->title}}" type="text" class="form-control me-4">
                            <input type="file" name="thumbnail" class="form-control image-upload" accept="image/*">
                            @isset($category->thumbnail) 
                                <img src="{{asset($category->thumbnail)}}" class="rounded border ms-4" style="height: 40px; width: auto;" alt="{{$category->title}}">
                            @endisset
                            <button class="btn btn-primary ms-4" type="submit">
                                <i class="fa fa-tags me-2"></i> بروزرسانی دسته بندی
                            </button>
                            <a class="btn btn-danger ms-4" href="{{route('categories.index')}}">
                                <i class="fa fa-times me-2"></i> لغو
                            </a>
                        </div>
                    </form>
                    <p class="badge bg-success my-4 mx-2 fs-6">تاریخ ایجاد : {{PersianNumbers::toNumber(jdate($category->created_at)->format('Y/m/d'))}}</p>
                    <p class="badge bg-info my-4 mx-2 fs-6">تاریخ آخرین بروزرسانی : {{PersianNumbers::toNumber(jdate($category->updated_at)->format('Y/m/d'))}}</p>
                    @error('title')
                    <x-fail-alert>{{$message}}</x-fail-alert>
                    @enderror
                    @error('thumbnail')
                    <x-fail-alert>{{$message}}</x-fail-alert>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    @endsection