<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'searchOption'=>'required|integer|numeric',
            'searchValue'=>'required|regex:/^[[:alpha:][:space:](,;.áéíóúÁÉÍÓÚ)]+$/|max:100',
        ];
    }
        /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages(){
        return [
            'searchOption.required'=>'Seleccione una opción de búsqueda',
            'searchOption.integer'=>'El campo :attribute debe ser un número',
            'searchOption.regex'=>'Opción seleccionada inválida',
            
            'searchValue.required'=>'El campo :attribute es obligatorio',
            'searchValue.regex'=>'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales ni numéricos',
            'searchValue.max'=>'La :attribute no debe ser mayor a 100 caracteres',
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
            'searchOption' => 'opción',
            'searchValue' => 'búsqueda',
        ];
    }
}
