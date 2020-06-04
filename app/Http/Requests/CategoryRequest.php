<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
            'description'=> 'nullable|regex:/^[[:alpha:][:space:](,;.áéíóúÁÉÍÓÚ)]+$/|max:255',
            'icon' => "nullable|image|mimes:jpeg,png|max:1024",//el tamaño esta expresado en kilibytes, equivale a 1MB
        ];
    }
    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages(){
        return [
            'description.max'=>'La :attribute no debe ser mayor a 255 caracteres',
            'description.regex'=>'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',

            'icon.image'=>'Debe ser una imágen',
            'icon.mimes'=>'Los formatos permitidos son jpeg, jpg y png',
            'icon.max'=>'El tamaño máximo para el :attribute es 1MB',
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
            'description' => 'descripción',
            'icon'=> 'ícono',
        ];
    }
}
