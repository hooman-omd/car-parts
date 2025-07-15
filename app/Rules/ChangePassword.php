<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ChangePassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    public function __construct(public $newPassword=null)
    {
        
    }

    public function validate(string $attribute, mixed $currentPassword, Closure $fail): void
    {
        if ($currentPassword != null && $this->newPassword == null) {
            $fail('رمز عبور جدید را وارد کنید');
        }
    }
}
