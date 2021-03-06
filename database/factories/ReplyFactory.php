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

$factory->define(App\Models\Reply::class, function (Faker $faker) {
    return [
        // 'user_id' => function () {
        //     return factory('App\Models\User')->create()->id;
        // },
        // 'thread_id' => function () {
        //     return factory('App\Models\Thread')->create()->id;
        // },
        'user_id'   => App\Models\User::all()->random()->id, // This way grabs existing Users
        'thread_id' => App\Models\Thread::all()->random()->id, // This way grabs existing Threads
        'body'      => $faker->paragraph
    ];
});
