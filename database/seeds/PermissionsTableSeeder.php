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
            'description'=>'Lista los roles registrados',
            'private'=>true,
        ]);
        Permission::create([
            'name'=>'Crear roles',
            'slug'=>'roles.create',
            'description'=>'Crear los roles del sistema',
            'private'=>true,
        ]);
        Permission::create([
            'name'=>'Ver detalle de rol',
            'slug'=>'roles.show',
            'description'=>'Ver en detalle cada uno de los roles registrados',
            'private'=>true,
        ]);
        Permission::create([
            'name'=>'Editar roles',
            'slug'=>'roles.edit',
            'description'=>'Editar registros de roles',
            'private'=>true,
        ]);
        Permission::create([
            'name'=>'Eliminar roles',
            'slug'=>'roles.destroy',
            'description'=>'Eliminar registros de roles',
            'private'=>true,
        ]);
        //Permisos sobre el módulo de directiva
        Permission::create([
            'name'=>'Listar miembros de la directiva',
            'slug'=>'members.index',
            'description'=>'Lista los miembros de la directiva registrados',
            'private'=>false,
        ]);
        Permission::create([
            'name'=>'Crear miembro de la directiva',
            'slug'=>'members.create',
            'description'=>'Registrar a los miembros de la directiva',
            'private'=>false,
        ]);
        Permission::create([
            'name'=>'Ver detalle de miembros de la directiva',
            'slug'=>'members.show',
            'description'=>'Ver en detalle cada uno de los miembros de la directiva registrados',
            'private'=>false,
        ]);
        Permission::create([
            'name'=>'Editar miembros de la directiva',
            'slug'=>'members.edit',
            'description'=>'Editar registros de miembros de la directiva',
            'private'=>false,
        ]);
        Permission::create([
            'name'=>'Eliminar miembros de la directiva',
            'slug'=>'members.destroy',
            'description'=>'Eliminar registros de los miembros de la directiva',
            'private'=>false,
        ]);
        //Permisos sobre el módulo de directiva referente a los cargos
        Permission::create([
            'name'=>'Listar cargos de la directiva',
            'slug'=>'positions.index',
            'description'=>'Lista los cargos de los miembros de la directiva',
            'private'=>false,
        ]);
        Permission::create([
            'name'=>'Crear cargo',
            'slug'=>'positions.create',
            'description'=>'Registrar los cargos de la directiva',
            'private'=>false,
        ]);
        Permission::create([
            'name'=>'Ver detalle de los cragos de la directiva',
            'slug'=>'positions.show',
            'description'=>'Ver en detalle cada uno de los cargos de la directiva',
            'private'=>false,
        ]);
        Permission::create([
            'name'=>'Editar cargos de la directiva',
            'slug'=>'positions.edit',
            'description'=>'Editar registros de cargos de la directiva',
            'private'=>false,
        ]);
        Permission::create([
            'name'=>'Eliminar cargos de la directiva',
            'slug'=>'positions.destroy',
            'description'=>'Eliminar registros de los cargos de la directiva',
            'private'=>false,
        ]);
    }
}
