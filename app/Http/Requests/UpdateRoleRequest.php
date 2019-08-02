<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
            //
            'name'=> 'required|regex:/^[[:alpha:][:space:]]+$/|min:3|max:25',
            'slug'=> 'required|regex:/^[[:lower:]]+$/|min:3|max:15',
            'description'=> 'required|regex:/^[[:alpha:][:space:](,;.áéíóúÁÉÍÓÚ)]+$/|min:10|max:255',
            'permissions'=>'required_unless:special,all-access,no-access',
        ];
    }
    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
            //
            'name.required'=>'El campo :attribute es obligatorio',
            'name.min'=>'El :attribute debe ser mayor a 3 caracteres',
            'name.max'=>'El :attribute no debe ser mayor a 25 caracteres',
            'name.regex'=>'El :attribute debe estar conformado por caracteres alfabéticos, no se admiten signos de puntuación ni caracteres especiales',
            
            'slug.required'=>'El campo :attribute es obligatorio',
            'slug.min'=>'El :attribute debe ser mayor a 3 caracteres',
            'slug.max'=>'El :attribute no debe ser mayor a 15 caracteres',
            'slug.regex'=>'El :attribute debe ser una cadena de caracteres alfabéticos en minúsculas sin espacios, no se adminen signos de puntuación ni caracteres especiales',
            
            'description.required'=>'El campo :attribute es obligatorio',
            'description.min'=>'La :attribute debe ser mayor a 5 caracteres',
            'description.max'=>'La :attribute no debe ser mayor a 255 caracteres',
            'description.regex'=>'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',

            'permissions.required_unless'=>'Debe seleccionar al menos un permiso',
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
            //
            'name' => 'nombre',
            'description' => 'descripción',
        ];
    }
}
