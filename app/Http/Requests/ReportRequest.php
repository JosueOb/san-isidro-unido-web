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
            'title'=>'required|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/|min:3|max:255',
            'description'=> 'required|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/',
            "images" => "nullable|array|max:5",
            "images.*" => "nullable|image|mimes:jpeg,jpg,png|max:1024",//el tamaño esta expresado en kilibytes, equivale a 1MB
            "document"=>"nullable|file|mimes:pdf|max:5120",//el tamaño esta expresado en kilibytes, equivale a 5MB
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

            'images.max'=> 'Solo se premiten 5 imágenes',
            'images.*.image'=>'Solo se admiten :attribute en formato jpeg y png',
            'images.*.mimes'=>'Los formatos permitidos son jpeg y png',
            'images.*.max'=>'El tamaño máximo para la :attribute es 1MB',

            'document.mimes'=> "El formato del documento no es permitido",
            'document.max'=>"El tamaño máximo para el :attribute es 5MB",
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
            'images.*'=>'imágenes',
            'document'=>'documento',
        ];
    }
}
