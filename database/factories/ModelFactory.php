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

$factory->define(App\Http\Models\Admin\Package::class, function (Faker\Generator $faker) {
    return [
        'uuid' => $faker->uuid,
        //'address' => $faker->address,
        // 'latitude' => $faker->latitude($min = 10.762622, $max = -10.762622),
        // 'longitude' => $faker->longitude($min = 106.660172, $max = -106.660172),
        //'shipper_id' => $faker->numberBetween($min = 1, $max = 10),
        'customer_id' => $faker->numberBetween($min = 1, $max = 100),
        'status' => $faker->numberBetween($min = 1, $max = 5),
        'county' => $faker->numberBetween($min = 1, $max = 19),
        'delivery_at' => $faker->dateTime($startDate='now', $endDate='+90 days'),
        'note' => $faker->text($maxNbChars = 500),
        'label' => 'auto',
    ];
});
