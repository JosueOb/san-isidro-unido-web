<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PublicService;
use Faker\Generator as Faker;

$factory->define(PublicService::class, function (Faker $faker) {
    return [
        'name'=>$faker->catchPhrase,
        // 'ubication'=>,
        'subcategory_id'=>1,
        'email'=>$faker->email,
        // 'public_opening'=>,
    ];
});
