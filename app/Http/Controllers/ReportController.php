<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ReportRequest;
use App\Image;
use App\Post;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportRequest $request)
    {
        $validated = $request->validated();

       session()->flash('success', 'Informe registrado con exito');
        return response()->json(['success'=>'Datos recibidos correctamente']);
        // return $request->all();
        
        //Se obtiene la fecha y hora del sistema
        // $dateTime = now();
        // $date = $dateTime->toDateString(); 
        // $time = $dateTime->toTimeString();
        // //Se obtiene a la categoría de informe
        // $category = Category::where('slug', 'informe')->first();
        // //Se crea un nuevo objeto Post
        // $report = new Post();
        // $report->title = $request['title'];
        // $report->description = $request['description'];
        // $report->date = $date;
        // $report->time = $time;
        // $report->user_id = $request->user()->id;
        // $report->category_id = $category->id;
        // $report->save();
        // //Se guardan la imagenes del post, en caso de que se hayan selecionado
        // if($request['images']){
        //     foreach($request['images'] as $image){
        //         Image::create([
        //             'url'=> $image->store('images_reports', 'public'),
        //             'post_id' => $report->id,
        //         ]);
        //     }
        // }
        // dd('Se a registrado exitosamente el informe N. '.$report->id);
        //return redirect()->route('profile')->with('success', 'Registro con exito');
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
