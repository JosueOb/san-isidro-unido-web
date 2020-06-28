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
            'name' => 'Listar roles',
            'slug' => 'roles.index',
            'description' => 'Lista los roles registrados',
            'private' => true,
            'group' => 'roles',
        ]);
        Permission::create([
            'name' => 'Ver detalle de rol',
            'slug' => 'roles.show',
            'description' => 'Ver en detalle cada uno de los roles registrados',
            'private' => true,
            'group' => 'roles',
        ]);
        Permission::create([
            'name' => 'Editar roles',
            'slug' => 'roles.edit',
            'description' => 'Editar registros de roles',
            'private' => true,
            'group' => 'roles',
        ]);
        //Permisos sobre el módulo de directiva
        Permission::create([
            'name' => 'Listar miembros de la directiva',
            'slug' => 'members.index',
            'description' => 'Lista los miembros de la directiva registrados',
            'private' => true,
            'group' => 'directiva',
        ]);
        Permission::create([
            'name' => 'Crear miembro de la directiva',
            'slug' => 'members.create',
            'description' => 'Registrar a los miembros de la directiva',
            'private' => true,
            'group' => 'directiva',
        ]);
        Permission::create([
            'name' => 'Ver detalle de miembros de la directiva',
            'slug' => 'members.show',
            'description' => 'Ver en detalle cada uno de los miembros de la directiva registrados',
            'private' => true,
            'group' => 'directiva',
        ]);
        Permission::create([
            'name' => 'Editar miembros de la directiva',
            'slug' => 'members.edit',
            'description' => 'Editar registros de miembros de la directiva',
            'private' => true,
            'group' => 'directiva',
        ]);
        Permission::create([
            'name' => 'Eliminar miembros de la directiva',
            'slug' => 'members.destroy',
            'description' => 'Eliminar registros de los miembros de la directiva',
            'private' => true,
            'group' => 'directiva',
        ]);
        //Permisos sobre el módulo de directiva referente a los cargos
        Permission::create([
            'name' => 'Listar cargos de la directiva',
            'slug' => 'positions.index',
            'description' => 'Lista los cargos de los miembros de la directiva',
            'private' => true,
            'group' => 'cargo',
        ]);
        Permission::create([
            'name' => 'Crear cargo',
            'slug' => 'positions.create',
            'description' => 'Registrar los cargos de la directiva',
            'private' => true,
            'group' => 'cargo',
        ]);
        Permission::create([
            'name' => 'Editar cargos de la directiva',
            'slug' => 'positions.edit',
            'description' => 'Editar registros de cargos de la directiva',
            'private' => true,
            'group' => 'cargo',
        ]);
        Permission::create([
            'name' => 'Eliminar cargos de la directiva',
            'slug' => 'positions.destroy',
            'description' => 'Eliminar registros de los cargos de la directiva',
            'private' => true,
            'group' => 'cargo',
        ]);
        //Permisos sobre el módulo de morador - vecino
        Permission::create([
            'name' => 'Listar a los vecinos del barrio',
            'slug' => 'neighbors.index',
            'description' => 'Lista a los vecinos registrados',
            'private' => false,
            'group' => 'morador',
        ]);
        Permission::create([
            'name' => 'Crear vecino del barrio',
            'slug' => 'neighbors.create',
            'description' => 'Registrar a los vecinos del barrio',
            'private' => false,
            'group' => 'morador',
        ]);
        Permission::create([
            'name' => 'Ver detalle de los vecinos del barrio',
            'slug' => 'neighbors.show',
            'description' => 'Ver en detalle cada uno de vecinos del barrio registrados',
            'private' => false,
            'group' => 'morador',
        ]);
        Permission::create([
            'name' => 'Editar vecinos del barrio',
            'slug' => 'neighbors.edit',
            'description' => 'Editar registros de los vecinos del barrio',
            'private' => false,
            'group' => 'morador',
        ]);
        Permission::create([
            'name' => 'Eliminar vecinos del barrio',
            'slug' => 'neighbors.destroy',
            'description' => 'Eliminar registros de los vecinos del barrio',
            'private' => false,
            'group' => 'morador',
        ]);
        //Permisos sobre el módulo de reportes
        Permission::create([
            'name' => 'Listar los informes',
            'slug' => 'reports.index',
            'description' => 'Lista los informes registrados por la directiva',
            'private' => true,
            'group' => 'informe',
        ]);
        Permission::create([
            'name' => 'Crear informe',
            'slug' => 'reports.create',
            'description' => 'Registrar un nuevo informe',
            'private' => true,
            'group' => 'informe',
        ]);
        Permission::create([
            'name' => 'Ver a detalle un informe',
            'slug' => 'reports.show',
            'description' => 'Ver en detalle cada uno de los informes registrados',
            'private' => true,
            'group' => 'informe',
        ]);
        Permission::create([
            'name' => 'Editar informes',
            'slug' => 'reports.edit',
            'description' => 'Editar los informes registrados',
            'private' => true,
            'group' => 'informe',
        ]);
        Permission::create([
            'name' => 'Eliminar informes',
            'slug' => 'reports.destroy',
            'description' => 'Eliminar los registros de informes',
            'private' => true,
            'group' => 'informe',
        ]);
        //Permisos sobre el módulo de categorías
        Permission::create([
            'name' => 'Listar las categorías',
            'slug' => 'categories.index',
            'description' => 'Lista las categorías por defecto',
            'private' => true,
            'group' => 'categoría',
        ]);
        Permission::create([
            'name' => 'Editar categoría',
            'slug' => 'categories.edit',
            'description' => 'Editar las categorías registradas',
            'private' => true,
            'group' => 'categoría',
        ]);
        //Permisos sobre el módulo de subcategorías
        Permission::create([
            'name' => 'Listar las subcategorías',
            'slug' => 'subcategories.index',
            'description' => 'Lista las subcategorías registradas',
            'private' => true,
            'group' => 'subcategoría',
        ]);
        Permission::create([
            'name' => 'Crear subcategoría',
            'slug' => 'subcategories.create',
            'description' => 'Registrar las subcategorías',
            'private' => true,
            'group' => 'subcategoría',
        ]);
        Permission::create([
            'name' => 'Editar subcategoría',
            'slug' => 'subcategories.edit',
            'description' => 'Editar las subcategorías registradas',
            'private' => true,
            'group' => 'subcategoría',
        ]);
        Permission::create([
            'name' => 'Eliminar subcategoría',
            'slug' => 'subcategories.destroy',
            'description' => 'Eliminar registros de las subcategorías registradas',
            'private' => true,
            'group' => 'subcategoría',
        ]);
        //Permisos sobre el módulo de servicio público
        Permission::create([
            'name' => 'Listar servicios públicos',
            'slug' => 'publicServices.index',
            'description' => 'Lista los registros de servicios públicos',
            'private' => false,
            'group' => 'servico público',
        ]);
        Permission::create([
            'name' => 'Crear servicio público',
            'slug' => 'publicServices.create',
            'description' => 'Registra los servicios públicos',
            'private' => false,
            'group' => 'servico público',
        ]);
        Permission::create([
            'name' => 'Ver detalle de servicio público',
            'slug' => 'publicServices.show',
            'description' => 'Ver en detalle cada uno de los servicios públicos registrados',
            'private' => false,
            'group' => 'servico público',
        ]);
        Permission::create([
            'name' => 'Editar servicio público',
            'slug' => 'publicServices.edit',
            'description' => 'Edita los registros de servicios públicos',
            'private' => false,
            'group' => 'servico público',
        ]);
        Permission::create([
            'name' => 'Eliminar servicios públicos',
            'slug' => 'publicServices.destroy',
            'description' => 'Elimina los registros de servicios público',
            'private' => false,
            'group' => 'servico público',
        ]);
        //Permisos sobre el módulo de eventos
        Permission::create([
            'name' => 'Listar los eventos',
            'slug' => 'events.index',
            'description' => 'Lista los eventos registrados por la directiva',
            'private' => false,
            'group' => 'evento',
        ]);
        Permission::create([
            'name' => 'Crear evento',
            'slug' => 'events.create',
            'description' => 'Registrar un nuevo evento',
            'private' => false,
            'group' => 'evento',
        ]);
        Permission::create([
            'name' => 'Ver a detalle un evento',
            'slug' => 'events.show',
            'description' => 'Ver en detalle cada uno de los eventos registrados',
            'private' => false,
            'group' => 'evento',
        ]);
        Permission::create([
            'name' => 'Editar evento',
            'slug' => 'events.edit',
            'description' => 'Editar los eventos registrados',
            'private' => false,
            'group' => 'evento',
        ]);
        Permission::create([
            'name' => 'Eliminar evento',
            'slug' => 'events.destroy',
            'description' => 'Eliminar los registros de eventos',
            'private' => false,
            'group' => 'evento',
        ]);
        //Permisos sobre el módulo de moderadores
        Permission::create([
            'name' => 'Asignar moderador',
            'slug' => 'moderators.assign',
            'description' => 'Asigna el rol de moderador',
            'private' => true,
            'group' => 'moderador',
        ]);
        Permission::create([
            'name' => 'Listar moderadores asignados',
            'slug' => 'moderators.index',
            'description' => 'Lista a los moderadores registrados',
            'private' => true,
            'group' => 'moderador',
        ]);
        Permission::create([
            'name' => 'Crear moderador',
            'slug' => 'moderators.create',
            'description' => 'Registrar a los nuevos moderadores',
            'private' => true,
            'group' => 'moderador',
        ]);
        Permission::create([
            'name' => 'Ver detalle de los moderadores asignados',
            'slug' => 'moderators.show',
            'description' => 'Ver en detalle cada uno de los moderadores registrados',
            'private' => true,
            'group' => 'moderador',
        ]);
        Permission::create([
            'name' => 'Editar moderador',
            'slug' => 'moderators.edit',
            'description' => 'Editar registros de los moderadores',
            'private' => true,
            'group' => 'moderador',
        ]);
        Permission::create([
            'name' => 'Eliminar moderador',
            'slug' => 'moderators.destroy',
            'description' => 'Desactiva a los moderadores',
            'private' => true,
            'group' => 'moderador',
        ]);
        //Permisos sobre el módulo de policía
        Permission::create([
            'name' => 'Listar a los policías del barrio',
            'slug' => 'policemen.index',
            'description' => 'Lista a los policías registrados',
            'private' => false,
            'group' => 'policía',
        ]);
        Permission::create([
            'name' => 'Crear policía del barrio',
            'slug' => 'policemen.create',
            'description' => 'Registrar a los policías del barrio',
            'private' => false,
            'group' => 'policía',
        ]);
        Permission::create([
            'name' => 'Ver detalle de los policías del barrio',
            'slug' => 'policemen.show',
            'description' => 'Ver en detalle cada uno de policías del barrio registrados',
            'private' => false,
            'group' => 'policía',
        ]);
        Permission::create([
            'name' => 'Editar policías del barrio',
            'slug' => 'policemen.edit',
            'description' => 'Editar registros de los policía del barrio',
            'private' => false,
            'group' => 'policía',
        ]);
        Permission::create([
            'name' => 'Eliminar policías del barrio',
            'slug' => 'policemen.destroy',
            'description' => 'Eliminar registros de los policías del barrio',
            'private' => false,
            'group' => 'policía',
        ]);

        /**
         * Problemas sociales reportados
         **/
        Permission::create([
            'name' => 'Notificar los problemas sociales reportados',
            'slug' => 'notifications.problems',
            'description' => 'Notifica, lista y visualiza la solicitud de problemas sociales',
            'private' => true,
            'group' => 'notificación',
        ]);
        Permission::create([
            'name' => 'Aceptar o rechazar las solicitudes de problemas sociales',
            'slug' => 'socialProblemReports.approveOrReject',
            'description' => 'Se permite aceptar o rechazar las solicitudes de problemas sociales',
            'private' => true,
            'group' => 'notificación',
        ]);

        /**
         * Emergencias reportados
         **/
        Permission::create([
            'name' => 'Notificar las emergencias reportadas',
            'slug' => 'notifications.emergencies',
            'description' => 'Notifica, lista y visualiza la solicitud de emergencias',
            'private' => true,
            'group' => 'notificación',
        ]);
        Permission::create([
            'name' => 'Publicar las emergencias reportadas',
            'slug' => 'emergencyReport.publish',
            'description' => 'Se permite hacer pública una emergencia reportada',
            'private' => true,
            'group' => 'notificación',
        ]);

        /**
         * SOLICITUD DE AFILIACIÓN
         **/
        Permission::create([
            'name' => 'Notificar las solicitudes de afiliación',
            'slug' => 'notifications.memberships',
            'description' => 'Notifica, lista y visualiza la solicitud de de afiliación',
            'private' => true,
            'group' => 'notificación',
        ]);
        Permission::create([
            'name' => 'Aceptar o rechazar las solicitudes de afiliación',
            'slug' => 'membership.approveOrReject',
            'description' => 'Se permite aceptar o rechazar las solicitudes de afiliación',
            'private' => true,
            'group' => 'notificación',
        ]);
        /**
         * Módulo de problema social (Directiva)
         **/
        Permission::create([
            'name' => 'Listar los problemas sociales atendidos por el moderador',
            'slug' => 'socialProblems.index',
            'description' => 'Lista los problemas sociales aprobados o rechazados por parte del moderador',
            'private' => true,
            'group' => 'problema social',
        ]);
        Permission::create([
            'name' => 'Ver detalle del problema social reportado',
            'slug' => 'socialProblems.show',
            'description' => 'Ver en detalle cada uno de los problemas sociales atendidos por el moderador',
            'private' => true,
            'group' => 'problema social',
        ]);
        Permission::create([
            'name' => 'Atender o rechazar un poblema social',
            'slug' => 'socialProblems.attendOrReject',
            'description' => 'Se permite atender o rechazar un problema social',
            'private' => true,
            'group' => 'problema social',
        ]);
    }
}
