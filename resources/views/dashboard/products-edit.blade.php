@extends('layouts.dashboard_layout')

@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">بروزرسانی محصول</h2>
    </div>

    @if($errors->any())
    <x-fail-alert :icon="false">
        @foreach($errors->all() as $error)
        <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{$error}}<br>
        @endforeach
    </x-fail-alert>
    @endif

    <!-- Product Form -->
    <div class="card">
        <div class="card-body">
            <form id="productForm" method="post" action="{{route('products.store')}}" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <input type="hidden" name="product_id" value="{{$product->id}}">
                <div class="row">
                    <!-- Category Selection -->
                    <div class="col-md-6 mb-3">
                        <label for="categorySelect" class="form-label">دسته بندی</label>
                        <select name="category_id" class="form-select" id="categorySelect">
                            <option selected disabled>انتخاب دسته بندی...</option>
                            @foreach($categories as $category)
                            <option @selected($product->category->id == $category->id) value="{{$category->id}}">{{$category->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Product Title -->
                    <div class="col-md-6 mb-3">
                        <label for="productTitle" class="form-label">عنوان محصول</label>
                        <input name="title" value="{{$product->title}}" type="text" class="form-control" id="productTitle" placeholder="عنوان محصول را وارد کنید">
                    </div>

                    <!-- Price -->
                    <div class="col-md-6 mb-3">
                        <label for="productPrice" class="form-label">قیمت (تومان)</label>
                        <input name="price" value="{{$product->price}}" type="number" class="form-control" id="productPrice" placeholder="قیمت محصول">
                    </div>

                    <!-- Engine Type -->
                    <div class="col-md-6 mb-3">
                        <label for="engineType" class="form-label">نوع موتور</label>
                        <input name="engine_type" value="{{$product->engine_type}}" type="text" class="form-control" id="engineType" placeholder="نوع موتور">
                    </div>

                    <!-- Guarantee -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">گارانتی</label>
                        <div class="form-check">
                            <input name="has_guarantee" @checked($product->has_guarantee) class="form-check-input" type="checkbox" id="hasGuarantee">
                            <label class="form-check-label" for="hasGuarantee">
                                دارای گارانتی
                            </label>
                        </div>
                    </div>

                    <!-- Country of Origin -->
                    <div class="col-md-6 mb-3">
                        <label for="countryOrigin" class="form-label">کشور سازنده</label>
                        <input name="country_of_origin" value="{{$product->country_of_origin}}" type="text" class="form-control" id="countryOrigin" placeholder="کشور سازنده">
                    </div>

                    <!-- Inventory -->
                    <div class="col-md-6 mb-3">
                        <label for="productInventory" class="form-label">موجودی انبار</label>
                        <input name="inventory" value="{{$product->inventory}}" type="number" class="form-control" id="productInventory" placeholder="تعداد موجودی">
                    </div>

                    <!-- Cars -->
                    <div class="col-12 mb-3">
                        <label class="form-label mt-3">قابلیت استفاده</label>
                        <fieldset class="border rounded p-4 mb-3">
                            @foreach($cars as $car)
                            <div class="form-check form-check-inline">
                                <input name="cars[]" @checked(in_array($car->id, $product->car->pluck('id')->toArray())) class="form-check-input" type="checkbox" value="{{$car->id}}">
                                <label class="form-check-label" for="inlineCheckbox1">{{$car->model}}</label>
                            </div>
                            @endforeach
                        </fieldset>
                    </div>

                    <!-- Description -->
                    <div class="col-12 mb-3">
                        <label for="productDescription" class="form-label">توضیحات محصول</label>
                        <textarea name="description" class="form-control" id="productDescription" rows="4" placeholder="توضیحات کامل محصول">{{$product->description}}</textarea>
                    </div>

                    <!-- Uploaded Images -->
                    <div class="col-12 mb-3">
                        <label class="form-label mt-3">تصاویر محصول</label>
                        <fieldset class="border rounded p-4 mb-3">

                            <div class="col-md-3 my-4">
                                <div class="image-upload-container">
                                    <input type="file" name="thumbnail_1" class="form-control image-upload" accept="image/*">
                                </div>
                            </div>
                            @if(isset($product->thumbnail_1))
                            <img style="width: 150px; height:auto; display:inline-block; margin:0 8px;" class="border rounded" src="{{asset($product->thumbnail_1)}}" alt="{{$product->title}}">
                            <a href="{{route('products.deleteImage',['productId'=>$product->id , 'thumbnailId'=>'thumbnail_1'])}}" class="btn btn-sm btn-outline-danger">حذف تصویر</a>
                            @endif
                            <div class="col-md-3 my-4">
                                <div class="image-upload-container">
                                    <input type="file" name="thumbnail_2" class="form-control image-upload" accept="image/*">
                                </div>
                            </div>
                            @if(isset($product->thumbnail_2))
                            <img style="width: 150px; height:auto; display:inline-block; margin:0 8px;" class="border rounded" src="{{asset($product->thumbnail_2)}}" alt="{{$product->title}}">
                            <a href="{{route('products.deleteImage',['productId'=>$product->id , 'thumbnailId'=>'thumbnail_2'])}}" class="btn btn-sm btn-outline-danger">حذف تصویر</a>
                            @endif
                            <div class="col-md-3 my-4">
                                <div class="image-upload-container">
                                    <input type="file" name="thumbnail_3" class="form-control image-upload" accept="image/*">
                                </div>
                            </div>
                            @if(isset($product->thumbnail_3))
                            <img style="width: 150px; height:auto; display:inline-block; margin:0 8px;" class="border rounded" src="{{asset($product->thumbnail_3)}}" alt="{{$product->title}}">
                            <a href="{{route('products.deleteImage',['productId'=>$product->id , 'thumbnailId'=>'thumbnail_3'])}}" class="btn btn-sm btn-outline-danger">حذف تصویر</a>
                            @endif
                            <div class="col-md-3 my-4">
                                <div class="image-upload-container">
                                    <input type="file" name="thumbnail_4" class="form-control image-upload" accept="image/*">
                                </div>
                            </div>
                            @if(isset($product->thumbnail_4))
                            <img style="width: 150px; height:auto; display:inline-block; margin:0 8px;" class="border rounded" src="{{asset($product->thumbnail_4)}}" alt="{{$product->title}}">
                            <a href="{{route('products.deleteImage',['productId'=>$product->id , 'thumbnailId'=>'thumbnail_4'])}}" class="btn btn-sm btn-outline-danger">حذف تصویر</a>
                            @endif

                        </fieldset>
                    </div>


                    <!-- Form Buttons -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-save me-2"></i> بروزرسانی محصول
                        </button>
                        <a class="btn btn-danger ms-4" href="{{route('products.index')}}">
                            <i class="fa fa-times me-2"></i> لغو
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection