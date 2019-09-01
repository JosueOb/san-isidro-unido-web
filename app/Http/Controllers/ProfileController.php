<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvatarUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(){
        return view('profile.index');
    }

    public function changeAvatar(AvatarUserRequest $request){

        //Se obtiene al usuario que realizar la petición
        $user = $request->user();
        //Se obtiene el antiguo avatar del usuario
        $oldAvatar = $user->avatar;
        //Se obtene a la imagen subida desde el formulario
        $newAvatar = $request->file('avatar');
        //Se procede a guardar el nuevo avatar en el usuario que realizó la petición
        $user->avatar = $newAvatar->store('avatars', 'public');
        $user->save();

        //En caso de que el usuario tenga una imagen en el directorio avatars se procede 
        //a eliminarla
        if(Storage::disk('public')->exists($oldAvatar)){
            Storage::disk('public')->delete($oldAvatar);
        }

        return redirect()->route('profile')->with('success', 'Avatar actualizado');

    }
}
