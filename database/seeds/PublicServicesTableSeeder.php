<?php

use App\Category;
use App\Phone;
use App\PublicService;
use Illuminate\Database\Seeder;

class PublicServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $category_public_service = Category::where('slug', 'servicio-publico')->first();
        $subcategories_public_service = $category_public_service->subcategories;
        factory(App\PublicService::class, 20)
            ->create()
            ->each(function (PublicService $publicService) use ($subcategories_public_service, $faker) {
                $subcategory_public_service = $subcategories_public_service->random();
                $publicService->subcategory_id = $subcategory_public_service->id;

                $ubication = [
                    'lat' => $faker->latitude($min = -90, $max = 90),
                    'lng' => $faker->longitude($min = -180, $max = 180),
                    'address' => $faker->address,
                    'description' => $faker->text($maxNbChars = 30),
                ];

                $public_opening = [
                    'open_time' =>  $faker->time($format = 'H:i', $max = 'now'),
                    'close_time' =>  $faker->time($format = 'H:i', $max = 'now'),
                ];

                $publicService->ubication = json_encode($ubication);
                $publicService->public_opening = json_encode($public_opening);

                $publicService->save();

                for ($i = 0; $i < rand(1, 3); $i++) {
                    $phone_number = new Phone(['phone_number' => '09' . rand(10000000, 99999999)]);
                    $publicService->phones()->save($phone_number);
                }
            });
    }
}
