<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvatarUserRequest;
use App\Http\Requests\DataUserRequest;
use App\Http\Requests\PasswordUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(){
        return view('profile.index');
    }

    public function changeAvatar(AvatarUserRequest $request){
        //Se valida los campos del formulario
        $validated = $request->validated();
        //Se obtiene al usuario que realizar la petición
        $user = $request->user();
        //Se obtiene el antiguo avatar del usuario
        $oldAvatar = $user->avatar;
        //Se obtene a la imagen subida desde el formulario
        $newAvatar = $validated['avatar'];
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

    public function changePersonalData(DataUserRequest $request){
        //Se valida los datos del formulario
        $validated = $request->validated();
        //Se obtiene al usuario que está realizado la petición
        $user = $request->user();
        //Se actualiza los datos del usuario
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->number_phone = $validated['number_phone'];
        $user->save();

        return redirect()->route('profile')->with('success', 'Datos personales actualizados');
    }

    public function changePassword(PasswordUserRequest $request){
        //Se valida los datos del formulario
        $validated = $request->validated();
        //Se obtiene al usuario que está realizando la petición
        $user = $request->user();
        //Se actualiza su contraseña
        $user->password = password_hash($validated['password'],PASSWORD_DEFAULT);
        $user->save();

        return redirect()->route('profile')->with('success', 'Contraseña actualizada');
    }
}
