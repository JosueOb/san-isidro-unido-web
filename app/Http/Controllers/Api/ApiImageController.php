<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\ApiBaseController;


class ApiImageController extends ApiBaseController
{

    /**
     * Sirve una imagen del Storage de Imagenes de la API
     * @param string $filename
     *
     * @return resource
     */
    public function getImageB64($filename) {
        //Disco donde se guarda las imagenes
        // $diskImage = 'images';
        $diskImage = \Config::get('siu_config.API_IMAGES_DISK');
        // Verifica si existe el archivo
        $exists = Storage::disk($diskImage)->exists($filename);
        //Si existe el archivo lo sirvo, caso contrario retorno una respuesta de no encontrado
        if ($exists) {
            // Obtener un array con la extension simple y completa de la Imagen
            $filename_array = explode(".", $filename);
            // Obtener la extensión completa de la Imagen
            $img_ext = $filename_array[1];
            // Especificar la extensión completa para servir correctamente la imagen
            $full_extension = "image/$img_ext";
            $file = Storage::disk($diskImage)->get($filename);
            return (new Response($file, 200))->header('Content-Type', $full_extension);
           
        } else {
            return $this->sendError(404, "La Imagen solicitada no existe", ['message' => 'Image Not Found']);
        }
    }
}
