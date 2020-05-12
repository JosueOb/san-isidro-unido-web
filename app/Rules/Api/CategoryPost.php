<?php

namespace App\Rules\Api;

use Illuminate\Contracts\Validation\Rule;
use App\Category;

class CategoryPost implements Rule
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
        $validCategories = Category::all()->pluck('slug')->toArray();
        return in_array($value, $validCategories);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Categoria No Válida';
    }
}
