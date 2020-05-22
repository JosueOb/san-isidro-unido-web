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
        $publicService = Category::where('slug', 'servicio-publico')->first();
        Subcategory::create([
            'name'=>'Farmacia',
            'slug'=>'farmacia',
            'description'=>'',
            'category_id'=>$publicService->id,
            'icon'=> 'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/subcategory_icons/subcategory_icon_default.jpg',
        ]);
        Subcategory::create([
            'name'=>'Ferretería',
            'slug'=>'ferreteria',
            'description'=>'',
            'category_id'=>$publicService->id,
            'icon'=> 'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/subcategory_icons/subcategory_icon_default.jpg',
        ]);
        Subcategory::create([
            'name'=>'Mercado',
            'slug'=>'mercado',
            'description'=>'',
            'category_id'=>$publicService->id,
            'icon'=> 'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/subcategory_icons/subcategory_icon_default.jpg',
        ]);

        //Subcategorías de Eventos
        $event = Category::where('slug', 'evento')->first();
        Subcategory::create([
            'name'=>'Social',
            'slug'=>'social',
            'description'=>'',
            'category_id'=>$event->id,
            'icon'=> 'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/subcategory_icons/subcategory_icon_default.jpg',
        ]);
        Subcategory::create([
            'name'=>'Comunitario',
            'slug'=>'comunitario',
            'description'=>'',
            'category_id'=>$event->id,
            'icon'=> 'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/subcategory_icons/subcategory_icon_default.jpg',
        ]);
        Subcategory::create([
            'name'=>'Cultural',
            'slug'=>'cultural',
            'description'=>'',
            'category_id'=>$event->id,
            'icon'=> 'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/subcategory_icons/subcategory_icon_default.jpg',
        ]);
        Subcategory::create([
            'name'=>'Deportivo',
            'slug'=>'deportivo',
            'description'=>'',
            'category_id'=>$event->id,
            'icon'=> 'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/subcategory_icons/subcategory_icon_default.jpg',
        ]);
        Subcategory::create([
            'name'=>'Educativo',
            'slug'=>'educativo',
            'description'=>'',
            'category_id'=>$event->id,
            'icon'=> 'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/subcategory_icons/subcategory_icon_default.jpg',
        ]);
        
    }
}
