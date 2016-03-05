<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Http\Models\Admin\User::class, function (Faker\Generator $faker) {
    return [
        'uuid' => $faker->uuid,
        'username' => 'vovanduc',
        'name' => 'Võ Văn Đức',
        'email' => 'vovanduc1989@gmail.com',
        'is_root' => 1,
        'is_admin' => 1,
        'password' => bcrypt('123456'),
    ];
});

$factory->define(App\Http\Models\Admin\Customer::class, function (Faker\Generator $faker) {
    return [
        'uuid' => $faker->uuid,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'name' => $faker->name,
        'address' => $faker->address,
    ];
});

$factory->define(App\Http\Models\Admin\Shipper::class, function (Faker\Generator $faker) {
    return [
        'uuid' => $faker->uuid,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'name' => $faker->name,
        'address' => $faker->address,
    ];
});
