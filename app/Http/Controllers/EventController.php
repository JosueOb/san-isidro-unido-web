<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\EventRequest;
use App\Post;
use App\Resource;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('events.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::where('slug', 'evento')->first();
        $subcategories = $category->subcategories()->get();
        return view('events.create',[
            'subcategories'=>$subcategories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        //Se obtiene la fecha y hora del sistema
        $dateTime = now();
        $date = $dateTime->toDateString(); 
        $time = $dateTime->toTimeString();

        $category_event = Category::where('slug', 'evento')->first();

        $validated = $request->validated();
        //se decodifica un string JSON en un array recursivo
        $ubication = json_decode($validated['ubication'], true);
        //Se le agrega al arreglo el detalle de la descripción de ubicación
        $ubication['description'] = $validated['ubication-description'];

        $additional_data = [
            'event'=>[
                'responsible'=> $validated['responsible'],
                'range_date' => [
                    'start_date' => $validated['start-date'],
                    'end_date' => $validated['end-date'],
                    'start_time' => $validated['start-time'],
                    'end_time' => $validated['end-time'],
                ]
            ]
        ];

        $event = new Post();
        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->state = true;
        $event->date = $date;
        $event->time = $time;
        $event->ubication = json_encode($ubication);//Se devuelve una representación de un JSON;
        $event->user_id = $request->user()->id;
        $event->category_id = $category_event->id;
        $event->subcategory_id = $validated['id'];
        $event->additional_data = json_encode($additional_data);
        $event->save();

        if($request->file('images')){
            foreach($request->file('images') as $image){
                Resource::create([
                    'url'=> $image->store('event_images', 'public'),
                    'post_id' => $event->id,
                    'type'=>'image',
                ]);
            }
        }

        return response()->json(['success'=>'Datos recibidos correctamente', 'form'=> $request->all(), 'validated'=>$validated]);
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
