<?php

namespace App\Rules\Api;

use Illuminate\Contracts\Validation\Rule;
use App\User;

class UniqueUserEmail implements Rule
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
        $userToCheck = User::where('email', $value)
        ->first();
        return (is_null($userToCheck)) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El Email ya ha sido utilizado por otro usuario';
    }
}
