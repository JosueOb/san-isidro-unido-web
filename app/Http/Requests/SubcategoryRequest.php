<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubcategoryRequest extends FormRequest
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
        $uniqueName = null;

        if($this->method() === 'POST'){
            $uniqueName = 'unique:subcategories,name';
        }
        // if($this->method() === 'PUT'){
        //     $uniqueName = 'unique:subcategories,name,'.$this->route('subcategory')->id;
        // }
        
        return [
            'name'=>'required|regex:/^[[:alpha:][:space:](áéíóúÁÉÍÓÚ)]+$/|min:3|max:25|'.$uniqueName,
            'description'=> 'nullable|regex:/^[[:alpha:][:space:](,;.áéíóúÁÉÍÓÚ)]+$/|max:255',
            'category'=>'required|exists:categories,id',
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
            'name.required'=>'El campo :attribute es obligatorio',
            'name.min'=>'El :attribute debe ser mayor a 3 caracteres',
            'name.max'=>'El :attribute no debe ser mayor a 25 caracteres',
            'name.regex'=>'El :attribute debe estar conformado por caracteres alfabéticos, no se admiten signos de puntuación ni caracteres especiales',
            'name.unique'=>'El nombre ingresado ya existe',
            
            'description.max'=>'La :attribute no debe ser mayor a 255 caracteres',
            'description.regex'=>'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',

            'category.required'=>'El campo :attribute es obligatorio',
            'category.exists'=>'La :attribute seleccionada no existe',

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
            'name' => 'nombre',
            'description' => 'descripción',
            'icon'=> 'ícono',
            'category'=>'categoría'
        ];
    }
}
