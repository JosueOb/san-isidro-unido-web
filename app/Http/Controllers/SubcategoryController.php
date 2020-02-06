<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\SubcategoryRequest;
use App\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('subcategories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Se consultan a todas las categorías registradas
        $categories = Category::all();
        return view('subcategories.create', [
            'categories'=>$categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubcategoryRequest $request)
    {
        $validated = $request->validated();
        // dd($validated);

        $slug = $this->returnSlug($validated['name']);
        $icon = $request->file('icon');
        
        $subcategory = new Subcategory();
        $subcategory->name = $validated['name'];
        $subcategory->slug = $slug;
        $subcategory->description = $validated['description'];
        $subcategory->category_id = $validated['category'];
        
        if($icon){
            $subcategory->icon = $icon->store('subcategory_icons', 'public');
        }else{
            $subcategory->icon = env('SUBCATEGORY_ICON_DEFAULT');
        }

        $subcategory->save();

        return redirect()->route('subcategories.index')->with('success','Subcategoría registrada exitosamente');
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
    /**
     * Función que en base al nombre de la categoría se retorna un slug adecuado
     *
     * @param  string  $string
     * @return $string
     */
    public function returnSlug($string){
        //Se reemplazan las tíldes por su respectiva vocal
        $string = str_replace(
            array('Á','É','Í','Ó','Ú','á','é','í','ó','ú'),
            array('A','E','I','O','U','a','e','i','o','u'),
            $string
        );
        //Se convierte a la cadena a minúsculas
        $string = strtolower($string);
        //Se reemplazan los espacios por un guion
        $string = str_replace(' ', '-', $string);
        return $string;
    }
}
