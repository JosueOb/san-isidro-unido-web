<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PositionRequest extends FormRequest
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
            $uniqueName = 'unique:positions,name';
        }
        if($this->method() === 'PUT'){
            $uniqueName = 'unique:positions,name,'.$this->route('position')->id;
        }
        
        return [
            'name'=>'required|regex:/^[[:alpha:][:space:]]+$/|min:3|max:25|'.$uniqueName,
            'allocation'=>'required|'.Rule::in(['one-person', 'several-people']),
            'description'=> 'nullable|regex:/^[[:alpha:][:space:](,;.áéíóúÁÉÍÓÚ)]+$/|max:255',
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
            
            'allocation.required'=>'El campo :attribute es obligatorio',
            'allocation.in'=>'Lo opción seleccionada es inválida',
            
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
            'name' => 'nombre',
            'allocation' => 'asignación',
            'description' => 'descripción',
        ];
    }
}
