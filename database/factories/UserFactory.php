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

$factory->define(App\Models\User::class, function (Faker $faker) {

    $date_time = $faker->date . ' ' . $faker->time;
    //密码为静态变量即全局共享，所有用户密码均一致
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?:$password=bcrypt('123123'), // secret
        'is_admin'=>false,
        'remember_token' => str_random(10),
        'created_at'=>$date_time,
        'updated_at'=>$date_time,
    ];
});
