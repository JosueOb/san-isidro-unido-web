<?php

namespace App\Http\Controllers;

use App\Http\Requests\NeighborRequest;
use App\Notifications\NeighborCreated;
use App\User;
use Caffeinated\Shinobi\Models\Role as ModelsRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PoliceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role_police = ModelsRole::where('slug', 'policia')->first();
        $policemen = $role_police->users()->paginate();

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
        substr($validated['first_name'],0,1).'+'.substr($validated['last_name'],0,1).
        '&size=255';
        $rolePolice = ModelsRole::where('slug', 'policia')->first();

        $police = new User();
        $police->first_name = $validated['first_name'];
        $police->last_name = $validated['last_name'];
        $police->email = $validated['email'];
        $police->avatar = $avatar;
        $police->password = \password_hash($password, PASSWORD_DEFAULT);
        $police->number_phone = $validated['number_phone'];
        $police->state = true;
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
