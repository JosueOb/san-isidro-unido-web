<?php

use App\Helpers\OnesignalNotification;
use App\Position;
use App\SocialProfile;
use Illuminate\Database\Seeder;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

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
         * Variables globales
         */
        $faker = \Faker\Factory::create();
        $provider_options = ['facebook', 'google'];
        /**
         * Roles del sistema web y aplicación móvil
         * 
         */
        $admin_role = Role::where('slug', 'admin')->first();
        $directive_role = Role::where('slug', 'directivo')->first();
        $moderator_role = Role::where('slug', 'moderador')->first();
        $neighbor_role = Role::where('slug', 'morador')->first();
        $guest_role = Role::where('slug', 'invitado')->first();
        $police_role = Role::where('slug', 'policia')->first();

        /**
         * ADMINISTRADOR USER
         * Se registra al usuario administrador
         **/
        $avatar =  $avatar = 'https://ui-avatars.com/api/?name=' .
            mb_substr(env('USER_FIRST_NAME'), 0, 1) . '+' . mb_substr(env('USER_LAST_NAME'), 0, 1) .
            '&size=250';
        $userAdmin = User::create([
            'first_name' => env('USER_FIRST_NAME'),
            'last_name' => env('USER_LAST_NAME'),
            'avatar' => $avatar,
            'email' => env('USER_EMAIL'),
            'number_phone' => env('USER_PHONE'),
            'password' => password_hash(env('USER_PASSWORD'), PASSWORD_DEFAULT),
            'email_verified_at' => now(),
        ]);
        //Se asigna el rol de administrador y vecino al administrador
        $userAdmin->roles()->attach([$neighbor_role->id, $admin_role->id], ['state' => true]);

        /**
         * DIRECTIVA
         * Se registra a los usuario de la directiva barrial
         **/
        $president = User::create([
            'first_name' => 'Pablo Aníbal',
            'last_name' => 'Vela Galarza',
            'email' => 'directive@example.com',
            'number_phone' => '09' . mt_rand(80000000, 99999999),
            'password' => password_hash('Directive01', PASSWORD_DEFAULT),
            'email_verified_at' => now(),
            'position_id' => 1, //se le asigna el cargo de presiente
        ]);
        $president->avatar = 'https://ui-avatars.com/api/?name=' .
            mb_substr($president->first_name, 0, 1) . '+' . mb_substr($president->last_name, 0, 1) .
            '&size=250';
        $president->save();
        $president->roles()->attach([$neighbor_role->id, $directive_role->id], ['state' => true]);
        // Registro de 4 directivos
        //Se obtiene todas los cargos registrado excepto el cargo de presidente
        $positions = Position::whereNotIn('name', ['Presidente'])->get();
        $positions->each(function (Position $position) use ($directive_role, $neighbor_role) {
            // Por cada cargo se a a crea un directivo
            $member = factory(User::class)->create();
            $member->avatar = 'https://ui-avatars.com/api/?name=' .
                mb_substr($member->first_name, 0, 1) . '+' . mb_substr($member->last_name, 0, 1) .
                '&size=250';
            $member->position_id = $position->id;
            $member->save();
            $member->roles()->attach([$neighbor_role->id, $directive_role->id], ['state' => true]);
        });

        /**
         * MORADORES - VECINOS
         * Se registra a los moradores del barrio
         **/
        $neighbors = factory(User::class, 40)->create();
        $neighbors->each(function (User $neighbor) use ($neighbor_role) {

            $neighbor->avatar = 'https://ui-avatars.com/api/?name=' .
                mb_substr($neighbor->first_name, 0, 1) . '+' . mb_substr($neighbor->last_name, 0, 1) .
                '&size=250';
            $neighbor->save();
            $neighbor->roles()->attach([$neighbor_role->id], ['state' => true]);
        });

        /**
         * MODERADOR
         * Se registra y se asigna el rol de moderador
         **/
        $moderator = User::create([
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => 'moderator@example.com',
            'number_phone' => '09' . mt_rand(80000000, 99999999),
            'password' => password_hash('Moderator01', PASSWORD_DEFAULT),
            'email_verified_at' => now(),
        ]);
        $moderator->avatar = 'https://ui-avatars.com/api/?name=' .
            mb_substr($moderator->first_name, 0, 1) . '+' . mb_substr($moderator->last_name, 0, 1) .
            '&size=250';
        $moderator->save();
        $moderator->roles()->attach([$neighbor_role->id, $moderator_role->id], ['state' => true]);
        // Se asigna aleatoreamente el rol de moderador a tres moradores registrados
        $moderators = $neighbors->random(3);
        $moderators->each(function (User $moderator) use ($moderator_role) {
            $moderator->roles()->attach([$moderator_role->id], ['state' => true]);
        });

        /**
         * POLICÍA
         * registo de la policía comunitaria
         */
        $police = User::create([
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => 'police@example.com',
            'number_phone' => '09' . mt_rand(80000000, 99999999),
            'password' => password_hash('Police01', PASSWORD_DEFAULT),
            'email_verified_at' => now(),
        ]);
        $police->avatar = 'https://ui-avatars.com/api/?name=' .
            mb_substr($police->first_name, 0, 1) . '+' . mb_substr($police->last_name, 0, 1) .
            '&size=250';
        $police->save();
        $police->roles()->attach([$police_role->id], ['state' => true]);
        //Registro de 2 policías
        $polices = factory(User::class, 2)->create();
        $polices->each(function(User $police) use($police_role) {
            $police->avatar = 'https://ui-avatars.com/api/?name=' .
                mb_substr($police->first_name, 0, 1) . '+' . mb_substr($police->last_name, 0, 1) .
                '&size=250';
            $police->save();
            $police->roles()->attach([$police_role->id], ['state' => true]);
        });

        /**
         * INVITADO
         * Se asigna el rol de invitado a los usuarios registrados de redes sociales o registrados desde la aplicación móvil
         **/
        //Registro de usuarios ivitados por el formulario por parte del directivo o Moderador
        $guests = factory(User::class, 5)->create();
        $guests->each(function (User $guest) use ($guest_role) {
            $guest->avatar = 'https://source.unsplash.com/daily';
            $guest->save();
            $guest->roles()->attach([$guest_role->id], ['state' => true]);
        });

        //Registro de usuarios por redes sociales
        $guest01 = User::create([
            'first_name' => 'Jose',
            'last_name' => 'Maza',
            'email' => 'guest@example.com',
            'number_phone' => '09' . mt_rand(80000000, 99999999),
            'avatar' => "https://ui-avatars.com/api/?name=Jose+Maza&size=255",
            'password' => password_hash('Guest01', PASSWORD_DEFAULT),
            'email_verified_at' => now(),
        ]);
        SocialProfile::create([
            'user_id' => $guest01->id,
            'social_id' => '487asasd8a7ddldskfkds4',
            "provider" =>  $provider_options[0],
        ]);
        SocialProfile::create([
            'user_id' => $guest01->id,
            'social_id' => '12151515151swswsxwxw',
            "provider" =>  $provider_options[1],
        ]);
        $guest01->roles()->attach([$guest_role->id], ['state' => true]);
    }
}
