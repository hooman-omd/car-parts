<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Utilities\PersianNumbers;

class DiscountCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    public function applyDiscount(int $price,int $percentage):string{
        return PersianNumbers::toPrice($price - ($price * ($percentage/100)));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.discount-card');
    }
}
