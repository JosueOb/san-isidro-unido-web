<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'title'=>'required|regex:/^[[:alpha:][:space:]]+$/|min:3|max:45|',
            'description'=> 'required|regex:/^[[:alpha:][:space:](,;.áéíóúÁÉÍÓÚ)]+$/|max:255',
        ];
    }
     /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages(){
        return [
            'title.required'=>'El campo :attribute es obligatorio',
            'title.min'=>'El :attribute debe ser mayor a 3 caracteres',
            'title.max'=>'El :attribute no debe ser mayor a 45 caracteres',
            'title.regex'=>'El :attribute debe estar conformado por caracteres alfabéticos, no se admiten signos de puntuación ni caracteres especiales',
            
            'description.required'=>'El campo :attribute es obligatorio',
            'description.max'=>'La :attribute no debe ser mayor a 255 caracteres',
            'description.regex'=>'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',
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
            'title' => 'título',
            'description' => 'descripción',
        ];
    }
}
