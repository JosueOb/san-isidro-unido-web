<?php

namespace App\Http\Requests\Api;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Rules\Api\ProviderData;

class ApiCreateProblemRequest extends FormRequest
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
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            "images" => ['array'],
            "subcategory_id" => 'required|integer',
            "ubication" => ['required', 'array'],
            "ubication.latitude" => ['numeric', 'required_with:ubication', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],

            "ubication.longitude" => ['numeric', 'required_with:ubication', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
 
            "ubication.description" => ['string','required_with:ubication'],
 
            "ubication.address" => ['string', 'required_with:ubication'],
        ];
    }


    /**
    * Sobreescribir los mensajes de validación
    *
    * @return array
    */
    public function messages(){
        return [
            'ubication.latitude.regex' => 'El campo :attribute debe contener una latitud válida',
            'ubication.longitude.regex' => 'El campo :attribute debe contener una longitud válida',
            'ubication.address.regex' => 'El campo :attribute debe contener solo letras y números',
            'ubication.description.regex' => 'El campo :attribute debe contener solo letras y números'
        ];
    }

    /**
     * Sobreescribir la validacion del formRequest para retornar un JSON.
     */
    protected function failedValidation(Validator $validator) { 
        $code = 400;
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()->first('title'),
            'errors' => $validator->errors(),
            'code' => $code
        ], $code));
      
    }
}
