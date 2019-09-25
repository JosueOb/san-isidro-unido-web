<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ReportRequest;
use App\Image;
use App\Position;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::where('slug', 'informe')->first();
        $reports = $category->posts()->paginate();

        return view('reports.index',[
            'reports' =>$reports,
        ]);
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

        //Se obtiene la fecha y hora del sistema
        $dateTime = now();
        $date = $dateTime->toDateString(); 
        $time = $dateTime->toTimeString();
        //Se obtiene a la categoría de informe
        $category = Category::where('slug', 'informe')->first();
        //Se crea un nuevo objeto Post
        $report = new Post();
        $report->title = $request['title'];
        $report->description = $request['description'];
        $report->date = $date;
        $report->time = $time;
        $report->state = true;
        $report->user_id = $request->user()->id;
        $report->category_id = $category->id;
        $report->save();

        //Se guardan la imagenes del post, en caso de que se hayan selecionado
        // if($request['images']){
        if($request->file('images')){
            foreach($request->file('images') as $image){
                Image::create([
                    'url'=> $image->store('images_reports', 'public'),
                    'post_id' => $report->id,
                ]);
            }
        }

        session()->flash('success', 'Informe registrado con exito');
        return response()->json(['success'=>'Datos recibidos correctamente']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $report)
    {
        $images= $report->images()->get();
        return view('reports.show',[
            'report'=>$report,
            'images'=>$images,
        ]);
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
    public function destroy(Post $report)
    {
        $message = '';
        if($report->state){
            $report->state = false;
            $message='desactivado';
        }else{
            $report->state = true;
            $message='activado';
        }
        $report->save();
        
        return back()->with('success', "Informe $message con éxito");
    }
}
