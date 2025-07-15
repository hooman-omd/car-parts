<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable=['address', 'receiver_name', 'receiver_phone', 'postal_code', 'is_default', 'title'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
