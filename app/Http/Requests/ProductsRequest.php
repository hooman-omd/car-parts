<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductsRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'min:3'],
            'price' => ['required', 'numeric'],
            'engine_type' => ['required'],
            'has_guarantee' => [],
            'country_of_origin' => ['required'],
            'description' => ['required', 'min:8'],
            'inventory' => ['required', 'numeric'],
            'cars' => ['required'],
            'thumbnail_1' => ['image','mimes:png,jpg,jpeg,webp'],
            'thumbnail_2' => ['image','mimes:png,jpg,jpeg,webp'],
            'thumbnail_3' => ['image','mimes:png,jpg,jpeg,webp'],
            'thumbnail_4' => ['image','mimes:png,jpg,jpeg,webp'],
        ];
    }

    public function messages(): array
    {
        return [
            // Single message for all required fields
            'required' => 'فیلد :attribute الزامی است.',
            'image' => ':attribute انتخاب شده نا معتبر می باشد',
            'mimes' => 'فرمت :attribute مجاز نمی باشد',

            // Custom messages for other rules
            'title.min' => 'عنوان باید حداقل ۳ کاراکتر باشد.',
            'description.min' => 'توضیحات باید حداقل ۸ کاراکتر باشد.',
            'numeric' => 'فیلد :attribute باید عددی باشد.',
            'category_id.exists' => 'دسته بندی وارد شده نامعتبر می باشد',
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'دسته بندی',
            'title' => 'عنوان',
            'price' => 'قیمت',
            'engine_type' => 'نوع موتور',
            'country_of_origin' => 'کشور سازنده',
            'description' => 'توضیحات',
            'cars' => 'خودرو ها',
            'inventory' => 'موجودی',
            'thumbnail_1' => 'عکس اول',
            'thumbnail_2' => 'عکس دوم',
            'thumbnail_3' => 'عکس سوم',
            'thumbnail_4' => 'عکس چهارم'
        ];
    }
}
