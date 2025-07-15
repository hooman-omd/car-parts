<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable=['total_price','status','cart','discount_code_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function payment(){
        return $this->hasOne(Payment::class);
    }

    public function discountCode(){
        return $this->belongsTo(DiscountCode::class);
    }
}
