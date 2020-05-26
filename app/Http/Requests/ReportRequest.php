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
        // dd($this->isMethod('PUT'));
        $rules = [
            'title' => 'required|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/|min:3|max:255',
            'description' => 'required|regex:/^[[:alpha:][:space:](0-9)(,;.áéíóúÁÉÍÓÚÑñ)]+$/',
        ];

        if ($this->isMethod('POST')) {
            $rules += [
                "new_images" => "nullable|array|max:" . env('NUMBER_REPORT_IMAGES_ALLOWED'),
                "new_images.*" => "nullable|image|mimes:jpeg,jpg,png|max:". env('SIZE_IMAGES_ALLOWED'), //el tamaño esta expresado en kilobytes, equivale a 1MB

                "new_documents" => "nullable|array|max:" . env('NUMBER_REPORT_DOCUMENTS_ALLOWED'),
                "new_documents.*" => "nullable|file|mimes:pdf|max:". env('SIZE_DOCUMENTS_ALLOWED'), //el tamaño esta expresado en kilobytes, equivale a 5MB];
            ];
        }

        if ($this->isMethod('PUT')) {
            //Se obtiene la cantidad de elementos en los arregos de imágenes nuevas y antiguas
            $numberOfNewImages = $this->hasFile('new_images') ? count($this->file('new_images')) : 0;
            $numberOfOldImages = $this->has('old_images') ? count($this->get('old_images')) : 0;
            $totalImages = $numberOfNewImages + $numberOfOldImages;

            if ($totalImages > env('NUMBER_REPORT_IMAGES_ALLOWED')) {
                $rules += [
                    'images_allowed' => 'required',
                ];
            } else {
                $rules += [
                    "new_images" => "nullable|array",
                    'old_images' => 'nullable|array',
                    "new_images.*" => "nullable|image|mimes:jpeg,jpg,png|max:". env('SIZE_IMAGES_ALLOWED')
                ];
            }

            $numberOfNewDocuments = $this->hasFile('new_documents') ? count($this->file('new_documents')) : 0;
            $numberOfOlDocuments = $this->has('old_documents') ? count($this->get('old_documents')) : 0;
            $totalDocuments = $numberOfNewDocuments + $numberOfOlDocuments;

            if ($totalDocuments > env('NUMBER_REPORT_DOCUMENTS_ALLOWED')) {
                $rules += [
                    'documents_allowed' => 'required',
                ];
            } else {
                $rules += [
                    "new_documents" => "nullable|array",
                    'old_documents' => 'nullable|array',
                    "new_documents.*" => "nullable|file|mimes:pdf|max:". env('SIZE_DOCUMENTS_ALLOWED'), //el tamaño esta expresado en kilibytes, equivale a 5MB
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
    public function messages()
    {
        return [
            'title.required' => 'El campo :attribute es obligatorio',
            'title.min' => 'El :attribute debe ser mayor a 3 caracteres',
            'title.max' => 'El :attribute no debe ser mayor a 255 caracteres',
            'title.regex' => 'El :attribute debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',

            'description.required' => 'El campo :attribute es obligatorio',
            'description.max' => 'La :attribute no debe ser mayor a 255 caracteres',
            'description.regex' => 'La :attribute  debe estar conformado por caracteres alfabéticos, no se admiten caracteres especiales',

            'new_images.max' => 'Solo se permiten ' . env('NUMBER_REPORT_IMAGES_ALLOWED') . ' imágenes',
            'new_images.*.image' => 'Solo se admiten :attribute en formato jpeg y png',
            'new_images.*.mimes' => 'Los formatos permitidos son jpeg y png',
            'new_images.*.max' => 'El tamaño máximo para las :attribute es '. env('SIZE_IMAGES_ALLOWED'). ' kilobytes',

            'new_documents.max' => 'Solo se permite(n) ' . env('NUMBER_REPORT_DOCUMENTS_ALLOWED') . ' documento(s)',
            'new_documents.*.file' => 'Solo se admiten :attribute en formato pdf',
            'new_documents.*.mimes' => 'El formato permitido es pdf',
            'new_documents.*.max' => 'El tamaño máximo para los :attribute es '. env('SIZE_DOCUMENTS_ALLOWED') .' kilobytes',

            'images_allowed.required' => 'Solo se permiten ' . env('NUMBER_REPORT_IMAGES_ALLOWED') . ' imágenes',
            'documents_allowed.required' => 'Solo se permite(n) ' . env('NUMBER_REPORT_DOCUMENTS_ALLOWED') . ' documento(s)',
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
            'new_images.*' => 'imágenes',
            'new_documents.*' => 'documentos',
            'images_allowed' => 'imágenes',
            'documents_allowed' => 'documentos',
        ];
    }
}
