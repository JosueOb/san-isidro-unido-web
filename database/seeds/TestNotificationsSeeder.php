<?php

use App\Category;
use App\Post;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Notification;
use App\HelpersClass\AdditionalData;
use App\Notifications\EmergencyReported;
use App\Notifications\SocialProblemReported;
use App\Resource;
use App\User;

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

        $emergency_category = Category::where('slug', 'emergencia')->first();

        $neighbors->each(function ($neighbor, $key) use ($problem_category, $problem_subcategory, $emergency_category, $faker, $moderators_active) {
            $additionalData = new AdditionalData();
            $additional_data = $additionalData->getInfoSocialProblem();
            $ubication = [
                'latitude' => $faker->latitude($min = -90, $max = 90),
                'longitude' => $faker->longitude($min = -180, $max = 180),
                'address' => $faker->address,
                'description' => $faker->text($maxNbChars = 30),
            ];
            //Registro de problemas sociales reportados
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

            for ($i = 0; $i < rand(1, 3); $i++) {
                Resource::create([
                    'url' => 'https://source.unsplash.com/collection/'.$i,
                    'post_id' => $problem->id,
                    'type' => 'image'
                ]);
            }

            Notification::send($moderators_active, new SocialProblemReported($problem, $neighbor));

            $additionalDataEmergency = new AdditionalData();
            $additional_data_emergency = $additionalDataEmergency->getEmergencyData();
            //Registro de emergencias reportadas
            $emergency = Post::create([
                'title' => 'Emergencia' . ' ' . $key,
                'description' => 'Descripción' . ' ' . $key,
                'category_id' => $emergency_category->id,
                'state' => false,
                'user_id' => $neighbor->id,
                'ubication' => json_encode($ubication),
                'additional_data' => $additional_data_emergency,
            ]);

            for ($i = 0; $i < rand(1, 3); $i++) {
                Resource::create([
                    'url' => 'https://source.unsplash.com/collection/'.$i,
                    'post_id' => $emergency->id,
                    'type' => 'image'
                ]);
            }
            Notification::send($moderators_active, new EmergencyReported($emergency, $neighbor));
        });

    }
}
