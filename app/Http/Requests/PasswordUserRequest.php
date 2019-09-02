<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'=>'required|min:8|max:100||same:passwordConfirmation|regex:/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,100}$/',
            'passwordConfirmation'=>'required',
        ];
    }
    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages(){
        return [
            'password.required'=>'El campo :attribute es obligatorio',
            'password.same'=>'Las contraseñas ingresadas no coinciden',
            'password.min'=>'La :attribute debe contener al menos a 5 caracteres',
            'password.max'=>'La :attribute no debe ser mayor a 100 caracteres',
            'password.regex'=>'La :attribute ingresada no es segura',

            'passwordConfirmation.required'=>'El campo :attribute es obligatorio',
        ];
    }
    /**
    * Get custom attributes for validator errors.
    *
    * @return array
    */
    public function attributes()
    {
        return [
            'password'=>'contraseña',
            'passwordConfirmation'=>'confirmación de contraseña',
        ];
    }
}
