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
            'group'=>'report',
            'description'=>'Informe de las actividades realizadas por la directiva del barrio'
        ]);
    }
}
