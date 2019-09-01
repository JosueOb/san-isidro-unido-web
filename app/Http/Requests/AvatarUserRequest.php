<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvatarUserRequest extends FormRequest
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
        //el tamaño esta dado en kilobytes
        return [
            'avatar'=>'required|image|max:1000',
        ];
    }
    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages(){
        return [
            'avatar.required'=>'La :attribute es obligatoria',
            'avatar.image'=>'Los formatos permitidos para la :attribute son jpeg, png, bmp, gif, svg o webp',
            'avatar.max'=>'El tamaño máximo para la :attribute es 1MB',
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
            'avatar'=>'imagen',
        ];
    }
}
