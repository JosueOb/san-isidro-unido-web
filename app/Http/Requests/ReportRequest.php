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
        $rules = [
            'title'=>'required|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/|min:3|max:255',
            'description'=> 'required|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/',
        ];

        if($this->isMethod('POST')){
            $rules += [
                "images" => "nullable|array|max:".env('NUMBER_IMAGES_ALLOWED'),
                "images.*" => "nullable|image|mimes:jpeg,jpg,png|max:1024",//el tamaño esta expresado en kilibytes, equivale a 1MB
                "document"=>"nullable|file|mimes:pdf|max:5120",//el tamaño esta expresado en kilibytes, equivale a 5MB];
            ];
        }

        if($this->isMethod('PUT')){
            //Se obtiene la cantidad de elementos en los arregos de imágenes nuevas y antiguas
            $numberOfNewImages = $this->hasFile('images') ? count($this->file('images')) : 0;
            $numberOfOldImages = $this->has('images_report') ? count($this->get('images_report')) : 0;
            $totalImages = $numberOfNewImages + $numberOfOldImages;
            //Se obtiene al documento nuevo y antiguo
            $numberOfNewDocument = $this->hasFile('document') ? 1 : 0;
            //Se verifica si el campo contiene contiene un valor, ya que no se recibe un array
            $numberOfOldDocument = $this->has('old_document') ? 1 : 0;
            $totalDocuments = $numberOfNewDocument + $numberOfOldDocument;

            if($totalImages > env('NUMBER_IMAGES_ALLOWED')){
                $rules += [
                    'images_allowed'=>'required',
                ];
            }else{
                $rules += [
                    "images" => "nullable|array",
                    'images_report'=>'nullable|array',
                    "images.*" => "nullable|image|mimes:jpeg,jpg,png|max:1024",//el tamaño esta expresado en kilibytes, equivale a 1MB
                ];
            }

            if($totalDocuments > 1){
                $rules += [
                    'documents_allowed'=>'required',
                ];
            }else{
                $rules += [
                    "document"=>"nullable|file|mimes:pdf|max:5120",//el tamaño esta expresado en kilibytes, equivale a 5MB];
                    'old_document'=>'nullable'
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
            'title.max'=>'El :attribute no debe ser mayor a 45 caracteres',
            'title.regex'=>'El :attribute debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',
            
            'description.required'=>'El campo :attribute es obligatorio',
            'description.max'=>'La :attribute no debe ser mayor a 255 caracteres',
            'description.regex'=>'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',

            'images.max'=> 'Solo se permiten '.env('NUMBER_IMAGES_ALLOWED').' imágenes',
            'images.*.image'=>'Solo se admiten :attribute en formato jpeg y png',
            'images.*.mimes'=>'Los formatos permitidos son jpeg y png',
            'images.*.max'=>'El tamaño máximo para la :attribute es 1MB',

            'document.file'=>'Solo se permite un documento',
            'document.mimes'=> "El formato del documento no es permitido",
            'document.max'=>"El tamaño máximo para el :attribute es 5MB",

            'images_allowed.required'=>'Solo se permiten '.env('NUMBER_IMAGES_ALLOWED').' imágenes',
            'documents_allowed.required'=>'Solo se permite un documento',
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
            'images.*' => 'imágenes',
            'document' => 'documento',
            'images_allowed' => 'imágenes',
            'documents_allowed' => 'documento',
        ];
    }
}
