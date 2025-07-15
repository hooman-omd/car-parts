<?php

namespace App\Rules;

use App\Models\UserAddress;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class AddressBelongsTo implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $address_id, Closure $fail): void
{
    $address = UserAddress::find($address_id);
    
    if (!$address || $address->user_id !== Auth::id()) {
        $fail('آدرس مورد نظر یافت نشد');
    }
}
}
