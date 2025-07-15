@extends('layouts.dashboard_layout')

@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">افزودن محصول جدید</h2>
    </div>

    @if($errors->any())
    <x-fail-alert>
        @foreach($errors->all() as $error)
        <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{$error}}<br>
        @endforeach
    </x-fail-alert>
    @endif

    @session('success-message')
    <x-success-alert>{{$value}}</x-success-alert>
    @endsession

    <!-- Product Form -->
    <div class="card">
        <div class="card-body">
            <form id="productForm" method="post" action="{{route('products.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Category Selection -->
                    <div class="col-md-6 mb-3">
                        <label for="categorySelect" class="form-label">دسته بندی</label>
                        <select name="category_id" class="form-select" id="categorySelect">
                            <option selected disabled>انتخاب دسته بندی...</option>
                            @foreach($categories as $category)
                                <option @selected(old('category_id')==$category->id) value="{{$category->id}}">{{$category->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Product Title -->
                    <div class="col-md-6 mb-3">
                        <label for="productTitle" class="form-label">عنوان محصول</label>
                        <input name="title" value="{{old('title')}}" type="text" class="form-control" id="productTitle" placeholder="عنوان محصول را وارد کنید">
                    </div>

                    <!-- Price -->
                    <div class="col-md-6 mb-3">
                        <label for="productPrice" class="form-label">قیمت (تومان)</label>
                        <input name="price" value="{{old('price')}}" type="number" class="form-control" id="productPrice" placeholder="قیمت محصول">
                    </div>

                    <!-- Engine Type -->
                    <div class="col-md-6 mb-3">
                        <label for="engineType" class="form-label">نوع موتور</label>
                        <input name="engine_type" value="{{old('engine_type')}}" type="text" class="form-control" id="engineType" placeholder="نوع موتور">
                    </div>

                    <!-- Guarantee -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">گارانتی</label>
                        <div class="form-check">
                            <input name="has_guarantee" @checked(old('has_guarantee')) class="form-check-input" type="checkbox" id="hasGuarantee">
                            <label class="form-check-label" for="hasGuarantee">
                                دارای گارانتی
                            </label>
                        </div>
                    </div>

                    <!-- Country of Origin -->
                    <div class="col-md-6 mb-3">
                        <label for="countryOrigin" class="form-label">کشور سازنده</label>
                        <input name="country_of_origin" value="{{old('country_of_origin')}}" type="text" class="form-control" id="countryOrigin" placeholder="کشور سازنده">
                    </div>

                    <!-- Inventory -->
                    <div class="col-md-6 mb-3">
                        <label for="productInventory" class="form-label">موجودی انبار</label>
                        <input name="inventory" value="{{old('inventory')}}" type="number" class="form-control" id="productInventory" placeholder="تعداد موجودی">
                    </div>

                    <!-- Cars -->
                    <div class="col-12 mb-3">
                        <label class="form-label mt-3">قابلیت استفاده</label>
                        <fieldset class="border rounded p-4 mb-3">
                            @foreach($cars as $car)
                                <div class="form-check form-check-inline">
                                    <input name="cars[]" @checked(in_array($car->id, old('cars', []))) class="form-check-input" type="checkbox" value="{{$car->id}}">
                                    <label class="form-check-label" for="inlineCheckbox1">{{$car->model}}</label>
                                </div>
                            @endforeach
                        </fieldset>
                    </div>

                    <!-- Description -->
                    <div class="col-12 mb-3">
                        <label for="productDescription" class="form-label">توضیحات محصول</label>
                        <textarea name="description" class="form-control" id="productDescription" rows="4" placeholder="توضیحات کامل محصول">{{old('description')}}</textarea>
                    </div>

                    <!-- Image Uploads -->
                    <div class="col-12 mb-4">
                        <label class="form-label">تصاویر محصول (حداکثر 4 تصویر)</label>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="image-upload-container">
                                    <input type="file" name="thumbnail_1" class="form-control image-upload" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="image-upload-container">
                                    <input type="file" name="thumbnail_2" class="form-control image-upload" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="image-upload-container">
                                    <input type="file" name="thumbnail_3" class="form-control image-upload" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="image-upload-container">
                                    <input type="file" name="thumbnail_4" class="form-control image-upload" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Buttons -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-save me-2"></i> ذخیره محصول
                        </button>
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i> بازنشانی
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection