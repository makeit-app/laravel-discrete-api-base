<?php

/** @noinspection PhpUndefinedFieldInspection */

namespace MakeIT\DiscreteApiBase\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class MatchCurrentPasswordRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Hash::check($value, auth()->user()->password)) {
            $fail(__('The provided password does not match your current password.'));
        }
    }
}
