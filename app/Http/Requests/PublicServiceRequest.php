<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicServiceRequest extends FormRequest
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
        $uniqueEmail = null;
         if($this->method() === 'POST'){
            $uniqueEmail = 'unique:public_services,email';
         }
         if($this->method() === 'PUT'){
            $uniqueEmail = 'unique:public_services,email,'.$this->route('publicService')->id;
         }
        return [
            'name'=>'required|regex:/^[[:alpha:][:space:](áéíóúÁÉÍÓÚ)]+$/|min:3|max:45',
            'description'=> 'nullable|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/|max:255',
            'subcategory'=>'required|exists:subcategories,id',
            'phone_numbers'=>'required|array|max:3',
            "phone_numbers.*" => array("required","regex:/(^(09)[0-9]{8})+$|(^(02)[0-9]{7})+$/"),
            'email'=>'nullable|email|'.$uniqueEmail,
            "ubication"=>"required|json",
            'ubication-description'=>'nullable|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/|max:255',
        ];
    }
    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages(){
        return [
            'name.required'=>'El campo :attribute es obligatorio',
            'name.min'=>'El :attribute debe ser mayor a 3 caracteres',
            'name.max'=>'El :attribute no debe ser mayor a 25 caracteres',
            'name.regex'=>'El :attribute debe estar conformado por caracteres alfabéticos, no se admiten signos de puntuación ni caracteres especiales',
            
            'description.required'=>'El campo :attribute es obligatorio',
            'description.max'=>'La :attribute no debe ser mayor a 255 caracteres',
            'description.regex'=>'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',
            
            'subcategory.required'=>'El campo :attribute es obligatorio',
            'subcategory.exists'=>'La :attribute seleccionada no existe',
            
            'phone_numbers.required'=>'El campo :attribute es obligatorio',
            'phone_numbers.max'=> 'Solo se permiten 3 números telefónicos',
            'phone_numbers.min'=> 'Se requiere de un número telefónico',
            
            'phone_numbers.*.regex'=>'No se cumple con el formato permitido',

            'email.email'=>'Fortamo del :attribute ingresado es incorrecto',
            'email.unique'=>'El :attribute ingresado ya existe',

            'ubication.required'=>'Debe seleccionar una ubicación en el mapa',
            'ubication.json'=>'No se cumple con el formaro JSON',

            'ubication-description.max'=>'El :attribute no debe ser mayor a 255 caracteres',
            'ubication-description.regex'=>'El :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',
            
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
            'name' => 'nombre',
            'description' => 'descripción',
            'subcategory' => 'categoría',
            'phone_numbers' => 'teléfonos',
            'email'=>'correo electrónico',
            'ubication' => 'ubicación',
            'ubication-description' => 'detalle de la ubicación',
        ];
    }
}
