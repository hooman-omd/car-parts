<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $fillable=['code','discount_value','max_uses','is_active'];

    public function order(){
        return $this->hasMany(Order::class);
    }
}
