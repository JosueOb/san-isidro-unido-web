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
            'group'=>'roles',
        ]);
        // Permission::create([
        //     'name'=>'Crear roles',
        //     'slug'=>'roles.create',
        //     'description'=>'Crear los roles del sistema',
        //     'private'=>true,
        // ]);
        Permission::create([
            'name'=>'Ver detalle de rol',
            'slug'=>'roles.show',
            'description'=>'Ver en detalle cada uno de los roles registrados',
            'private'=>true,
            'group'=>'roles',
        ]);
        Permission::create([
            'name'=>'Editar roles',
            'slug'=>'roles.edit',
            'description'=>'Editar registros de roles',
            'private'=>true,
            'group'=>'roles',
        ]);
        // Permission::create([
        //     'name'=>'Eliminar roles',
        //     'slug'=>'roles.destroy',
        //     'description'=>'Eliminar registros de roles',
        //     'private'=>true,
        // ]);
        //Permisos sobre el módulo de directiva
        Permission::create([
            'name'=>'Listar miembros de la directiva',
            'slug'=>'members.index',
            'description'=>'Lista los miembros de la directiva registrados',
            'private'=>false,
            'group'=>'directiva',
        ]);
        Permission::create([
            'name'=>'Crear miembro de la directiva',
            'slug'=>'members.create',
            'description'=>'Registrar a los miembros de la directiva',
            'private'=>false,
            'group'=>'directiva',
        ]);
        Permission::create([
            'name'=>'Ver detalle de miembros de la directiva',
            'slug'=>'members.show',
            'description'=>'Ver en detalle cada uno de los miembros de la directiva registrados',
            'private'=>false,
            'group'=>'directiva',
        ]);
        Permission::create([
            'name'=>'Editar miembros de la directiva',
            'slug'=>'members.edit',
            'description'=>'Editar registros de miembros de la directiva',
            'private'=>false,
            'group'=>'directiva',
        ]);
        Permission::create([
            'name'=>'Eliminar miembros de la directiva',
            'slug'=>'members.destroy',
            'description'=>'Eliminar registros de los miembros de la directiva',
            'private'=>false,
            'group'=>'directiva',
        ]);
        //Permisos sobre el módulo de directiva referente a los cargos
        Permission::create([
            'name'=>'Listar cargos de la directiva',
            'slug'=>'positions.index',
            'description'=>'Lista los cargos de los miembros de la directiva',
            'private'=>false,
            'group'=>'cargo',
        ]);
        Permission::create([
            'name'=>'Crear cargo',
            'slug'=>'positions.create',
            'description'=>'Registrar los cargos de la directiva',
            'private'=>false,
            'group'=>'cargo',
        ]);
        // Permission::create([
        //     'name'=>'Ver detalle de los cragos de la directiva',
        //     'slug'=>'positions.show',
        //     'description'=>'Ver en detalle cada uno de los cargos de la directiva',
        //     'private'=>false,
        // ]);
        Permission::create([
            'name'=>'Editar cargos de la directiva',
            'slug'=>'positions.edit',
            'description'=>'Editar registros de cargos de la directiva',
            'private'=>false,
            'group'=>'cargo',
        ]);
        Permission::create([
            'name'=>'Eliminar cargos de la directiva',
            'slug'=>'positions.destroy',
            'description'=>'Eliminar registros de los cargos de la directiva',
            'private'=>false,
            'group'=>'cargo',
        ]);
        //Permisos sobre el módulo de morador - vecino
        Permission::create([
            'name'=>'Listar a los vecinos del barrio',
            'slug'=>'neighbors.index',
            'description'=>'Lista a los vecinos registrados',
            'private'=>false,
            'group'=>'morador',
        ]);
        Permission::create([
            'name'=>'Crear vecino del barrio',
            'slug'=>'neighbors.create',
            'description'=>'Registrar a los vecinos del barrio',
            'private'=>false,
            'group'=>'morador',
        ]);
        Permission::create([
            'name'=>'Ver detalle de los vecinos del barrio',
            'slug'=>'neighbors.show',
            'description'=>'Ver en detalle cada uno de vecinos del barrio registrados',
            'private'=>false,
            'group'=>'morador',
        ]);
        Permission::create([
            'name'=>'Editar vecinos del barrio',
            'slug'=>'neighbors.edit',
            'description'=>'Editar registros de los vecinos del barrio',
            'private'=>false,
            'group'=>'morador',
        ]);
        Permission::create([
            'name'=>'Eliminar vecinos del barrio',
            'slug'=>'neighbors.destroy',
            'description'=>'Eliminar registros de los vecinos del barrio',
            'private'=>false,
            'group'=>'morador',
        ]);
        //Permisos sobre el módulo de reportes
        Permission::create([
            'name'=>'Listar los informes',
            'slug'=>'reports.index',
            'description'=>'Lista los informes registrados por la directiva',
            'private'=>false,
            'group'=>'informe',
        ]);
        Permission::create([
            'name'=>'Crear informe',
            'slug'=>'reports.create',
            'description'=>'Registrar un nuevo informe',
            'private'=>false,
            'group'=>'informe',
        ]);
        Permission::create([
            'name'=>'Ver a detalle un informe',
            'slug'=>'reports.show',
            'description'=>'Ver en detalle cada uno de los informes registrados',
            'private'=>false,
            'group'=>'informe',
        ]);
        Permission::create([
            'name'=>'Editar informes',
            'slug'=>'reports.edit',
            'description'=>'Editar los informes registrados',
            'private'=>false,
            'group'=>'informe',
        ]);
        Permission::create([
            'name'=>'Eliminar informes',
            'slug'=>'reports.destroy',
            'description'=>'Eliminar los registros de informes',
            'private'=>false,
            'group'=>'informe',
        ]);
    }
}
