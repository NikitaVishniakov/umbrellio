<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Post::class, function (Faker $faker) {
    return [
        'header' => $faker->title,
        'content' => $faker->paragraph,
        'user_ip' => $faker->ipv4,
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        }
    ];
});
