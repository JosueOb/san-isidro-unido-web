<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Category;
use Illuminate\Support\Facades\Storage;
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

        // $slug = $this->returnSlug($validated['name']);
        // $category->slug = $slug;
        
        $category->name = $validated['name'];
        $category->description = $validated['description'];
        
        $icon = $request->file('icon');
        if($icon){
            //Se elimina la imagen del storage de laravel
            if(Storage::disk('public')->exists($category->icon)){
                Storage::disk('public')->delete($category->icon);
            }
            $category->icon = $icon->store('images_default', 'public');
        }

        $category->save();

        return redirect()->route('categories.index')->with('success','Categoría actualizada exitosamente');
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     return view('categories.create');
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(CategoryRequest $request)
    // {
    //     $validated = $request->validated();

    //     $slug = $this->returnSlug($validated['name']);
    //     $icon = $request->file('icon');
        
    //     $category = new Category();
    //     $category->name = $validated['name'];
    //     $category->slug = $slug;
    //     $category->description = $validated['description'];

    //     if($icon){
    //         $category->icon = $icon->store('category_icons', 'public');
    //     }

    //     $category->save();

    //     return redirect()->route('categories.index')->with('success','Categoría registrada exitosamente');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Category $category)
    // {
    //     $hasPost = $category->posts()->first();
    //     $hasSubcategories = $category->subcategories()->first();
    //     $hasPublicServices = $category->publicServices()->first();

        
    //     if(!$hasPost && !$hasSubcategories && !$hasPublicServices){
    //         // $category->delete();
    //         // return redirect()->route('categories.index')->with('success','Categoría eliminada exitosamente');
    //         echo 'SI SE PUEDE ELIMNAR';
    //     }else{
    //         // return redirect()->route('categories.index')->with('danger','La categoría '.strtolower($category->name).' no se puede eliminar debido a que esta siendo utilizada');
    //         echo 'no se lo puede eliminar';
    //     }
    //     dd($hasPost, $hasPublicServices, $hasSubcategories);
    // }

    // //Función que en base al nombre de la categoría se retorna un slug adecuado
    // public function returnSlug($string){
    //     //Se reemplazan las tíldes por su respectiva vocal
    //     $string = str_replace(
    //         array('Á','É','Í','Ó','Ú','á','é','í','ó','ú'),
    //         array('A','E','I','O','U','a','e','i','o','u'),
    //         $string
    //     );
    //     //Se convierte a la cadena a minúsculas
    //     $string = strtolower($string);
    //     //Se reemplazan los espacios por un guion
    //     $string = str_replace(' ', '-', $string);
    //     return $string;
    // }
}
