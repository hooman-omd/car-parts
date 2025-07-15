@extends('layouts.dashboard_layout')

@section('content')
@use('App\Utilities\PersianNumbers')

<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">مدیریت کاربران</h2>
    </div>

    <!-- Search and Filter -->
    <form action="{{route('users.index')}}" method="get">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="جستجوی کاربر..." name="q" value="{{request()->query('q')}}">
                            <button type="submit" class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-outline-danger btn-block d-inline-block ms-3">
                                <i class="fa fa-times"></i> نمایش همه کاربران
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
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
                            <th>نام و نام خانوادگی</th>
                            <th>ایمیل</th>
                            <th>موبایل</th>
                            <th>نقش کاربری</th>
                            <th>تاریخ عضویت</th>
                            <th>عملیات</th>
                            <th>آدرس ها</th>
                            <th>سفارشات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{PersianNumbers::toNumber($loop->iteration)}}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3">{{ mb_substr($user->name, 0, 1) }}</div>
                                    <div>{{$user->name}}</div>
                                </div>
                            </td>
                            <td>{{$user->email}}</td>
                            <td>{{PersianNumbers::toNumber($user->phone)}}</td>
                            <td><span @class(["badge", "bg-success"=>$user->role == 'admin',
                                    "bg-primary"=>$user->role == 'regular'])>{{$user->role == 'admin'? 'مدیر': 'کاربر عادی'}}</span></td>
                            <td>{{PersianNumbers::toNumber(jdate($user->created_at)->format('H:i - Y/m/d'))}}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2" type="button" data-bs-toggle="modal" data-bs-target="#userEditModal" data-user='@json($user)'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{route('users.deleteUser')}}" method="post" class="d-inline-block" onsubmit="return confirmSubmit()">
                                    @csrf
                                    @method('delete')
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary me-2" href="{{route('users.getAddresses',$user->id)}}">
                                    مشاهده
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary me-2" href="{{route('orders.get')}}?user={{$user->name}}">
                                    مشاهده
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="userEditModal" tabindex="-1" aria-labelledby="userEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userEditModalLabel">ویرایش کاربر</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="{{route('users.editUser')}}" method="post">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="id" id="editUserId">
                    <div class="mb-3">
                        <label for="editUserName" class="form-label">نام کامل</label>
                        <input type="text" class="form-control" id="editUserName" placeholder="نام و نام خانوادگی" name="name">
                    </div>

                    <div class="mb-3">
                        <label for="editUserEmail" class="form-label">ایمیل</label>
                        <input type="text" class="form-control text-end" id="editUserEmail" placeholder="example@domain.com" name="email">
                    </div>

                    <div class="mb-3">
                        <label for="editUserPassword" class="form-label">رمز عبور جدید</label>
                        <input type="password" class="form-control" id="editUserPassword" placeholder="در صورت عدم نیاز خالی بگذارید" name="password">
                        <div class="form-text">برای حفظ رمز عبور فعلی، این فیلد را خالی بگذارید</div>
                    </div>

                    <div class="mb-3">
                        <label for="editUserPhone" class="form-label">شماره تماس</label>
                        <input type="text" class="form-control" id="editUserPhone" placeholder="09123456789" name="phone">
                    </div>

                    <div class="mb-3">
                        <label for="editUserRole" class="form-label">نقش کاربری</label>
                        <select class="form-select" id="editUserRole" name="role">
                            <option value="regular">کاربر عادی</option>
                            <option value="admin">مدیر سیستم</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmSubmit() {
        return confirm("آیا می خواهید کاربر انتخاب شده حذف شود؟");
        // Returns `true` if "OK", `false` if "Cancel"
    }

    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('userEditModal');

        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userData = JSON.parse(button.getAttribute('data-user'));

            // Update modal fields
            document.getElementById('editUserName').value = userData.name;
            document.getElementById('editUserEmail').value = userData.email;
            document.getElementById('editUserPhone').value = userData.phone || '';
            document.getElementById('editUserRole').value = userData.role;
            document.getElementById('editUserId').value = userData.id;
        });
    });
</script>

@endsection