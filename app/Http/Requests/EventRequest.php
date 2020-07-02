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
            'description'=> 'required|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/|max:255',
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
            'end-date'=>'nullable|date_format:Y-m-d|after:start-date',
            'responsible'=>'required|regex:/^[[:alpha:][:space:](áéíóúÁÉÍÓÚñÑ)]+$/|min:3|max:125',
            'phone_numbers'=>'required|array|max:3',
            "phone_numbers.*" => array("required","regex:/(^(09)[0-9]{8})+$|(^(02)[0-9]{7})+$/"),
            "ubication"=>"required|json",
            'ubication-description'=>'nullable|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/|max:255',
        ];

        if($this->isMethod('POST')){
            $rules += [
                "new_images" => "nullable|array|max:".env('NUMBER_EVENT_IMAGES_ALLOWED'),
                "new_images.*" => "nullable|image|mimes:jpeg,jpg,png|max:".env('SIZE_IMAGES_ALLOWED'),//el tamaño esta expresado en kilibytes, equivale a 1MB
            ];
        }
        if($this->isMethod('PUT')){
             //Se obtiene la cantidad de elementos en los arregos de imágenes nuevas y antiguas
             $numberOfNewImages = $this->hasFile('new_images') ? count($this->file('new_images')) : 0;
             $numberOfOldImages = $this->has('old_images') ? count($this->get('old_images')) : 0;
             $totalImages = $numberOfNewImages + $numberOfOldImages;

             if($totalImages > env('NUMBER_EVENT_IMAGES_ALLOWED')){
                $rules += [
                    'images_allowed'=>'required',
                ];
            }else{
                $rules += [
                    "new_images" => "nullable|array",
                    'old_images'=>'nullable|array',
                    "new_images.*" => "nullable|image|mimes:jpeg,jpg,png|max:".env('SIZE_IMAGES_ALLOWED'),//el tamaño esta expresado en kilibytes, equivale a 1MB
                ];
            }
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
            'title.required'=>'El campo :attribute es obligatorio',
            'title.min'=>'El :attribute debe ser mayor a 3 caracteres',
            'title.max'=>'El :attribute no debe ser mayor a 255 caracteres',
            'title.regex'=>'El :attribute debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',
            
            'description.required'=>'El campo :attribute es obligatorio',
            'description.max'=>'La :attribute no debe ser mayor a 255 caracteres',
            'description.regex'=>'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',

            'id.required'=>'El campo :attribute es obligatorio',
            'id.exists'=>'La :attribute seleccionada no existe',

            'start-time.required'=>'El campo :attribute es obligatorio',
            'start-time.regex'=>'La :attribute es inválida',

            'end-time.regex'=>'La :attribute es inválida',

            'start-date.required'=>'El campo :attribute es obligatorio',
            'start-date.date_format'=>'La :attribute es inválida',

            'end-date.date_format'=>'La :attribute es inválida',

            'responsible.required'=>'El campo :attribute es obligatorio',
            'responsible.min'=>'El :attribute debe ser mayor a 3 caracteres',
            'responsible.max'=>'El :attribute no debe ser mayor a 125 caracteres',
            'responsible.regex'=>'El :attribute debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',

            'phone_numbers.required'=>'El campo :attribute es obligatorio',
            'phone_numbers.max'=> 'Solo se permiten 3 números telefónicos',
            'phone_numbers.min'=> 'Se requiere de un número telefónico',
            
            'phone_numbers.*.regex'=>'No se cumple con el formato permitido',

            'ubication.required'=>'Debe seleccionar una ubicación en el mapa',
            'ubication.json'=>'No se cumple con el formaro JSON',

            'ubication-description.max'=>'El :attribute no debe ser mayor a 255 caracteres',
            'ubication-description.regex'=>'El :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',

            'new_images.max'=> 'Solo se permite(n) '.env('NUMBER_EVENT_IMAGES_ALLOWED').' imágenes',
            'new_images.*.image'=>'Solo se admiten :attribute en formato jpeg y png',
            'new_images.*.mimes'=>'Los formatos permitidos son jpeg y png',
            'new_images.*.max'=>'El tamaño máximo para las :attribute es '.env('SIZE_IMAGES_ALLOWED').' kilobytes',

            'images_allowed.required'=>'Solo se permiten '.env('NUMBER_EVENT_IMAGES_ALLOWED').' imágenes',
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
            'title'=>'título',
            'description' => 'descripción',
            'id' => 'categoría',
            'start-time' => 'hora de inicio',
            'end-time' => 'hora de cierre',
            'start-date' => 'fecha de inicio',
            'end-date' => 'fecha de cierre',
            'responsible' => 'responsable',
            'phone_numbers' => 'teléfonos',
            'ubication' => 'ubicación',
            'ubication-description' => 'detalle de la ubicación',
            'new_images.*' => 'imágenes',
            'images_allowed' => 'imágenes',
        ];
    }
}
