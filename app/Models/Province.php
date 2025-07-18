<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable=['province'];

    public function city(){
        return $this->hasMany(City::class);
    }
}
