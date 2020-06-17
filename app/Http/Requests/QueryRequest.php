<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QueryRequest extends FormRequest
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
        $rules = [
            'filterOption'=>'nullable|regex:/^[(0-9)]+$/',
        ];
        //Se verifica que para realizar una búsqueda debe contener las dos varibles
        //la opción de búsqueda y el valor a buscar
        if($this->has(['searchOption', 'searchValue'])){
            $rules += [
                'searchOption'=>'required|regex:/^[(0-9)]+$/',
                'searchValue'=>'required|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/|max:50',
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
            'filterOption.regex'=>'Selección de filtro inválido',

            'searchOption.required'=>'Seleccione una opción de búsqueda',
            'searchOption.regex'=>'Opción de búsqueda seleccionada inválida',
            
            'searchValue.required'=>'El campo :attribute es obligatorio',
            'searchValue.regex'=>'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',
            'searchValue.max'=>'La :attribute no debe ser mayor a 50 caracteres',
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
