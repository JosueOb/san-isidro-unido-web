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
            'icon'=>env('CATEGORY_REPORT_ICON_DEFAULT')
        ]);
        Category::create([
            'name'=>'Servicio público',
            'slug'=>'servicio-publico',
            'description'=>'Categoría de servicios públicos',
            'icon'=>env('CATEGORY_PUBLIC_SERVICE_ICON_DEFAULT')
        ]);
        Category::create([
            'name'=>'Evento',
            'slug'=>'evento',
            'description'=>'Categoría de eventos',
            'icon'=>env('CATEGORY_EVENT_ICON_DEFAULT')
        ]);
        Category::create([
            'name'=>'Emergencia',
            'slug'=>'emergencia',
            'description'=>'Categoría de emergencias',
            'icon'=>env('CATEGORY_EMERGENCY_ICON_DEFAULT')
        ]);
        Category::create([
            'name'=>'Problema',
            'slug'=>'problema',
            'description'=>'Categoría de problemas',
            'icon'=>env('CATEGORY_PROBLEM_ICON_DEFAULT')
        ]);
        
    }
}
