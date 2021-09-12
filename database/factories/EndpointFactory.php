<?php
use App\Endpoint;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
$factory->define(Endpoint::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'cors_origin' => $faker->url,
        'user_id' => factory(User::class)->create()
    ];
});
