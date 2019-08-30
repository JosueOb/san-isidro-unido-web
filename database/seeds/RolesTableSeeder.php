<?php

use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create([
            'name'=>'Administrador',
            'slug'=>'admin',
            'description'=> 'Rol administrativo del sistema',
            'private'=>true,
        ]);
        $adminRole->save();
        $adminRole->permissions()->attach([1,2,3,4,5,6,7,8,9,10,11,12,13,14]);
        
        Role::create([
            'name'=>'Invitado',
            'slug'=>'invitado',
            'description'=> 'Rol para los moradores del barrio',
            'private'=>true,
        ]);

        $directiveRole = Role::create([
            'name'=>'Directivo',
            'slug'=>'directivo',
            'description'=> 'Rol para los directivos del barrio',
            'private'=>false,
        ]);
        $directiveRole->save();
        $directiveRole->permissions()->attach([6,7,8,9,10,11,12,13,14]);
    }
}
