<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ProtectedDirectiveUsers;
use App\User;
use Caffeinated\Shinobi\Contracts\Role;
use Caffeinated\Shinobi\Models\Role as ModelsRole;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ModeratorController extends Controller
{
    public function __construct()
    {
        $this->middleware(ProtectedDirectiveUsers::class)->only('store', 'show', 'destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role_moderator = ModelsRole::where('slug', 'moderador')->first();
        $moderators = $role_moderator->users()->paginate();

        return view('moderators.index', [
            'moderators'=> $moderators,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // //Se realiza la consulta, se buscan los usuario que poseean el rol de morador pero no el del administrador
        // $neighbors = User::whereHas('roles', function(Builder $query){
        //     $query->where('slug', 'morador');
        // })->whereDoesntHave('roles', function (Builder $query) {
        //     $query->where('slug',  'admin');
        //         //   ->where('slug', 'moderador');
        // })->get();

        
        
        $role_neighbor = ModelsRole::where('slug', 'morador')->first();
        $neighbors = $role_neighbor->users()->whereNull('position_id')->paginate();
        
        
        // dd($neighbors);
        return view('moderators.create', [
            'neighbors' => $neighbors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(User $user)
    {
        //Se verifica que el usuario esté activo como morador
        if($user->getRelationshipStateRolesUsers('morador')){
            // Se verifica si el usuario ya tiene asignado el rol de moderador
            if($user->getASpecificRole('moderador')){
                return redirect()->back()->with('danger', 'Moderador ya asignado');
            }
            $role_moderator = ModelsRole::where('slug', 'moderador')->first();
            $user->roles()->attach($role_moderator->id, ['state'=>true]);
            return redirect()->back()->with('success', 'Moderador asignado correctamente');
        }else{
            return redirect()->back()->with('danger', 'El morador se encuentra inactivo');
        }


        // dd('ingreso a guardar el usuario '.$user->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('moderators.show', [
            'moderator'=> $user,
        ]);
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $message = null;
        $roleModeratorUser = $user->getASpecificRole('moderador');

        if($roleModeratorUser->pivot->state){
            $message = 'desactivado';
            $user->roles()->updateExistingPivot($roleModeratorUser->id, ['state'=>false]);
        }else{
            $message = 'activado';
            $user->roles()->updateExistingPivot($roleModeratorUser->id, ['state'=>true]);
        }
        return redirect()->back()->with('success', 'Moderador '.$message.' con éxito');
    }
}
