<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EditAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address_id' => ['required', 'exists:user_addresses,id'],
            'title' => ['required'],
            'receiver_name' => ['required'],
            'receiver_phone' => ['required', 'regex:/^09\d{9}$/'],
            'address' => ['required', 'min:10'],
            'postal_code' => ['required', 'regex:/^\d{5}-?\d{5}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'فیلد :attribute الزامی است',
            'address_id.exists' => 'آدرس مورد نظر وجود ندارد',
            'address.min' => 'فیلد آدرس حداقل باید 8 کاراکتر داشته باشد',
            'postal_code.regex' => 'کدپستی وارد شده صحیح نمی باشد',
            'recipient_phone.regex' => 'شماره تلفن وارد شده معتبر نمی باشد',
        ];
    }

    public function attributes(): array
    {
        return [
            'address_id' => 'شناسه آدرس',
            'title' => 'عنوان',
            'address' => 'آدرس',
            'postal_code' => 'کدپستی',
            'receiver_name' => 'نام تحویل گیرنده',
            'receiver_phone' => 'شماره تحویل گیرنده',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('active_form', 'edit');
        parent::failedValidation($validator);
    }
}