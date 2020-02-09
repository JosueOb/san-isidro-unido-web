<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\PublicService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class ApiPublicServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $publicServicesCategories = Category::findByType(PublicService::class)->get();
        $numServices = 5;
        $directions = [
            ["latitude" => -0.139413, "longitude" => -78.472171],
            [ "latitude" => -0.219476, "longitude" => -78.520626],
            [ "latitude" => -0.159305, "longitude" => -78.481897],
            [ "latitude" => -0.219476, "longitude" => -78.520626]
        ];

        foreach ($publicServicesCategories as $category) {
            for($i = 0; $i < $numServices; $i++){
                $name = $faker->citySuffix;
                $indexRandom = rand(0, count($directions) - 1);
                DB::table('public_services')->insertGetId([
                    'name' => $name,
                    'description' => "$name con la mejor atención al mejor precio",
                    'ubication' => json_encode([
                        "latitude" => $directions[$indexRandom]['latitude'],
                        "longitude" => $directions[$indexRandom]['longitude'],
                        "address" => $faker->address
                    ]),
                    'category_id' => $category->id,
                    'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
                ]);
            }
        }
    }
}
