@extends('layouts.dashboard_layout')

@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">دسته بندی ها</h2>
    </div>

    <!-- Insert data -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <form action="{{route('categories.store')}}" method="post" enctype="multipart/form-data">
                        <div class="input-group">
                            @csrf
                            <input name="title" value="{{old('title')}}" type="text" class="form-control me-4" placeholder="نام دسته بندی">
                            <input type="file" name="thumbnail" class="form-control image-upload" accept="image/*">
                            <button class="btn btn-primary ms-4" type="submit">
                                <i class="fa fa-tags me-2"></i> افزودن دسته بندی جدید
                            </button>
                        </div>
                    </form>
                    @error('title')
                    <x-fail-alert>{{$message}}</x-fail-alert>
                    @enderror
                    @error('thumbnail')
                    <x-fail-alert>{{$message}}</x-fail-alert>
                    @enderror
                    @session('fail-message')
                    <x-fail-alert>{{$value}}</x-fail-alert>
                    @endsession
                    @session('success-message')
                    <x-success-alert>{{$value}}</x-success-alert>
                    @endsession

                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="90">ردیف</th>
                            <th>نام دسته بندی</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>{{$category->title}}
                                        @isset($category->thumbnail)
                                        <img src="{{asset($category->thumbnail)}}" class="rounded border ms-4" style="height: 40px; width: auto;" alt="{{$category->title}}">
                                        @endisset
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{route('categories.edit',$category->id)}}" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form id="destroy-form" style="display: inline;" action="{{route('categories.destroy',$category->id)}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick="return confirmSubmit()" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                دسته بندی یافت نشد <i class="fa fa-tags me-2"></i>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    function confirmSubmit() {
        return confirm("آیا می خواهید دسته بندی انتخاب شده حذف شود؟");
        // Returns `true` if "OK", `false` if "Cancel"
    }
</script>

@endsection