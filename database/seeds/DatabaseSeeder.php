<?php

use Illuminate\Database\Seeder;
use App\User;
use Caffeinated\Shinobi\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        //Se inserta un usuario
        $avatar =  $avatar = 'https://ui-avatars.com/api/?name='.
        substr(env('USER_FIRST_NAME'),0,1).'+'.substr(env('USER_LAST_NAME'),0,1).
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
        //Se inserta el rol de administrador
        $rolAdmin = Role::create([
            'name'=>'Administrador',
            'slug'=>'admin',
            'description'=> 'Rol administrativo del sistema',
            'special'=> 'all-access'
        ]);
        $rolGuest = Role::create([
            'name'=>'Invitado',
            'slug'=>'invitado',
            'description'=> 'Rol para los moradores del barrio',
        ]);
        //Se le asigna el rol de aministrador al usuario
        $userAdmin->roles()->attach([$rolAdmin->id, $rolGuest->id]);

        //LLamado al seeder PermissionsTableSeeder
        $this->call(PermissionsTableSeeder::class);
    }
}
