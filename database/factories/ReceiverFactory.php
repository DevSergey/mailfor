<?php
use App\Receiver;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
$factory->define(Receiver::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'email' => $faker->unique()->safeEmail,
        'user_id' => factory(User::class)->create()
    ];
});
