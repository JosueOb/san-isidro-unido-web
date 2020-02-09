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
            'name' => 'Emergencias',
            'slug' => 'emergencias',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/alarm.svg?sanitize=true',
            'categorizable_type' => Post::class,
            'description' => 'Categoria para Publicaciones de Emergencia',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        //TODO:Segunda Categoria
        $idTwo = DB::table('categories')->insertGetId([
            'name' => 'Problemas Sociales',
            'slug' => 'problemas_sociales',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/protest.svg?sanitize=true',
            'categorizable_type' => Post::class,
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
            'name' => 'Eventos',
            'slug' => 'eventos',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/calendar.svg?sanitize=true',
            'categorizable_type' => Post::class,
            'description' => 'Categoria para Eventos',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        //TODO:Cuarta Categoria
        DB::table('categories')->insert([
            'name' => 'Reportes',
            'slug' => 'reportes',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/analytics_report.svg?sanitize=true',
            'categorizable_type' => Post::class,
            'description' => 'Categoria para Reportes',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);

         /**CATEGORIAS PUBLIC SERVICES */
        DB::table('categories')->insert([
            'name' => 'Ferreterias',
            'slug' => 'ferreterias',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/ironmongery.svg?sanitize=true',
            'categorizable_type' => PublicService::class,
            'description' => 'Categoria para ferreterias',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        DB::table('categories')->insert([
            'name' => 'Tiendas',
            'slug' => 'tiendas',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/store.svg?sanitize=true',
            'categorizable_type' => PublicService::class,
            'description' => 'Categoria para tiendas',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        DB::table('categories')->insert([
            'name' => 'Centros Médicos',
            'slug' => 'centros_medicos',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/hospital.svg?sanitize=true',
            'categorizable_type' => PublicService::class,
            'description' => 'Categoria para centros médicos',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        DB::table('categories')->insert([
            'name' => 'Farmacias',
            'slug' => 'farmacias',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/medicine.svg?sanitize=true',
            'categorizable_type' => PublicService::class,
            'description' => 'Categoria para farmacias',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        DB::table('categories')->insert([
            'name' => 'Restaurantes',
            'slug' => 'restaurantes',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/baker.svg?sanitize=true',
            'categorizable_type' => PublicService::class,
            'description' => 'Categoria para todo tipo de establecimientos para venta de comida',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        DB::table('categories')->insert([
            'name' => 'Hospedaje',
            'slug' => 'hospedaje',
            'image' => 'https://raw.githubusercontent.com/StalinMazaEpn/StalinResources/master/svg/hotel.svg?sanitize=true',
            'categorizable_type' => PublicService::class,
            'description' => 'Categoria para hoteles, hostales',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
    }
}
