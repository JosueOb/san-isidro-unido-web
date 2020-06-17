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
        $subcategories = Subcategory::orderBy('name', 'asc')->paginate(10);
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
            $subcategory->icon = $icon->store('subcategory_icons', 's3');
        }else{
            $subcategory->icon = 'https://siu-dev97-sd.s3-sa-east-1.amazonaws.com/recursos_publicos/subcategory_icons/subcategory_icon_default.jpg';
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

            //Se elimina al ícono antiguo
            if(Storage::disk('s3')->exists($subcategory->icon)){
                Storage::disk('s3')->delete($subcategory->icon);
            }
            $subcategory->icon = $icon->store('subcategory_icons', 's3');
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
        //Se verifica si la subcatoría tiene un post (problema o evento) asignado
        $hasPots = $subcategory->posts()->first();
        //Se verifica que la subcategoría tiene una servicio público asignado
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
