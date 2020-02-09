<?php

use Illuminate\Database\Seeder;

class ApiDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //TODO: LLamado al seeder Roles
        $this->call(ApiRoleSeeder::class);
        //TODO: LLamado al seeder Positions
        $this->call(ApiPositionSeeder::class);
        //TODO: LLamado al seeder Category
        $this->call(ApiCategorySeeder::class);
        //TODO: LLamado al seeder Usuarios
        $this->call(ApiUserSeeder::class);
        //TODO: LLamado al seeder Post
        $this->call(ApiPostSeeder::class);
        //TODO: LLamado al seeder Public Service 
        $this->call(ApiPublicServiceSeeder::class);
        //TODO: Llamado al Seeder Notificaciones
        //$this->call(ApiMobileNotificationsSeeder::class);
        //TODO: Llamado al Seeder Directives
        $this->call(ApiDirectivesSeeder::class);
    }
}
