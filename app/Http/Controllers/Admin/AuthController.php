<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use App\Models\User;
use App\Rules\ChangePassword;
use App\Rules\PasswordExists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
            'remember' => ['nullable'],
        ], [
            'name.required' => 'نام کاربری را وارد کنید',
            'password.required' => 'رمز عبور را وارد کنید',
        ]);

        if (!Auth::attempt(['name' => $validatedData['name'], 'password' => $validatedData['password']], isset($validatedData['remember']))) {
            throw ValidationException::withMessages([
                'attempt-failed' => 'نام کاربری یا رمز عبور اشتباه است',
            ]);
        }

        $request->session()->regenerate();
        return redirect('/');
    }

    public function registerUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'unique:users,name'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'phone' => ['required', 'regex:/^09\d{9}$/'],
        ], [
            'name.required' => 'نام کاربری را وارد کنید',
            'name.unique' => 'نام کاربری وارد شده وجود دارد',
            'password.required' => 'رمز عبور را وارد کنید',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد',
            'password.confirmed' => 'تکرار رمز عبور اشتباه است',
            'phone.required' => 'تلفن خود را وارد کنید',
            'phone.regex' => 'شماره تلفن وارد شده نادرست می باشد',
            'email.required' => 'ایمیل خود را وارد کنید',
            'email.email' => 'ایمیل وارد شده نا معتبر می باشد',
        ]);

        $user = User::create($validatedData);
        Auth::login($user);
        return redirect('/');
    }

    public function editProfile(Request $request)
    {

        $data = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'regex:/^09\d{9}$/'],
            'current_password' => ['nullable', new PasswordExists(), new ChangePassword($request->input('password'))],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ], [
            'name.required' => 'نام کاربری را وارد کنید',
            'phone.required' => 'تلفن خود را وارد کنید',
            'phone.regex' => 'شماره تلفن وارد شده نادرست می باشد',
            'email.required' => 'ایمیل خود را وارد کنید',
            'email.email' => 'ایمیل وارد شده نا معتبر می باشد',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد',
            'password.confirmed' => 'تکرار رمز عبور اشتباه است',
        ]);

        $user = Auth::user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        if (isset($data['current_password']) && isset($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();
        return back()->with('success', 'اطلاعات کاربری با موفقیت بروزرسانی شدند');
    }

    public function resestPassword()
    {
        return \view('auth.reset-password');
    }

    public function setEmail(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'ایمیل خود را وارد کنید',
            'email.email' => 'ایمیل وارد شده معتبر نمی باشد',
            'email.exists' => 'ایمیل وارد شده در سیستم وجود ندارد'
        ]);
        $resetKey = bin2hex(random_bytes(10));
        $user = User::where('email', $data['email'])->firstOrFail();
        $user->reset_link = $resetKey;
        $user->save();
        try {
            Mail::to($user->email)->send(new ForgetPasswordMail($resetKey));
            return \back()->with('success', 'لینک بازیابی رمز عبور به ایمیل تان ارسال شد');
        } catch (\Exception $e) {
            return \back()->with('fail', 'خطا در ارسال ایمیل');
        }
    }

    public function setNewPasswordView(Request $request)
    {
        return \view('auth.set-new-password');
    }

    public function setNewPassword(Request $request) {
        $data = $request->validate([
            'resetKey' => 'required|exists:users,reset_link',
            'password' => 'required|min:8|confirmed'
        ],[
            'resetKey' => 'خطا در بروزرسانی رمزعبور',
            'password.required' => 'فیلد رمز عبور الزامی است',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد',
            'password.confirmed' => 'تکرار رمز عبور اشتباه است',
        ]);

        $user = User::where('reset_link',$data['resetKey'])->first();
        $user->reset_link = null;
        $user->password = Hash::make($data['password']);
        $user->save();
        return \redirect()->route('auth.form.login')->with('success','رمز عبور جدید با موفقیت ثبت شد');
    }
}
