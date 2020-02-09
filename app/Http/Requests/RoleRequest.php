<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
        // $uniqueName = null;
        // $uniqueSlug = null;

        // if($this->method() === 'POST'){
        //     $uniqueName = 'unique:roles,name';
        //     $uniqueSlug = 'unique:roles,slug';
        // }
        // if($this->method() === 'PUT'){
        //     $uniqueName = 'unique:roles,name,'.$this->route('role')->id;
        //     $uniqueSlug = 'unique:roles,slug,'.$this->route('role')->id;
        // }

        return [
            // 'name'=> 'required|regex:/^[[:alpha:][:space:]]+$/|min:3|max:25|'.$uniqueName,
            // 'slug'=> 'required|regex:/^[[:lower:]]+$/|min:3|max:15|'.$uniqueSlug,
            'description'=> 'nullable|regex:/^[[:alpha:][:space:](,;.áéíóúÁÉÍÓÚ)]+$/|max:255',
            'permissions'=>'required|'.Rule::exists('permissions','id')->where('private',false),
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
            // 'name.required'=>'El campo :attribute es obligatorio',
            // 'name.min'=>'El :attribute debe ser mayor a 3 caracteres',
            // 'name.max'=>'El :attribute no debe ser mayor a 25 caracteres',
            // 'name.regex'=>'El :attribute debe estar conformado por caracteres alfabéticos, no se admiten signos de puntuación ni caracteres especiales',
            // 'name.unique'=>'El :attribute ingresado ya existe',
            
            // 'slug.required'=>'EL campo :attribute es obligatorio',
            // 'slug.min'=>'La :attribute debe ser mayor a 3 caracteres',
            // 'slug.max'=>'La :attribute no debe ser mayor a 15 caracteres',
            // 'slug.regex'=>'La :attribute debe ser una cadena de caracteres alfabéticos en minúsculas sin espacios, no se adminen signos de puntuación ni caracteres especiales',
            // 'slug.unique'=>'La :attribute ingresada ya existe',
            
            'description.max'=>'La :attribute no debe ser mayor a 255 caracteres',
            'description.regex'=>'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',

            'permissions.required'=>'Debe seleccionar al menos un permiso',
            'permissions.exists'=>'El permiso seleccionado inválido',
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
            // 'name' => 'nombre',
            // 'slug'=>'url',
            'description' => 'descripción',
        ];
    }
}
