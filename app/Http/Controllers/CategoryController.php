<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::paginate(10);
        return view('categories.index',[
            'categories'=>$categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        $slug = $this->returnSlug($validated['name']);
        
        $category = new Category();
        $category->name = $validated['name'];
        $category->slug = $slug;
        $category->description = $validated['description'];
        $category->save();

        return redirect()->route('categories.index')->with('success','Categoría registrada exitosamente');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.edit',[
            'category'=>$category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        $slug = strtolower($validated['name']);
        $slug =str_replace(' ', '-', $slug);

        $category->name = $validated['name'];
        $category->slug = $slug;
        // $category->group = $validated['group'];
        $category->description = $validated['description'];
        $category->save();

        return redirect()->route('categories.index')->with('success','Categoría actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //Se debe verificar si esta siendo utilizada en la tabla post, en la subcategories y public service
        //solo se verifica en post y public service
        // $categoryPosts = $category->posts()->get();
        // No se debe eliminar la categoría informes
        $categoryPublicService = $category->publicServices()->get();

        if(count($categoryPublicService) > 0){
            return redirect()->route('categories.index')->with('danger','La categoría '.strtolower($category->name).' no se puede eliminar debido a que esta siendo utilizada');
        }else{
            $category->delete();
            return redirect()->route('categories.index')->with('success','Categoría eliminada exitosamente');
            
        }
    }

    //Función que en base al nombre de la categoría se retorna un slug adecuado
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
