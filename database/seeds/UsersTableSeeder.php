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
        $roleAdmin = Role::where('name', 'Administrador')->first();
        $roleGuest = Role::where('name', 'Morador')->first();
        //Se le asigna el rol de aministrador al usuario
        $userAdmin->roles()->attach([$roleAdmin->id, $roleGuest->id]);
        
        $roleDirective = Role::where('name','Directivo')->first();
        $positions = Position::all();
        $members = factory(User::class,5)->create();
        $members->each(function(User $user)use($roleDirective, $roleGuest,$positions){
            $user->avatar = 'https://ui-avatars.com/api/?name='.
            substr($user->first_name,0,1).'+'.substr($user->last_name,0,1).
            '&size=255';
            //se resta uno, debido a que el primer usurio administardor tiene el id = 1
            $user->position_id = $positions->where('id', $user->id-1)->first()->id;
            $user->save();
            $user->roles()->attach([$roleDirective->id, $roleGuest->id]);
        });
    }
}
