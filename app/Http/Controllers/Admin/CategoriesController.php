<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use App\Utilities\ImageUpload;

class CategoriesController extends Controller
{

    private function generateSlug(string $title){
        $slug = preg_replace('/[[:punct:]]/', '', $title);
        $slug = strtolower(str_replace(' ', '-', $slug));
        return $slug;
    }

    public function index()
    {
        $categories = Category::get();
        return view('dashboard.categories', ['categories' => $categories]);
    }

    public function store(CategoryRequest $request)
    {
        $validatedData = $request->validated();

        $title = $validatedData['title'];
        $thumbnail = ImageUpload::uploadImage('category-images',$validatedData['thumbnail']);

        $slug = $this->generateSlug($title);

        $newCategory = Category::create(["title" => $title, "slug" => $slug, 'thumbnail' => $thumbnail]);

        if (!$newCategory) {
            return back()->with('fail-message', 'خطا در ثبت دسته بندی جدید');
        }
        return back()->with('success-message', 'دسته بندی جدید با موفقیت ثبت شد');
    }


    public function destroy(int $category_id){
        $category = Category::find($category_id);
        ImageUpload::unlinkImage($category->thumbnail);
        $category->delete();
        return back()->with('success-message', 'دسته بندی با موفقیت حذف شد');
    }

    public function edit(int $category_id){
        $category = Category::findOrFail($category_id);
        return view('dashboard.category-update', ['category' => $category]);
    }

    public function update(Request $request,int $category_id){
        $validatedData = $request->validate([
            'title'=>['min:3'],
            'thumbnail'=>['image','mimes:png,jpg,jpeg,webp'],
        ],[
            'title.min' => 'نام دسته بندی حداقل باید 3 حرفی باشد',
            'thumbnail.image' => 'فایل انتخاب شده باید عکس باشد',
            'thumbnail.mimes' => 'فرمت عکس غیر مجاز است',
        ]);
        $category = Category::find($category_id);
        $title = $validatedData['title'];
        $slug = $this->generateSlug($title);
        $data =[
            'title' => $title,
            'slug' => $slug,
        ];

        if(isset($validatedData['thumbnail'])){
            $thumbnail = ImageUpload::uploadImage('category-images',$validatedData['thumbnail']);
            $data['thumbnail'] = $thumbnail;
            if($category->thumbnail){
                ImageUpload::unlinkImage($category->thumbnail);
            }
        }

        $updateCategory = $category->update($data);

        if(!$updateCategory){
            return redirect()->route('categories.index')->with('fail-message', 'خطا در بروزرسانی دسته بندی');
        }

        return redirect()->route('categories.index')->with('success-message', 'دسته بندی با موفقیت بروزرسانی شد');
    }
}
