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
        return [
            'first_name'=>'required|regex:/^[[:alpha:][:space:](áéíóúÁÉÍÓÚ)]+$/|min:5|max:100',
            'last_name'=>'required|regex:/^[[:alpha:][:space:](áéíóúÁÉÍÓÚ)]+$/|min:5|max:100',
            'email'=>'required|email|unique:users,email',
            'position'=>'required|exists:positions,id'
        ];
    }
    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages(){
        return [
            'first_name'=>'',
            'last_name'=>'',
            'email'=>'',
            'position'=>''
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
