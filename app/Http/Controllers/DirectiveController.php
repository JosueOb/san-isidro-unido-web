<?php

namespace App\Http\Controllers;

use App\Http\Middleware\MemberIsActive;
use App\Http\Middleware\PreventMakingChangesToYourself;
use App\Http\Middleware\ProtectedAdminUsers;
use App\Http\Middleware\ProtectedGuestUsers;
use App\Http\Middleware\ProtectedModeratorUsers;
use App\Http\Middleware\ProtectedNeighborUsers;
use App\Http\Middleware\ProtectedPoliceUsers;
use App\Http\Requests\DirectiveRequest;
use App\Notifications\UserCreated;
use App\Position;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DirectiveController extends Controller
{
    public function __construct()
    {
        $this->middleware(ProtectedAdminUsers::class)->only('show','edit','update','destroy');
        $this->middleware(ProtectedNeighborUsers::class)->only('show','edit','update','destroy');
        $this->middleware(ProtectedModeratorUsers::class)->only('show','edit','update','destroy');
        $this->middleware(ProtectedPoliceUsers::class)->only('show','edit','update','destroy');
        $this->middleware(ProtectedGuestUsers::class)->only('show','edit','update','destroy');

        $this->middleware(MemberIsActive::class)->only('edit','update');
        $this->middleware(PreventMakingChangesToYourself::class)->only('edit','update','destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Se buscan a todos los usuarios con el rol directivo/a para listarlos
        $members = User::whereHas('roles',function(Builder $query){
            $query->where('slug','directivo');
        })->orderBy('last_name', 'asc')->paginate(10);

        return view('directive.index',[
            'members'=>$members,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $positions = Position::all();

        return view('directive.create',[
            'positions'=>$positions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DirectiveRequest $request)
    {
        $validated = $request->validated();

        //Se obtiene el cargo que fue seleccionado para el registro
        $getPosition = Position::find($validated['position']);
        //Se verifica si la asignación del cargo es de one-person (solo para una persona)
        if($getPosition->allocation === 'one-person'){
            //Se procede a verificar por cada usuario con el cargo seleccionado, si se encuentra activo como directivo
            if($this->checkMemberPosition($getPosition)){
                
                //En caso de encuentrar un miembro de la directiva activo con el cargo seleccionado se retorna
                //a la misma devuelve a la vista en la que se encontraba el usuario con los datos del formulario y un mensaje de error
                return back()->withInput()->with('observations',[
                    'La directiva ya cuenta con un usuario activo con el cargo de '.strtolower($getPosition->name),
                    'Se recomienda:',
                    '* Desactivar al usuario registrado con el cargo de '.strtolower($getPosition->name).
                    ' para proceder con el registro del nuevo miembro de la directiva',
                ]);
            }
        }

        $avatar  = 'https://ui-avatars.com/api/?name='.
        mb_substr($validated['first_name'],0,1).'+'.mb_substr($validated['last_name'],0,1).
        '&size=255';
        $password = Str::random(8);
        $roleNeighbor = Role::where('slug', 'morador')->first();
        $roleDirective = Role::where('slug','directivo')->first();

        $directiveMember = new User();
        $directiveMember->first_name = $validated['first_name'];
        $directiveMember->last_name = $validated['last_name'];
        $directiveMember->avatar = $avatar;
        $directiveMember->email = $validated['email'];
        $directiveMember->password =  password_hash($password,PASSWORD_DEFAULT);
        $directiveMember->state = true;
        $directiveMember->position_id = $validated['position'];
        $directiveMember->save();

        $directiveMember->roles()->attach([$roleDirective->id,$roleNeighbor->id],['state'=>true]);

        $directiveMember->notify(new UserCreated($password, $roleDirective->name));

        return redirect()->route('members.index')->with('success', 'Miembro registrado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('directive.show',[
            'member'=>$user,
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
        $positions = Position::all();

        return view('directive.edit',[
            'member'=> $user,
            'positions'=>$positions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DirectiveRequest $request, User $user)
    {
        //Se validan el campo email y position
        $validated = $request->validated();

        //Se obtiene el cargo que fue seleccionado para el registro
        $getPosition = Position::find($validated['position']);
        //Se verifica si la asignación del cargo es de one-person (solo para una persona) y se haya seleccionado otro cargo 
        //diferente al del miembro de la directiva
        if($getPosition->allocation === 'one-person' && $user->position->id != $validated['position']){
            //Se procede a verificar por cada usuario con el cargo seleccionado, si se encuentra activo como directivo
            if($this->checkMemberPosition($getPosition)){
                //En caso de encuentrar un miembro de la directiva activo con el cargo seleccionado se retorna
                //a la misma devuelve a la vista en la que se encontraba el usuario con los datos del formulario y un mensaje de error
                return back()->withInput()->with('observations',[
                    'La directiva ya cuenta con un usuario activo con el cargo de '.strtolower($getPosition->name),
                    'Se recomienda:',
                    '* Desactivar al usuario registrado con el cargo de '.strtolower($getPosition->name).
                    ' para proceder con la actualización del presente directivo',
                ]);
            }
        }

        //Se obtiene el correo del objeto usuario y del formulario
        $oldEmail = $user->email;
        $newEmail = $validated['email'];
        //Se actualiza el campo email y position del usuario
        $user->email = $validated['email'];
        $user->position_id = $validated['position'];
        //Se verifica si el correo del formulario con el del usuario no iguales
        if($oldEmail != $newEmail){
            //Se procede a generar una contraseña
            $password = Str::random(8);
            //Se cambia la contraseña del usuario
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            //Se envía una notificación
            $user->notify(new UserCreated($password, 'directivo'));
        }

        $user->save();

        return redirect()->route('members.index')->with('success','Miembro de la directiva actualizado exitosamente');
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
        //Se obtiene el rol del miembro de la directiva
        $roleUser = $user->getASpecificRole('directivo');
        //Se verifica si el rol de directivo se encuentra activo
        if($roleUser->pivot->state){
            $message= 'desactivado';
            //Se actualiza el estado de la relacion user_role en el campo state
            $user->roles()->updateExistingPivot($roleUser->id,['state'=> false]);
        }else{
            //Se obtiene el cargo que fue seleccionado para el registro
            $getPosition = Position::find($user->position_id);
            //Se verifica si la asignación del cargo es de one-person (solo para una persona)
            if($getPosition->allocation === 'one-person'){
                //Se procede a verificar por cada usuario con el cargo seleccionado, si se encuentra activo como directivo
                if($this->checkMemberPosition($getPosition)){
                    //En caso de encuentrar un miembro de la directiva activo con el cargo seleccionado se retorna
                    //a la misma devuelve a la vista en la que se encontraba el usuario con los datos del formulario y un mensaje de error
                    return back()->with('observations',[
                        'La directiva ya cuenta con un usuario activo con el cargo de '.strtolower($getPosition->name),
                        'Se recomienda:',
                        '* Desactivar al usuario registrado con el cargo de '.strtolower($getPosition->name).
                        ' para proceder con la activación del directivo selecionado',
                    ]);
                }
            }
            $message= 'activo';
            //Se actualiza el estado de la relacion user_role en el campo state
            $user->roles()->updateExistingPivot($roleUser->id,['state'=> true]);
        }
        return redirect()->back()->with('success','Miembro de la directiva '.$message);
    }
   
    //Permite verificar si algún usuario con el cargo de asignación de one-person, se encuentra activo
    //como directivo para impedir el registro de un nuevo miembros con dicho cargo. Solo se permitirá el 
    //registro de un nuevo usuario con el cargo de asignación de one-person si los anteriores miembros
    //se encuentren inactivos
    public function checkMemberPosition(Position $position){
        //Se obtienen a los usuarios con el cargo enviado como parámetro
        $users = $position->users;
        //Permite conocer si existe algún usuario con el cargo seleccionado se ecuentra activo como directivo
        $state = false;
        //Se recoren a los usuario con el cargo seleccionado
        foreach($users as $user){
            if($user->getRelationshipStateRolesUsers('directivo')){
                $state = true;
            }
        }
        return $state;
    }
}
