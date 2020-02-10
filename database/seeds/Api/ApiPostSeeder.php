<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Subcategory;
use App\Category;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class ApiPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numPosts = 12;
        $faker = \Faker\Factory::create();
        $categoriaEventos = Category::where('slug', 'evento')->first();
        $categoriaProblemasSociales = Category::where('slug', 'problema_social')->first();
        $categoriaEmergencias = Category::where('slug', 'emergencia')->first();
        $categoriaInformes = Category::where('slug', 'informe')->first();

        //Crear Emergencias
        for($em = 1; $em <= $numPosts; $em++){
            $user = User::orderBy(DB::raw('RAND()'))->take(1)->first();
            $initialDate =  CarbonImmutable::now();
            $idPostEmergencia = DB::table('posts')->insertGetId([
                'title' => $faker->realText(50,2),
                'description' =>  $faker->realText(200,2),
                'date' => $initialDate->toDateString(),
                'time' => $initialDate->toTimeString(),
                "ubication" => json_encode([
                    "latitude" => $faker->latitude,
                    "longitude" => $faker->longitude,
                    "address" => $faker->address
                ]),
                "user_id" => $user->id,
                'state'=>true,
                "is_attended" => rand(0, 1),
                "category_id" =>  $categoriaEmergencias->id,
                "subcategory_id" => null,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
            for($img = 1; $img <= 3; $img++){
                DB::table('resources')->insert([
                    'url' => $faker->imageUrl(800, 480),
                    'post_id' => $idPostEmergencia,
                    'type' => "image",
                    'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                ]);
            }

        }
        //Problemas Sociales
        for($sp = 1; $sp <= $numPosts; $sp++){
            $user = User::orderBy(DB::raw('RAND()'))->take(1)->first();
            $initialDate =  CarbonImmutable::now();
            $subcategory = Subcategory::where('category_id', $categoriaProblemasSociales->id)->orderBy(DB::raw('RAND()'))->take(1)->first();
            $idPostProblemaSocial = DB::table('posts')->insertGetId([
                'title' => $faker->realText(50,2),
                'description' =>  $faker->realText(200,2),
                'date' => $initialDate->toDateString(),
                'time' => $initialDate->toTimeString(),
                "ubication" => json_encode([
                    "latitude" => $faker->latitude,
                    "longitude" => $faker->longitude,
                    "address" => $faker->address
                ]),
                "is_attended" => rand(0, 1),
                "user_id" => $user->id,
                'state'=>true,
                "category_id" => $categoriaProblemasSociales->id,
                "subcategory_id" => $subcategory->id,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
            for($img_sp = 1; $img_sp <= 3; $img_sp++){
                DB::table('resources')->insert([
                    'url' => $faker->imageUrl(800, 480),
                    'post_id' => $idPostProblemaSocial,
                    'type' => "image",
                    'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                ]);
            }
            DB::table('details')->insert([
                'post_id' => $idPostProblemaSocial,
                'user_id' => $user->id,
                'type' => 'like',
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
        }
        //Crear Eventos
        for($ev = 1; $ev <= $numPosts; $ev++){
            $user = User::orderBy(DB::raw('RAND()'))->take(1)->first();
            $intervalDays = rand(2, 15);
            $initialDate =  CarbonImmutable::now();
            $finalDate = $initialDate->add($intervalDays, 'day');
            $idPostEvento = DB::table('posts')->insertGetId([
                'title' => $faker->realText(50,2),
                'description' =>  $faker->realText(200,2),
                'date' => $initialDate->toDateString(),
                'time' => $initialDate->toTimeString(),
                'state'=>true,
                "range_date" => json_encode([
                    'start_date' => $initialDate->toDateString(),
                    'end_date' =>  $finalDate->toDateString(),
                    'start_time' => $initialDate->toTimeString(),
                    'end_time' =>  $finalDate->toTimeString(),
                ]),
                "ubication" => json_encode([
                    "latitude" => $faker->latitude,
                    "longitude" => $faker->longitude,
                    "address" => $faker->address
                ]),
                //responsible" => $faker->name,
                "additional_data" => json_encode([
                    "log_event" => [
                        "responsable" => $faker->firstname
                    ]
                ]),
                "user_id" => $user->id,
                "category_id" => $categoriaEventos->id,
                "subcategory_id" => null,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
            for($iev = 1; $iev <= 3; $iev++){
                DB::table('resources')->insert([
                    'url' => $faker->imageUrl(800, 480),
                    'post_id' => $idPostEvento,
                    "type" => "image",
                    'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                ]);
            }
            DB::table('details')->insert([
                'post_id' => $idPostEvento,
                'user_id' => $user->id,
                'type' => 'assistance',
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
        }
        //Crear Reportes o Informes
        for($rp = 1; $rp <= $numPosts; $rp++){
            $user = User::orderBy(DB::raw('RAND()'))->take(1)->first();
            $initialDate =  CarbonImmutable::now();
            $idPostReporte = DB::table('posts')->insertGetId([
                'title' => $faker->realText(50,2),
                'description' =>  $faker->realText(200,2),
                'date' => $initialDate->toDateString(),
                'time' => $initialDate->toTimeString(),
                "ubication" => json_encode([
                    "latitude" => $faker->latitude,
                    "longitude" => $faker->longitude,
                    "address" => $faker->address
                ]),
                "user_id" => $user->id,
                'state'=>true,
                "category_id" => $categoriaInformes->id,
                "subcategory_id" => null,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
            for($irp = 1; $irp <= 3; $irp++){
                DB::table('resources')->insert([
                    'url' => $faker->imageUrl(800, 480),
                    'post_id' => $idPostReporte,
                    "type" => 'image',
                    'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                ]);
            }
        }
    }
}
