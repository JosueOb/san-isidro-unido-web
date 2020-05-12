<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\PublicService;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\Category;

//php artisan migrate --path=/database/migrations/Api

class ApiSubCategorySeeder extends Seeder
{

     /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        
        $category = Category::slug('servicio-publico')->first();
        if($category){

            /**CATEGORIAS PUBLIC SERVICES */
                    DB::table('subcategories')->insert([
                        'name' => 'Ferreterias',
                        'slug' => 'ferreterias',
                        'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/ironmongery.svg?sanitize=true',
                        "category_id" => $category->id, 
                        'description' => 'Categoria para ferreterias',
                        'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                    ]);
                    DB::table('subcategories')->insert([
                        'name' => 'Tiendas',
                        'slug' => 'tiendas',
                        'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/store.svg?sanitize=true',
                        "category_id" => $category->id,
                        'description' => 'Categoria para tiendas',
                        'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                    ]);
                    DB::table('subcategories')->insert([
                        'name' => 'Centros Médicos',
                        'slug' => 'centros_medicos',
                        'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/hospital.svg?sanitize=true',
                        "category_id" => $category->id,
                        'description' => 'Categoria para centros médicos',
                        'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                    ]);
                    DB::table('subcategories')->insert([
                        'name' => 'Farmacias',
                        'slug' => 'farmacias',
                        'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/medicine.svg?sanitize=true',
                        "category_id" => $category->id,
                        'description' => 'Categoria para farmacias',
                        'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                    ]);
                    DB::table('subcategories')->insert([
                        'name' => 'Restaurantes',
                        'slug' => 'restaurantes',
                        'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/baker.svg?sanitize=true',
                        "category_id" => $category->id,
                        'description' => 'Categoria para todo tipo de establecimientos para venta de comida',
                        'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                    ]);
                    DB::table('subcategories')->insert([
                        'name' => 'Hospedaje',
                        'slug' => 'hospedaje',
                        'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/hotel.svg?sanitize=true',
                        "category_id" => $category->id,
                        'description' => 'Categoria para hoteles, hostales',
                        'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                    ]);
        }
    }
}


