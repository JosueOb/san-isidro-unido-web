<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name'=>'Informe',
            'slug'=>'informe',
            'description'=>'Informe de las actividades realizadas por la directiva del barrio',
            'icon'=>'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/category_icons/report_icon_default.jpg'
        ]);
        Category::create([
            'name'=>'Servicio público',
            'slug'=>'servicio-publico',
            'description'=>'Categoría de servicios públicos',
            'icon'=>'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/category_icons/public_service_icon_default.jpg'
        ]);
        Category::create([
            'name'=>'Evento',
            'slug'=>'evento',
            'description'=>'Categoría de eventos',
            'icon'=>'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/category_icons/event_icon_default.jpg'
        ]);
        Category::create([
            'name'=>'Emergencia',
            'slug'=>'emergencia',
            'description'=>'Categoría de emergencias',
            'icon'=>'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/category_icons/emergency_icon_default.jpg'
        ]);
        Category::create([
            'name'=>'Problema',
            'slug'=>'problema',
            'description'=>'Categoría de problemas',
            'icon'=>'https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/category_icons/problem_icon_default.png'
        ]);
        
    }
}
