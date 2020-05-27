<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\PublicServiceRequest;
use App\Phone;
use App\PublicService;

class PublicServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $publicServices = PublicService::paginate(10);
        return view('public-services.index', [
            'publicServices'=> $publicServices,
        ]);
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
        
        $public_opening = [
            'open_time' => $validated['open-time'],
            'close_time' => $validated['close-time'],
        ];

        $publicService = new PublicService();
        $publicService->name = $validated['name'];
        $publicService->ubication = json_encode($ubication);//Se devuelve una representación de un JSON
        $publicService->subcategory_id = $validated['id'];
        $publicService->public_opening = json_encode($public_opening);
        $publicService->email = $validated['email'];
        $publicService->save();

        $phones = $validated['phone_numbers'];
        foreach($phones as $phone){
            $phone_number = new Phone(['phone_number' => $phone]);
            $publicService->phones()->save($phone_number);
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
    public function show(PublicService $publicService)
    {
        $ubication = json_decode($publicService->ubication, true);
        $public_opening = json_decode($publicService->public_opening, true);
        return view('public-services.show', [
            'publicService'=>$publicService,
            'ubication'=>$ubication,
            'publicOpening'=>$public_opening,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PublicService $publicService)
    {
        $category = Category::where('slug', 'servicio-publico')->first();
        $subcategories = $category->subcategories()->get();
        $public_opening = json_decode($publicService->public_opening, true);
        $ubication = json_decode($publicService->ubication, true);
        return view('public-services.edit', [
            'publicService'=>$publicService,
            'subcategories'=>$subcategories,
            'publicOpening'=>$public_opening,
            'ubication'=> $ubication,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PublicServiceRequest $request, PublicService $publicService)
    {
        $validated = $request->validated();

        //se decodifica un string JSON en un array recursivo
        $ubication = json_decode($validated['ubication'], true);
        //Se le agrega al arreglo el detalle de la descripción de ubicación
        $ubication['description'] = $validated['ubication-description'];
        $public_opening = [
            'open_time' => $validated['open-time'],
            'close_time' => $validated['close-time'],
        ];

        $publicService->name = $validated['name'];
        $publicService->ubication = json_encode($ubication);//Se devuelve una representación de un JSON
        $publicService->subcategory_id = $validated['id'];
        $publicService->public_opening = json_encode($public_opening);
        $publicService->email = $validated['email'];
        $publicService->save();

        $newPhones = $validated['phone_numbers'];
        $oldPhones = $publicService->phones;

        $this->deleteOldPhones($oldPhones, $newPhones);
        $this->saveNewPhones($newPhones, $oldPhones, $publicService);

        session()->flash('success', 'Servicio público actualizado con éxito');
        return response()->json([
            'success'=>'Datos recibidos correctamente',
            'redirect'=>route('publicServices.index'),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PublicService $publicService)
    {
        $publicService->phones()->delete();
        $publicService->delete();
        session()->flash('success', 'Servicio público eliminado con éxito');
        return redirect()->route('publicServices.index')->with('success', 'Servicio público eliminado con éxito');
        
    }

    /**
     * Función que permite detemrinar la existencia de un número telefónico en un arreglo dado.
     *
     * @param  string $phone_search 
     * @param  Collection $phone_array
     * @return boolean
     */
    public function isThereAPhoneNumber($phone_search, $phone_array){
        foreach($phone_array as $phone){
            if($phone === $phone_search){
                return true;
            }
        }
        return false;
    }

    /**
     * Función que elimina los teléfonos registrado a partir de la existencia del 
     * mismo en un determinado arreglo
     */
    public function deleteOldPhones($oldPhones, $newPhones){
        foreach($oldPhones as $oldPhone){
            //Si el teléfono fue elimnado
            if(!$this->isThereAPhoneNumber($oldPhone->phone_number, $newPhones)){
                $oldPhone->delete();
            }
        }
    }

    /**
     * Función que guarda en la base de datos los números telefónicos que sean diferentes
     * a los almacenados anteriormente
     */
    public function saveNewPhones($newPhones, $oldPhones, $publicService){
        $oldPhones = $oldPhones->pluck('phone_number')->toArray();
        foreach($newPhones as $newPhone){
            //Si el teléfono es nuevo
            if(!$this->isThereAPhoneNumber($newPhone, $oldPhones)){
                $phone_number = new Phone(['phone_number' => $newPhone]);
                $publicService->phones()->save($phone_number);
            }
        }
    }
}
