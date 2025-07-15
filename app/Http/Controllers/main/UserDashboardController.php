<?php

namespace App\Http\Controllers\main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\EditAddressRequest;
use App\Models\Order;
use App\Models\Province;
use App\Models\User;
use App\Models\UserAddress;
use App\Rules\AddressBelongsTo;
use App\Rules\PasswordExists;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function orders()
    {
        $orders = Auth::user()->order;
        return view('user.orders', ['orders' => $orders]);
    }

    public function orderDetail(int $order_id){
        $order = Order::findOrFail($order_id);
        return \view('user.order-details',['order'=>$order]);
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function addresses()
    {
        $addresses = Auth::user()->userAddresses;
        $provinces = Province::get();

        return view('user.addresses', ['addresses' => $addresses, 'provinces' => $provinces]);
    }

    public function getCities(Request $request)
    {
        $province_id = $request->query('id');
        for ($i = 1; $i < 3; $i++) {
            try {
                $response = Http::get('https://iran-locations-api.ir/api/v1/fa/cities?state_id=' . $province_id);
                if ($response->successful()) {
                    $cities = $response->json();
                    break;
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        return $cities;
    }

    public function addAddress(AddressRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        $address = new UserAddress();
        $address->title = $data['title'];
        $address->address = $data['province'] . ' , ' . $data['city'] . ' , ' . $data['address'];
        $address->postal_code = $data['postal_code'];
        $address->receiver_name = $data['recipient_name'];
        $address->receiver_phone = $data['recipient_phone'];
        $address->is_default = isset($data['is_default']) ? true : false;

        $address->is_default ? $this->removeDefaultAddress() : null;
        count(Auth::user()->userAddresses) == 0 && !$address->is_default ? $address->is_default = true : null;

        $user->userAddresses()->save($address);

        return back()->with('success', 'آدرس جدید با موفقیت ثبت شد');
    }

    private function removeDefaultAddress($userId = null)
    {
        $addresses = $userId == null ? Auth::user()->userAddresses : UserAddress::where('user_id',$userId)->get();
        foreach ($addresses as $address) {
            $address->is_default = false;
            $address->save();
        }
    }

    public function editAddress(EditAddressRequest $request)
    {
        $validatedData = $request->validated();
        $user = Auth::user();
        $address = UserAddress::findOrFail($validatedData['address_id']);

        if ($address->user_id === $user->id) {
            unset($validatedData['address_id']);
            $address->update($validatedData);
            return back()->with('success', 'آدرس مورد نظر با موفقیت بروزرسانی شد');
        } else {
            abort(404);
        }
    }

    public function setDefualt(Request $request)
    {
        $addressId = $request->validate([
            'address_id' => ['required', 'exists:user_addresses,id', url()->previous() == route('userdashboard.addresses') ? new AddressBelongsTo() : null],
        ]);

        $this->removeDefaultAddress($request->has('user_id')? $request->input('user_id') : null);
        $address = UserAddress::find($addressId['address_id']);
        $address->is_default = true;
        $address->save();
        return back();
    }

    private function deleteAddressPart($addressId, $user)
    {
        $address = UserAddress::findOrFail($addressId);
        if ($address->is_default && count($user->userAddresses) > 1) {
            $defaultAddress = $user->userAddresses()->whereNotIn('id', [$addressId])->first();
            $defaultAddress->is_default = true;
            $defaultAddress->save();
        }

        $address->delete();
    }

    public function deleteAddressDashboard(Request $request)
    {
        $addressId = $request->validate([
            'address_id' => ['required', 'exists:user_addresses,id', new AddressBelongsTo()],
        ]);
        $user = Auth::user();
        $this->deleteAddressPart($addressId['address_id'],$user);
        return back()->with('success', 'آدرس مورد نظر با موفقیت حذف شد');
    }

    public function deleteAddressPanel(Request $request)
    {
        $validatedData = $request->validate([
            'address_id' => ['required', 'exists:user_addresses,id'],
            'user_id' => ['required', 'exists:users,id'],
        ]);
        $user = User::find($validatedData['user_id']);
        $this->deleteAddressPart($validatedData['address_id'],$user);
        return back()->with('success', 'آدرس مورد نظر با موفقیت حذف شد');
    }

    public function editAddressPanel(EditAddressRequest $request)
    {
        $validatedData = $request->validated();
        $address = UserAddress::find($validatedData['address_id']);
        unset($validatedData['address_id']);
        $address->update($validatedData);
        return back()->with('success', 'آدرس مورد نظر با موفقیت بروزرسانی شد');
    }
}
