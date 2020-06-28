<?php

namespace App\Http\Controllers;

use App\Http\Middleware\PoliceIsActive;
use App\Http\Middleware\ProtectedAdminUsers;
use App\Http\Middleware\ProtectedDirectiveUsers;
use App\Http\Middleware\ProtectedGuestUsers;
use App\Http\Middleware\ProtectedModeratorUsers;
use App\Http\Middleware\ProtectedNeighborUsers;
use App\Http\Requests\NeighborRequest;
use App\Notifications\NeighborCreated;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PoliceController extends Controller
{
    public function __construct()
    {
        $this->middleware(ProtectedAdminUsers::class)->only('show','edit','update','destroy');
        $this->middleware(ProtectedDirectiveUsers::class)->only('show','edit','update','destroy');
        $this->middleware(ProtectedModeratorUsers::class)->only('show','edit','update','destroy');
        $this->middleware(ProtectedNeighborUsers::class)->only('show','edit','update','destroy');
        $this->middleware(ProtectedGuestUsers::class)->only('show','edit','update','destroy');
        
        $this->middleware(PoliceIsActive::class)->only('edit','update');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role_police = Role::where('slug', 'policia')->first();
        $policemen = $role_police->users()->orderBy('last_name', 'asc')->paginate(10);

        return view('policemen.index', [
            'policemen'=>$policemen,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('policemen.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NeighborRequest $request)
    {
        $validated = $request->validated();

        $password = Str::random(8);
        $avatar  = 'https://ui-avatars.com/api/?name='.
        mb_substr($validated['first_name'],0,1).'+'.mb_substr($validated['last_name'],0,1).
        '&size=255';
        $rolePolice = Role::where('slug', 'policia')->first();

        $police = new User();
        $police->first_name = $validated['first_name'];
        $police->last_name = $validated['last_name'];
        $police->email = $validated['email'];
        $police->avatar = $avatar;
        $police->password = \password_hash($password, PASSWORD_DEFAULT);
        $police->number_phone = $validated['number_phone'];
        $police->save();

        $police->roles()->attach($rolePolice->id, ['state'=>true]);

        $police->notify(new NeighborCreated($password, $rolePolice->name));

        return redirect()->route('policemen.index')->with('success', 'Policía registrado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('policemen.show', [
            'police'=>$user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('policemen.edit', [
            'police'=>$user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NeighborRequest $request, User $user)
    {
        $validated = $request->validated();

        //Se obtiene el correo del objeto usuario y del formulario
        $oldEmail = $user->email;
        $newEmail = $validated['email'];

        //Se actualiza el campo email y teléfono del usuario
        $user->email = $validated['email'];
        $user->number_phone = $validated['number_phone'];
        //Se verifica si el correo del formulario con el del usuario no iguales
        if($oldEmail != $newEmail){
            //Se procede a generar una contraseña
            $password = Str::random(8);
            //Se cambia la contraseña del usuario
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            //Se envía una notificación
            $user->notify(new NeighborCreated($password, 'policía'));
        }
        $user->save();

        return redirect()->route('policemen.index')->with('success','Policía actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $message = null;
        $roleUser = $user->getASpecificRole('policia');
        if($roleUser->pivot->state){
            $message='desactivado';
            $user->roles()->updateExistingPivot($roleUser->id, ['state'=>false]);
        }else{
            $message='activo';
            $user->roles()->updateExistingPivot($roleUser->id, ['state'=>true]);
        }
        return redirect()->back()->with('success', 'Policía '.$message.' con éxito');
    }
}
