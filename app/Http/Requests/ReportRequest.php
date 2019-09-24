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
            'title'=>'required|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚ)]+$/|min:3|max:45',
            'description'=> 'required|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚ)]+$/|max:255',
            "images" => "nullable|array",
            "images.*" => "nullable|image|mimes:jpeg,png|max:1000",
            
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
            'title.regex'=>'El :attribute debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',
            
            'description.required'=>'El campo :attribute es obligatorio',
            'description.max'=>'La :attribute no debe ser mayor a 255 caracteres',
            'description.regex'=>'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',

            'images.*.required'=>'El campo :attribute es obligatoria',
            'images.*.image'=>'Solo se admiten :attribute en formato jpeg y png',
            'images.*.mimes'=>'Los formatos permitidos son jpeg y png',
            'images.*.max'=>'El tamaño máximo para la :attribute es 1MB',
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
            'images.*'=>'imágenes'
        ];
    }
}
