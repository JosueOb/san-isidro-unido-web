<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DirectiveRequest extends FormRequest
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
        $rules = [];
        if($this->method() === 'POST'){
            $rules = [
                'first_name'=>'required|regex:/^[[:alpha:][:space:](áéíóúÁÉÍÓÚñÑ)]+$/|min:3|max:25',
                'last_name'=>'required|regex:/^[[:alpha:][:space:](áéíóúÁÉÍÓÚñÑ)]+$/|min:5|max:25',
                'email'=>'required|email|unique:users,email',
                'position'=>'required|exists:positions,id'
            ];
        }
        if($this->method() === 'PUT'){
           $rules = [
                'email'=>'required|email|unique:users,email,'.$this->route('user')->id,
                'position'=>'required|exists:positions,id',
           ];
        }

        return $rules;
    }
    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages(){
        return [
            'first_name.required'=>'El campo :attribute es obligatorio',
            'first_name.regex'=>'El :attribute debe estar conformado por caracteres alfabéticos, no se admiten signos de puntuación ni caracteres especiales',
            'first_name.min'=>'El :attribute debe ser mayor a 3 caracteres',
            'first_name.max'=>'El :attribute no debe ser mayor a 25 caracteres',

            'last_name.required'=>'Los :attribute son obligatorios',
            'last_name.regex'=>'Los :attribute deben estar conformado por caracteres alfabéticos, no se admiten signos de puntuación ni caracteres especiales',
            'last_name.min'=>'Los :attribute deben ser mayor a 5 caracteres',
            'last_name.max'=>'Los :attribute no deben ser mayor a 25 caracteres',

            'email.required'=>'El campo :attribute es obligatorio',
            'email.email'=>'Fortamo del :attribute ingresado es incorrecto',
            'email.unique'=>'El :attribute ingresado ya existe',

            'position.required'=>'El campo :attribute es obligatorio',
            'position.exists'=>'El :attribute seleccionado no existe',
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
            'first_name'=>'nombre',
            'last_name'=>'apellidos',
            'email'=>'correo electrónico',
            'position'=>'cargo'
        ];
    }
}
