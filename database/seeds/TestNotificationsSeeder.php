<?php

use App\Category;
use App\Notifications\SocialProblem;
use App\Post;
use App\Subcategory;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Notification;
use App\HelpersClass\AdditionalData;

class TestNotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $problem_category = Category::where('slug', 'problema')->first();
        //Se obtiene la primer subcategoría de problema
        $problem_subcategory = $problem_category->subcategories->first();

        $neighbor_role = Role::where('slug', 'morador')->first();
        //OJO NO SE VERIFICA QUE LOS VECINOS SE ENCUENTREN ACTIVOS
        $neighbors = $neighbor_role->users()->take(3)->get();

        //Se buscan a todos los moderadores activos
        $moderator_role = Role::where('slug', 'moderador')->first();
        $moderators = $moderator_role->users;
        $moderators_active = $moderators->filter(function($moderator, $key) use($moderator_role){
            return $moderator->getRelationshipStateRolesUsers($moderator_role->slug);
        });


        $faker = Faker\Factory::create();
        // $additionalData = new AdditionalData();

        $neighbors->each(function ($neighbor, $key) use ($problem_category, $problem_subcategory, $faker, $moderators_active) {
            $additionalData = new AdditionalData();
            $additional_data = $additionalData->getInfoSocialProblem();
            $ubication = [
                'latitude' => $faker->latitude($min = -90, $max = 90),
                'longitude' => $faker->longitude($min = -180, $max = 180),
                'address' => $faker->address,
                'description' => $faker->text($maxNbChars = 30),
            ];

            $problem = Post::create([
                'title' => 'Problema' . ' ' . $key,
                'description' => 'Descripción' . ' ' . $key,
                'category_id' => $problem_category->id,
                'subcategory_id' => $problem_subcategory->id,
                'state' => false,
                'user_id' => $neighbor->id,
                'ubication' => json_encode($ubication),
                // 'additional_data' => json_encode($additional_data),
                'additional_data' => $additional_data,
            ]);

            Notification::send($moderators_active, new SocialProblem($problem, $neighbor));
        });

    }
}
