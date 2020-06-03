<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title'=> $faker->sentence($nbWords = 3, $variableNbWords = false),
        'description'=>$faker->text($maxNbChars = 200),
        'state'=>true,
        //user_id y category_id se los cambian en el PostsSeeder
        'user_id'=> 2,
        'category_id'=> 2,
    ];
});
