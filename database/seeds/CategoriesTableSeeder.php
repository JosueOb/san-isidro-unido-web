<?php

use App\Category;
use App\Post;
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
            "categorizable_type" => Post::class,
            'description'=>'Informe de las actividades realizadas por la directiva del barrio'
        ]);
    }
}
