<?php

namespace App\Models;

use App\Casts\DiscountPriceCast;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'price',
        'engine_type',
        'has_guarantee',
        'country_of_origin',
        'description',
        'inventory',
        'thumbnail_1',
        'thumbnail_2',
        'thumbnail_3',
        'thumbnail_4',
        'percentage',
        'discount_expiry',
    ];

    protected $casts = [
        'has_guarantee' => 'boolean',
        //'price' => DiscountPriceCast::class,
    ];

    public function getPrice(){
        $price = $this->price;
        $percentage = $this->percentage;
        if (!empty($percentage)) {
            $price = $price - ($price * ($percentage/100));
        }
        return (int)$price;
    }

    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function car(){
        return $this->belongsToMany(Car::class);
    }
}
