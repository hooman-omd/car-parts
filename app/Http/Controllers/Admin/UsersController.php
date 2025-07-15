<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = $request->has('q') ? User::where('name','like','%'.$request->query('q').'%')->get() : User::get();
        return view('dashboard.users', ['users' => $users]);
    }

    public function editUser(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|regex:/^09\d{9}$/',
            'role' => 'required|in:admin,regular',
            'password' => 'nullable|min:8',
        ],[
            'required'=>'لطفا تمام فیلد ها را تکمیل نمائید',
            'email.email' =>'ایمیل وارد شده معتبر نمی باشد',
            'phone.regex' => 'تلفن وارد شده معتبر نمی باشد',
            'role.in' =>'نقش کاربری معتبر نمی باشد',
            'password.min' => 'رمزعبور باید حداقل 8 کاراکتر باشد',
        ]);

        if ($request->has('password') && $request->password) {
            $validated['password'] = Hash::make($request->password);
        }else{
            unset($validated['password']);
        }
        $user = User::find($validated['id']);
        unset($validated['id']);
        $user->update($validated);
        return back()->with('success','اطلاعات کاربر مورد نظر با موفقت بروزرسانی شد');
    }

    public function deleteUser(Request $request){
        $userId = $request->input('user_id');
        $user=User::find($userId);
        if ($user->id == Auth::user()->id) {
            return back()->with('fail','کاربر جاری قابل حذف نمی باشد');
        }
        $user->delete();
        return back()->with('success','کاربر مورد نظر با موفقیت حذف شد');
    }

    public function getAddresses(int $user_id){
        $addresses = User::find($user_id)->userAddresses;
        return view('dashboard.addresses',['addresses'=>$addresses]);
    }
}
