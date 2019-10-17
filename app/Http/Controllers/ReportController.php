<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ReportRequest;
use App\Image;
use App\Position;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

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

        session()->flash('success', 'Informe registrado con éxito');
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
    public function edit(Post $report)
    {
        $images = $report->images()->get();
        return view('reports.edit', [
            'report'=>$report,
            'images'=>$images,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReportRequest $request, Post $report)
    {
        $validated = $request->validated();

         //Se obtiene la fecha y hora del sistema
         $dateTime = now();
         $date = $dateTime->toDateString(); 
         $time = $dateTime->toTimeString();

         //Se actualiza al reporte
         $report->title = $validated['title'];
         $report->description = $validated['description'];
         $report->date = $date;
         $report->time = $time;
         //Se mantiene el id del usuario que publicó el informe
         $report->save();

        //Se verifica si alguna imagen del reporte se mantiene o fue eliminada
        $newImagesReport = $request['images_report'];
        $collectionImageReport = $report->images()->get();
        
        // foreach ($newImagesReport as $value) {
        //     echo $value."\n";
        // }
        
        if($newImagesReport){

            foreach($collectionImageReport as $oldImageReport){
                $oldImageUrl = $oldImageReport->url;
                // echo $oldImageUrl."\n";
                if($this->searchDeletedImages($oldImageUrl, $newImagesReport)){
                    // echo $oldImageUrl."\n";
                    //Eliminar a la imagen de la bdd y del local storage
                    // Image::where('url', $oldImageUrl)->first()->delete();
                    $report->images()->where('url', $oldImageUrl)->delete();
                    if(Storage::disk('public')->exists($oldImageUrl)){
                        Storage::disk('public')->delete($oldImageUrl);
                    }
                }
            }
        }else{
            //En caso no recibir el arreglo de las imagenes registradas con el reporte,
            //se verifica si el reporte contiene imágenes
            if(count($collectionImageReport) > 0){
                //Si el reporte contiene imágenes, se procede a eliminar todas las imágenes
                foreach ($collectionImageReport as $oldImageReport) {
                    $oldImageUrl = $oldImageReport->url;
                    if(Storage::disk('public')->exists($oldImageUrl)){
                        Storage::disk('public')->delete($oldImageUrl);
                    }
                }
                $report->images()->delete();
            }
        }

        // //Se guardan las nuevas imágenes  seleccionadas por el usuario
        if($request->file('images')){
            foreach($request->file('images') as $image){
                Image::create([
                    'url'=> $image->store('images_reports', 'public'),
                    'post_id' => $report->id,
                ]);
            }
        }

        session()->flash('success', 'Informe actualizado con éxito');

        return response()->json([
            'success'=>'Reporte actualizado con exito',
            // 'request'=>$request->all(),
        ]);
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
                // echo "son iguales \n";
                // echo $image."\n";
                // echo $search."\n";
                // break;
            }
        }
        return $imageIsDeleted;
    }
}