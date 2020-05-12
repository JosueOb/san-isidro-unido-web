<?php

namespace App\Rules\Api;

use Illuminate\Contracts\Validation\Rule;

class ReactionType implements Rule
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
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        $validProviders = array("like", "assistance");
        return in_array($value, $validProviders);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El campo :attribute debe ser un tipo válido';
    }
}
