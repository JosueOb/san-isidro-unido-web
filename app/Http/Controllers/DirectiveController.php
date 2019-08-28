<?php

namespace App\Http\Controllers;

use App\Http\Middleware\DirectiveRoleExists;
use App\Http\Middleware\ProtectedAdminUsers;
use App\Http\Middleware\UserIsActive;
use App\Http\Requests\DirectiveRequest;
use App\Notifications\UserCreated;
use App\Position;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class DirectiveController extends Controller
{
    public function __construct()
    {
        $this->middleware(DirectiveRoleExists::class);
        $this->middleware(UserIsActive::class)->only('edit','update');
        $this->middleware(ProtectedAdminUsers::class)->only('show','edit','update','destroy');
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
            $query->whereIn('name',['Directivo', 'Directiva']);
        })->paginate();

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
        $avatar  = 'https://ui-avatars.com/api/?name='.
        substr($validated['first_name'],0,1).'+'.substr($validated['last_name'],0,1).
        '&size=255';
        $password = Str::random(8);
        $roleGuest = Role::where('name', 'Invitado')->first();
        $roleDirective = Role::whereIn('name',['Directivo', 'Directiva'])->first();
        
        $directiveMember = new User();
        $directiveMember->first_name = $validated['first_name'];
        $directiveMember->last_name = $validated['last_name'];
        $directiveMember->avatar = $avatar;
        $directiveMember->email = $validated['email'];
        $directiveMember->password =  password_hash($password,PASSWORD_DEFAULT);
        $directiveMember->state = true;
        $directiveMember->position_id = $validated['position'];

        $directiveMember->save();

        $directiveMember->roles()->attach([$roleGuest->id, $roleDirective->id]);

        $directiveMember->notify(new UserCreated($password));

        return redirect()->route('members.index')->with('success', 'Miembro registrado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $member)
    {
        return view('directive.show',[
            'member'=>$member,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $member)
    {
        $positions = Position::all();

        return view('directive.edit',[
            'member'=> $member,
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
    public function update(Request $request, User $member)
    {
        //Se validan el campo email y positision
        $filter = Validator::make($request->only(['email', 'position']),[
            'email'=>'required|email|unique:users,email,'.$member->id,
            'position'=>'required|exists:positions,id',
        ],[
            'email.required'=>'El campo correo electrónico es obligatorio',
            'email.email'=>'Fortamo del correo electrónico ingresado es incorrecto',
            'email.unique'=>'El correo electrónico ingresado ya existe',

            'position.required'=>'El campo cargo es obligatorio',
            'position.exists'=>'El cargo seleccionado no existe',

        ])->validate();
        //Se obtiene el correo del objeto usuario y del formulario
        $oldEmail = $member->email;
        $newEmail = $filter['email'];
        //Se actualiza el campo email y position del usuario
        $member->email = $filter['email'];
        $member->position_id = $filter['position'];
        //Se verifica si el correo del formulario con el del usuario no iguales
        if($oldEmail != $newEmail){
            //Se procede a generar una contraseña
            $password = Str::random(8);
            //Se cambia la contraseña del usuario
            $member->password = password_hash($password, PASSWORD_DEFAULT);
            //Se envía una notificación
            $member->notify(new UserCreated($password));
        }

        $member->save();

        return redirect()->route('members.index')->with('success','Miembro de la directiva actualizado exitosamente');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $member)
    {
        $mesage = '';
        //Se verifica si el usuario esta activo para desactivarlo y viceversa
        if($member->state){
            $message= 'desactivado';
            $member->state = false;
        }else{
            $message= 'activado';
            $member->state = true;
        }
        $member->save();

        return redirect()->back()->with('success','Miembro de la directiva '.$message);
    }
    /**
     * filtros para listar usuarios activo, inactivo y todos.
     *
     * @param  int  $option
     * @return App\User;
     */
    public function filters($option){

        $members = null;

        switch ($option) {
            case 1:
                $members = User::whereHas('roles',function(Builder $query){
                    $query->whereIn('name',['Directivo', 'Directiva']);
                })->paginate();
                break;
            case 2:
                $members = User::whereHas('roles',function(Builder $query){
                    $query->whereIn('name',['Directivo', 'Directiva']);
                })->where('state',true)->paginate();
                break;
            case 3:
                $members = User::whereHas('roles',function(Builder $query){
                    $query->whereIn('name',['Directivo', 'Directiva']);
                })->where('state',false)->paginate();
                break;
            default:
                return abort(404);
                break;
        }
        return view('directive.index',[
            'members'=>$members,
        ]);
    }
}
