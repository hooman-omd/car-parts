<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DiscountCode;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index(){
        $codes = DiscountCode::get();
        return \view('payment.discount-codes',['codes'=>$codes]);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'code' => ['required','unique:discount_codes,code'],
            'discount_value' => ['required','numeric','digits_between:1,100'],
            'max_uses' => ['required','numeric','min:1'],
        ],[
            'code.required' => 'فیلد کد الزامی است',
            'discount_value.required' =>'فیلد درصد تخفیف الزامی است',
            'max_uses.required' => 'فیلد حداکثر استفاده الزامی است',
            'code.unique' => 'کد وارد شده موجود است',
            'discount_value.numeric' => 'درصد تخفیف باید عدد باشد',
            'discount_value.digits_between' => 'درصد تخفیف باید بین 1 و 100 باشد',
            'max_uses' => 'مقدار حداکثر استفاده نامعتبر می باشد',
        ]);

        $code = DiscountCode::create($validatedData);
        if ($code) {
            return \back()->with('success','کد تخفیف جدید با موفقیت ثبت شد');
        }else{
            return \back()->with('fail','خطا در ثبت کد تخفیف');
        }
    }

    public function edit(Request $request){
        $code = DiscountCode::find($request->input('code_id'));
        $code->code=$request->input('code');
        $code->discount_value=$request->input('discount_value');
        $code->max_uses=$request->input('max_uses');
        $code->is_active=$request->input('is_active');
        $code->save();
        return \back()->with('success','کد تخفیف با موفقیت بروزرسانی شد');
    }

    public function delete(Request $request){
        DiscountCode::find($request->input('code_id'))->delete();
        return \back()->with('success','کد تخفیف با موفقیت حذف شد');
    }
}
