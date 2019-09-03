<?php

namespace App\Http\Controllers;

use App\Http\Requests\NeighborRequest;
use App\Notifications\NeighborCreated;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NeighborController extends Controller
{
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
            $query->where('name', 'Morador');
        })->whereDoesntHave('roles', function (Builder $query) {
            $query->where('name', 'Administrador');
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
        $roleNeighbord = Role::where('name', 'Morador')->first();

        $neighbor = new User();
        $neighbor->first_name = $validated['first_name'];
        $neighbor->last_name = $validated['last_name'];
        $neighbor->email = $validated['email'];
        $neighbor->password = \password_hash($password, PASSWORD_DEFAULT);
        $neighbor->number_phone = $validated['number_phone'];
        $neighbor->state = true;
        $neighbor->save();

        $neighbor->roles()->attach($roleNeighbord->id, ['state'=>true]);

        $neighbor->notify(new NeighborCreated($password));

        return redirect()->route('neighbors.index')->with('success', 'Morador registrado con éxito');
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
