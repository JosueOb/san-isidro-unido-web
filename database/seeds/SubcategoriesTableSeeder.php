<?php

use App\Category;
use App\Subcategory;
use Illuminate\Database\Seeder;

class SubcategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Subcategorías de Sevicios Públicos
        $category = Category::where('slug', 'servicio-publico')->first();
        Subcategory::create([
            'name'=>'Farmacia',
            'slug'=>'farmacia',
            'description'=>'',
            'category_id'=>$category->id,
            'icon'=> env('SUBCATEGORY_ICON_DEFAULT'),
        ]);
        Subcategory::create([
            'name'=>'Ferretería',
            'slug'=>'ferreteria',
            'description'=>'',
            'category_id'=>$category->id,
            'icon'=> env('SUBCATEGORY_ICON_DEFAULT'),
        ]);
        Subcategory::create([
            'name'=>'Mercado',
            'slug'=>'mercado',
            'description'=>'',
            'category_id'=>$category->id,
            'icon'=> env('SUBCATEGORY_ICON_DEFAULT'),
        ]);
    }
}
