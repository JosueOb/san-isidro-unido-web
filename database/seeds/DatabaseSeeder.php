<?php

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
    }
}
