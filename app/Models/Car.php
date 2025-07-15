<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['brand','model'];

    public function product(){
        return $this->belongsToMany(Product::class);
    }
}
