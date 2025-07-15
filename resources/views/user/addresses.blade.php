@extends('user.dashboard')

@section('user-title', 'مدیریت آدرس‌ها')

@section('user-content')
@use('App\Utilities\PersianNumbers')

@session('success')
<x-success-alert>
    {{$value}}
</x-success-alert>
@endsession

<div class="mb-4 text-left">
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAddressModal" id="addAddressModalBtn">
        <i class="fas fa-plus ml-2"></i> افزودن آدرس جدید
    </button>
</div>

@empty($addresses)
<div class="alert alert-info text-center">
    آدرسی ثبت نشده است
</div>
@else
<div class="row">
    @foreach($addresses as $address)
    <div class="col-md-6 mb-4">
        <div class="card h-100 border-{{ $address->is_default ? 'primary' : 'secondary' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="card-title mb-0">
                        {{ $address->title }}
                        @if($address->is_default)
                        <span class="badge badge-primary">پیش‌فرض</span>
                        @endif
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary edit-address-btn"
                            data-address="{{ json_encode($address) }}" data-toggle="modal" data-target="#editAddressModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form id="delete-form-{{ $address->id }}" action="{{ route('userdashboard.deleteAddressDashboard') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="address_id" value="{{ $address->id }}">
                            <button
                                type="button"
                                class="btn btn-outline-danger delete-btn"
                                data-form-id="delete-form-{{ $address->id }}"
                                data-address-id="{{ $address->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <p class="card-text">
                    <strong>تحویل گیرنده:</strong> {{ $address->receiver_name }}<br>
                    <strong>تلفن:</strong> {{ PersianNumbers::toNumber($address->receiver_phone) }}<br>
                    <strong>آدرس:</strong> {{ PersianNumbers::toNumber($address->address) }}<br>
                    <strong>کد پستی:</strong> {{PersianNumbers::toNumber($address->postal_code)}}
                </p>

                @if(!$address->is_default)
                <form action="{{route('userdashboard.setDefualt')}}" method="POST" class="mt-2">
                    @csrf
                    <input type="hidden" name="address_id" value="{{$address->id}}">
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        تنظیم به عنوان آدرس پیش‌فرض
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endempty

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('userdashboard.addAddress')}}">
                @csrf
                @if($errors->any() && session('active_form')=='add')
                <x-fail-alert :icon="false">
                    @foreach($errors->all() as $message)
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{$message}} <br>
                    @endforeach
                </x-fail-alert>
                @endif
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">افزودن آدرس جدید</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">عنوان آدرس (مثال: خانه، محل کار)</label>
                        <input type="text" value="{{old('title')}}" class="form-control" id="title" name="title">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="recipient_name">نام تحویل گیرنده</label>
                                <input value="{{old('recipient_name') ?? Auth::user()->name}}" type="text" class="form-control" id="recipient_name" name="recipient_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="recipient_phone">تلفن تحویل گیرنده</label>
                                <input value="{{old('recipient_phone') ?? Auth::user()->phone}}" type="tel" class="form-control" id="recipient_phone" name="recipient_phone">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="province">استان</label>
                                <select class="form-control" id="province" name="province">
                                    <option value="">انتخاب کنید</option>
                                    @foreach($provinces as $province)
                                    <option value="{{ $province->province }}" data-id="{{ $province->id }}" @selected(old('province')==$province->province)>
                                        {{ $province->province  }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">شهر</label>
                                <select class="form-control" id="city" name="city" disabled>
                                    <option value="">ابتدا استان را انتخاب کنید</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">آدرس دقیق</label>
                        <textarea class="form-control" id="address" name="address" rows="3">{{old('address')}}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="postal_code">کد پستی</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{old('postal_code')}}">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default" @checked(old('is_default'))>
                        <label class="form-check-label mr-3" for="is_default">تنظیم به عنوان آدرس پیش‌فرض</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary">ذخیره آدرس</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="#" id="editAddressForm">
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Content will be loaded by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Load cities when province changes (for add modal)
        $('#province').change(function() {
            const provinceId = $(this).find(':selected').data('id');
            console.log(provinceId);
            const citySelect = $('#city');

            if (!provinceId) {
                citySelect.html('<option value="">ابتدا استان را انتخاب کنید</option>').prop('disabled', true);
                return;
            }

            citySelect.html('<option value="">در حال دریافت شهرها...</option>').prop('disabled', true);

            $.ajax({
                url: `{{route('userdashboard.get-cities')}}?id=${provinceId}`,
                method: 'GET',
                success: function(response) {
                    let options = '<option value="">انتخاب کنید</option>';
                    response.forEach(function(city) {
                        options += `<option value="${city.name}">${city.name}</option>`;
                    });

                    citySelect.html(options).prop('disabled', false);
                },
                error: function() {
                    citySelect.html('<option value="">خطا در دریافت شهرها</option>');
                }
            });
        });

        // Edit address button click handler
        $('.edit-address-btn').click(function() {
            const address = $(this).data('address');
            const formAction = "{{route('userdashboard.editAddress')}}";

            $('#editAddressForm').attr('action', formAction);

            let modalBody = `
            <input type="hidden" name="address_id" value="${address.id}">
            <div class="form-group">
                <label for="edit_title">عنوان آدرس</label>
                <input type="text" class="form-control" id="edit_title" name="title" value="${address.title}">
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="edit_recipient_name">نام تحویل گیرنده</label>
                        <input type="text" class="form-control" id="edit_recipient_name" name="receiver_name" value="${address.receiver_name}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="edit_recipient_phone">تلفن تحویل گیرنده</label>
                        <input type="tel" class="form-control" id="edit_recipient_phone" name="receiver_phone" value="${address.receiver_phone}">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="edit_address">آدرس دقیق</label>
                <textarea class="form-control" id="edit_address" name="address" rows="3">${address.address}</textarea>
            </div>
            
            <div class="form-group">
                <label for="edit_postal_code">کد پستی</label>
                <input type="text" class="form-control" id="edit_postal_code" name="postal_code" value="${address.postal_code}">
            </div>
        `;

            $('#editAddressModal .modal-body').html(modalBody);
            $('#editAddressModal').modal('show');

            // Load provinces for edit modal
            $.ajax({
                url: 'https://iran-locations-api.ir/api/v1/fa/states',
                method: 'GET',
                success: function(provinces) {
                    let provinceOptions = '<option value="">انتخاب کنید</option>';
                    provinces.forEach(function(province) {
                        const selected = province.name === address.province ? 'selected' : '';
                        provinceOptions += `
                        <option value="${province.name}" data-id="${province.id}" ${selected}>
                            ${province.name}
                        </option>`;
                    });

                    $('#edit_province').html(provinceOptions);

                    // If province was selected, load its cities
                    if (address.province) {
                        const selectedProvince = provinces.find(p => p.name === address.province);
                        if (selectedProvince) {
                            loadCitiesForEdit(selectedProvince.id, address.city);
                        }
                    }
                },
                error: function() {
                    $('#edit_province').html('<option value="">خطا در دریافت استان‌ها</option>');
                }
            });
        });

        // Function to load cities for edit modal
        function loadCitiesForEdit(provinceId, selectedCity) {
            const citySelect = $('#edit_city');
            citySelect.html('<option value="">در حال دریافت شهرها...</option>').prop('disabled', true);

            $.ajax({
                url: `https://iran-locations-api.ir/api/v1/fa/cities?state_id=${provinceId}`,
                method: 'GET',
                success: function(cities) {
                    let options = '<option value="">انتخاب کنید</option>';
                    cities.forEach(function(city) {
                        const selected = city.name === selectedCity ? 'selected' : '';
                        options += `<option value="${city.name}" ${selected}>${city.name}</option>`;
                    });

                    citySelect.html(options).prop('disabled', false);
                },
                error: function() {
                    citySelect.html('<option value="">خطا در دریافت شهرها</option>');
                }
            });
        }
    });

    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault(); // prevent the default button behavior

        var formId = $(this).data('form-id');
        var addressId = $(this).data('address-id');

        Swal.fire({
            title: 'آیا مطمئنید؟',
            text: 'آیا از حذف این آدرس مطمئن هستید؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'بله، حذف شود!',
            cancelButtonText: 'خیر'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#' + formId).submit();
            }
        });
    });

    @if($errors -> any())
    @if(session('active_form') == 'add')
    $('#addAddressModalBtn').click();
    @else
    Swal.fire({
        title: 'خطا در بروزرسانی آدرس',
        html: `
                @foreach($errors->all() as $m)
                    {{$m}}<br>
                @endforeach
            `,
        icon: 'error',
        confirmButtonText: 'تائید'
    });
    @endif
    @endif
</script>
@endsection