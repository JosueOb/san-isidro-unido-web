<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\SubcategoryRequest;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcategories = Subcategory::paginate(10);
        return view('subcategories.index',[
            'subcategories'=>$subcategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Se consultan a todas las categorías registradas
        // $categories = Category::all();
        $categories = Category::whereNotIn('slug', ['informe', 'emergencia'])->get();
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Subcategory $subcategory)
    {
         //Se consultan a todas las categorías registradas
         $categories = Category::whereNotIn('slug', ['informe', 'emergencia'])->get();
        return view('subcategories.edit',[
            'subcategory'=>$subcategory,
            'categories'=>$categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubcategoryRequest $request, Subcategory $subcategory)
    {
        $validated = $request->validated();

        $slug = $this->returnSlug($validated['name']);
        $icon = $request->file('icon');
        
        $subcategory->name = $validated['name'];
        $subcategory->slug = $slug;
        $subcategory->description = $validated['description'];
        $subcategory->category_id = $validated['category'];

        if($icon){

            $icon_default = env('SUBCATEGORY_ICON_DEFAULT');
            $subcategory_icon = $subcategory->icon;

            //Se verifica que el ícono por defecto de subcategorías sea diferecte al ícono registrados,
            //esto se realiza con la finalidad de no eliminar la imagen por defecto dentro del almacenamiento de laravel
            if($icon_default !== $subcategory_icon){
                if(Storage::disk('public')->exists($subcategory_icon)){
                    Storage::disk('public')->delete($subcategory_icon);
                }
            }
            $subcategory->icon = $icon->store('subcategory_icons', 'public');
        }

        $subcategory->save();

        return redirect()->route('subcategories.index')->with('success','Subcategoría actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcategory $subcategory)
    {
        $hasPots = $subcategory->posts()->first();
        $hasPublicServices = $subcategory->publicServices()->first();

        if(!$hasPots && !$hasPublicServices){
            $subcategory->delete();
            return redirect()->route('subcategories.index')->with('success','Subcategoría eliminada exitosamente');
        }else{
            return redirect()->route('subcategories.index')->with('danger','La subcategoría '.strtolower($subcategory->name).' no se puede eliminar debido a que esta siendo utilizada');
        }
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
