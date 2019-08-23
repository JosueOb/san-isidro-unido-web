<?php

namespace App\Http\Controllers;

use App\Http\Middleware\DirectiveRoleExists;
use App\Http\Requests\DirectiveRequest;
use App\Notifications\UserCreated;
use App\Position;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class DirectiveController extends Controller
{
    public function __construct()
    {
        $this->middleware(DirectiveRoleExists::class);
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
        //
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
        $avatar =  $avatar = 'https://ui-avatars.com/api/?name='.
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
