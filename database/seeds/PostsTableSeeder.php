<?php

use App\Category;
use App\HelpersClass\AdditionalData;
use App\HelpersClass\Ubication;
use App\Phone;
use App\Post;
use App\Reaction;
use App\Resource;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Faker\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Variables gobales
         */
        $faker = Faker\Factory::create();
        //Start point of our range date
        $start = strtotime("10 September 2018");
        //End point of our date range.
        $end = strtotime("22 July 2020");
        /**
         * Categorías de Publicaciones
         */
        $category_report = Category::where('slug', 'informe')->first();
        $category_event = Category::where('slug', 'evento')->first();
        $category_social_problem = Category::where('slug', 'problema')->first();
        $category_emergency = Category::where('slug', 'emergencia')->first();

        /**
         * REPORTE DE ACTIVIDADES
         * Se registran 25 reportes de actividades
         */
        //Se obtienen a los miembros directivos
        $directive_role = Role::where('slug', 'directivo')->first();
        $members = $directive_role->users()
            ->wherePivot('state', true)->with('roles')->get();

        $reports = factory(Post::class, 25)
            ->create()
            ->each(function (Post $post) use ($members, $category_report, $faker) {
                $member = $members->random(); //Se selecciona un mimebro de la directiva aleatoreamente
                $post->user_id = $member->id;
                $post->category_id = $category_report->id;
                $post->save();
                for ($i = 0; $i < rand(0, 2); $i++) {
                    Resource::create([
                        'url' => 'https://source.unsplash.com/collection/' . $i,
                        'post_id' => $post->id,
                        'type' => 'image'
                    ]);
                }
            });

        /**
         * EVENTOS
         * Se registran 25 eventos
         */
        //Se obtienen las subcategorías de eventos
        $subcategories_event = $category_event->subcategories;
        $events = factory(App\Post::class, 25)
            ->create()
            ->each(function (Post $post) use ($members, $category_event, $subcategories_event, $faker, $start, $end) {
                $member = $members->random();
                $post->user_id = $member->id;
                $post->category_id = $category_event->id;
                $post->subcategory_id = $subcategories_event->random()->id;

                $timestamp = mt_rand($start, $end);
                $additional_data = new AdditionalData();
                $additional_data->setInfoEvent([
                    'responsible' => $faker->name(),
                    'range_date' => [
                        'start_date' => date("Y-m-d", $timestamp),
                        'end_date' => date("Y-m-d", strtotime(date("Y-m-d", $timestamp) . "+ 1 week")),
                        'start_time' => $faker->time($format = 'H:i', $max = 'now'),
                        'end_time' => $faker->time($format = 'H:i', $max = 'now'),
                    ]
                ]);
                $ubication = new Ubication(
                    $faker->address,
                    $faker->latitude($min = -90, $max = 90),
                    $faker->longitude($min = -180, $max = 180),
                    $faker->text($maxNbChars = 30)
                );

                $post->additional_data = $additional_data->getInfoEvent();
                $post->ubication = $ubication->getAll();
                $post->save();

                for ($i = 0; $i < rand(1, 3); $i++) {
                    $phone_number = new Phone(['phone_number' => '09' . mt_rand(80000000, 99999999)]);
                    $post->phones()->save($phone_number);
                }
                for ($i = 0; $i < rand(0, 2); $i++) {
                    Resource::create([
                        'url' => 'https://source.unsplash.com/collection/' . $i,
                        'post_id' => $post->id,
                        'type' => 'image'
                    ]);
                }
            });

        /**
         * PROBLEMAS SOCIALES
         */
        //Se obtienen a los moradores activos
        $neighbor_role = Role::where('slug', 'morador')->first();
        $neighbors = $neighbor_role->users()
            ->wherePivot('state', true)->get();
        //Se obtiene a todos los moderadores activos
        $moderator_role = Role::where('slug', 'moderador')->first();
        $moderators = $moderator_role->users()
            ->wherePivot('state', true)->with('roles')->get();
        //Se obtiene las subcategorías pertenecientes a la categoría de poblema social
        $problem_subcategories = $category_social_problem->subcategories()->get();

        // Se registran 15 problemas entre aprobados, atendidos o rechazados por parte del moderador o directivo
        $socialProblems = factory(App\Post::class, 15)
            ->create()
            ->each(function (Post $socialProblem) use ($neighbors, $category_social_problem, $problem_subcategories, $moderators, $members, $faker) {
                $neighbor = $neighbors->random(); //se obtiene un vecino de forma aleatorea
                $problem_subcategory = $problem_subcategories->random(); //se obtiene una subcategoría de forma aleatorea
                $moderator = $moderators->random(); //se obtiene un moderador de forma aleatorea
                $directive = $members->random(); //se obtiene un moderador de forma aleatorea

                //Datos de aprobación, rechazo y atención por parte de los moderadores o directivo
                $approveAttendRechazed = collect([
                    [
                        "approved" => [
                            'who' => $moderator, //moderador que aprobó el problema
                            'date' => now()->toDateTimeString(), //fecha de aprobación
                        ],
                        "status_attendance" => 'aprobado'
                    ],
                    [
                        "rechazed" => [
                            'who' => $moderator, //moderador que aprobó el problema
                            'reason' => $faker->text($maxNbChars = 100), //razón del rechazo del problema social
                            'date' => now()->toDateTimeString(), //fecha de aprobación
                        ],
                        "status_attendance" => 'rechazado'
                    ],
                    [
                        "rechazed" => [
                            'who' => $directive, //moderador que aprobó el problema
                            'reason' => $faker->text($maxNbChars = 100), //razón del rechazo del problema social
                            'date' => now()->toDateTimeString(), //fecha de aprobación
                        ],
                        "status_attendance" => 'rechazado'
                    ],
                    [
                        "attended" => [
                            'who' => $directive, //directivo que aprobó el problema
                            'date' => now()->toDateTimeString(), //fecha de aprobación
                        ],
                        "status_attendance" => 'atendido'
                    ]
                ]);

                $additional_data = new AdditionalData();
                $additional_data->setInfoSocialProblem($approveAttendRechazed->random());
                $getInfoSocialProblem = $additional_data->getInfoSocialProblem();

                $ubication = new Ubication(
                    $faker->address,
                    $faker->latitude($min = -90, $max = 90),
                    $faker->longitude($min = -180, $max = 180),
                    $faker->text($maxNbChars = 30)
                );

                if ($getInfoSocialProblem['status_attendance'] === 'rechazado') {
                    $socialProblem->state = false;
                }

                $socialProblem->user_id = $neighbor->id;
                $socialProblem->category_id = $category_social_problem->id;
                $socialProblem->subcategory_id = $problem_subcategory->id;
                $socialProblem->ubication = $ubication->getAll();
                $socialProblem->additional_data = $getInfoSocialProblem;
                $socialProblem->save();

                //Se adjunta al problema social entre 1 a 3 imágenes
                for ($i = 0; $i < rand(1, 3); $i++) {
                    Resource::create([
                        'url' => 'https://source.unsplash.com/daily',
                        'post_id' => $socialProblem->id,
                        'type' => 'image'
                    ]);
                }

                //Se agrega reacciones 
                //Se obtiene una cantidad de 5 de moradores aleatoreos para las reacciones del problema social
                //random() seleccionar un determinada cantidad de registros randomicos, //rand() genera un número aleatorea entre 1 a 5
                $neighbors_to_reaction = $neighbors->random(rand(1, 5));
                $neighbors_to_reaction->each(function (User $neighbor_to_reaction) use ($socialProblem) {
                    Reaction::create([
                        'post_id' => $socialProblem->id,
                        'user_id' => $neighbor_to_reaction->id,
                        'type' => 'support', //apoyar
                    ]);
                });
            });

        /**
         * EMERGENCIAS
         */
        //Se obtiene a los policías activos
        $police_role = Role::where('slug', 'policia')->first();
        $polices = $police_role->users()
            ->wherePivot('state', true)->with('roles')->get();

        // Se registran 15 emergencias entre atendidas o rechazados por parte del policía
        $emergencies = factory(App\Post::class, 15)
            ->create()
            ->each(function (Post $emergency) use ($neighbors, $category_emergency, $polices, $faker) {
                $neighbor = $neighbors->random(); //se obtiene un vecino de forma aleatore
                $police = $polices->random(); //se obtiene un policía de forma aleatorea

                //Datos de atención o rechazo por parte de la policía comunitaria
                $attendRechazed = collect([
                    [
                        "attended" => [
                            'who' => $police, //policía que atendió la emergencia
                            'date' => now()->toDateTimeString(), //fecha de aprobación
                        ],
                        "status_attendance" => 'atendido'
                    ],
                    [
                        "rechazed" => [
                            'who' => $police, //policía que rechazó la emergencia
                            'reason' => $faker->text($maxNbChars = 100), //razón del rechazo
                            'date' => now()->toDateTimeString(), //fecha de aprobación
                        ],
                        "status_attendance" => 'rechazado'
                    ],
                ]);

                $additional_data = new AdditionalData();
                $additional_data->setInfoEmergency($attendRechazed->random());
                $getInfoEmergency = $additional_data->getInfoEmergency();

                $ubication = new Ubication(
                    $faker->address,
                    $faker->latitude($min = -90, $max = 90),
                    $faker->longitude($min = -180, $max = 180),
                    $faker->text($maxNbChars = 30)
                );

                if ($getInfoEmergency['status_attendance'] === 'rechazado') {
                    $emergency->state = false;
                }

                $emergency->user_id = $neighbor->id;
                $emergency->category_id = $category_emergency->id;
                $emergency->ubication = $ubication->getAll();
                $emergency->additional_data = $getInfoEmergency;
                $emergency->save();

                //Se adjunta a la emergencia entre 1 a 3 imágenes
                for ($i = 0; $i < rand(1, 3); $i++) {
                    Resource::create([
                        'url' => 'https://source.unsplash.com/daily',
                        'post_id' => $emergency->id,
                        'type' => 'image'
                    ]);
                }
            });
    }
}
