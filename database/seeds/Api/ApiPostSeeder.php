<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Subcategory;
use App\Category;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
use App\HelpersClass\AdditionalData as AdditionalDataCls;
use App\HelpersClass\Ubication as UbicationCls;
use Illuminate\Database\Eloquent\Builder;

class ApiPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numPosts = 25;
        $faker = \Faker\Factory::create();
        $categoriaEventos = Category::where('slug', 'evento')->first();
        $categoriaProblemasSociales = Category::where('slug', 'problema')->first();
        $categoriaEmergencias = Category::where('slug', 'emergencia')->first();
        $categoriaInformes = Category::where('slug', 'informe')->first();
        
       
        //Crear Emergencias
        for($em = 1; $em <= $numPosts; $em++){
            $aditionalData = new AdditionalDataCls();
            $user = User::orderBy(DB::raw('RAND()'))->take(1)->first();
            $aditionalData->setInfoEmergency([
                "approved" => [
                    'who'=>null,
                    'date'=>null,
                ],
                "rechazed"=>[
                    'who'=>null,
                    'reason'=>null,
                    'date'=>null,
                ],
                "attended"=>[
                    'who'=>null,
                    'date'=>null
                ],
                "status_attendance" => $faker->randomElement(['pendiente'],1)
            ]);
            $initialDate =  CarbonImmutable::now();
            $ubicationData = new UbicationCls($faker->address, $faker->latitude,$faker->longitude, 'fake description');
            $idPostEmergencia = DB::table('posts')->insertGetId([
                'title' => $faker->realText(50,2),
                'description' =>  $faker->realText(200,2),
                "ubication" => json_encode($ubicationData->getAll()),
                'additional_data' => json_encode($aditionalData->getInfoEmergency()),
                "user_id" => $user->id,
                'state'=>true,
                "category_id" =>  $categoriaEmergencias->id,
                "subcategory_id" => null,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
            for($img = 1; $img <= 3; $img++){
                DB::table('resources')->insert([
                    'url' => "https://source.unsplash.com/random",
                    'post_id' => $idPostEmergencia,
                    'type' => "image",
                    'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                ]);
            }

        }
        // //Problemas Sociales
        // for($sp = 1; $sp <= $numPosts; $sp++){
        //     $aditionalData = new AdditionalDataCls();
        //     $aditionalData->setInfoSocialProblem([
        //         "approved_by" => null, 
        //         "status_attendance" => $faker->randomElement(['pendiente'],1)
        //     ]);
        //     $user = User::whereHas('roles',function(Builder $query){
        //         $query->where('slug','directivo');
        //     })->orderBy(DB::raw('RAND()'))->first();
        //     $initialDate =  CarbonImmutable::now();
        //     $subcategory = Subcategory::where('category_id', $categoriaProblemasSociales->id)->orderBy(DB::raw('RAND()'))->first();
        //     $ubicationData = new UbicationCls($faker->address, $faker->latitude,$faker->longitude, 'fake description');
        //     $idPostProblemaSocial = DB::table('posts')->insertGetId([
        //         'title' => $faker->realText(50,2),
        //         'description' =>  $faker->realText(200,2),
        //         // 'date' => $initialDate->toDateString(),
        //         // 'time' => $initialDate->toTimeString(),
        //         "ubication" => json_encode($ubicationData->getAll()),
        //         'additional_data' => json_encode($aditionalData->getInfoSocialProblem()),
        //         // "is_attended" => rand(0, 1),
        //         "user_id" => $user->id,
        //         'state'=>true,
        //         "category_id" => $categoriaProblemasSociales->id,
        //         "subcategory_id" => $subcategory->id,
        //         'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        //     ]);
        //     for($img_sp = 1; $img_sp <= 3; $img_sp++){
        //         DB::table('resources')->insert([
        //             'url' => "https://source.unsplash.com/randomp",
        //             'post_id' => $idPostProblemaSocial,
        //             'type' => "image",
        //             'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        //         ]);
        //     }
        //     DB::table('reactions')->insert([
        //         'post_id' => $idPostProblemaSocial,
        //         'user_id' => $user->id,
        //         'type' => 'like',
        //         'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        //     ]);
        // }
        //Crear Eventos
        //Start point of our range date
        $start = strtotime("10 September 2018");
        //End point of our date range.
        $end = strtotime("22 July 2020");
        
        for($ev = 1; $ev <= $numPosts; $ev++){
            $timestamp = mt_rand($start, $end);
            $aditionalDataEvento = new AdditionalDataCls();
            $aditionalDataEvento->setInfoEvent([
                'responsible' => $faker->name,
                "range_date" => [
                    'start_date' => date("Y-m-d", $timestamp),
                    'end_date' => date("Y-m-d",strtotime(date("Y-m-d", $timestamp)."+ 1 week")),
                    'start_time' => date("H:i:s", $timestamp),
                    'end_time' => date("H:i:s", strtotime('+3 hours', strtotime(date("H:i:s", $timestamp)))) 
                ],
                "approved_by" => null, 
                "status_attendance" => $faker->randomElement(['pendiente'],1)
            ]);
            $user = User::orderBy(DB::raw('RAND()'))->take(1)->first();
            $subcategoryEvents = $categoriaEventos->subcategories()->orderBy(DB::raw('RAND()'))->first();
            $intervalDays = rand(2, 15);
            $initialDate =  CarbonImmutable::now();
            $ubicationData = new UbicationCls($faker->address, $faker->latitude,$faker->longitude, 'nightloigs description');
            // $finalDate = $initialDate->add($intervalDays, 'day');
           
            $idPostEvento = DB::table('posts')->insertGetId([
                'title' => $faker->realText(50,2),
                'description' =>  $faker->realText(200,2),
                'state'=>true,
                'additional_data' => json_encode($aditionalDataEvento->getInfoEvent()),
                "ubication" => json_encode($ubicationData->getAll()),
                "user_id" => $user->id,
                "category_id" => $categoriaEventos->id,
                "subcategory_id" => $subcategoryEvents->id,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
            
            for($iev = 1; $iev <= 3; $iev++){
                DB::table('resources')->insert([
                    'url' => "https://source.unsplash.com/random",
                    'post_id' => $idPostEvento,
                    "type" => "image",
                    'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                ]);
            }
            // DB::table('reactions')->insert([
            //     'post_id' => $idPostEvento,
            //     'user_id' => $user->id,
            //     'type' => 'assistance',
            //     'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            // ]);
        }
        //Crear Actividades Barriales
        for($rp = 1; $rp <= $numPosts; $rp++){
            $aditionalData = new AdditionalDataCls();
            // $aditionalData->setInfoActivity([
            //     "approved_by" => null, 
            //     "status_attendance" => $faker->randomElement(['pendiente'],1)
            // ]);
            $user = User::orderBy(DB::raw('RAND()'))->take(1)->first();
            $initialDate =  CarbonImmutable::now();
            $ubicationData = new UbicationCls($faker->address, $faker->latitude,$faker->longitude, 'nightly description');
            $idPostReporte = DB::table('posts')->insertGetId([
                'title' => $faker->realText(50,2),
                'description' =>  $faker->realText(200,2),
                "ubication" => json_encode($ubicationData->getAll()),
                // 'additional_data' => json_encode($aditionalData->getActivityData()),
                "user_id" => $user->id,
                'state'=>true,
                "category_id" => $categoriaInformes->id,
                "subcategory_id" => null,
                'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
            ]);
            for($irp = 1; $irp <= 3; $irp++){
                DB::table('resources')->insert([
                    'url' => "https://source.unsplash.com/random",
                    'post_id' => $idPostReporte,
                    "type" => 'image',
                    'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                ]);
            }
            for($irp = 1; $irp <= 2; $irp++){
                DB::table('resources')->insert([
                    'url' => 'https://siu-dev97-sd.s3-sa-east-1.amazonaws.com/CursoPugDesdeCero.pdf',
                    'post_id' => $idPostReporte,
                    "type" => 'document',
                    'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                ]);
            }
        }
    }
}
