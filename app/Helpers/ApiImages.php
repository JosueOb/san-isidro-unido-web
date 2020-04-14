<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use Error;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ApiImages
{

     /**
     * Constructor Clase
     *
     * @return void
     */
    public function __construct()
    {
        $this->diskImage = Config::get('siu_config.API_IMAGES_DISK');
    }

    /**
     * Guarda la imagen de un usuario y retorna el nombre de la imagen guardada
     * @param string $base64IMG
     * @param  mixed  $previous_name
     *
     * @return string
     */
    public function saveUserImageApi($base64IMG, $previous_name = null)
    {
        try {
            $img_file = $this->getB64Image($base64IMG);
            $img_extension = $this->getB64Extension($base64IMG);
            $img_name = ($previous_name) ? $previous_name : 'user' . time() . '.' . $img_extension;
            $this->saveImageInDisk($this->diskImage, $img_name, $img_file);
            return $img_name;
        } catch (Error $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Guarda la imagen de un usuario y retorna el nombre de la imagen guardada
     * @param string $base64IMG
     * @param  mixed  $previous_name
     *
     * @return string
     */
    public function savePostFileImageApi($imageFile, $previous_name = null)
    {
        try {
            $uploadedFile = File::get($imageFile);
            $img_extension = $imageFile->extension();
            $img_name = ($previous_name) ? $previous_name : 'post_siu' . time() . '.' . $img_extension;
            $this->saveImageInDisk($this->diskImage, $img_name, $uploadedFile);
            return $img_name;
        } catch (Error $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Guarda la imagen de una publicacion y retorna el nombre de la imagen guardada
     * @param string $base64IMG
     * @param  mixed  $previous_name
     *
     * @return string
     */
    public function savePostImageApi($base64IMG, $previous_name = null)
    {
        try {
            $img_file = $this->getB64Image($base64IMG);
            $img_extension = $this->getB64Extension($base64IMG);
            $img_name = ($previous_name) ? $previous_name : 'post' . time() . '.' . $img_extension;
            $this->saveImageInDisk($this->diskImage, $img_name, $img_file);
            return $img_name;
        } catch (Error $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Guarda la imagen de una solicitud de afiliacion y retorna el nombre de la imagen guardada
     * @param string $base64IMG
     * @param  mixed  $previous_name
     *
     * @return string
     */
    public function saveAfiliationImageApi($base64IMG, $previous_name = null)
    {
        try {
            $img_file = $this->getB64Image($base64IMG);
            $img_extension = $this->getB64Extension($base64IMG);
            $img_name = ($previous_name) ? $previous_name : 'afiliation' . time() . '.' . $img_extension;
            $this->saveImageInDisk($this->diskImage, $img_name, $img_file);
            return $img_name;
        } catch (Error $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Guarda la imagen de una solicitud de afiliacion y retorna el nombre de la imagen guardada
     * @param string $base64IMG
     * @param  mixed  $previous_name
     *
     * @return string
     */
    public function saveAfiliationFileImageApi($imageFile, $previous_name = null, $titleIncluded='user_afiliation')
    {
        try {
            $uploadedFile = File::get($imageFile);
            $img_extension = $imageFile->extension();
            $img_name = ($previous_name) ? $previous_name : $titleIncluded . time() . '.' . $img_extension;
            $this->saveImageInDisk($this->diskImage, $img_name, $uploadedFile);
            return $img_name;
        } catch (Error $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Guarda la imagen en un disco del Storage
     * @param string $diskname
     * @param string $img_name
     * @param  mixed  $img_file
     *
     * @return void
     */
    private function saveImageInDisk($diskname, $img_name, $img_file)
    {
        try {
            if (Storage::disk($diskname)->exists($img_name)) {
                Storage::disk($diskname)->delete($img_name);
            }
            Storage::disk($diskname)->put($img_name, $img_file);
        } catch (Error $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Devuelve una imagen Base64 Decodificada
     * @param string $base64_image_encoded
     *
     * @return string
     */
    private function getB64Image($base64_image_encoded)
    {
        try { 
            // Obtener el String base-64 de los datos
            $image_service_str = substr($base64_image_encoded, strpos($base64_image_encoded, ",") + 1);
            // Decodificar ese string y devolver los datos de la imagen
            $image = base64_decode($image_service_str);
            return $image;
        } catch (Error $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Devuelve la extension de una imagen Base64
     * @param string $base64_image
     * @param boolean $full
     *
     * @return string
     */
    private function getB64Extension($base64_image, $full = null)
    {

        try {
            $img = explode(',', $base64_image);
            $ini = substr($img[0], 11);
            $img_extension = explode(';', $ini);
            if ($full) {
                return "image/" . $img_extension[0];
            } else {
                return $img_extension[0];
            }
        } catch (Error $e) {
            echo $e->getMessage();
        }
    }

    public function getApiUrlLink($value){
        $diskname = \Config::get('siu_config.API_IMAGES_DISK');
        if($this->checkURLValid($value)){
            return $value;
        }
        if (\Storage::disk($diskname)->exists($value)) {
            return \Storage::disk($diskname)->url($value);
        }
        return "https://ui-avatars.com/api/?name=Siu+Subcategoria";
    }

    public function checkURLValid($url){
        $value = $url;
        return (preg_match(
            "/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/", $value
        ));
    }
}
