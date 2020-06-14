<?php

use App\Category;
use App\Notifications\SocialProblem;
use App\Post;
use App\Subcategory;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Notification;
use App\HelpersClass\AdditionalData;
use App\Notifications\EmergencyReported;

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
        //Se crea una subcategoría de problema
        $problem_subcategory = Subcategory::create([
            'name' => 'Transporte y transito',
            'slug' => 'transporte',
            'description' => '',
            'category_id' => $problem_category->id,
            'icon' => 'https://siu-dev97-sd.s3-sa-east-1.amazonaws.com/recursos_publicos/subcategory_icons/subcategory_icon_default.jpg',
        ]);


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
                'lat' => $faker->latitude($min = -90, $max = 90),
                'lng' => $faker->longitude($min = -180, $max = 180),
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

            Notification::send($moderators_active, new SocialProblem($problem, $neighbor));

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

            Notification::send($moderators_active, new EmergencyReported($emergency, $neighbor));
        });

    }
}
