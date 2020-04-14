<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use App\Rules\Api\Base64FormatImage;
use Illuminate\Support\Facades\Validator;
use Closure;

class ConvertBase64ToImage{
	/**
	 * Verifica si existe un token valido de autorizacion 
     * de un usuario en la petición
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
        //Convertir Imagenes
        $request = $this->mergeInputArray($request, 'images');
        //Convertir Avatar
        $request = $this->mergeInput($request, 'avatar');
        //Convertir BasicServiceImage
        $request = $this->mergeInput($request, 'basic_service_image');
		return $next($request);
		
    }

    function mergeInput($request, $inputName){
        $tempVariable = $request->input($inputName, null);
        if(isset($tempVariable)){
            $validatorTemp = Validator::make([$inputName => $tempVariable], [
                $inputName => [new Base64FormatImage],
            ]);
            if ($validatorTemp->fails()){
                return response()->json($validatorTemp->messages(), 400);
            }
            $imgFileTemp = $this->getFileFromBase64($tempVariable);
            $request->merge([$inputName => $imgFileTemp]);
        }
        return $request;
    }

    function mergeInputArray($request, $inputName){
        $tempVariableArr = $request->input($inputName, []);
        $tempArr = [$inputName => $tempVariableArr];
        if(count($tempVariableArr) > 0){
            $validatorArrTemp = Validator::make($tempArr, [
                $inputName => ['required', 'array'],
                $inputName.".*" => [new Base64FormatImage],
            ]);
            if ($validatorArrTemp->fails()){
                return response()->json($validatorArrTemp->messages(), 400);
            }
            $tempFiles = [];
            foreach ($tempVariableArr as $tempVar) {
            //    print($imgBase64);
                $varFile = $this->getFileFromBase64($tempVar);
                array_push($tempFiles, $varFile);
            }
            $request->merge([$inputName => $tempFiles]);
        }
        return $request;
    }
    
    function getFileFromBase64($base64File){
         // decode the base64 file
         $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64File));

         // save it to temporary dir first.
         $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
         file_put_contents($tmpFilePath, $fileData);
 
         // this just to help us get file info.
         $tmpFile = new File($tmpFilePath);
 
         $file = new UploadedFile(
             $tmpFile->getPathname(),
             $tmpFile->getFilename(),
             $tmpFile->getMimeType(),
             0,
             true // Mark it as test, since the file isn't from real HTTP POST.
         );
         return $file;
    }


}
