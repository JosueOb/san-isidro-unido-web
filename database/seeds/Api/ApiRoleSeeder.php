<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class ApiRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Morador Afiliado',
            'slug' => 'morador',
            'description' => 'Rol de un morador afiliado',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        //
        DB::table('roles')->insert([
            'name' => 'Invitado',
            'slug' => 'invitado',
            'description' => 'Rol de un morador invitado',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        //
        DB::table('roles')->insert([
            'name' => 'Policia Comunitario',
            'slug' => 'policia',
            'description' => 'Rol de un policia comunitario',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        //
        DB::table('roles')->insert([
            'name' => 'Directivo',
            'slug' => 'directivo',
            'description' => 'Rol de un directivo',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        DB::table('roles')->insert([
            'name' => 'Administrador',
            'slug' => 'admin',
            'description' => 'Rol de un administrador',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        DB::table('roles')->insert([
            'name' => 'Moderador',
            'slug' => 'moderador',
            'description' => 'Rol de un moderador',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
    }
}
