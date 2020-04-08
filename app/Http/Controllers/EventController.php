<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\EventRequest;
use App\Phone;
use App\Post;
use App\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category_event = Category::where('slug', 'evento')->first();
        $events = $category_event->posts()->paginate();
        return view('events.index',[
            'events' => $events,
        ]);
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

        $phones = $validated['phone_numbers'];
        foreach($phones as $phone){
            $phone_number = new Phone(['phone_number' => $phone]);
            $event->phones()->save($phone_number);
        }

        if($request->file('new_images')){
            foreach($request->file('new_images') as $image){
                Resource::create([
                    'url'=> $image->store('event_images', 'public'),
                    'post_id' => $event->id,
                    'type'=>'image',
                ]);
            }
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
    public function show(Post $event)
    {
        $additional_data = json_decode($event->additional_data, true);
        $event_range_date = $additional_data['event']['range_date'];
        $event_responsible = $additional_data['event']['responsible'];
        $ubication = json_decode($event->ubication, true);
        $images = $event->resources()->where('type', 'image')->get();
        return view('events.show', [
            'event' => $event,
            'event_range_date' => $event_range_date,
            'event_responsible' => $event_responsible,
            'ubication' => $ubication,
            'images'=> $images,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $event)
    {
        $category = Category::where('slug', 'evento')->first();
        $subcategories = $category->subcategories()->get();
        $additional_data = json_decode($event->additional_data, true);
        $event_range_date = $additional_data['event']['range_date'];
        $event_responsible = $additional_data['event']['responsible'];
        $ubication = json_decode($event->ubication, true);
        $images = $event->resources()->where('type', 'image')->get();
        return view('events.edit',[
            'event'=>$event,
            'subcategories'=>$subcategories,
            'event_range_date' => $event_range_date,
            'event_responsible' => $event_responsible,
            'ubication' => $ubication,
            'images'=> $images,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, Post $event)
    {
        // $category_event = Category::where('slug', 'evento')->first();

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

        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->ubication = json_encode($ubication);//Se devuelve una representación de un JSON;
        // $event->user_id = $request->user()->id;
        // $event->category_id = $category_event->id;
        $event->subcategory_id = $validated['id'];
        $event->additional_data = json_encode($additional_data);
        $event->save();

        $newPhones = $validated['phone_numbers'];
        $oldPhones = $event->phones;

        $this->deleteOldPhones($oldPhones, $newPhones);
        $this->saveNewPhones($newPhones, $oldPhones, $event);

        //Se verifica si alguna imagen del envento registrado anteriormenete, haya sido eliminada
        $oldEventImages = $request['old_images'];
        $oldCollectionEventImages = $event->resources()->where('type', 'image')->get();

        if($oldEventImages){
            foreach($oldCollectionEventImages as $oldImageEvent){
                $oldImageUrl = $oldImageEvent->url;

                if($this->searchDeletedImages($oldImageUrl, $oldEventImages)){
                    //Eliminar a la imagen de la bdd y del local storage
                    $event->resources()->where('type', 'image')
                                        ->where('url', $oldImageUrl)->delete();
                    if(Storage::disk('public')->exists($oldImageUrl)){
                        Storage::disk('public')->delete($oldImageUrl);
                    }
                }
            }
        }else{
            //En caso no recibir el arreglo de las imagenes registradas con el reporte,
            //se verifica si el reporte contiene imágenes
            if(count($oldCollectionEventImages) > 0){
                //Si el reporte contiene imágenes, se procede a eliminar todas las imágenes
                foreach ($oldCollectionEventImages as $oldImage) {
                    $oldImageUrl = $oldImage->url;
                    if(Storage::disk('public')->exists($oldImageUrl)){
                        Storage::disk('public')->delete($oldImageUrl);
                    }
                }

                $event->resources()->where('type', 'image')->delete();
            }
        }

        if($request->file('new_images')){
            foreach($request->file('new_images') as $image){

                Resource::create([
                    'url'=> $image->store('event_images', 'public'),
                    'post_id' => $event->id,
                    'type'=>'image',
                ]);
            }
        }

        session()->flash('success', 'Servicio público actualizado con éxito');
        return response()->json(['success'=>'Datos recibidos correctamente']);
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
    /**
     * Check if any images were deleted
     *
     * @param  string  $search
     * @param  array  $array
     * @return boolean $imageIsDeleted
     */

    public function searchDeletedImages($search, $array){
        $imageIsDeleted = true;
        foreach($array as $image){
            if($image === $search){
                $imageIsDeleted = false;
            }
        }
        return $imageIsDeleted;
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
