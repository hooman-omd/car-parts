<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'title' => ['required', 'min:3', 'unique:categories,title'],
            'thumbnail' => ['required','image','mimes:png,jpg,jpeg,webp'],
        ];
    }

    public function messages(): array{
        return [
            'title.required' => 'نام دسته بندی را وارد نمائید',
            'title.min' => 'نام دسته بندی حداقل باید 3 حرفی باشد',
            'title.unique' => 'دسته بندی وارد شده در سیستم وجود دارد',
            'thumbnail.required' => 'لطفا یک تصویر انتخاب کنید',
            'thumbnail.image' => 'فایل انتخاب شده باید عکس باشد',
            'thumbnail.mimes' => 'فرمت عکس غیر مجاز است',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'title' => trim($this->input('title'))
        ]);
    }
}
