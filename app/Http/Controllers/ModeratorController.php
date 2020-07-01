<?php

namespace App\Http\Controllers;

use App\Http\Middleware\IsModerator;
use App\Http\Middleware\ModeratorIsActive;
use App\Http\Middleware\NeighborIsActive;
use App\Http\Middleware\OnlyModerators;
use App\Http\Middleware\ProtectedAdminUsers;
use App\Http\Middleware\ProtectedDirectiveUsers;
use App\Http\Middleware\ProtectedGuestUsers;
use App\Http\Middleware\ProtectedModeratorUsers;
use App\Http\Middleware\ProtectedPoliceUsers;
use App\Http\Requests\NeighborRequest;
use App\Notifications\ModeratorCreated;
use App\Notifications\UserCreated;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class ModeratorController extends Controller
{
    public function __construct()
    {
        $this->middleware(OnlyModerators::class)->only('storeAssign', 'show', 'edit', 'update', 'destroy');
        
        
        // $this->middleware(ProtectedPoliceUsers::class)->only('storeAssign', 'show', 'edit', 'update', 'destroy');
        // $this->middleware(ProtectedGuestUsers::class)->only('storeAssign', 'show', 'edit', 'update', 'destroy');
        
        $this->middleware(ProtectedModeratorUsers::class)->only('storeAssign');
        $this->middleware(NeighborIsActive::class)->only('storeAssign');
        $this->middleware(ModeratorIsActive::class)->only( 'edit', 'update');
    }

    public function assign()
    {
        $neighbor_role = Role::where('slug', 'morador')->first();

        $neighbors = $neighbor_role->users()->whereDoesntHave('roles', function (Builder $query) {
            //Se evita que se listen a los regitros de administrador, directivo y moderadores asignados
            $query->whereIn('slug', ['admin', 'directivo', 'moderador']);
        })->orderBy('last_name', 'asc')->paginate(10);

        return view('moderators.assign', [
            'neighbors' => $neighbors,
        ]);
    }

    public function storeAssign(User $user)
    {
        //Se verifica que el usuario tenga verificado su correo electrónico
        if ($user->email_verified_at) {
            $role_moderator = Role::where('slug', 'moderador')->first();
            $user->roles()->attach($role_moderator->id, ['state' => true]);
            //Se envía un correo electrónico
            $user->notify(new ModeratorCreated());
            return redirect()->back()->with('success', 'Moderador asignado correctamente, puedes observarlo en la opción de Listar moderadores');
        } else {
            return redirect()->back()->with('danger', 'El morador no a verificado su correo electrónico');
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role_moderator = Role::where('slug', 'moderador')->first();
        $moderators = $role_moderator->users()->orderBy('last_name', 'asc')->paginate(10);

        return view('moderators.index', [
            'moderators' => $moderators,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('moderators.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(NeighborRequest $request)
    {
        $validated = $request->validated();

        $password = Str::random(8);
        $avatar  = 'https://ui-avatars.com/api/?name='.
        mb_substr($validated['first_name'],0,1).'+'.mb_substr($validated['last_name'],0,1).
        '&size=255';
        $moderator_role = Role::where('slug', 'moderador')->first();

        $moderator = new User();
        $moderator->first_name = $validated['first_name'];
        $moderator->last_name = $validated['last_name'];
        $moderator->email = $validated['email'];
        $moderator->avatar = $avatar;
        $moderator->password = \password_hash($password, PASSWORD_DEFAULT);
        $moderator->number_phone = $validated['number_phone'];
        $moderator->save();

        $moderator->roles()->attach($moderator_role->id, ['state'=>true]);

        $moderator->notify(new UserCreated($password, $moderator_role->name));

        return redirect()->route('moderators.index')->with('success', 'Moderador registrado con éxito');
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
            'moderator' => $user,
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
        return view('moderators.edit', [
            'moderator'=> $user,
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
            $user->notify(new UserCreated($password, 'moderador'));
        }

        $user->save();

        return redirect()->route('moderators.index')->with('success','Moderador actualizado exitosamente');
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
        $roleModeratorUser = $user->getASpecificRole('moderador');

        if ($roleModeratorUser->pivot->state) {
            $message = 'desactivado';
            $user->roles()->updateExistingPivot($roleModeratorUser->id, ['state' => false]);
        } else {
            $message = 'activado';
            $user->roles()->updateExistingPivot($roleModeratorUser->id, ['state' => true]);
        }
        return redirect()->back()->with('success', 'Moderador ' . $message . ' con éxito');
    }
}
