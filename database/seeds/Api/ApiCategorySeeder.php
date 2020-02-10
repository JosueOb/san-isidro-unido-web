<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\PublicService;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

//php artisan migrate --path=/database/migrations/Api

class ApiCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new Faker\Provider\pt_BR\PhoneNumber($faker) );
        /**CATEGORIAS POSTS */
        //TODO: Primer Categoria
        DB::table('categories')->insert([
            'name' => 'Emergencia',
            'slug' => 'emergencia',
            "categorizable_type" => Post::class,
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/alarm.svg?sanitize=true',
            'description' => 'Categoria para Publicaciones de Emergencia',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        //TODO:Segunda Categoria
        $idTwo = DB::table('categories')->insertGetId([
            'name' => 'Problema Social',
            'slug' => 'problema_social',
            "categorizable_type" => Post::class,
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/protest.svg?sanitize=true',
            'description' => 'Categoria para Problemas Sociales',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        DB::table('subcategories')->insert([
            'name' => 'Transporte y Transito',
            'slug' => 'transporte_transito',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/car.svg?sanitize=true',
            'description' => 'SubCat Transporte Transito',
            'category_id' => $idTwo,
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        
        DB::table('subcategories')->insert([
            'name' => 'Espacios Verdes',
            'slug' => 'espacios_verdes',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/sprout.svg?sanitize=true',
            'description' => 'SubCat Espacios Verdes',
            'category_id' => $idTwo,
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        
        DB::table('subcategories')->insert([
            'name' => 'Seguridad',
            'slug' => 'seguridad',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/shield.svg?sanitize=true',
            'description' => 'SubCat Seguridad',
            'category_id' => $idTwo,
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        
        DB::table('subcategories')->insert([
            'name' => 'Proteccion Animal',
            'slug' => 'proteccion_animal',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/veterinary.svg?sanitize=true',
            'description' => 'SubCat Transporte Transito',
            'category_id' => $idTwo,
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        //TODO:Tercera Categoria
        DB::table('categories')->insert([
            'name' => 'Evento',
            'slug' => 'evento',
            "categorizable_type" => Post::class,
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/calendar.svg?sanitize=true',
            'description' => 'Categoria para Eventos',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        //TODO:Quinta Categoria
        DB::table('categories')->insert([
            'name' => 'Servicio Publico',
            'slug' => 'servicio-publico',
            "categorizable_type" => PublicService::class,
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/calendar.svg?sanitize=true',
            'description' => 'Categoria para Servicios Publicos',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        
    }
}
