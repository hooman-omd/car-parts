@extends('layouts.dashboard_layout')

@section('content')
@use('App\Utilities\PersianNumbers')

<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">آدرس های کاربران</h2>
    </div>

    @session('success')
    <x-success-alert>{{$value}}</x-success-alert>
    @endsession

    @session('fail')
    <x-fail-alert>{{$value}}</x-fail-alert>
    @endsession

    @if($errors->any())
    <x-fail-alert :icon="false">
        @foreach($errors->all() as $message)
        {{$message}}<br>
        @endforeach
    </x-fail-alert>
    @endif

    <!-- Users Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50">ردیف</th>
                            <th>عنوان</th>
                            <th>آدرس</th>
                            <th>نام تحویل گیرنده</th>
                            <th>شماره تحویل گیرنده</th>
                            <th>کدپستی</th>
                            <th>نوع آدرس</th>
                            <th>تنظیم بعنوان پیشفرض</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($addresses as $address)
                        <tr>
                            <td>{{PersianNumbers::toNumber($loop->iteration)}}</td>
                            <td>{{$address->title}}</td>
                            <td>{{PersianNumbers::toNumber($address->address)}}</td>
                            <td>{{$address->receiver_name}}</td>
                            <td>{{PersianNumbers::toNumber($address->receiver_phone)}}</td>
                            <td>{{PersianNumbers::toNumber($address->postal_code)}}</td>
                            <td><span @class(["badge", "bg-success"=>$address->is_default,
                                    "bg-primary"=>!$address->is_default])>{{$address->is_default ? 'پیشفرض' : 'غیرپیشفرض' }}</span></td>
                            <td>
                                @if(!$address->is_default)
                                <form action="{{route('userdashboard.setDefualt')}}" method="POST" class="ms-3">
                                    @csrf
                                    <input type="hidden" name="address_id" value="{{$address->id}}">
                                    <input type="hidden" name="user_id" value="{{$address->user_id}}">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-bookmark"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" type="button" data-bs-toggle="modal" data-bs-target="#editAddressModal" data-user='@json($address)'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{route('userdashboard.deleteAddressPanel')}}" method="post" class="d-inline-block" onsubmit="return confirmSubmit()">
                                    @csrf
                                    @method('delete')
                                    <input type="hidden" name="address_id" value="{{$address->id}}">
                                    <input type="hidden" name="user_id" value="{{$address->user_id}}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('userdashboard.editAddressPanel')}}" id="editAddressForm">
                @csrf
                @method('patch')
                @if($errors->any() && session('active_form')=='edit')
                <x-fail-alert :icon="false">
                    @foreach($errors->all() as $message)
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{$message}} <br>
                    @endforeach
                </x-fail-alert>
                @endif
                <div class="modal-header">
                    <h5 class="modal-title" id="editAddressModalLabel">ویرایش آدرس</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="address_id" id="address_id">
                    <div class="form-group">
                        <label for="edit_title">عنوان آدرس</label>
                        <input type="text" class="form-control mb-3 mt-3" id="edit_title" name="title">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_recipient_name">نام تحویل گیرنده</label>
                                <input type="text" class="form-control mb-3 mt-3" id="edit_recipient_name" name="receiver_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_recipient_phone">تلفن تحویل گیرنده</label>
                                <input type="tel" class="form-control mb-3 mt-3" id="edit_recipient_phone" name="receiver_phone">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_address">آدرس دقیق</label>
                        <textarea class="form-control mb-3 mt-3" id="edit_address" name="address" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="edit_postal_code">کد پستی</label>
                        <input type="text" class="form-control mb-3 mt-3" id="edit_postal_code" name="postal_code">
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
        return confirm("آیا می خواهید آدرس انتخاب شده حذف شود؟");
        // Returns `true` if "OK", `false` if "Cancel"
    }

    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editAddressModal');

        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userData = JSON.parse(button.getAttribute('data-user'));

            // Update modal fields
            document.getElementById('address_id').setAttribute('value', userData.id);
            document.getElementById('edit_title').setAttribute('value', userData.title);
            document.getElementById('edit_recipient_name').setAttribute('value', userData.receiver_name);
            document.getElementById('edit_recipient_phone').setAttribute('value', userData.receiver_phone);
            document.getElementById('edit_address').value = userData.address;
            document.getElementById('edit_postal_code').setAttribute('value', userData.postal_code);
        });
    });
</script>

@endsection