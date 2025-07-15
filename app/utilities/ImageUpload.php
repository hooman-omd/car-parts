<?php
namespace App\utilities;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageUpload{
    public static function uploadImage($folder,$validatedData)
    {
        $path = "$folder/" . rand(10, 90) . time() . $validatedData->getClientOriginalName();
        Storage::disk('public-storage')->put($path, File::get($validatedData));
        return $path;
    }

    public static function unlinkImage($thumbnail){
        $filePath = public_path($thumbnail);
        unlink($filePath);
    }
}