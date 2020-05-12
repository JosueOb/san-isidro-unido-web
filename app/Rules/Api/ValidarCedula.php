<?php

namespace App\Rules\Api;

use Illuminate\Contracts\Validation\Rule;
use App\Helpers\Utils;

class ValidarCedula implements Rule
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
        $utils = new Utils();
        $cedulaValida = $utils->verificarCedula($value);
        return $cedulaValida;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La Cédula no es válida';
    }
}
