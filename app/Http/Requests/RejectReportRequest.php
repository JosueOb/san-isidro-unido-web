<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectReportRequest extends FormRequest
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
            'description' => 'required|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/',
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
            
            'description.required' => 'El campo :attribute es obligatorio',
            'description.max' => 'La :attribute no debe ser mayor a 255 caracteres',
            'description.regex' => 'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',
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
        ];
    }
}
