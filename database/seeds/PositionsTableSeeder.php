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
            'allocation'=>'one-person',
        ]);
        Position::create([
            'name'=>'Vicepresidente',
            'allocation'=>'one-person',
        ]);
        Position::create([
            'name'=>'Tesorero',
            'allocation'=>'one-person',
        ]);
        Position::create([
            'name'=>'Secretario',
            'allocation'=>'one-person',
        ]);
        Position::create([
            'name'=>'Vocal',
            'allocation'=>'several-people',
        ]);
    }
}
