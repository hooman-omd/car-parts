<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'title' => ['required'],
            'recipient_name' => ['required'],
            'recipient_phone' => ['required', 'regex:/^09\d{9}$/'],
            'province' => ['required'],
            'city' => ['required'],
            'address' => ['required', 'min:10'],
            'postal_code' => ['required', 'regex:/^\d{5}-?\d{5}$/'],
            'is_default' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'فیلد :attribute الزامی است',
            'address.min' => 'فیلد آدرس حداقل باید 8 کاراکتر داشته باشد',
            'postal_code.regex' => 'کدپستی وارد شده صحیح نمی باشد',
            'recipient_phone.regex' => 'شماره تلفن وارد شده معتبر نمی باشد',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'عنوان',
            'province' => 'استان',
            'city' => 'شهر',
            'address' => 'آدرس',
            'postal_code' => 'کدپستی',
            'recipient_name' => 'نام تحویل گیرنده',
            'recipient_phone' => 'شماره تحویل گیرنده',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('active_form', 'add');
        parent::failedValidation($validator);
    }
}
