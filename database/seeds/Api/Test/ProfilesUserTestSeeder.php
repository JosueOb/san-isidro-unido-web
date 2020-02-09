<?php

use Illuminate\Database\Seeder;

class ProfileUserTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $provider_options = ['facebook', 'google'];
        $social_profiles = [];
        for ($i=0; $i < 12; $i++) {
            $social_profile = [
                "created_at" =>  $faker->dateTime()->format('Y-m-d H:i:s'),
                "id" => $faker->numberBetween(100, 500),
                "provider" =>  $provider_options[array_rand($provider_options)],
                "social_id" => $faker->creditCardNumber,
                "updated_at"=> $faker->dateTime()->format('Y-m-d H:i:s'),
                "user_id" => 3
            ];
            array_push($social_profiles, $social_profile);
        }
        // dd($social_profiles);
        DB::table('social_profiles')->insert($social_profiles);
    }
}
