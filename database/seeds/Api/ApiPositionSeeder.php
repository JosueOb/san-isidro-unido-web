<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;
//php artisan make:seeder Api\PositionSeeder 

class ApiPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->insert([
            'name' => 'Presidente',
            'slug' => 'presidente',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        DB::table('positions')->insert([
            'name' => 'Vicepresidente',
            'slug' => 'vicepresidente',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        DB::table('positions')->insert([
            'name' => 'Secretario',
            'slug' => 'secretario',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        DB::table('positions')->insert([
            'name' => 'Tesorero',
            'slug' => 'tesorero',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
        DB::table('positions')->insert([
            'name' => 'Vocal',
            'slug' => 'vocal',
            'allocation' => 'several-people',
            'created_at' => CarbonImmutable::now()->subMinutes(rand(1, 255))->toDateTimeString()
        ]);
    }
}
