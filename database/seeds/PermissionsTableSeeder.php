<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permisos sobre el módulo de rol
        Permission::create([
            'name'=>'Listar roles',
            'slug'=>'roles.index',
            'description'=>'Lista los roles registrados'
        ]);
        Permission::create([
            'name'=>'Crear roles',
            'slug'=>'roles.create',
            'description'=>'Crear los roles del sistema'
        ]);
        Permission::create([
            'name'=>'Ver detalle de rol',
            'slug'=>'roles.show',
            'description'=>'Ver en detalle cada uno de los roles registrados'
        ]);
        Permission::create([
            'name'=>'Editar roles',
            'slug'=>'roles.edit',
            'description'=>'Editar registros de roles'
        ]);
        Permission::create([
            'name'=>'Eliminar roles',
            'slug'=>'roles.destroy',
            'description'=>'Eliminar registros de roles'
        ]);
    }
}
