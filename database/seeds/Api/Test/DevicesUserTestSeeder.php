<?php

use Illuminate\Database\Seeder;

class DevicesUserTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $devices = [];
        for ($i=0; $i < 12; $i++) {
            $device = [
                "created_at" =>  $faker->dateTime()->format('Y-m-d H:i:s'),
                "description" => $faker->sentence(6,true),
                "id" => $faker->numberBetween(100, 500),
                "phone_id" => $faker->creditCardNumber,
                "phone_model" => $faker->name,
                "updated_at"=> $faker->dateTime()->format('Y-m-d H:i:s'),
                "user_id" => 3
            ];
            array_push($devices, $device);
            // $devices[]= $faker->unique()->randomDigit;
        }
        // dd($devices);
        DB::table('devices')->insert($devices);
    }
}
