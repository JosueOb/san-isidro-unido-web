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
}
