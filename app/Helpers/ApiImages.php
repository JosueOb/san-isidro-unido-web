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
     * @param string $fileImage
     * @param  mixed  $previous_name
     *
     * @return string
     */
    public function saveUserImageApi($imageFile, $previous_name = null)
    {
        try {
            $uploadedFile = File::get($imageFile);
            $img_extension = $imageFile->extension();
            $img_name = ($previous_name) ? $previous_name : 'user' . time() . '.' . $img_extension;
            $this->saveImageInDisk($this->diskImage, $img_name, $uploadedFile);
            return $img_name;
        } catch (Error $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Guarda la imagen de un usuario y retorna el nombre de la imagen guardada
     * @param string $fileImage
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
     * Guarda la imagen de una solicitud de afiliacion y retorna el nombre de la imagen guardada
     * @param string $fileImage
     * @param  mixed  $previous_name
     *
     * @return string
     */
    public function saveAfiliationImageApi($imageFile, $previous_name = null, $img_default_name)
    {
        try {
            $uploadedFile = File::get($imageFile);
            $img_default_name = ($img_default_name) ? $img_default_name: 'afiliation' . time();
            $img_extension = $imageFile->extension();
            $img_name = ($previous_name) ? $previous_name : $img_default_name . '.'.$img_extension;
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
        // dd($diskname);
        try {
            if (Storage::disk($diskname)->exists($img_name)) {
                Storage::disk($diskname)->delete($img_name);
            }
            Storage::disk($diskname)->put($img_name, $img_file);
        } catch (Error $e) {
            echo $e->getMessage();
        }
    }
    
    public function getApiUrlLink($value){
        $diskname = \Config::get('siu_config.API_IMAGES_DISK');
        if($this->checkURLValid($value)){
            return $value;
        }
        return \Storage::disk($diskname)->url($value);
    }

    public function checkURLValid($url){
        $value = $url;
        return (preg_match(
            "/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/", $value
        ));
    }
}
