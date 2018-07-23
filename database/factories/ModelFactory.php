<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Models\User::class, function (Faker $faker) {
    return [
        'login' => $faker->unique()->lastName,
    ];
});

$factory->define(\App\Models\UserIp::class, function (Faker $faker) {
    return [
        'ip' => $faker->ipv4,
        'user_id' => function(){
            return \App\Models\User::inRandomOrder()->first()->id;
        }
    ];
});

$factory->define(\App\Models\Post::class, function (Faker $faker) {
    return [
        'header' => $faker->text(100),
        'content' => $faker->paragraph,
        'user_ip' => function () {
            return \App\Models\UserIp::inRandomOrder()->first()->id;
        },
        'user_id' => function () {
            return \App\Models\User::inRandomOrder()->first()->id;
        }
    ];
});

$factory->define(\App\Models\Review::class, function (Faker $faker) {
    return [
        'rating' => $faker->text(100),
        'post_id' => function () {
            return \App\Models\Post::inRandomOrder()->first()->id;
        }
    ];
});

