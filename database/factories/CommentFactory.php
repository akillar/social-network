<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Comment::class, function (Faker $faker) {
    return [
        'body' => $faker->paragraph,
        'user_id' => function() {
            return factory(\App\User::class)->create();
        },
        'status_id' => function() {
            return factory(\App\Models\Status::class)->create();
        }
    ];
});
