<?php

use App\Nonprofit;

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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Nonprofit::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'description' => $faker->text,
        'email' => $faker->email,
        'location' => $faker->streetAddress . ' ' . strtolower($faker->stateAbbr),
        'location_lat' => $faker->latitude,
        'location_lng' => $faker->longitude,
        'verified' => true,
    ];
});

$factory->define(App\Opportunity::class, function (Faker\Generator $faker) {
    $total_nonprofits = Nonprofit::count();
    return [
        'nonprofit_id' => $faker->numberBetween(1, $total_nonprofits),
        'title' => $faker->sentence,
        'description' => $faker->text,
        'skills' => $faker->text,
        'start_date' => $faker->dateTimeBetween('now', '+3 months'),
        'duration' => $faker->numberBetween(1, 3),
        'hours_estimate' => $faker->numberBetween(8, 48),
        'virtual' => $faker->boolean(30),
        'published' => true,
        'location' => $faker->streetAddress . ' ' . strtolower($faker->stateAbbr),
        'location_lat' => $faker->latitude,
        'location_lng' => $faker->longitude,
    ];
});