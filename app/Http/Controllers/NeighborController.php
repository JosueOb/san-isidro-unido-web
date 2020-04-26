<?php

namespace App\Http\Controllers;

use App\Http\Middleware\NeighborIsActive;
use App\Http\Middleware\PreventMakingChangesToYourself;
use App\Http\Middleware\ProtectedAdminUsers;
use App\Http\Middleware\ProtectedDirectiveUsers;
use App\Http\Requests\NeighborRequest;
use App\Notifications\NeighborCreated;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class NeighborController extends Controller
{
    public function __construct()
    {
        $this->middleware(ProtectedAdminUsers::class)->only('show','edit','update','destroy');
        $this->middleware(ProtectedDirectiveUsers::class)->only('edit','update');
        $this->middleware(NeighborIsActive::class)->only('edit','update');
        $this->middleware(PreventMakingChangesToYourself::class)->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Inicializa @rownum
        DB::statement(DB::raw('SET @rownum = 0'));
        //Se realiza la consulta, se buscan los usuario que poseean el rol de morador pero no el del administrador
        $neighbors = User::whereHas('roles', function(Builder $query){
            $query->where('slug', 'morador');
        })->whereDoesntHave('roles', function (Builder $query) {
            $query->where('slug', 'admin');
        })->select('*',DB::raw('@rownum := @rownum + 1 as rownum'))->paginate();
        // dd($neighbors);

        return view('neighbors.index',[
            'neighbors'=>$neighbors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('neighbors.create');
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
        substr($validated['first_name'],0,1).'+'.substr($validated['last_name'],0,1).
        '&size=255';
        $roleNeighbord = Role::where('slug', 'morador')->first();

        $neighbor = new User();
        $neighbor->first_name = $validated['first_name'];
        $neighbor->last_name = $validated['last_name'];
        $neighbor->email = $validated['email'];
        $neighbor->avatar = $avatar;
        $neighbor->password = \password_hash($password, PASSWORD_DEFAULT);
        $neighbor->number_phone = $validated['number_phone'];
        $neighbor->state = true;
        $neighbor->save();

        $neighbor->roles()->attach($roleNeighbord->id, ['state'=>true]);

        $neighbor->notify(new NeighborCreated($password, $roleNeighbord->name));

        return redirect()->route('neighbors.index')->with('success', 'Morador registrado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('neighbors.show', [
            'neighbor'=> $user,
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
        return view('neighbors.edit', [
            'neighbor'=> $user,
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
        //Se actualiza el campo email y position del usuario
        $user->email = $validated['email'];
        $user->number_phone = $validated['number_phone'];
        //Se verifica si el correo del formulario con el del usuario no iguales
        if($oldEmail != $newEmail){
            //Se procede a generar una contraseña
            $password = Str::random(8);
            //Se cambia la contraseña del usuario
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            //Se envía una notificación
            $user->notify(new NeighborCreated($password, 'morador'));
        }

        $user->save();

        return redirect()->route('neighbors.index')->with('success','Morador actualizado exitosamente');
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
        $roleUser = $user->getASpecificRole('morador');

        if($roleUser->pivot->state){
            $message='desactivado';
            $user->roles()->updateExistingPivot($roleUser->id, ['state'=>false]);
        }else{
            $message='activo';
            $user->roles()->updateExistingPivot($roleUser->id, ['state'=>true]);
        }

        return redirect()->back()->with('success', 'Morador '.$message.' con éxito');
    }
    /**
     * filtros para listar usuarios activo, inactivo y todos.
     *
     * @param  int  $option
     * @return App\User;
     */
    public function filters($option){

        //Se obtienen a todos los usuarios con el rol de morador excepto al administrador
        $neighbors = User::whereHas('roles', function(Builder $query){
            $query->where('slug', 'morador');
        })->whereDoesntHave('roles', function (Builder $query) {
            $query->where('slug', 'admin');
        })->get();

        switch ($option) {
            case 1:
            //Se filtran a los moradores activos
                $neighbors = $neighbors->filter(function(User $value){
                    return $value->getRelationshipStateRolesUsers('morador');
                })->values();
                break;
            case 2:
            //Se filtran a los moradores inactivos
                $neighbors = $neighbors->filter(function(User $value){
                    return !$value->getRelationshipStateRolesUsers('morador');
                })->values();
                break;
            default:
                return abort(404);
                break;
        }

        //Se crear un paginador manualmente
        $total = count($neighbors);
        $pageName = 'page';
        $perPage = 15;

        //Se agrega un campo al usuario indicando su posición en el arreglo
        $neighbors->each(function($user, $key){
            data_fill($user,'rownum',  $key = $key + 1);
        });

        $neighbors = new LengthAwarePaginator($neighbors->forPage(Paginator::resolveCurrentPage(), $perPage), $total, $perPage, Paginator::resolveCurrentPage(), [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);

        return view('neighbors.index',[
            'neighbors'=>$neighbors,
        ]);
    }
}
