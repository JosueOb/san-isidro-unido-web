<?php

use App\Category;
use App\Post;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Notification;
use App\HelpersClass\AdditionalData;
use App\HelpersClass\ResponsibleMembership;
use App\HelpersClass\Ubication;
use App\Membership;
use App\Notifications\MembershipRequest;
use App\Notifications\PublicationReport;
use App\Resource;
use App\Subcategory;
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
        /**
         * Variable global
         */

        //Se obtiene a todos los moderadores activos para notificar las publicaciones realizadas
        $moderator_role = Role::where('slug', 'moderador')->first();
        $moderators = $moderator_role->users()
            ->wherePivot('state', true)->get();

        /**
         * Notificaciones de problemas sociales
         */

        //Se obtiene aleatoreamente dos subcategorías de problemas sociales
        $problem_category = Category::where('slug', 'problema')->first();
        $problem_subcategories = $problem_category->subcategories()
            ->inRandomOrder()->take(2)->get();

        //Se obtiene aleatoreamente 3 moradores activos
        $neighbor_role = Role::where('slug', 'morador')->first();
        $neighbors = $neighbor_role->users()
            ->wherePivot('state', true)
            ->inRandomOrder()->take(3)->get();

        //Por cada usuario se va registrar los dos problemas sociales con las dos subcategoría obtenidas anteriormente
        $neighbors->each(function (User $neighbor) use ($problem_category, $problem_subcategories, $moderators) {
            $problem_subcategories->each(function (Subcategory $subcategory, $key) use ($problem_category, $neighbor, $moderators) {
                $additionalData = new AdditionalData();
                $faker = Faker\Factory::create();
                $ubication = new Ubication(
                    $faker->address,
                    $faker->latitude($min = -90, $max = 90),
                    $faker->longitude($min = -180, $max = 180),
                    $faker->text($maxNbChars = 30)
                );

                $socialProblem = Post::create([
                    //Se agerga como título y descripción de problema social, información de la subcategoría que se le asigna
                    'title' => 'Problema social de ' . $subcategory->name,
                    'description' => 'Descripción de ' . $subcategory->description,
                    'category_id' => $problem_category->id,
                    'subcategory_id' => $subcategory->id,
                    'state' => false,
                    'user_id' => $neighbor->id,
                    'ubication' => $ubication->getAll(),
                    'additional_data' => $additionalData->getInfoSocialProblem(),
                ]);
                //Se adjunta al problema social entre 1 a 3 imágenes
                for ($i = 0; $i < rand(1, 3); $i++) {
                    Resource::create([
                        'url' => 'https://source.unsplash.com/daily',
                        'post_id' => $socialProblem->id,
                        'type' => 'image'
                    ]);
                }
                //Por cada problema social registrado, se notifica a los moderadores activos
                Notification::send(
                    $moderators,
                    new PublicationReport(
                        'problem_reported', //tipo de la notificación
                        $subcategory->name, //título de la notificación
                        $neighbor->getFullName() . ' ha reportado un problema social', //descripcción de la notificación
                        $socialProblem, // post que almacena la notificación
                        $neighbor //morador que reportó el problema social
                    )
                );
            });
        });

        /**
         * Notificaciones de emergencias
         */

        //Se obtiene la categoría de emergencia
        $emergency_category = Category::where('slug', 'emergencia')->first();

        //Se obtiene aleatoreamente 3 moradores activos
        $neighbors = $neighbor_role->users()
            ->wherePivot('state', true)
            ->inRandomOrder()->take(3)->get();

        //Por cada usuario se va a reportar una emergencia
        $additionalData = new AdditionalData();
        $faker = Faker\Factory::create();
        $neighbors->each(function (User $neighbor) use ($faker, $additionalData, $emergency_category, $moderators) {
            $ubication = new Ubication(
                $faker->address,
                $faker->latitude($min = -90, $max = 90),
                $faker->longitude($min = -180, $max = 180),
                $faker->text($maxNbChars = 30)
            );

            $emergency = Post::create([
                //Se agerga como título y descripción de problema social, información de la subcategoría que se le asigna
                'title' => 'Título de la emergencia',
                'description' => 'Descripción de emergencia',
                'category_id' => $emergency_category->id,
                'state' => false,
                'user_id' => $neighbor->id,
                'ubication' => $ubication->getAll(),
                'additional_data' => $additionalData->getInfoEmergency(),
            ]);
            //Se adjunta a la emergencia entre 1 a 3 imágenes
            for ($i = 0; $i < rand(1, 3); $i++) {
                Resource::create([
                    'url' => 'https://source.unsplash.com/daily',
                    'post_id' => $emergency->id,
                    'type' => 'image'
                ]);
            }
            //Por cada problema social registrado, se notifica a los moderadores activos
            Notification::send(
                $moderators,
                new PublicationReport(
                    'emergency_reported', // tipo de la notificación
                    'Emergencia', // título de la notificación
                    $neighbor->getFullName() . ' ha reportado una emergencia', // descripción de la notificación
                    $emergency, // post que almacena la notificación
                    $neighbor //morador que reportó la emergencia
                )
            );
        });

        /**
         * Notificaciones de solicitud de afiliación
         **/

        //Se obtiene a tres usuarios invitados aleatoreamente
        $guest_role = Role::where('slug', 'invitado')->first();
        $guests = $guest_role->users()->inRandomOrder()->take(3)->get();
        $responsible_membership = new ResponsibleMembership();

        //Se reagistra una afiliación por los usuarios invitados obtenidos
        $guests->each(function (User $guest) use ($responsible_membership, $moderators) {
            $membership = Membership::create([
                'identity_card' => '1724449325',
                'basic_service_image' => 'https://source.unsplash.com/collection/190727/1600x900',
                'status_attendance' => 'pendiente',
                'responsible' => $responsible_membership->getAll(),
                'user_id' => $guest->id,
            ]);

            Notification::send(
                $moderators,
                new MembershipRequest(
                    'Solicitud de afiliación', //título de la notificación
                    $guest->getFullName() . ' ha solicitado afiliación', //descripción de la notificación
                    $membership, //membresía
                    $guest //usuario que realizó la solicitud
                )
            );
        });
    }
}
