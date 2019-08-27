<?php

use App\Position;
use Illuminate\Database\Seeder;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::create([
            'name'=>'Presidente',
        ]);
        Position::create([
            'name'=>'Vicepresidente',
        ]);
        Position::create([
            'name'=>'Tesorero',
        ]);
        Position::create([
            'name'=>'Secretario',
        ]);
        Position::create([
            'name'=>'Vocal',
        ]);
    }
}
