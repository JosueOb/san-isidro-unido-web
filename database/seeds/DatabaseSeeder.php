<?php

use App\Subcategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //LLamado al seeder PermissionsTableSeeder
        $this->call(PermissionsTableSeeder::class);
        //LLamado al seeder RolesTableSeeder
        $this->call(RolesTableSeeder::class);
        //LLamado al seeder PositionsTableSeeder
        $this->call(PositionsTableSeeder::class);
        //LLamado al seeder UsersTableSeeder
        $this->call(UsersTableSeeder::class);

        //API SEEDERS
        //TODO: LLamado al seeder Usuarios
        $this->call(ApiUserSeeder::class);
        //TODO: LLamado al seeder Category
        $this->call(ApiCategorySeeder::class);
        //TODO: LLamado al seeder Subcategory
        $this->call(ApiSubCategorySeeder::class);
        //TODO: LLamado al seeder Post
        $this->call(ApiPostSeeder::class);
        //TODO: LLamado al seeder Public Service 
        $this->call(ApiPublicServiceSeeder::class);
        //TODO: Llamado al Seeder Directives
        $this->call(ApiDirectivesSeeder::class);
    }
}
