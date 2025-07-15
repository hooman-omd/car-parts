@extends('layouts.dashboard_layout')

@section('content')
@use('App\Utilities\PersianNumbers')
<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">کدهای تخفیف <i class="fas fa-percent"></i></h2>
    </div>

    <!-- Insert data -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10 mb-3 mb-md-0">
                    <form action="{{route('codes.store')}}" method="post">
                        <div class="input-group">
                            @csrf
                            <input name="code" value="{{old('code')}}" type="text" class="form-control me-4" placeholder="متن کد را وارد کنید">
                            <input name="discount_value" value="{{old('discount_value')}}" type="number" class="form-control me-4" placeholder="درصد تخفیف را وارد کنید">
                            <input name="max_uses" value="{{old('max_uses')}}" type="number" class="form-control me-4" placeholder="حداکثر تعداد استفاده برای هر کاربر">
                            <button class="btn btn-primary ms-4" type="submit">
                                <i class="fas fa-percent"></i> افزودن کد تخفیف جدید
                            </button>
                        </div>
                    </form>
                    @error('code')
                    <x-fail-alert>{{$message}}</x-fail-alert>
                    @enderror
                    @error('discount_value')
                    <x-fail-alert>{{$message}}</x-fail-alert>
                    @enderror
                    @error('max_uses')
                    <x-fail-alert>{{$message}}</x-fail-alert>
                    @enderror
                    @session('success')
                    <x-success-alert>{{$value}}</x-success-alert>
                    @endsession
                    @session('fail')
                    <x-fail-alert>{{$value}}</x-fail-alert>
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
                            <th>متن کد</th>
                            <th>درصد تخفیف</th>
                            <th>تعداد استفاده</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($codes as $code)
                        <tr>
                            <td>{{PersianNumbers::toNumber($loop->iteration)}}</td>
                            <td>{{$code->code}}</td>
                            <td>% {{PersianNumbers::toNumber($code->discount_value)}}</td>
                            <td>{{PersianNumbers::toNumber($code->max_uses)}}</td>
                            <td><span @class(["badge", "bg-success"=>$code->is_active,
                                    "bg-primary"=>!$code->is_active])>{{$code->is_active ? 'فعال' : 'غیرفعال' }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" type="button" data-bs-toggle="modal" data-bs-target="#editCodeModal" data-code='@json($code)'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form id="destroy-form" style="display: inline;" action="{{route('codes.destroy')}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <input type="hidden" name="code_id" value="{{$code->id}}">
                                    <button type="submit" onclick="return confirmSubmit()" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                کد تخفیف یافت نشد <i class="fas fa-percent"></i>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit discount Modal -->
<div class="modal fade" id="editCodeModal" tabindex="-1" role="dialog" aria-labelledby="editCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('codes.edit')}}" id="editCodeForm">
                @csrf
                @method('patch')
                @if($errors->any())
                <x-fail-alert :icon="false">
                    @foreach($errors->all() as $message)
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{$message}} <br>
                    @endforeach
                </x-fail-alert>
                @endif
                <div class="modal-header">
                    <h5 class="modal-title" id="editCodeModalLabel">ویرایش کد تخفیف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="code_id" name="code_id">
                    <div class="form-group">
                        <label for="edit_code">متن کد</label>
                        <input type="text" class="form-control mb-3 mt-3" id="edit_code" name="code">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_discount_value">درصد تخفیف</label>
                                <input type="number" class="form-control mb-3 mt-3" id="edit_discount_value" name="discount_value">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_max_uses">تعداد استفاده</label>
                                <input type="number" class="form-control mb-3 mt-3" id="edit_max_uses" name="max_uses">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_is_active">وضعیت</label>
                        <select name="is_active" id="edit_is_active" class="form-select mb-3 mt-3">
                            <option value="1">فعال</option>
                            <option value="0">غیرفعال</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    function confirmSubmit() {
        return confirm("آیا می خواهید دسته بندی انتخاب شده حذف شود؟");
        // Returns `true` if "OK", `false` if "Cancel"
    }

    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editCodeModal');

        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userData = JSON.parse(button.getAttribute('data-code'));

            // Update modal fields
            document.getElementById('code_id').setAttribute('value', userData.id);
            document.getElementById('edit_code').setAttribute('value', userData.code);
            document.getElementById('edit_discount_value').setAttribute('value', userData.discount_value);
            document.getElementById('edit_max_uses').setAttribute('value', userData.max_uses);
            document.getElementById('edit_is_active').value = userData.is_active;
        });
    });
</script>

@endsection