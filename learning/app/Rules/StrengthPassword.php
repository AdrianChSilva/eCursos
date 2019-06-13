<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrengthPassword implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     * Comprobamos que se está introduciendo un numero, una eltra mayuscula, otra minuscula
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ( ! $value) {
            return true;
        }

        $uppercase          = preg_match('@[A-Z]@', $value);
        $lowercase          = preg_match('@[a-z]@', $value);
        $number             = preg_match('@[0-9]@', $value);
        $length             = strlen($value) >= 8;

        $success = true;

        if ( ! $uppercase || ! $lowercase || ! $number || ! $length) {
            $success = false;
        }

        return $success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('La :attribute debe tener 8 caracteres, un número, una letra mayúscula y una letra minúscula');
    }
}
