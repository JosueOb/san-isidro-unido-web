<?php

namespace App\Http\Requests\Api;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Rules\Api\ProviderData;

class ApiUserProviderRequest extends FormRequest
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
            'provider'=> ['required', 'string', new ProviderData],
        ];
    }
    /**
     * Sobreescribir la validacion del formRequest para retornar un JSON.
     */
    protected function failedValidation(Validator $validator) { 
        $code = 400;
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()->first('provider'),
            'errors' => $validator->errors(),
            'code' => $code
        ], $code));
      
    }
}
