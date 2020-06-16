<?php

use App\HelpersClass\Membership;
use App\Position;
use Illuminate\Database\Seeder;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Support\Facades\App;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * ADMINISTRADOR USER
         * Se registra al usuario administrador
         **/
        $avatar =  $avatar = 'https://ui-avatars.com/api/?name=' .
            mb_substr(env('USER_FIRST_NAME'), 0, 1) . '+' . mb_substr(env('USER_LAST_NAME'), 0, 1) .
            '&size=255';
        $userAdmin = User::create([
            'first_name' => env('USER_FIRST_NAME'),
            'last_name' => env('USER_LAST_NAME'),
            'avatar' => $avatar,
            'email' => env('USER_EMAIL'),
            'password' => password_hash(env('USER_PASSWORD'), PASSWORD_DEFAULT),
            'state' => true,
            'email_verified_at' => now(),
        ]);
        $roleAdmin = Role::where('slug', 'admin')->first();
        $roleGuest = Role::where('slug', 'morador')->first();
        //Se asigna el rol de administrador y invitado an usuario registrado anteriormente
        $userAdmin->roles()->attach([$roleAdmin->id, $roleGuest->id], ['state' => true]);


        /**
         * DIRECTIVA
         * Se registra a los usuario del la directiva barrial
         **/
        $roleDirective = Role::where('slug', 'directivo')->first();
        $positions = Position::all();
        $members = factory(User::class, 5)->create();
        $members->each(function (User $user) use ($roleDirective, $roleGuest, $positions) {

            $user->avatar = 'https://ui-avatars.com/api/?name=' .
                mb_substr($user->first_name, 0, 1) . '+' . mb_substr($user->last_name, 0, 1) .
                '&size=250';
            //se resta uno, debido a que el primer usurio administardor tiene el id = 1
            $user->position_id = $positions->where('id', $user->id - 1)->first()->id;
            // $user->position_id = $positions->random()->id;
            $user->save();
            $user->roles()->attach([$roleDirective->id, $roleGuest->id], ['state' => true]);
        });

        /**
         * MORADORES - VECINOS
         * Se registra a los moradores del barrio
         **/
        $roleNeighbor = Role::where('slug', 'morador')->first();
        $neighbors = factory(User::class, 40)->create();
        $neighbors->each(function (User $neighbor) use ($roleNeighbor) {

            $neighbor->avatar = 'https://ui-avatars.com/api/?name=' .
                mb_substr($neighbor->first_name, 0, 1) . '+' . mb_substr($neighbor->last_name, 0, 1) .
                '&size=250';
            $neighbor->save();
            $neighbor->roles()->attach([$roleNeighbor->id], ['state' => true]);
        });

        /**
         * MODERADOR
         * Se asigna el rol de moderador a los miembros de la directiva o vecinos registrados
         **/
        $moderatorRole = Role::where('slug', 'moderador')->first();
        $moderators = $neighbors->random(4);
        $moderators->each(function (User $moderator) use ($moderatorRole) {
            $moderator->roles()->attach([$moderatorRole->id], ['state' => true]);
        });
        /**
         * INVITADO
         * Se asigna el rol de invitadi a los usuarios registrados de redes sociales o registrados desde la aplicación móvil
         **/
        $guestRole = Role::where('slug', 'invitado')->first();
        $faker = Faker\Factory::create();

        $guests = factory(User::class, 5)->create();
        $guests->each(function (User $guest) use ($guestRole, $faker) {
            $membership = new Membership();
            // $membership->setIdentityCard($faker->numberBetween($min = 1000000000, $max = 9999999999));
            $membership->setIdentityCard($faker->randomNumber());
            $membership->setBasicServiceImage('https://source.unsplash.com/collection/');

            $guest->membership = $membership->getAll();
            $guest->avatar = 'https://source.unsplash.com/collection/';
            $guest->save();

            $guest->roles()->attach([$guestRole->id], ['state' => true]);

            // Notification::send($moderators_active, new SocialProblem($problem, $neighbor));

        });
    }
}
