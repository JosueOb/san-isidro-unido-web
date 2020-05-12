<?php

namespace App\Rules\Api;

use Illuminate\Contracts\Validation\Rule;

class StringJSON implements Rule
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
        return $this->isJson($value);
    }

    /**
     * Retornar true/false si el valor es un JSON Válido
     * @param string $json_string
     *
     * @return boolean
     */
    public function isJson($json_string) {
        json_decode($json_string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El campo :attribute debe ser un string JSON válido';
    }
}
