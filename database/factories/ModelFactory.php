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

$factory->define(App\Http\Models\Admin\Customer::class, function (Faker\Generator $faker) {
    return [
        'uuid' => $faker->uuid,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'name' => $faker->name,
        'address' => $faker->address,
        'created_by' => 1,
        'updated_by' => 1,
    ];
});
