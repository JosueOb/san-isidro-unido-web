<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\PublicServiceRequest;
use App\Phone;
use App\PublicService;
use App\Subcategory;
use Illuminate\Http\Request;

class PublicServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('public-services.index');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::where('slug', 'servicio-publico')->first();
        $subcategories = $category->subcategories()->get();
        return view('public-services.create',[
            'subcategories'=>$subcategories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PublicServiceRequest $request)
    {
        $validated = $request->validated();
        //se decodifica un string JSON en un array recursivo
        $ubication = json_decode($validated['ubication'], true);
        //Se le agrega al arreglo el detalle de la descripción de ubicación
        $ubication['description'] = $validated['ubication-description'];
        // dd($ubication);

        $publicService = new PublicService();
        $publicService->name = $validated['name'];
        $publicService->description = $validated['description'];
        $publicService->ubication = json_encode($ubication);//Se devuelve una representación de un JSON
        $publicService->subcategory_id = $validated['subcategory'];
        $publicService->email = $validated['email'];
        $publicService->save();

        $phones = $validated['phone_numbers'];
        foreach($phones as $phone){
            Phone::create([
                'phone_number'=> $phone,
                'public_service_id' => $publicService->id,
            ]);
        }
        
        session()->flash('success', 'Servicio público registrado con éxito');
        return response()->json(['success'=>'Datos recibidos correctamente']);
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
