<?php

use App\Category;
use App\Notifications\SocialProblem;
use App\Post;
use App\Subcategory;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Notification;

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
        $neighbors = $neighbor_role->users()->take(2)->get();

        //Se buscan a todos los moderadores activos
        $moderator_role = Role::where('slug', 'moderador')->first();
        $moderators = $moderator_role->users;
        $moderators_active = $moderators->filter(function($moderator, $key) use($moderator_role){
            return $moderator->getRelationshipStateRolesUsers($moderator_role->slug);
        });


        $faker = Faker\Factory::create();

        $neighbors->each(function ($neighbor, $key) use ($problem_category, $problem_subcategory, $faker, $moderators_active) {
            $additional_data = [
                'problem' => [
                    'approved_by' => null,
                    'status_attendance' => 'pendiente'
                ]
            ];
            $ubication = [
                'lat' => $faker->latitude($min = -90, $max = 90),
                'lng' => $faker->longitude($min = -180, $max = 180),
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
                'additional_data' => json_encode($additional_data),
            ]);

            Notification::send($moderators_active, new SocialProblem($problem, $neighbor));
        });

    }
}
