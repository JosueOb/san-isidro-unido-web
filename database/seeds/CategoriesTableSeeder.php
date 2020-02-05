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
            'icon'=>env('REPORT_ICON_DEFAULT')
        ]);
        Category::create([
            'name'=>'Servicio público',
            'slug'=>'public-service',
            'description'=>'Categoría de servicios públicos',
            'icon'=>env('PUBLIC_SERVICE_ICON_DEFAULT')
        ]);
        Category::create([
            'name'=>'Evento',
            'slug'=>'event',
            'description'=>'Categoría de eventos',
            'icon'=>env('EVENT_ICON_DEFAULT')
        ]);
        Category::create([
            'name'=>'Emergencia',
            'slug'=>'emergency',
            'description'=>'Categoría de emergencias',
            'icon'=>env('EMERGENCY_ICON_DEFAULT')
        ]);
        Category::create([
            'name'=>'Problema',
            'slug'=>'problem',
            'description'=>'Categoría de problemas',
            'icon'=>env('PROBLEM_ICON_DEFAULT')
        ]);
        
    }
}
