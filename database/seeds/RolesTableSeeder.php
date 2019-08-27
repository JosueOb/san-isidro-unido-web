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
        Role::create([
            'name'=>'Administrador',
            'slug'=>'admin',
            'description'=> 'Rol administrativo del sistema',
            'special'=> 'all-access'
        ]);
        Role::create([
            'name'=>'Invitado',
            'slug'=>'invitado',
            'description'=> 'Rol para los moradores del barrio',
        ]);
        Role::create([
            'name'=>'Directivo',
            'slug'=>'directivo',
            'description'=> 'Rol para los directivos del barrio',
        ]);
    }
}
