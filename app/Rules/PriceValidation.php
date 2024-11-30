<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PriceValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^\d{1,8}(\.\d{1,2})?$/', $value)) {
            $fail('Sayı 8 basamaktan fazla olamaz ve virgülden sonra en fazla 2 basamak içerebilir.');
        }
    }
}
