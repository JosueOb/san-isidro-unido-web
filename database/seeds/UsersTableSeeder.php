<?php

use App\Position;
use Illuminate\Database\Seeder;
use App\User;
use Caffeinated\Shinobi\Models\Role;

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
        $avatar =  $avatar = 'https://ui-avatars.com/api/?name='.
        mb_substr(env('USER_FIRST_NAME'),0,1).'+'.mb_substr(env('USER_LAST_NAME'),0,1).
        '&size=255';
        $userAdmin = User::create([
            'first_name'=>env('USER_FIRST_NAME'),
            'last_name'=>env('USER_LAST_NAME'),
            'avatar'=> $avatar,
            'email'=> env('USER_EMAIL'),
            'password'=> password_hash(env('USER_PASSWORD'), PASSWORD_DEFAULT),
            'state'=>true,
            'email_verified_at'=> now(),
        ]);
        $roleAdmin = Role::where('slug', 'admin')->first();
        $roleGuest = Role::where('slug', 'morador')->first();
        //Se asigna el rol de administrador y invitado an usuario registrado anteriormente
        $userAdmin->roles()->attach([$roleAdmin->id, $roleGuest->id],['state'=>true]);


        /**
         * DIRECTIVA
         * Se registra a los usuario del la directiva barrial
        **/
        $roleDirective = Role::where('slug','directivo')->first();
        $positions = Position::all();
        $members = factory(App\User::class,5)->create();
        $members->each(function(User $user)use($roleDirective, $roleGuest,$positions){
            // var_dump($user->first_name .' '. $user->last_name);
            // var_dump( 'https://ui-avatars.com/api/?name='.
            // substr($user->first_name,0,1).'+'.substr($user->last_name,0,1).
            // '&size=255');

            $user->avatar = 'https://ui-avatars.com/api/?name='.
            mb_substr($user->first_name,0,1).'+'.mb_substr($user->last_name,0,1).
            '&size=250';
            //se resta uno, debido a que el primer usurio administardor tiene el id = 1
            $user->position_id = $positions->where('id', $user->id-1)->first()->id;
            $user->save();
            $user->roles()->attach([$roleDirective->id, $roleGuest->id],['state'=>true]);

        });

        /**
         * MORADORES - VECINOS
         * Se registra a los moradores del barrio
        **/
        $roleNeighbor = Role::where('slug', 'morador')->first();
        $neighbors = factory(User::class, 15)->create();
        $neighbors->each(function(User $neighbor)use($roleNeighbor){
            // var_dump($neighbor->first_name.' '.$neighbor->last_name);
            // var_dump('https://ui-avatars.com/api/?name='.
            // substr($neighbor->first_name,0,1).'+'.substr($neighbor->last_name,0,1).
            // '&size=255');

            $neighbor->avatar = 'https://ui-avatars.com/api/?name='.
            mb_substr($neighbor->first_name,0,1).'+'.mb_substr($neighbor->last_name,0,1).
            '&size=250';
            $neighbor->save();
            $neighbor->roles()->attach([$roleNeighbor->id],['state'=>true]);
        });

        /**
         * MODERADOR
         * Se asigna el rol de moderador a los miembros de la directiva o vecinos registrados
        **/
        $moderatorRole = Role::where('slug', 'moderador')->first();
        $moderators = $neighbors->random(4);
        $moderators->each(function(User $moderator) use ($moderatorRole){
            $moderator->roles()->attach([$moderatorRole->id], ['state'=>true]);
        });
    }
}
