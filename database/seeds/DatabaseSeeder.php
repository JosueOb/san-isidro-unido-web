<?php

use App\Notifications\SocialProblem;
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
        //LLamado al seeder CategoriesTableSeeder
        $this->call(CategoriesTableSeeder::class);
        //Llamando al seeder de SubcategoriesTableSeeder
        $this->call(SubcategoriesTableSeeder::class);
        //Llamando al seeder de PostsTableSeeder
        $this->call(PostsTableSeeder::class);
        //Llamando al seeder de PublicServicesTableSeeder
        $this->call(PublicServicesTableSeeder::class);


        //API SEEDERS
        //TODO: LLamado al seeder Usuarios
        // $this->call(ApiUserSeeder::class);(unificado)
        // //TODO: LLamado al seeder Category
        // $this->call(ApiCategorySeeder::class); (unificado)
        // //TODO: LLamado al seeder Subcategory
        // $this->call(ApiSubCategorySeeder::class); (unificado)
        //TODO: LLamado al seeder Post
        // $this->call(ApiPostSeeder::class); (Unificado)
        //TODO: LLamado al seeder Public Service
        // $this->call(ApiPublicServiceSeeder::class); (unificado)
        //TODO: Llamado al Seeder Directives
        // $this->call(ApiDirectivesSeeder::class); (unificado)


        //TEST-NOTIFICATIONS
        $this->call(TestNotificationsSeeder::class);
    }
}
