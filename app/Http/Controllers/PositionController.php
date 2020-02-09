<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function __construct()
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = Position::paginate(5);

        return view('positions.index',[
            'positions'=>$positions,
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
        return view('positions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionRequest $request)
    {
        $validated = $request->validated();

        $position = new Position();
        $position->name = $validated['name'];
        $position->allocation = $validated['allocation'];
        $position->description = $validated['description'];
        $position->save();

        return redirect()->route('positions.index')->with('success','Cargo registrado exitosamente');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Position $position)
    {

        return view('positions.edit',[
            'position'=>$position
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PositionRequest $request, Position $position)
    {
        
        $validated = $request->validated();
        
        $position->name = $validated['name'];
        $position->allocation = $validated['allocation'];
        $position->description = $validated['description'];
        $position->save();

        return redirect()->route('positions.index')->with('success','Cargo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        //Se verifica si el cargo esta siendo utilizado por algún usuario
        $positionUsed = $position->users()->get();
        if(count($positionUsed)>0){
            return redirect()->route('positions.index')->with('danger','El cargo '.strtolower($position->name).' no se puede eliminar debido a que esta siendo utilizado');
        }else{
            $position->delete();
            return redirect()->route('positions.index')->with('success','Cargo eliminado exitosamente');
        }
    }
}
