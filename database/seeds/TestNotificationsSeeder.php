<?php

use App\Category;
use App\Post;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Notification;
use App\HelpersClass\AdditionalData;
use App\HelpersClass\ResponsibleMembership;
use App\Membership;
use App\Notifications\EmergencyReported;
use App\Notifications\MembershipRequest;
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
        $moderators_active = $moderators->filter(function ($moderator, $key) use ($moderator_role) {
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
                    'url' => 'https://source.unsplash.com/collection/' . $i,
                    'post_id' => $problem->id,
                    'type' => 'image'
                ]);
            }

            // Notification::send($moderators_active, new SocialProblemReported($problem, $neighbor));

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
                    'url' => 'https://source.unsplash.com/collection/' . $i,
                    'post_id' => $emergency->id,
                    'type' => 'image'
                ]);
            }
            // Notification::send($moderators_active, new EmergencyReported($emergency, $neighbor));
        });


        /**
         * Notificaciones de solicitud de afiliación
         **/

        $guest_role = Role::where('slug', 'invitado')->first();
        $guests = $guest_role->users()->take(3)->get();
        $responsible_membership = new ResponsibleMembership();

        $guests->each(function (User $guest) use ($responsible_membership, $moderators_active) {
            $membership = Membership::create([
                'identity_card' => '1724449325',
                'basic_service_image' => 'https://source.unsplash.com/collection/190727/1600x900',
                'status_attendance' => 'pendiente',
                'responsible' => $responsible_membership->getAll(),
                'user_id' => $guest->id,
            ]);
            
            Notification::send(
                $moderators_active,
                new MembershipRequest(
                    'Solicitud de afiliación',//título de la notificación
                    $guest->getFullName().' ha solicitado afiliación',//descripción de la notificación
                    $membership,//membresía
                    $guest//usuario que realizó la solicitud
                )
            );
        });
    }
}
