<?php

namespace App\Http\Requests;

use App\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
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
        $categoryEvent = Category::where('slug', 'evento')->first();
        $rules = [
            'title'=>'required|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/|min:3|max:255',
            'description'=> 'nullable|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/|max:255',
            //se recibe el id de la subcategoría
            'id'=>[
                'required',
                //Se verifica que el id se la subacategoría perteneczan solo a la categoría evento
                Rule::exists('subcategories')->where(function($query) use ($categoryEvent){
                    $query->where('category_id', $categoryEvent->id);
                }),
            ],
            'start-time'=>[
                'required',
                'regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/m'
            ],
            'end-time'=>[
                'nullable',
                'regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/m'
            ],
            'start-date'=>'required|date_format:Y-m-d',
            'end-date'=>'nullable|date_format:Y-m-d',
            'responsible'=>'required|regex:/^[[:alpha:][:space:](áéíóúÁÉÍÓÚ)]+$/|min:3|max:125',
            'phone_numbers'=>'required|array|max:3',
            "phone_numbers.*" => array("required","regex:/(^(09)[0-9]{8})+$|(^(02)[0-9]{7})+$/"),
            "ubication"=>"required|json",
            'ubication-description'=>'nullable|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/|max:255',
        ];

        if($this->isMethod('POST')){
            $rules += [
                "images" => "nullable|array|max:".env('NUMBER_IMAGES_EVENTS_ALLOWED'),
                "images.*" => "nullable|image|mimes:jpeg,jpg,png|max:1024",//el tamaño esta expresado en kilibytes, equivale a 1MB
            ];
        }

        return $rules;
    }
     /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages(){
        return [
            
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

        ];
    }
}
